<?php

namespace Database\Factories;

use App\Models\RuleSet;
use App\Models\SourceField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rule>
 */
class RuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rule_set_id' => fn() => RuleSet::factory()->create()->id,
            'source_field_id' => fn() => SourceField::inRandomOrder()->first()->id,
            'source_field_extra_name' => null,
            'compare_class' => 'IsEqual',
            'not' => $this->faker->boolean,
            'compare_value' => $this->faker->words(2),

        ];
    }
}
