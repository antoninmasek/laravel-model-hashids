# Easily use Hashids with Laravel models.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/run-tests?label=tests)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/Check%20&%20fix%20styling?label=code%20style)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)

Recently I started using an excellent [Hashids](https://hashids.org/php/) package as user-facing route key. I like, that
Hashids are a bit less awkward to read as opposed to UUIDs and in my opinion the resulting URL looks
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
    /*
     * By default, the following column is considered to be hash_id. If you decide to also bind
     * models to hash_id, then this column will be used as route key name.
     */
    'hash_id_column' => 'hash_id',

    /*
     * This alphabet will be used by default if you won't overwrite it
     * on a per model basis.
     */
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',

    /*
     * This salt will be used by default if you won't overwrite it
     * on a per model basis.
     */
    'salt' => '',

    /*
     * Define minimum length for generated Hashids. Please note, that this is minimum length
     * and not exact length. That means, that if you specify 5 the resulting Hashid can
     * have length of 5 characters or more.
     */
    'min_length' => 0,

    /*
     * Define column name, that should be encoded.
     */
    'model_key' => 'id',
];
```

## Usage

To use this package you'll be most interested in the following two traits: `GeneratesHashId` and `BindsOnHashId`.

### GeneratesHashId

If you use this trait on your model it makes sure the hash_id column will be filled with generated
hash_id.

### BindsOnHashId

This trait will make sure, the route model binding can resolve this model via `hash_id` and not regular `id`.

## Overriding

If you'd like to specify per-model rules you may easily do so via the following methods:

### Override hash_id column
To override the default column name just on a specific model, you may implement the following method:

```php
public function hashIdColumn(): string|array
{
    return 'column-name';
}
```

### Override alphabet
To override the default alphabet just on a specific model, you may implement the following method:

```php
public function hashIdAlphabet(): string
{
    return 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
}
```

### Override salt
To override the default salt just on a specific model, you may implement the following method:

```php
public function hashIdSalt(): string
{
    return 'My salt';
}
```

### Override min length
To override the minimum length just on a specific model, you may implement the following method:

```php
public function hashIdMinLength(): int
{
    return 10;
}
```

## Limitations
If your model key is auto-incrementing then, at least at the moment, there are 2 round-trips to the
database. 1st to create model and receive the ID and then 2nd to set the hash_id based on the ID.

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
