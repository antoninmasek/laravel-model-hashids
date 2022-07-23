<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModelWithAlphabetGenerator extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'alphabet_test_models';

    protected $guarded = [];

    public $timestamps = false;

    public function hashIdAlphabet()
    {
        return '1234567890abcdef';
    }
}
