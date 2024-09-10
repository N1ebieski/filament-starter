<?php

namespace Database\Factories\Role;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role\Role>
 */
final class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
