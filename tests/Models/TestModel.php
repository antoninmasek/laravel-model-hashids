<?php

namespace AntoninMasek\Hashids\Tests\Models;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'test_models';

    protected $guarded = [];

    public $timestamps = false;
}
