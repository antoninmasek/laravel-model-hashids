<?php

namespace AntoninMasek\Hashids\Traits;

use Illuminate\Database\Eloquent\Model;

trait BindsOnHashId
{
    public function resolveRouteBinding($value, $field = null): Model
    {
        return self::whereHashId($value, $field)->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return ! method_exists($this, 'hashIdColumn')
            ? config('model-hashids.hash_id_column')
            : $this->hashIdColumn();
    }
}
