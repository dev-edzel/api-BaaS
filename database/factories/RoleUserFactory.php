<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoleUser>
 */
class RoleUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'role_id' => fake()->numberBetween(1, 3),
            'user_id' => User::factory()
        ];
    }
}
