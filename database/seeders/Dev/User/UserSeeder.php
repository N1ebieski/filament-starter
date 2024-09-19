<?php

declare(strict_types=1);

namespace Database\Seeders\Dev\User;

use App\Models\User\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function __construct(
        private User $user,
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->user->factory(200)->user()->create();
    }
}
