<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use GeneratesHashId;

    protected $table = 'test_models';

    protected $guarded = [];

    public $timestamps = false;
}
