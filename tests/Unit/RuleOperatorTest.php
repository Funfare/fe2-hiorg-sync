<?php

namespace Tests\Unit;

use App\Models\DestinationField;
use App\Models\Organization;
use App\Models\Rule;
use App\Models\RuleSet;
use App\Models\SourceField;
use App\Services\Sync;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RuleOperatorTest extends TestCase
{
    use DatabaseMigrations;

    protected Sync $sync;
    protected array $data;
    protected Organization $org;
    protected function setUp(): void
    {
        parent::setUp();
        $sourceFields = SourceField::all()->keyBy('id');
        $fields = DestinationField::pluck('key')->flip()->map(fn($i) => null)->toArray();
        $this->org = Organization::factory()->create();
        $this->data = include(base_path('tests/stubs/user_plain.php'));
        $this->sync = new Sync($sourceFields, $fields);
    }

    /**
     * @test
     */
    public function it_is_successfull_with_one_and() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Vorname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_is_successfull_with_one_or() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'or',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Vorname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_fails_with_one_false_and() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'NichtVorname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertNull($user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_fails_with_one_false_or() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'or',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'NichtVorname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertNull($user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }
    /**
     * @test
     */
    public function it_fails_with_one_false_one_true_and() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'NichtVorname',
        ])
            ->create();
        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.nachname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Nachname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertNull($user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_is_successfull_with_one_false_one_true_or() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'or',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'NichtVorname',
        ])
            ->create();
        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.nachname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Nachname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_is_successfull_with_two_true_or() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'or',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Vorname',
        ])
            ->create();
        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.nachname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Nachname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_is_successfull_with_two_true_and() {
        $ruleSet = RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();

        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.vorname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Vorname',
        ])
            ->create();
        Rule::factory()->for($ruleSet)->state([
            'source_field_id' => SourceField::where('key', 'attributes.nachname')->first()->id,
            'compare_class' => 'IsEqual',
            'not' => false,
            'compare_value' => 'Nachname',
        ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }
}
