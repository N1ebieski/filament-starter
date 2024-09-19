<?php

declare(strict_types=1);

namespace Database\Seeders\Install;

use Database\Seeders\Install\Permission\PermissionSeeder;
use Database\Seeders\Install\Role\RoleSeeder;
use Illuminate\Database\Seeder;

final class InstallSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}
