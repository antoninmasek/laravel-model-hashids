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

    public function test_it_generates_hash_id_when_model_is_created()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertNotNull($model->hash_id);
        $this->assertTrue($model->wasChanged(['hash_id']));

        $this->assertNotNull($model2->hash_id);
        $this->assertTrue($model2->wasChanged(['hash_id']));

        $this->assertSame($model->hash_id, $model2->hash_id);
    }

    public function test_it_does_not_generate_hash_id_when_model_already_has_hash_id_column_filled()
    {
        $model = TestModel::create(['hash_id' => 'test']);

        $this->assertSame('test', $model->hash_id);
        $this->assertFalse($model->wasChanged(['hash_id']));
    }

    public function test_when_using_binding_trait_it_binds_to_hash_id()
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

    public function test_when_not_using_binding_trait_it_does_not_bind_to_hash_id()
    {
        $model = TestModel::create();

        Route::get('models/{model}', function (TestModel $model) {
            return response()->json(['hash_id' => $model->hash_id]);
        })->middleware(SubstituteBindings::class);

        $this->assertNull(
            $this->getJson("models/$model->hash_id")->json('hash_id')
        );
    }

    public function test_it_is_possible_to_globally_overwrite_salt_generation_using_callback()
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

    public function test_it_is_possible_to_globally_overwrite_salt_generation_using_config()
    {
        config()->set('hashids.salt', 'test');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model2->id), $model2->hash_id);
    }

    public function test_it_is_possible_to_locally_overwrite_salt_generation_using_callback()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();
        $model3 = TestModelWithSaltGenerator::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model2->id, $model3->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame($model->hash_id, $model3->hash_id);
    }

    public function test_it_is_possible_to_globally_overwrite_min_length_generation_using_callback()
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

    public function test_it_is_possible_to_globally_overwrite_min_length_generation_using_config()
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

    public function test_it_is_possible_to_locally_overwrite_min_length_generation_using_callback()
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

    public function test_it_is_possible_to_globally_overwrite_alphabet_generation_using_callback()
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

    public function test_it_is_possible_to_globally_overwrite_alphabet_generation_using_config()
    {
        config()->set('hashids.alphabet', '1234567890abcdef');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::alphabet('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')->encode($model->id), $model->hash_id);
        $this->assertNotSame(Hashids::alphabet('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')->encode($model2->id), $model2->hash_id);
    }

    public function test_it_is_possible_to_locally_overwrite_alphabet_generation_using_callback()
    {
        $model = TestModel::create();
        $model2 = BindingTestModel::create();
        $model3 = TestModelWithAlphabetGenerator::create();

        $this->assertSame($model->id, $model2->id);
        $this->assertSame($model2->id, $model3->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame($model->hash_id, $model3->hash_id);
    }

    public function test_local_callback_has_precedence_over_global_callback()
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

    public function test_global_callback_has_precedence_over_config_value()
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

    public function test_config_value_has_precedence_over_default_package_value()
    {
        config()->set('hashids.salt', 'config');

        $model = TestModel::create();
        $model2 = BindingTestModel::create();

        $this->assertSame($model->id, $model2->id);

        $this->assertSame($model->hash_id, $model2->hash_id);
        $this->assertNotSame(Hashids::salt('')->encode($model->id), $model->hash_id);
    }

    public function test_it_does_not_do_two_trips_to_db_when_key_for_generation_is_present_during_creation()
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

    public function test_it_is_possible_to_use_different_column_to_fill_with_hash_id()
    {
        config()->set('model-hashids.hash_id_column', 'alternative_hash_id');

        $model = TestModel::create();

        $this->assertNull($model->hash_id);
        $this->assertNotNull($model->alternative_hash_id);
    }

    public function test_it_is_possible_to_use_different_column_to_fill_with_hash_id_for_specific_model()
    {
        $model = TestModel::create();
        $model2 = TestModelWithDifferentHashidColumn::create();

        $this->assertNotNull($model->hash_id);
        $this->assertNull($model->alternative_hash_id);

        $this->assertNull($model2->hash_id);
        $this->assertNotNull($model2->alternative_hash_id);
    }

    public function test_it_can_query_results_via_scope()
    {
        TestModel::create();
        $model = TestModel::create();
        $results = TestModel::whereHashId($model->hash_id)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($model->is($results->first()));
    }

    public function test_it_can_regenerate_hash_id_column_quietly()
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

    public function test_it_can_regenerate_hash_id_column_and_fire_updating_event()
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

    public function test_it_can_regenerate_hash_id_column()
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

    public function test_it_can_regenerate_hash_id_column_without_instantly_persisting_in_database()
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
