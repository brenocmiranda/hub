<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LeadsOrigins;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeadsOrigins>
 */
class LeadsOriginsFactory extends Factory
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
            'slug' => 'default',
        ];
    }
}
