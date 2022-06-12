<?php

namespace AntoninMasek\Hashids\Traits;

use AntoninMasek\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @mixin Model
 */
trait GeneratesHashId
{
    public static function bootGeneratesHashId(): void
    {
        static::creating(function ($model) {
            foreach (Arr::wrap($model->hashIdColumns()) as $index => $column) {
                static::setColumn($model, $column, $index);
            }
        });

        static::created(function ($model) {
            foreach (Arr::wrap($model->hashIdColumns()) as $index => $column) {
                static::setColumn($model, $column, $index);
            }

            if ($model->isDirty()) {
                $model->save();
            }
        });
    }

    private static function setColumn($model, $column, $index = null): void
    {
        if (! empty($model->$column)) {
            return;
        }

        $modelKeyColumn = static::get($index, $model->hashIdModelKeys());

        if (empty($model->$modelKeyColumn)) {
            return;
        }

        $salt = static::get($index, $model->hashIdSalts());
        $alphabet = static::get($index, $model->hashIdAlphabets());
        $minLength = static::get($index, $model->hashIdMinLengths());

        $model->$column = Hashids::make($salt, $minLength, $alphabet)
            ->encode($model->$modelKeyColumn);
    }

    private static function get(int $index, mixed $array): mixed
    {
        if (! is_array($array)) {
            return $array;
        }

        if (array_key_exists($index, $array)) {
            return $array[$index];
        }

        if (--$index < 0) {
            return null;
        }

        return static::get($index, $array);
    }

    /**
     * @return string|array<string>
     */
    public function hashIdColumns(): string|array
    {
        return Arr::wrap(config('hashids.hash_id_columns'));
    }

    /**
     * @return string|array<string>
     */
    public function hashIdAlphabets(): string|array
    {
        return Arr::wrap(config('hashids.alphabets'));
    }

    /**
     * @return string|array<string>
     */
    public function hashIdSalts(): string|array
    {
        return Arr::wrap(config('hashids.salts'));
    }

    /**
     * @return int|array<int>
     */
    public function hashIdMinLengths(): int|array
    {
        return Arr::wrap(config('hashids.min_lengths'));
    }

    /**
     * @return string|array<string>
     */
    public function hashIdModelKeys(): string|array
    {
        return Arr::wrap(config('hashids.model_keys'));
    }

    public function scopeWhereHashId(Builder $query, $hashId, $hashIdColumn = null): Builder
    {
        return $query->where(
            $hashIdColumn ?? Arr::get($this->hashIdColumns(), 0),
            $hashId,
        );
    }
}
