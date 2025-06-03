<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->unique()->name(),
            'due_date' => now()->addDays(fake()->numberBetween(10, 20)),
            'user_id' => fake(),
            'project_status_id' => fake()->numberBetween(1, 6),
        ];
    }
}
