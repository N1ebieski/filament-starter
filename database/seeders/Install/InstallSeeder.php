<?php

declare(strict_types=1);

namespace Database\Seeders\Install;

use Illuminate\Database\Seeder;
use Database\Seeders\Install\Role\RoleSeeder;
use Database\Seeders\Install\Permission\PermissionSeeder;

final class InstallSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}
