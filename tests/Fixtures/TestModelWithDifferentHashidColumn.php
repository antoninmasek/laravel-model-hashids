<?php

namespace AntoninMasek\Hashids\Tests\Fixtures;

use AntoninMasek\Hashids\Traits\BindsOnHashId;
use AntoninMasek\Hashids\Traits\GeneratesHashId;
use Illuminate\Database\Eloquent\Model;

class TestModelWithDifferentHashidColumn extends Model
{
    use BindsOnHashId;
    use GeneratesHashId;

    protected $table = 'diff_column_test_models';

    protected $guarded = [];

    public $timestamps = false;

    public function hashIdColumn()
    {
        return 'alternative_hash_id';
    }
}
