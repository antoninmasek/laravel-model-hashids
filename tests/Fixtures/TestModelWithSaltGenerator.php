<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModelWithSaltGenerator extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'salt_test_models';

    protected $guarded = [];

    public $timestamps = false;

    public function hashIdSalt()
    {
        return static::class;
    }
}
