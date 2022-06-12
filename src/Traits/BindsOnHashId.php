<?php

namespace AntoninMasek\Hashids\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait BindsOnHashId
{
    public function resolveRouteBinding($value, $field = null): Model
    {
        return self::whereHashId($value, $field)->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return Arr::get($this->hashIdColumns(), 0)
            ?? $this->getRouteKeyName();
    }
}
