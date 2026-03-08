[![Latest Stable Version](https://poser.pugx.org/ghanem/google-map/v/stable.svg)](https://packagist.org/packages/ghanem/google-map) [![License](https://poser.pugx.org/ghanem/google-map/license.svg)](https://packagist.org/packages/ghanem/google-map) [![Total Downloads](https://poser.pugx.org/ghanem/google-map/downloads.svg)](https://packagist.org/packages/ghanem/google-map)

# Nova Google Map with Autocomplete

A Laravel Nova field that provides an interactive Google Map with Places Autocomplete for picking locations. Users can search for addresses, drag a marker, or enter coordinates manually.

![Screenshot](docs/screenshot-fields.png)

## Requirements

- PHP 8.1+
- Laravel Nova 4.x or 5.x
- A Google Cloud API key with **Maps JavaScript API** and **Places API** enabled

## Installation

```bash
composer require ghanem/google-map
```

Publish the config file:

```bash
php artisan vendor:publish --provider="Ghanem\GoogleMap\FieldServiceProvider"
```

## Configuration

Add your Google Maps API key to `.env`:

```env
GMAPS_API_KEY=your-api-key-here
```

Optional environment variables with their defaults:

```env
GMAPS_DEFAULT_LATITUDE=41.657523
GMAPS_DEFAULT_LONGITUDE=-101.157292
GMAPS_DEFAULT_ZOOM=3
```

> Get your API key from the [Google Cloud Console](https://console.cloud.google.com/apis/credentials). Make sure to enable both **Maps JavaScript API** and **Places API**.

## Usage

Add the field to your Nova resource:

```php
use Ghanem\GoogleMap\GHMap;

public function fields(NovaRequest $request): array
{
    return [
        // ...
        GHMap::make('Location'),
    ];
}
```

Your model needs `latitude` and `longitude` columns (or custom names — see below).

### Custom Field Names

If your database columns are not named `latitude` and `longitude`:

```php
GHMap::make('Location')
    ->latitude('lat')
    ->longitude('lng'),
```

### Hide Coordinate Inputs

```php
GHMap::make('Location')
    ->hideLatitude()
    ->hideLongitude(),
```

### Custom Zoom Level

```php
GHMap::make('Location')
    ->zoom(10),
```

### Override API Key Per Field

```php
GHMap::make('Location')
    ->apiKey('different-api-key'),
```

### All Options Combined

```php
GHMap::make('Location')
    ->latitude('lat')
    ->longitude('lng')
    ->zoom(12)
    ->hideLatitude()
    ->hideLongitude(),
```

You can also use any standard Nova field methods (validation, visibility, etc.):

```php
GHMap::make('Location')
    ->rules('required')
    ->hideFromIndex(),
```

## Events

When a location is selected (via autocomplete or marker drag), the field emits Nova events that you can listen to in other custom fields:

| Event | Description |
|-------|-------------|
| `address-update` | Full formatted address |
| `city-update` | City / locality name |
| `state-update` | State / administrative area |
| `country-update` | Country name |
| `zip-code-update` | Postal code |
| `latitude-update` | Latitude value (incoming) |
| `longitude-update` | Longitude value (incoming) |

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## License

MIT - see [LICENSE](LICENSE) for details.

## Sponsor

[Become a Sponsor](https://github.com/sponsors/AbdullahGhanem)
