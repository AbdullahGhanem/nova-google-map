<template>
    <div
        class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0"
        :class="{
            'border-t border-gray-100 dark:border-gray-700': index !== 0
        }"
        :dusk="field.attribute"
    >
        <div class="md:w-1/4 md:py-3">
            <slot>
                <h4 class="font-bold md:font-normal">
                    <span>{{ field.name }}</span>
                </h4>
            </slot>
        </div>
        <div class="md:w-3/4 md:py-3 break-words">
            <slot name="value">
                <span v-if="hasCoordinates">{{ field.latitude + ', ' + field.longitude }}</span>
                <GMapMap
                    v-if="hasCoordinates"
                    :center="map.center"
                    :zoom="map.zoom"
                    style="height: 20rem; margin-top: 25px"
                >
                    <GMapMarker :position="map.center" :draggable="false" />
                </GMapMap>
                <span v-else>—</span>
            </slot>
        </div>
    </div>
</template>

<script>
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            map: { center: { lat: 0, lng: 0 }, zoom: 16 },
            hasCoordinates: false,
        }
    },

    mounted() {
        const lat = parseFloat(this.field.latitude)
        const lng = parseFloat(this.field.longitude)

        if (!isNaN(lat) && !isNaN(lng)) {
            this.map.center = { lat, lng }
            this.hasCoordinates = true
        }
    },
}
</script>
