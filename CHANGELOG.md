# Changelog

All notable changes to this package will be documented in this file.

## [Unreleased]

### Added
- Places Autocomplete search input on form view
- Debounced reverse geocoding on marker drag (300ms)
- Error handling for geocoding API calls
- Coordinate validation (lat: -90 to 90, lng: -180 to 180)
- Manual coordinate input updates the map in real-time
- Empty state handling in Detail and Index views
- Event listener cleanup on component unmount
- `mergeConfigFrom` so config works without publishing
- GitHub Actions CI workflow

### Changed
- Requires PHP 8.1+ (dropped PHP 7.4 and 8.0 support)
- Added PHP type hints and return types throughout
- Renamed `latitude_field()` / `longitude_field()` to `latitudeField()` / `longitudeField()` (camelCase)
- Fixed inconsistent meta key usage between `resolve()` and `fillAttributeFromRequest()`
- Fixed null comparison bug in `fillAttributeFromRequest()`
- Pinned `@fawmi/vue-google-maps` to `^0.9.0` instead of `*`
- Rewrote README with complete documentation

### Fixed
- String `'null'` comparison instead of proper null check when saving
- Missing error handling on geocoding fetch calls
- Unsafe `parseFloat()` calls without NaN guards
- Shared mutable state in Vue component `data()` (object reference bug)
