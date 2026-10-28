# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
- Code style formatted to PSR-12 with Laravel Pint (`composer format` / `composer lint`).
- Replaced Travis CI with GitHub Actions (PHP 8.2–8.4 × Laravel 11/12/13, plus a code style check).
- Fixed the facade alias: the package now registers `StubGenerator`. The old copy-pasted `ModelRepository` alias is kept for backward compatibility and will be removed in the next major release.


## [1.0.0] - 2021-09-07
- Initial release

## [1.0.1] - 2021-09-08
- bug fix on file full path generation

## [1.0.2] - 2021-09-11
- Added support to handle boolean type value as replacer
- Updated README

## [1.0.3] - 2021-12-17
- Merged [pull requests](https://github.com/touhidurabir/laravel-stub-generator/pull/3) from [GENL](https://github.com/GENL).
- Added github issue templates.

## [1.0.4] - 2022-02-16
- Merged [pull request](https://github.com/touhidurabir/laravel-stub-generator/pull/5) from [amirmasoud](https://github.com/amirmasoud) which add the ability to specify the file extension which was only set to php previously.
- Tests update.
- Readme update.
- Minor fix.


## [1.0.5] - 2022-02-23
- Merged [pull request](https://github.com/touhidurabir/laravel-stub-generator/pull/6) from [amirmasoud](https://github.com/amirmasoud) which add the ability to specify the file with no extension and add Laravel 9 support . 
- Readme update.

## [1.0.6] - 2022-03-18
- Bug fix for array replacebale
- Tests update.

## [1.0.7] - 2023-03-07
- Merged [PR](https://github.com/touhidurabir/laravel-stub-generator/pull/9) to add Laravel 10+ support .

## [1.0.8] - 2024-03-12
- Laravel 11 support added.
- PHPUnit XML configuration update.

## [1.0.9] - 2025-03-13
- Laravel 12 support added.

## [1.1.0] - 2026-08-22
- Laravel 13 support added.
- Added the missing `orchestra/testbench` constraints for Laravel 11 and 12 (`^9.0||^10.0`) alongside the new `^11.0` for Laravel 13.
- Added PHPUnit 13 support.
- Fixed a PHP 8.4 deprecation in `NamespaceResolver::generateFilePathFromNamespace()` where the `$namespace` parameter was implicitly nullable.
- Converted the `/** @test */` annotations to `test_` prefixed method names, as PHPUnit 12+ dropped support for metadata in doc-comments. The naming convention works across PHPUnit 9 through 13, so the whole supported matrix keeps running.
- Removed the hardcoded coverage `<report>` outputs from `phpunit.xml.dist`. PHPUnit 13 aborts the run when reports are configured but no coverage driver is installed. Coverage reports are now produced on demand through the new `composer test-coverage` script, which writes to the same paths as before.
- Added `composer test` and `composer test-coverage` scripts.
