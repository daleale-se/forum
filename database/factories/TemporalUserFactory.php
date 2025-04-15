<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TemporalUser;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemporalUser>
 */
class TemporalUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assigned_username' => TemporalUser::generateUsername()
        ];
    }
    
}
