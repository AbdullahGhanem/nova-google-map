<?php

namespace Ghanem\GoogleMap;

use Laravel\Nova\Fields\Field;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;

class GHMap extends Field
{
    public $component = 'gh-map';

    public function __construct(string $name, ?string $attribute = null, ?callable $resolveCallback = null)
    {
        $this->attribute = $attribute ?? str_replace(' ', '_', Str::lower($name));

        parent::__construct($name, $this->attribute);

        $this->apiKey(config('nova-google-maps.google_maps_api_key'))
            ->latitude(config('nova-google-maps.default_latitude'))
            ->longitude(config('nova-google-maps.default_longitude'))
            ->zoom(config('nova-google-maps.default_zoom'));
    }

    public function apiKey(string $apiKey): self
    {
        return $this->withMeta(['api_key' => $apiKey]);
    }

    public function latitude(string|float $latitude): self
    {
        return $this->withMeta(['latitude' => $latitude]);
    }

    public function longitude(string|float $longitude): self
    {
        return $this->withMeta(['longitude' => $longitude]);
    }

    public function latitudeField(string $latitudeField): self
    {
        return $this->withMeta(['latitude_field' => $latitudeField]);
    }

    public function longitudeField(string $longitudeField): self
    {
        return $this->withMeta(['longitude_field' => $longitudeField]);
    }

    public function zoom(int|float $zoom): self
    {
        return $this->withMeta(['zoom' => $zoom]);
    }

    public function hideLatitude(): self
    {
        return $this->withMeta(['hide_latitude' => true]);
    }

    public function hideLongitude(): self
    {
        return $this->withMeta(['hide_longitude' => true]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        $latitudeField = $this->meta['latitude_field'] ?? 'latitude';
        $longitudeField = $this->meta['longitude_field'] ?? 'longitude';

        $data = $request->input($attribute);

        if (is_array($data) && isset($data['latitude'], $data['longitude'])) {
            $lat = $data['latitude'];
            $lng = $data['longitude'];

            if ($lat !== null && $lat !== 'null' && $lng !== null && $lng !== 'null') {
                $lat = (float) $lat;
                $lng = (float) $lng;

                if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                    $model->{$latitudeField} = $lat;
                    $model->{$longitudeField} = $lng;
                }
            }
        }
    }

    public function resolve($resource, ?string $attribute = null): void
    {
        $latitudeField = $this->meta['latitude_field'] ?? 'latitude';
        $longitudeField = $this->meta['longitude_field'] ?? 'longitude';

        if ($resource->getAttribute($latitudeField) !== null) {
            $this->latitude(floatval($resource->getAttribute($latitudeField)));
        }
        if ($resource->getAttribute($longitudeField) !== null) {
            $this->longitude(floatval($resource->getAttribute($longitudeField)));
        }
    }
}
