<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BuildingsKeys;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuildingsKeys>
 */
class BuildingsKeysFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => 1,
            'value' => 'default',
            'buildings_id' => 1,
        ];
    }
}
