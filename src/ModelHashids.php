<?php

namespace AntoninMasek\Hashids;

use Illuminate\Database\Eloquent\Model;

class ModelHashids
{
    protected static $saltGenerator;

    protected static $minLengthGenerator;

    protected static $alphabetGenerator;

    /**
     * Specify the callback that should be invoked to generate salt when no model specific generator is defined.
     * This callback takes precedence over the value specified in the config file.
     */
    public static function generateSaltUsing(?callable $callback): void
    {
        static::$saltGenerator = $callback;
    }

    public static function generateSalt(Model $model, mixed $default = null): mixed
    {
        return (static::$saltGenerator ?? fn () => $default)($model);
    }

    /**
     * Specify the callback that should be invoked to generate min length when no model specific generator is defined.
     * This callback takes precedence over the value specified in the config file.
     */
    public static function generateMinLengthUsing(?callable $callback): void
    {
        static::$minLengthGenerator = $callback;
    }

    public static function generateMinLength(Model $model, mixed $default = null): mixed
    {
        return (static::$minLengthGenerator ?? fn () => $default)($model);
    }

    /**
     * Specify the callback that should be invoked to generate an alphabet when no model specific generator is defined.
     * This callback takes precedence over the value specified in the config file.
     */
    public static function generateAlphabetUsing(?callable $callback): void
    {
        static::$alphabetGenerator = $callback;
    }

    public static function generateAlphabet(Model $model, mixed $default = null): mixed
    {
        return (static::$alphabetGenerator ?? fn () => $default)($model);
    }
}
