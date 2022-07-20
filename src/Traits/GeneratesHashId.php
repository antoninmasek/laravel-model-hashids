<?php

namespace AntoninMasek\Hashids\Traits;

use AntoninMasek\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait GeneratesHashId
{
    public static function bootGeneratesHashId(): void
    {
        static::created(function ($model) {
            if (! empty($model->{$model->hashIdColumn()})) {
                return;
            }

            $hashId = Hashids::make(
                $model->hashIdSalt(),
                $model->hashIdMinLength(),
                $model->hashIdAlphabet(),
            )
                ->encode($model->{$model->hashIdKeyColumn()});

            $model->update([$model->hashIdColumn() => $hashId]);
        });
    }

    public function hashIdColumn(): string
    {
        return config('hashids.hash_id_column');
    }

    public function hashIdSalt(): string
    {
        return config('hashids.salt');
    }

    public function hashIdAlphabet(): string
    {
        return config('hashids.alphabet');
    }

    public function hashIdMinLength(): int
    {
        return config('hashids.min_length');
    }

    public function hashIdKeyColumn(): string
    {
        return config('hashids.model_key');
    }

    public function scopeWhereHashId(Builder $query, string $hashId): Builder
    {
        return $query->where(
            $this->hashIdColumn(),
            $hashId,
        );
    }
}
