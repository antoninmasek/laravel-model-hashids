<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModelWithMinLengthGenerator extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'min_length_test_models';

    protected $guarded = [];

    public $timestamps = false;

    public function hashIdMinLength()
    {
        return strlen(static::class);
    }
}
