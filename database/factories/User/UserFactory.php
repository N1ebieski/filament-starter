<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\ValueObjects\Role\DefaultName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
final class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
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

    public function user(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(DefaultName::User->value);
        });
    }

    public function admin(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(DefaultName::Admin->value);
        });
    }

    public function superAdmin(): self
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(DefaultName::SuperAdmin->value);
        });
    }
}
