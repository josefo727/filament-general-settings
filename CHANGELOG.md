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

### Fixed

- Updated `GeneralSettingResource::form()` method signature to use `Schema` instead of `Form` (Filament 4 requirement)
- Changed `->schema()` to `->components()` in form definition (Filament 4 API)
- Fixed `FilamentUser` interface implementation: `canAccessFilament()` → `canAccessPanel()` in test User model
- Configured PHPStan to handle Filament 4 type-hints correctly

### Code Quality

- Formatted entire codebase with Laravel Pint (28 files, 28 style issues fixed)
- All tests passing (37/37, 132 assertions)
- PHPStan level 5 analysis passing with no errors

### Notes

- Minimal code changes required - plugin architecture remains the same
- All existing features and functionality preserved
- Users on Filament 3 should continue using v1.x

---

## 1.0.0 - 2025-07-20

- Initial release
