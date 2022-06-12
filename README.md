# Easily use Hashids with Laravel models.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/run-tests?label=tests)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/Check%20&%20fix%20styling?label=code%20style)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)

Recently I started using excellent [Hashids](https://hashids.org/php/) package as user facing route key. I like, that
Hashids are a bit less awkward to read as opposed to UUIDs as well as, at least for me, the resulting URL looks 
a bit nicer.

## Installation

You can install the package via composer:

```bash
composer require antoninmasek/laravel-model-hashids
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-model-hashids-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$hashids = new AntoninMasek\Hashids();
echo $hashids->echoPhrase('Hello, AntoninMasek!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Antonín Mašek](https://github.com/antoninmasek)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Special thanks
- To [Spatie](https://spatie.be/) for their amazing [skeleton](https://github.com/spatie/package-skeleton-laravel) which I used to scaffold this repository.
- To Michael Dyrynda for his [laravel-model-uuid](https://github.com/michaeldyrynda/laravel-model-uuid) package, by which this package is heavily inspired.
