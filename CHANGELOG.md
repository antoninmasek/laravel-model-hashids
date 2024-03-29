# Changelog

All notable changes to `laravel-model-hashids` will be documented in this file.

## 0.6.0 - 2023-11-25

### Breaking change

The `updating` eloquent model event won't be fired when setting the `hash_id`. If you want to change this, you need to set `save_quietly` to false inside the config file.

## 0.5.0 - 2023-02-16

### What's Changed

- Laravel 10 Support by @sweptsquash in https://github.com/antoninmasek/laravel-model-hashids/pull/4

### New Contributors

- @sweptsquash made their first contribution in https://github.com/antoninmasek/laravel-model-hashids/pull/4

**Full Changelog**: https://github.com/antoninmasek/laravel-model-hashids/compare/0.4.0...0.5.0

## 0.4.0 - 2022-07-27

### What's Changed

- Possibility to regenerate hash id by @antoninmasek in https://github.com/antoninmasek/laravel-model-hashids/pull/3

**Full Changelog**: https://github.com/antoninmasek/laravel-model-hashids/compare/0.3.1...0.4.0

## 0.3.1 - 2022-07-23

- Rename service provider

**Full Changelog**: https://github.com/antoninmasek/laravel-model-hashids/compare/0.3.0...0.3.1

## 0.3.0 - 2022-07-23

### What's Changed

- Refactoring by @antoninmasek in https://github.com/antoninmasek/laravel-model-hashids/pull/2

**Full Changelog**: https://github.com/antoninmasek/laravel-model-hashids/compare/0.2.0...0.3.0

## 0.2.0 - 2022-07-20

### What's Changed

- Work with just one column by @antoninmasek in https://github.com/antoninmasek/laravel-model-hashids/pull/1

### New Contributors

- @antoninmasek made their first contribution in https://github.com/antoninmasek/laravel-model-hashids/pull/1

**Full Changelog**: https://github.com/antoninmasek/laravel-model-hashids/compare/0.1.0...0.2.0

## 0.1.0 - 2022-06-12

**Full Changelog**: https://github.com/antoninmasek/laravel-model-hashids/commits/0.1.0
