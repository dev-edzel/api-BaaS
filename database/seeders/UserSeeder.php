<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(500)->create()->each(function ($user) {
            UserInfo::factory()->create(['user_id' => $user->id]);
        });
    }
}
