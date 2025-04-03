<?php

namespace Database\Factories\User;

use App\Models\User\User;
use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
final class UserFactory extends Factory
{
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
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function user(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->assignRole(DefaultName::User->value);
        });
    }

    public function admin(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->assignRole(DefaultName::Admin->value);
        });
    }

    public function superAdmin(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->assignRole(DefaultName::SuperAdmin->value);
        });
    }
}
