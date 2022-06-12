# Easily use Hashids with Laravel models.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/run-tests?label=tests)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/antoninmasek/laravel-model-hashids/Check%20&%20fix%20styling?label=code%20style)](https://github.com/antoninmasek/laravel-model-hashids/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/antoninmasek/laravel-model-hashids.svg?style=flat-square)](https://packagist.org/packages/antoninmasek/laravel-model-hashids)

Recently I started using an excellent [Hashids](https://hashids.org/php/) package as user facing route key. I like, that
Hashids are a bit less awkward to read as opposed to UUIDs as well as, at least for me, the resulting URL looks
a bit nicer.

> [2022-06-12] Disclaimer  
> This package is work in progress at the moment. You may use it, but be aware that it can change. The main
> usage with traits will, however, stay probably the same. 

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
     * By default, the following columns are considered to be hash_id. If you decide to also bind
     * models to hash_id, then by default the first column specified here will be used as route
     * key name.
     */
    'hash_id_columns' => ['hash_id'],

    /*
     * Define alphabet that will be used by default. You may also define the value as an array
     * and each entry will be used in order for above specified columns.
     */
    'alphabets' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',

    /*
     * Define salt that will be used by default. You may also define the value as an array
     * and each entry will be used in order for above specified columns.
     */
    'salts' => '',

    /*
     * Define minimum length for generated Hashids. Please note, that this is minimum length
     * and not exact length. That means, that if you specify 5 the resulting Hashid can
     * have length of 5 characters or more. You may also define the value as an array
     * and each entry will be used in order for above specified columns.
     */
    'min_lengths' => 0,

    /*
     * Define column name, that should be encoded. You may also define the value as
     * an array and each entry will be used in order for above specified columns.
     */
    'model_keys' => 'id',
];
```

## Usage

To use this package you'll be most interested in the following two traits: `GeneratesHashId` and `BindsOnHashId`.

### GeneratesHashId

If you use this trait on your model it makes sure all columns marked as hash_id columns will be filled with generated
hash_id.

### BindsOnHashId

This trait will make sure, the route model binding can resolve this model via `hash_id` and not regular `id`.

## Overriding

If you'd like to specify per-model rules you may easily do so via the following methods:

### Override hash_id columns
To override the default column name just on a specific model, you may implement the following method:

```php
public function hashIdColumns(): string|array
{
    return 'column-name';
}
```

### Override alphabets
To override the default alphabet just on a specific model, you may implement the following method:

```php
public function hashIdAlphabet(): string
{
    return 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
}
```

### Override salts
To override the default salt just on a specific model, you may implement the following method:

```php
public function hashIdSalt(): string
{
    return 'My salt';
}
```

### Override min lengths
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

> [2022-06-12]   
> Please note, that I will be working on testing part more in the future. In the meantime I wrote basic tests that were
> sufficient for me at the moment, because I decided to create this package on the spot since I 
> was writing this logic for I don't know how many times already. The logic usually contained 
> just one hash_id column though, so supporting multiple is a new feature. So just bear in mind, 
> that tests are not complete for now.

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
