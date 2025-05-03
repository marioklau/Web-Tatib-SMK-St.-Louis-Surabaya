<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = \App\Models\User::class; // Tambahkan ini untuk mendefinisikan model

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(), // Ganti 'nama' dengan 'name'
            'username' => $this->faker->unique()->userName(), // Pastikan username diisi dengan benar
            'password' => Hash::make('password'), // Password yang di-hash
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null, // Pastikan ini sesuai dengan model
        ]);
    }
}