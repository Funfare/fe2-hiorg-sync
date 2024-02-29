<?php

namespace Tests\Unit;

use App\Models\DestinationField;
use App\Models\Organization;
use App\Models\RuleSet;
use App\Models\SourceField;
use App\Services\Sync;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RuleValueTest extends TestCase
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
    public function it_assigns_plain_text() {
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
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_assigns_hiorg_text() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => SourceField::where('key', 'attributes.name')->first()->id,
                'set_value_type' => 'field',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('Vorname Nachname', $user['firstName']);
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_assigns_hiorg_array_to_text() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => SourceField::where('key', 'attributes.gruppen_namen')->first()->id,
                'set_value_type' => 'field',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);
        $this->assertEquals('Gruppe 1,Gruppe 2', $user['firstName']);
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_assigns_hiorg_qualification_name_text() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'Medizinische Qualifikation',
                'set_value_type' => 'qualification:name',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);
        $this->assertEquals('Rettungs-Sanitäter/in', $user['firstName']);
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_assigns_hiorg_qualification_name_short_text() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'Medizinische Qualifikation',
                'set_value_type' => 'qualification:name_short',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);
        $this->assertEquals('RS', $user['firstName']);
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_assigns_hiorg_custom_field_text() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => SourceField::where('key', 'attributes.benutzerdefinierte_felder')->first()->id,
                'set_value_type' => 'field',
                'source_field_extra_name' => 'aPager E-Mail',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);
        $this->assertEquals('apager@example.com', $user['firstName']);
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_doesnt_assigns_hiorg_custom_field_text_if_not_exists() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => SourceField::where('key', 'attributes.benutzerdefinierte_felder')->first()->id,
                'set_value_type' => 'field',
                'source_field_extra_name' => 'Unbekannte Liste',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);
        $this->assertEquals('', $user['firstName']);
        foreach(\Arr::except($user, ['firstName']) as $field) {
            $this->assertNull($field);
        }
    }


    /**
     * @test
     */
    public function it_copies_a_fe2_value_text_to_text() {
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
                'set_value' => 'firstName',
                'set_value_type' => 'copy:fe2-field',
                'source_field_extra_name' => '',
                'order' => 0,
                'execute_at_end' => 1,
                'destination_field_id' => DestinationField::where('key', 'lastName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        $this->assertEquals('testvalue', $user['lastName']);
        foreach(\Arr::except($user, ['firstName', 'lastName']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_copies_a_fe2_value_text_to_array() {
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
                'set_value' => 'firstName',
                'set_value_type' => 'copy:fe2-field',
                'source_field_extra_name' => '',
                'order' => 0,
                'execute_at_end' => 1,
                'destination_field_id' => DestinationField::where('key', 'osFunctions')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue', $user['firstName']);
        $this->assertEquals(['testvalue'], $user['osFunctions']);
        foreach(\Arr::except($user, ['firstName', 'osFunctions']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_copies_a_fe2_value_array_to_text() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'osFunctions')->first()->id,
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
                'destination_field_id' => DestinationField::where('key', 'osFunctions')->first()->id,
            ])
            ->create();

        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'osFunctions',
                'set_value_type' => 'copy:fe2-field',
                'source_field_extra_name' => '',
                'order' => 0,
                'execute_at_end' => 1,
                'destination_field_id' => DestinationField::where('key', 'firstName')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals('testvalue,testvalue2', $user['firstName']);
        $this->assertEquals(['testvalue', 'testvalue2'], $user['osFunctions']);
        foreach(\Arr::except($user, ['firstName', 'osFunctions']) as $field) {
            $this->assertNull($field);
        }
    }

    /**
     * @test
     */
    public function it_copies_a_fe2_value_array_to_array() {
        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'testvalue',
                'set_value_type' => 'text',
                'source_field_extra_name' => '',
                'order' => 0,
                'destination_field_id' => DestinationField::where('key', 'osFunctions')->first()->id,
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
                'destination_field_id' => DestinationField::where('key', 'osFunctions')->first()->id,
            ])
            ->create();

        RuleSet::factory()
            ->for($this->org)
            ->state([
                'type' => 'set',
                'operation' => 'and',
                'set_value' => 'osFunctions',
                'set_value_type' => 'copy:fe2-field',
                'source_field_extra_name' => '',
                'order' => 0,
                'execute_at_end' => 1,
                'destination_field_id' => DestinationField::where('key', 'osGroups')->first()->id,
            ])
            ->create();
        $user = $this->sync->getDataFromHiorgUser($this->data, $this->org->ruleSets);

        $this->assertEquals(['testvalue', 'testvalue2'], $user['osGroups']);
        $this->assertEquals(['testvalue', 'testvalue2'], $user['osFunctions']);
        foreach(\Arr::except($user, ['osGroups', 'osFunctions']) as $field) {
            $this->assertNull($field);
        }
    }
}
