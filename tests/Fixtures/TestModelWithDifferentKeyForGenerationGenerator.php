<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModelWithDifferentKeyForGenerationGenerator extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'diff_key_test_models';

    protected $guarded = [];

    public $timestamps = false;

    public function hashIdKeyColumn()
    {
        return 'diff_key';
    }
}
