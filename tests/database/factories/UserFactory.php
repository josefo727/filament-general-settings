<?php

namespace Josefo727\FilamentGeneralSettings\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Josefo727\FilamentGeneralSettings\Tests\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}
