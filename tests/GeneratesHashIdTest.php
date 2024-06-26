<?php

namespace AntoninMasek\Hashids\Tests;

use AntoninMasek\Hashids\Facades\Hashids;
use AntoninMasek\Hashids\ModelHashids;
use AntoninMasek\Hashids\Tests\Fixtures\BindingTestModel;
use AntoninMasek\Hashids\Tests\Fixtures\TestModel;
use AntoninMasek\Hashids\Tests\Fixtures\TestModelWithAlphabetGenerator;
use AntoninMasek\Hashids\Tests\Fixtures\TestModelWithDifferentHashidColumn;
use AntoninMasek\Hashids\Tests\Fixtures\TestModelWithDifferentKeyForGenerationGenerator;
use AntoninMasek\Hashids\Tests\Fixtures\TestModelWithMinLengthGenerator;
use AntoninMasek\Hashids\Tests\Fixtures\TestModelWithSaltGenerator;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

class GeneratesHashIdTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ModelHashids::generateSaltUsing(null);
        ModelHashids::generateAlphabetUsing(null);
        ModelHashids::generateMinLengthUsing(null);
    }

    public function testItGeneratesHashIdWhenModelIsCreated()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertNotNull($model->hash_id);
        $this->assertTrue($model->wasChanged(['hash_id']));

        $this->assertNotNull($model2->hash_id);
        $this->assertTrue($model2->wasChanged(['hash_id']));

        $this->assertSame($model->hash_id, $model2->hash_id);
    }

    public function testItDoesNotGenerateHashIdWhenModelAlreadyHasHashIdColumnFilled()
    {
        $model = TestModel::create(['hash_id' => 'test']);

        $this->assertSame('test', $model->hash_id);
        $this->assertFalse($model->wasChanged(['hash_id']));
    }

    public function testWhenUsingBindingTraitItBindsToHashId()
    {
        $model = BindingTestModel::create();

        Route::get('models/{model}', function (BindingTestModel $model) {
            return response()->json(['hash_id' => $model->hash_id]);
        })->middleware(SubstituteBindings::class);

        $this->assertEquals(
            $model->hash_id,
            $this->getJson("models/$model->hash_id")->json('hash_id'),
        );
    }

    public function testWhenNotUsingBindingTraitItDoesNotBindToHashId()
    {
        $model = TestModel::create();

        Route::get('models/{model}', function (TestModel $model) {
            return response()->json(['hash_id' => $model->hash_id]);
        })->middleware(SubstituteBindings::class);

        $this->assertNull(
            $this->getJson("models/$model->hash_id")->json('hash_id')
        );
    }

    public function testItIsPossibleToGloballyOverwriteSaltGenerationUsingCallback()
    {
        ModelHashids::generateSaltUsing(function (Model $model) {
            return $model::class;
        });

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertNotSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::encode($model2->id), $model2->hash_id);
    }

    public function testItIsPossibleToGloballyOverwriteSaltGenerationUsingConfig()
    {
        config()->set('hashids.salt', 'test');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model2->id), $model2->hash_id);
    }

    public function testItIsPossibleToLocallyOverwriteSaltGenerationUsingCallback()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();
        $model3 = TestModelWithSaltGenerator::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model2->id, $model3->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame($model->hash_id, $model3->hash_id);
    }

    public function testItIsPossibleToGloballyOverwriteMinLengthGenerationUsingCallback()
    {
        ModelHashids::generateMinLengthUsing(function (Model $model) {
            return strlen($model::class);
        });

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertNotSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(strlen($model->hash_id), strlen($model2->hash_id));

        $this->assertSame(strlen(TestModel::class), strlen($model->hash_id));
        $this->assertSame(strlen(BindingTestModel::class), strlen($model2->hash_id));
    }

    public function testItIsPossibleToGloballyOverwriteMinLengthGenerationUsingConfig()
    {
        $minLength = 10;
        config()->set('hashids.min_length', $minLength);

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertSame($minLength, strlen($model->hash_id));
        $this->assertSame($minLength, strlen($model2->hash_id));
        $this->assertNotSame(Hashids::minLength(0)->encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::minLength(0)->encode($model2->id), $model2->hash_id);
    }

    public function testItIsPossibleToLocallyOverwriteMinLengthGenerationUsingCallback()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();
        $model3 = TestModelWithMinLengthGenerator::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model2->id, $model3->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame($model->hash_id, $model3->hash_id);
        $this->assertSame(strlen(TestModelWithMinLengthGenerator::class), strlen($model3->hash_id));
    }

    public function testItIsPossibleToGloballyOverwriteAlphabetGenerationUsingCallback()
    {
        ModelHashids::generateAlphabetUsing(function () {
            return '1234567890abcdef';
        });

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::encode($model2->id), $model2->hash_id);
    }

    public function testItIsPossibleToGloballyOverwriteAlphabetGenerationUsingConfig()
    {
        config()->set('hashids.alphabet', '1234567890abcdef');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::alphabet('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')->encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::alphabet('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')->encode($model2->id), $model2->hash_id);
    }

    public function testItIsPossibleToLocallyOverwriteAlphabetGenerationUsingCallback()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();
        $model3 = TestModelWithAlphabetGenerator::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model2->id, $model3->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame($model->hash_id, $model3->hash_id);
    }

    public function testLocalCallbackHasPrecedenceOverGlobalCallback()
    {
        ModelHashids::generateSaltUsing(function () {
            return 'test';
        });

        $model = TestModel::create();
        $model2 = BindingTestModel::create();
        $model3 = TestModelWithSaltGenerator::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model2->id, $model3->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame($model2->hash_id, $model3->hash_id);
        $this->assertNotSame(Hashids::encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::encode($model3->id), $model3->hash_id);
    }

    public function testGlobalCallbackHasPrecedenceOverConfigValue()
    {
        ModelHashids::generateSaltUsing(function () {
            return 'callback';
        });

        config()->set('hashids.salt', 'config');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model->id), $model->hash_id);
    }

    public function testConfigValueHasPrecedenceOverDefaultPackageValue()
    {
        config()->set('hashids.salt', 'config');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model->id), $model->hash_id);
    }

    public function testItDoesNotDoTwoTripsToDbWhenKeyForGenerationIsPresentDuringCreation()
    {
        $model = TestModel::create();
        $model2 = TestModelWithDifferentKeyForGenerationGenerator::create(['diff_key' => 1]);

        TestModelWithDifferentKeyForGenerationGenerator::updating(function ($model) {
            throw new Exception('This should not be triggered');
        });

        $this->assertSame($model->id, $model2->id);
        $this->assertTrue($model->wasChanged('hash_id'));
        $this->assertFalse($model2->wasChanged('hash_id'));
    }

    public function testItIsPossibleToUseDifferentColumnToFillWithHashId()
    {
        config()->set('model-hashids.hash_id_column', 'alternative_hash_id');

        $model = TestModel::create();

        $this->assertNull($model->hash_id);
        $this->assertNotNull($model->alternative_hash_id);
    }

    public function testItIsPossibleToUseDifferentColumnToFillWithHashIdForSpecificModel()
    {
        $model = TestModel::create();
        $model2 = TestModelWithDifferentHashidColumn::create();

        $this->assertNotNull($model->hash_id);
        $this->assertNull($model->alternative_hash_id);

        $this->assertNull($model2->hash_id);
        $this->assertNotNull($model2->alternative_hash_id);
    }

    public function testItCanQueryResultsViaScope()
    {
        TestModel::create();
        $model = TestModel::create();
        $results = TestModel::whereHashId($model->hash_id)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($model->is($results->first()));
    }

    public function testItCanRegenerateHashIdColumnQuietly()
    {
        $model = TestModel::create();
        $expectedHashId = $model->hash_id;

        $model->update(['hash_id' => null]);
        $this->assertEmpty($model->refresh()->hash_id);

        Event::fake();
        $model->regenerateHashId()->refresh();
        $this->assertSame($expectedHashId, $model->hash_id);
        $this->assertFalse($model->isDirty(['hash_id']));
        $this->assertTrue($model->wasChanged(['hash_id']));
        Event::assertNotDispatched('eloquent.updating: '.TestModel::class);
    }

    public function testItCanRegenerateHashIdColumnAndFireUpdatingEvent()
    {
        config()->set('model-hashids.save_quietly', false);

        $model = TestModel::create();
        $expectedHashId = $model->hash_id;

        $model->update(['hash_id' => null]);
        $this->assertEmpty($model->refresh()->hash_id);

        Event::fake();
        $model->regenerateHashId()->refresh();
        $this->assertSame($expectedHashId, $model->hash_id);
        $this->assertFalse($model->isDirty(['hash_id']));
        $this->assertTrue($model->wasChanged(['hash_id']));
        Event::assertDispatched('eloquent.updating: '.TestModel::class);
    }

    public function testItCanRegenerateHashIdColumn()
    {
        $model = TestModel::create();
        $expectedHashId = $model->hash_id;

        $model->update(['hash_id' => null]);
        $this->assertEmpty($model->refresh()->hash_id);

        $model->regenerateHashId()->refresh();
        $this->assertSame($expectedHashId, $model->hash_id);
        $this->assertFalse($model->isDirty(['hash_id']));
        $this->assertTrue($model->wasChanged(['hash_id']));
    }

    public function testItCanRegenerateHashIdColumnWithoutInstantlyPersistingInDatabase()
    {
        $model = TestModel::create();
        $expectedHashId = $model->hash_id;

        $model->update(['hash_id' => null]);
        $this->assertEmpty($model->refresh()->hash_id);

        $model->regenerateHashId(false);
        $this->assertSame($expectedHashId, $model->hash_id);
        $this->assertTrue($model->isDirty(['hash_id']));
        $this->assertTrue($model->wasChanged(['hash_id']));
    }
}
