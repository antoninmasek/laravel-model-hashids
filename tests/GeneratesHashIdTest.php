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

    public function testHashIdColumCanBeConfiguredAsString()
    {
        config()->set('hashids.hash_id_columns', 'hash_id');

        $this->assertNotNull(TestModel::create()->hash_id);
    }

    public function testItCanGenerateMultipleColumns()
    {
        config()->set('hashids.hash_id_columns', [
            'hash_id',
            'hash_id_2',
            'hash_id_3',
        ]);

        $model = TestModel::create();

        $this->assertNotNull($model->hash_id);
        $this->assertNotNull($model->hash_id_2);
        $this->assertNotNull($model->hash_id_3);

        $this->assertEquals($model->hash_id, $model->hash_id_2);
        $this->assertEquals($model->hash_id_2, $model->hash_id_3);
    }

    public function testItCanHaveMultipleSalts()
    {
        config()->set('hashids.hash_id_columns', [
            'hash_id',
            'hash_id_2',
            'hash_id_3',
        ]);

        config()->set('hashids.salts', [
            'a',
            'b',
            'c',
        ]);

        $model = TestModel::create();

        $this->assertNotNull($model->hash_id);
        $this->assertNotNull($model->hash_id_2);
        $this->assertNotNull($model->hash_id_3);

        $this->assertNotEquals($model->hash_id, $model->hash_id_2);
        $this->assertNotEquals($model->hash_id, $model->hash_id_3);
        $this->assertNotEquals($model->hash_id_2, $model->hash_id_3);
    }

    public function testItCanHaveMultipleAlphabets()
    {
        config()->set('hashids.hash_id_columns', [
            'hash_id',
            'hash_id_2',
            'hash_id_3',
        ]);

        config()->set('hashids.alphabets', [
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
            'abcdefghijklmnopqrstuvwxyz',
        ]);

        $model = TestModel::create();

        $this->assertNotNull($model->hash_id);
        $this->assertNotNull($model->hash_id_2);
        $this->assertNotNull($model->hash_id_3);

        $this->assertNotEquals($model->hash_id, $model->hash_id_2);
        $this->assertNotEquals($model->hash_id, $model->hash_id_3);
        $this->assertNotEquals($model->hash_id_2, $model->hash_id_3);
    }

    public function testItCanHaveMultipleLengths()
    {
        config()->set('hashids.hash_id_columns', [
            'hash_id',
            'hash_id_2',
            'hash_id_3',
        ]);

        config()->set('hashids.min_lengths', [
            5,
            6,
            7,
        ]);

        $model = TestModel::create();

        $this->assertNotNull($model->hash_id);
        $this->assertNotNull($model->hash_id_2);
        $this->assertNotNull($model->hash_id_3);

        $this->assertTrue(strlen($model->hash_id) === 5);
        $this->assertTrue(strlen($model->hash_id_2) === 6);
        $this->assertTrue(strlen($model->hash_id_3) === 7);
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

    public function testItBindsToFirstColumnWhenMultipleSpecified()
    {
        config()->set('hashids.hash_id_columns', [
            'hash_id',
            'hash_id_2',
            'hash_id_3',
        ]);

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
