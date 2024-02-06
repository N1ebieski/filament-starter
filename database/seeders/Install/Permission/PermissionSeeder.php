<?php

declare(strict_types=1);

namespace Database\Seeders\Install\Permission;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

final class PermissionSeeder extends Seeder
{
    public function __construct(
        private Permission $permission,
        private PermissionRegistrar $permissionRegistrar
    ) {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->permissionRegistrar->forgetCachedPermissions();

        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.*']);
        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.access']);

        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.home.*']);
        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.home.view']);

        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.user.*']);
        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.user.view']);

        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.role.*']);
        $this->permission->newQuery()->firstOrCreate(['name' => 'admin.role.view']);

        $this->permission->newQuery()->firstOrCreate(['name' => 'api.*']);

        $this->permission->newQuery()->firstOrCreate(['name' => 'web.*']);

        $this->permission->newQuery()->firstOrCreate(['name' => 'tenant.*']);
    }
}
