<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
    >
        <template #field>
            <div class="space-y-4">
                <div class="gh-map-autocomplete" v-if="showAutocomplete">
                    <GMapAutocomplete
                        placeholder="Search for a location..."
                        @place_changed="onPlaceChanged"
                        class="w-full form-control form-input form-input-bordered"
                    />
                </div>

                <GMapMap
                    :center="map.center"
                    :zoom="map.zoom"
                    style="height: 20rem"
                >
                    <GMapMarker
                        :position="map.center"
                        @dragend="markerDragend"
                        :draggable="true"
                    />
                </GMapMap>

                <div class="flex">
                    <div class="w-1/2 mr-6" v-if="!hideLatitude">
                        <p class="mb-1">Latitude</p>
                        <input
                            type="text"
                            class="w-full form-control form-input form-input-bordered"
                            :class="errorClasses"
                            v-model="latitude"
                            @change="onCoordinateInput"
                        />
                    </div>
                    <div class="w-1/2" v-if="!hideLongitude">
                        <p class="mb-1">Longitude</p>
                        <input
                            type="text"
                            class="w-full form-control form-input form-input-bordered"
                            :class="errorClasses"
                            v-model="longitude"
                            @change="onCoordinateInput"
                        />
                    </div>
                </div>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            map: { center: { lat: 0, lng: 0 }, zoom: 3 },
            latitude: null,
            longitude: null,
            hideLatitude: false,
            hideLongitude: false,
            showAutocomplete: true,
            geocodeTimer: null,
        }
    },

    created() {
        this._onLatUpdate = data => {
            this.latitude = data
            this.map.center = { ...this.map.center, lat: parseFloat(data) }
        }
        this._onLngUpdate = data => {
            this.longitude = data
            this.map.center = { ...this.map.center, lng: parseFloat(data) }
            this.map.zoom = 16
        }
    },

    mounted() {
        Nova.$on('latitude-update', this._onLatUpdate)
        Nova.$on('longitude-update', this._onLngUpdate)
    },

    beforeUnmount() {
        Nova.$off('latitude-update', this._onLatUpdate)
        Nova.$off('longitude-update', this._onLngUpdate)
        clearTimeout(this.geocodeTimer)
    },

    methods: {
        setInitialValue() {
            this.hideLatitude = !!this.field.hide_latitude
            this.hideLongitude = !!this.field.hide_longitude

            const lat = parseFloat(this.field.latitude) || 0
            const lng = parseFloat(this.field.longitude) || 0
            const zoom = parseInt(this.field.zoom) || 3

            this.map.center = { lat, lng }
            this.latitude = lat
            this.longitude = lng
            this.map.zoom = zoom

            if (this.resourceId) {
                this.map.zoom = 16
            }
        },

        fill(formData) {
            formData.append(this.field.attribute + '[latitude]', this.latitude)
            formData.append(this.field.attribute + '[longitude]', this.longitude)
        },

        onPlaceChanged(place) {
            if (!place || !place.geometry) return
            this.setPlace(place)
        },

        markerDragend(marker) {
            const lat = marker.latLng.lat()
            const lng = marker.latLng.lng()

            this.latitude = lat
            this.longitude = lng
            this.map.center = { lat, lng }

            clearTimeout(this.geocodeTimer)
            this.geocodeTimer = setTimeout(() => {
                this.reverseGeocode(lat, lng)
            }, 300)
        },

        reverseGeocode(lat, lng) {
            fetch(
                `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${Nova.appConfig.api_key}`
            )
                .then(response => response.json())
                .then(data => {
                    if (data.results && data.results.length > 0) {
                        this.emitAddressComponents(data.results[0])
                    }
                })
                .catch(error => {
                    console.error('Geocoding failed:', error)
                })
        },

        onCoordinateInput() {
            const lat = parseFloat(this.latitude)
            const lng = parseFloat(this.longitude)

            if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                this.map.center = { lat, lng }
                this.map.zoom = 16
            }
        },

        setPlace(place) {
            this.emitAddressComponents(place)

            const loc = place.geometry.location
            const lat = typeof loc.lat === 'function' ? loc.lat() : loc.lat
            const lng = typeof loc.lng === 'function' ? loc.lng() : loc.lng

            this.latitude = lat
            this.longitude = lng
            this.map.center = { lat, lng }
            this.map.zoom = 16
        },

        emitAddressComponents(place) {
            if (!place.address_components) return

            for (const component of place.address_components) {
                const componentType = component.types[0]
                switch (componentType) {
                    case 'postal_code':
                    case 'postal_code_suffix':
                        Nova.$emit('zip-code-update', component.long_name)
                        break
                    case 'locality':
                        Nova.$emit('city-update', component.long_name)
                        break
                    case 'administrative_area_level_1':
                        Nova.$emit('state-update', component.long_name)
                        break
                    case 'country':
                        Nova.$emit('country-update', component.long_name)
                        break
                }
            }

            if (place.formatted_address) {
                Nova.$emit('address-update', place.formatted_address)
            }
        },
    },
}
</script>
