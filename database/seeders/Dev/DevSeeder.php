<?php

declare(strict_types=1);

namespace Database\Seeders\Dev;

use Illuminate\Database\Seeder;

final class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(\Database\Seeders\Dev\User\UserSeeder::class);
    }
}
