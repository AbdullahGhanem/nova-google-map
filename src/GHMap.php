<?php

namespace Ghanem\GoogleMap;

use Laravel\Nova\Fields\Field;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;

class GHMap extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'gh-map';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        $this->attribute = $attribute ?? str_replace(' ', '_', Str::lower($name));

        parent::__construct($name, $this->attribute);

        $this->apiKey(config('nova-google-maps.google_maps_api_key'))
            ->latitude(config('nova-google-maps.default_latitude'))
            ->longitude(config('nova-google-maps.default_longitude'))
            ->zoom(config('nova-google-maps.default_zoom'));
    }

    public function apiKey($apiKey)
    {
        return $this->withMeta(['api_key' => $apiKey]);
    }

    public function latitude($latitude)
    {
        return $this->withMeta(['latitude' => $latitude, 'latitude_field' => $latitude]);
    }

    public function longitude($longitude)
    {
        return $this->withMeta(['longitude' => $longitude, 'longitude_field' => $longitude]);
    }

    public function latitude_field($latitude_field)
    {
        return $this->withMeta(['latitude_field' => $latitude_field]);
    }
    public function longitude_field($longitude_field)
    {
        return $this->withMeta(['longitude_field' => $longitude_field]);
    }


    public function zoom($zoom)
    {
        return $this->withMeta(['zoom' => $zoom]);
    }

    public function hideLatitude()
    {
        return $this->withMeta(['hide_latitude' => true]);
    }

    public function hideLongitude()
    {
        return $this->withMeta(['hide_longitude' => true]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $latitudeField = $this->meta["latitude_field"] ?? "latitude";
        $longitudeField = $this->meta["longitude_field"] ?? "longitude";

        foreach ($request->input($attribute) as $attr => $data) {
            if ($data != 'null') {
                $result = $request->{$requestAttribute};

                $model->{$latitudeField} = $result['latitude'];
                $model->{$longitudeField} = $result['longitude'];
            }
        }
    }

    public function resolve($resource, ?string $attribute = null): void
    {
        $latitudeField = $this->meta["latitude"] ?? "latitude";
        $longitudeField = $this->meta["longitude"] ?? "longitude";
        if ($resource->getAttribute($latitudeField)) {
            $this->latitude(floatval($resource->getAttribute($latitudeField)));
            $this->latitude_field($latitudeField);
        }
        if ($resource->getAttribute($longitudeField)) {
            $this->longitude(floatval($resource->getAttribute($longitudeField)));
            $this->longitude_field($longitudeField);
        }
    }
}
