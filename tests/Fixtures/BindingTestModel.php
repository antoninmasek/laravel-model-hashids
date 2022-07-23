<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class BindingTestModel extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'binding_test_models';

    protected $guarded = [];

    public $timestamps = false;
}
