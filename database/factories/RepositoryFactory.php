<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repository>
 */
class RepositoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'owner' => fake()->userName(),
            'name' => fake()->slug(2),
            'github_url' => fake()->url(),
            'balance' => 100,
            'total_commits' => 0,
        ];
    }
}
