<?php

namespace AntoninMasek\Hashids\Traits;

use AntoninMasek\Hashids\Facades\Hashids;
use AntoninMasek\Hashids\ModelHashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait GeneratesHashId
{
    public static function bootGeneratesHashId(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->hashIdKeyColumn()})) {
                return;
            }

            $hashIdColumn = ! method_exists($model, 'hashIdColumn')
                ? config('model-hashids.hash_id_column')
                : $model->hashIdColumn();

            if (! empty($model->{$hashIdColumn})) {
                return;
            }

            $model->{$hashIdColumn} = $model->generateHashId();
        });

        static::created(function ($model) {
            $hashIdColumn = ! method_exists($model, 'hashIdColumn')
                ? config('model-hashids.hash_id_column')
                : $model->hashIdColumn();

            if (! empty($model->{$hashIdColumn})) {
                return;
            }

            $model->update([$hashIdColumn => $model->generateHashId()]);
        });
    }

    public function generateHashId(): string
    {
        $salt = ! method_exists($this, 'hashIdSalt')
            ? ModelHashids::generateSalt($this)
            : $this->hashIdSalt();

        $alphabet = ! method_exists($this, 'hashIdAlphabet')
            ? ModelHashids::generateAlphabet($this)
            : $this->hashIdAlphabet();

        $minLength = ! method_exists($this, 'hashIdMinLength')
            ? ModelHashids::generateMinLength($this)
            : $this->hashIdMinLength();

        return Hashids::salt($salt)
            ->alphabet($alphabet)
            ->minLength($minLength)
            ->encode($this->{$this->hashIdKeyColumn()});
    }

    public function hashIdColumn(): string
    {
        return config('model-hashids.hash_id_column');
    }

    public function hashIdKeyColumn(): string
    {
        return config('model-hashids.model_key');
    }

    public function scopeWhereHashId(Builder $query, string $hashId): Builder
    {
        $hashIdColumn = ! method_exists($this, 'hashIdColumn')
            ? config('model-hashids.hash_id_column')
            : $this->hashIdColumn();

        return $query->where($hashIdColumn, $hashId);
    }
}
