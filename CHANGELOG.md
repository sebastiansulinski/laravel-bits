# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

[1.1.1]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.0.2...v1.1.0
[1.0.2]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/sebastiansulinski/laravel-bits/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/sebastiansulinski/laravel-bits/releases/tag/v1.0.0
