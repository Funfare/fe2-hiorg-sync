<?php

namespace Database\Factories;

use App\Models\DestinationField;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RuleSet>
 */
class RuleSetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => fn() => Organization::factory()->create()->id,
            'tab_id' => null,
            'type' => 'set',
            'operation' => 'and',
            'name' => $this->faker->word,
            'order' => $this->faker->numberBetween(0, 1000),
            'set_value' => $this->faker->words(2),
            'set_value_type' => 'text',
            'source_field_extra_name' => '',
            'destination_field_id' => fn() => DestinationField::inRandomOrder()->first()->id,

        ];
    }
}
