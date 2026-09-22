# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.3] - 2026-09-22

### Fixed
- `strict_models` now defaults to unset, with the service provider falling back to the runtime environment check it used before v1.5.0. The v1.5.0 default resolved the value from the `APP_ENV` variable in the configuration file, which is not equivalent: `Application::isProduction()` reads the environment the container resolved, so Artisan's `--env` flag moves it while the variable does not. An application that does not set the key now keeps its previous behaviour under `--env` as well

### Changed
- The service provider, rather than the configuration file, casts an explicit `strict_models` value, so that an unset key stays distinguishable from `false`

## [1.5.2] - 2026-09-22

### Changed
- Refreshed `composer.lock`, which had been out of date since v1.4.0. The development and test environment now resolves Laravel 13 and Orchestra Testbench 11, so the suite runs against the versions v1.4.0 declared support for
- Applied Laravel Pint 1.32, which imports the class names that docblocks previously wrote out in full

## [1.5.1] - 2026-09-22

### Fixed
- `composer lint` now names the paths to analyse, so it runs the static analysis instead of exiting with "At least one path must be specified to analyse"
- Requirements in README now state Laravel `^12.0 || ^13.0`, matching `composer.json` since v1.4.0

## [1.5.0] - 2026-09-22

### Added
- `strict_models` configuration key in `config/laravel-bits.php`, backed by the `LARAVEL_BITS_STRICT_MODELS` environment variable, so an application can decide for itself whether Eloquent runs in strict mode
- Documentation for the `strict_models` configuration key in README

### Changed
- `LaravelBitsServiceProvider` now reads `laravel-bits.strict_models` instead of deriving strict mode from the application environment name. The default is unchanged - strict mode stays on in every environment except `production` - so an application that does not set the key behaves exactly as before

## [1.4.0] - 2026-03-29

### Added
- Laravel 13 and Orchestra Testbench 11 support

## [1.3.1] - 2026-02-06

### Changed
- Switched from `strtolower` to `mb_strtolower` in both `Lowercase` cast and `whereLowercase` macro for proper Unicode support

### Added
- Unicode-specific tests for `Lowercase` cast and `whereLowercase` macro

## [1.3.0] - 2026-02-06

### Added
- `Lowercase` Eloquent cast for automatically converting string attributes to lowercase on get and set
- Documentation for `Lowercase` cast in README

### Changed
- **BREAKING:** Renamed `whereLower` macro to `whereLowercase` for clarity
- **BREAKING:** Renamed `QueryBuilderWhereLower` class to `QueryBuilderWhereLowercase`

## [1.2.0] - 2026-02-05

### Added
- `whereLower` query builder macro for comparing a column against a lowercased value with full `where()` signature support (operator, value, boolean)
- Documentation for `whereLower` macro in README

## [1.1.1] - 2026-01-27

### Changed
- Enhanced `label()` method in `EnhancedEnums` trait to use `Str::headline()` for automatic formatting of enum case names to human-readable labels (e.g., `ApiResources` → `Api Resources`, `orderHistory` → `Order History`, `payment_method` → `Payment Method`)

### Added
- Comprehensive test coverage for `label()` method supporting PascalCase, camelCase, snake_case, single words, and numbers

## [1.1.0] - 2026-01-27

### Added
- `AsBoolean` custom cast for converting various truthy/falsy values to boolean
- `AsUlid` custom cast for handling ULID values
- `Sorter` utility class with `SorterPayload` for handling sortable data with support for custom mappers and validation
- Detailed documentation for `Sorter` and `SorterPayload` usage in README

## [1.0.2] - 2026-01-27

### Added
- `keysToCamel` array macro for converting array keys to camelCase
- `keysToSnake` array macro for converting array keys to snake_case
- Service provider integration for array macros

## [1.0.1] - 2026-01-27

### Changed
- Enhanced `updateMany` macro to support multiple `UpdateManySet` instances
- Refactored code for cleaner handling of multiple update sets
- Removed `LICENSE.md` file, now references MIT license externally in README

### Added
- Additional test cases for multiple `UpdateManySet` instances

## [1.0.0] - 2026-01-27

### Added
- Initial release
- `EnhancedEnums` trait with utility methods for PHP enums
- `UpdateMany` database macro for efficient bulk updates
- Documentation in README, CONTRIBUTING, and LICENSE files
- MIT License

[1.5.3]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.5.2...v1.5.3
[1.5.2]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.5.1...v1.5.2
[1.5.1]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.5.0...v1.5.1
[1.5.0]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.4.0...v1.5.0
[1.4.0]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.3.1...v1.4.0
[1.3.1]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.3.0...v1.3.1
[1.3.0]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.1.1...v1.2.0
[1.1.1]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.0.2...v1.1.0
[1.0.2]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/sebastiansulinski/laravel-bits/releases/tag/v1.0.0
