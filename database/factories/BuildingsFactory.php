<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Buildings;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buildings>
 */
class BuildingsFactory extends Factory
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
            'name' => 'Default',
        ];
    }
}
