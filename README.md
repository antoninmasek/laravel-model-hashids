# Easily use Hashids with Laravel models.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/run-tests?label=tests)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/Check%20&%20fix%20styling?label=code%20style)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)

In some cases I really like to use [Hashids](https://hashids.org/php/) instead of uuids as my model keys. For me Hashids are less awkward to read and the resulting URL looks 
a bit nicer in my opinion. This package is inspired by [laravel-model-uuid](https://github.com/michaeldyrynda/laravel-model-uuid) by Michael Dyrynda and aims
to make it a breeze to start using Hashids as your model keys.

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
    /*
     * The following column will be filled with the generated hash id. If you decide to also bind
     * models to hash_id, then this column will be used as route key name.
     */
    'hash_id_column' => 'hash_id',

    /*
     * Define the column name, which will be used to generate the hash id. This column has to contain
     * a numeric value or an array of numbers and should be unique for each model to prevent
     * potential collisions.
     */
    'model_key' => 'id',
];
```

> This package uses [antoninmasek/laravel-hashids](https://github.com/antoninmasek/laravel-hashids) in the background. And if you wish
> to configure some aspects of the underlying hash id generation, then please
> take a look at a readme of the package.

## Usage

To use this package you'll be most interested in the following two traits: `GeneratesHashId` and `BindsOnHashId`.

### Generating hash id
In order for your model to automatically get a hash id after it is created just use 
`GeneratesHashId` trait on your model:

```php
use AntoninMasek\Hashids\Traits\GeneratesHashId;

class YourModel extend Model
{
    use GeneratesHashId;
}
```

### Binding on hash id
To also bind your model to hash id you also need to use `BindsOnHashId` trait:

```php
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use AntoninMasek\Hashids\Traits\BindsOnHashId;

class YourModel extend Model
{
    use GeneratesHashId;
    use BindsOnHashId;
}
```

## Configuration

If you need to execute some logic in order to determine salt/alphabet/minlength you have
a few callbacks at your disposal:

### Global
If you want to set these globally you may use the following callbacks. The callback is
supplied with the model as a parameter.

```php
use AntoninMasek\Hashids\ModelHashids;

ModelHashids::generateSaltUsing(function(Model $model) {
    // your logic   
    return $salt;
});

ModelHashids::generateMinLengthUsing(function(Model $model) {
    // your logic   
    return $minLength;
});

ModelHashids::generateAlphabetUsing(function(Model $model) {
    // your logic   
    return $alphabet;
});
```

## Local
If you wish to have a specific logic just for a certain model you may define these methods
on the desired model:

```php
// Overwrite the column to fill with hash id for this specific model
public function hashIdColumn(): string;

// Overwrite the column to use for hash id generation for this specific model
public function hashIdKeyColumn(): string;

// Overwrite the logic to generate salt for this specific model
public function hashIdSalt(): string;

// Overwrite the logic to generate alphabet for this specific model
public function hashIdAlphabet(): string;

// Overwrite the logic to generate min length for this specific model
public function hashIdMinLength(): string;
```

### Precedence
This is the order in which the values are taken:

1. Model specific logic
2. Global logic
3. Config values
4. Hashids package

## Limitations
If your model key is auto-incrementing then, at least at the moment, there are 2 round-trips to the
database. 1st to create the model and receive the ID and then 2nd to set the hash_id based on the ID.

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

- To [Spatie](https://spatie.be/) for their amazing [skeleton](https://github.com/spatie/package-skeleton-laravel) which
  I used to scaffold this repository.
- To Michael Dyrynda for his [laravel-model-uuid](https://github.com/michaeldyrynda/laravel-model-uuid) package, by
  which this package is heavily inspired.
