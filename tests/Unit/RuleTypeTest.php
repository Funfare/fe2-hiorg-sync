<?php

namespace Tests\Unit;

use App\Models\DestinationField;
use App\Models\Organization;
use App\Models\RuleSet;
use App\Models\SourceField;
use App\Services\Sync;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RuleTypeTest extends TestCase
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
    public function it_assigns_text_value() {
        RuleSet::factory()
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
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_doesnt_overwrite_text_value() {
        RuleSet::factory()
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
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
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
    public function it_replaces_text_value() {
        RuleSet::factory()
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
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'replace',
                'operation' => 'and',
                'set_value' => 'testvalue2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue2', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }
    /**
     * @test
     */
    public function it_adds_text_value() {
        RuleSet::factory()
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
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'add',
                'operation' => 'and',
                'set_value' => 'test2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvaluetest2', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_removes_text_value() {
        RuleSet::factory()
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
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'remove',
                'operation' => 'and',
                'set_value' => 'test',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('value', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_removes_nothing_if_not_found_text_value() {
        RuleSet::factory()
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
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'remove',
                'operation' => 'and',
                'set_value' => 'asdf',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
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
    public function it_removes_nothing_if_empty_text_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'remove',
                'operation' => 'and',
                'set_value' => 'asdf',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('', $user['firstName']);
        foreach(\Arr::except($user, ['firstName', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_aborts_sync() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'abort',
                'operation' => 'and',
                'set_value' => 'asdf',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => null,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertFalse($user);
    }


    /**
     * @test
     */
    public function it_assigns_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertIsArray($user['alarmGroups']);
        $this->assertEquals(['testvalue'], $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_doesnt_overwrite_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertIsArray($user['alarmGroups']);
        $this->assertEquals(['testvalue'], $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_replaces_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'replace',
                'operation' => 'and',
                'set_value' => 'testvalue2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertIsArray($user['alarmGroups']);
        $this->assertEquals(['testvalue2'], $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }
    /**
     * @test
     */
    public function it_adds_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'add',
                'operation' => 'and',
                'set_value' => 'test2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertIsArray($user['alarmGroups']);
        $this->assertEquals(['testvalue', 'test2'], $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_removes_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'add',
                'operation' => 'and',
                'set_value' => 'testvalue2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'remove',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertIsArray($user['alarmGroups']);
        $this->assertEquals(['testvalue2'], $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_removes_nothing_if_not_found_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'add',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'add',
                'operation' => 'and',
                'set_value' => 'testvalue2',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'remove',
                'operation' => 'and',
                'set_value' => 'testvalue3',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertIsArray($user['alarmGroups']);
        $this->assertEquals(['testvalue', 'testvalue2'], $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_removes_nothing_if_empty_array_value() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'remove',
                'operation' => 'and',
                'set_value' => 'asdf',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 1,
                'destination_field_id' => DestinationField::where('key', 'alarmGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('', $user['alarmGroups']);
        foreach(\Arr::except($user, ['alarmGroups', 'aPagerProFieldMode']) as $field) {
            $this->assertNull($field);
        }
    }


}
