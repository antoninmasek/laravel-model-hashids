<?php

namespace AntoninMasek\Hashids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Hashids\Hashids make(string $salt = null, int $min_length = null, string $alphabet = null)
 *
 * @see \AntoninMasek\Hashids\Hashids
 */
class Hashids extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AntoninMasek\Hashids\Hashids::class;
    }
}
