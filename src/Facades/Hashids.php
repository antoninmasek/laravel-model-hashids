<?php

namespace AntoninMasek\Hashids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AntoninMasek\Hashids\Hashids
 */
class Hashids extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-model-hashids';
    }
}
