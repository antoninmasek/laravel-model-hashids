<?php

namespace AntoninMasek\Hashids\Tests;

use AntoninMasek\Hashids\Tests\Models\TestModel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

class GeneratesHashIdTest extends TestCase
{
    public function testItGeneratesHashIdWhenModelIsCreated()
    {
        $this->assertNotNull(TestModel::create()->hash_id);
    }

    public function testItDoesNotGenerateHashIdWhenModelAlreadyHasHashIdColumnFilled()
    {
        $model = TestModel::create(['hash_id' => 'test']);

        $this->assertSame('test', $model->hash_id);
    }

    public function testItBindToHashId()
    {
        $model = TestModel::create();

        Route::get('models/{model}', function (TestModel $model) {
            return response()->json(['hash_id' => $model->hash_id]);
        })->middleware(SubstituteBindings::class);

        $this->assertEquals(
            $model->hash_id,
            $this->getJson("models/$model->hash_id")->json('hash_id'),
        );
    }
}
