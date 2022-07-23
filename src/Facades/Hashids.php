<?php

namespace AntoninMasek\Hashids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \AntoninMasek\Hashids\Hashids make(array $config = [])
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
