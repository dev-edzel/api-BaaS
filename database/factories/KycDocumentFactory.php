<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KycDocumentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['PENDING', 'APPROVED', 'REJECTED']);

        return [
            'user_id' => User::factory(),
            'document_type' => fake()->randomElement(['PRIMARY', 'SECONDARY']),
            'document_path' => Str::random(10) . '.' . fake()->randomElement(['jpg', 'png', 'jpeg']),
            'status' => $status,
            'rejection_reason' => $status === 'REJECTED' ? fake()->sentence() : null,
            'uploaded_at' => now(),
        ];
    }
}
