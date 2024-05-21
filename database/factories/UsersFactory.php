<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Users;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UsersFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => 1,
            'name' => 'Breno de Carvalho',
            'email' => 'breno.miranda@komuh.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('komuh@220'),
            'remember_token' => Str::random(10),
            'attempts' => 0,
            'users_roles_id' => 1,
            'companies_id' => 1,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
