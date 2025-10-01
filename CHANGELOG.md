# Changelog

All notable changes to `filament-general-settings` will be documented in this file.

## 2.0.0 - 2025-09-30

### Breaking Changes

- **PHP**: Minimum version increased from 8.1 to 8.2
- **Laravel**: Minimum version increased from 10.0 to 11.28
- **Filament**: Updated from v3.0 to v4.0

### Changed

- Updated `filament/filament` dependency to `^4.0`
- Updated `illuminate/support` dependency to `^11.28`
- Updated `php` requirement to `^8.2`
- Updated development dependencies:
  - `orchestra/testbench` from `^8.0` to `^9.0`
  - `nunomaduro/larastan` from `^2.11` to `^3.0`
  - `phpstan/phpstan` from `^1.12` to `^2.0`
  - `phpunit/phpunit` from `^10.0` to `^11.0`

### Notes

- No code changes required - the plugin structure is fully compatible with Filament 4
- All existing features and functionality remain unchanged
- Users on Filament 3 should continue using v1.x

---

## 1.0.0 - 2025-07-20

- Initial release
