<?php

declare(strict_types=1);

namespace Database\Seeders\Install\Role;

use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class RoleSeeder extends Seeder
{
    public function __construct(
        private Role $role,
        private PermissionRegistrar $permissionRegistrar
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->permissionRegistrar->forgetCachedPermissions();

        $superAdmin = $this->role->newQuery()->firstOrCreate(['name' => DefaultName::SuperAdmin->value]);

        if ($superAdmin->wasRecentlyCreated) {
            $superAdmin->givePermissionTo(['admin.*', 'web.*', 'api.*']);
        }

        $admin = $this->role->newQuery()->firstOrCreate(['name' => DefaultName::Admin->value]);

        if ($admin->wasRecentlyCreated) {
            $admin->givePermissionTo(['admin.*', 'web.*', 'api.*']);
        }

        $user = $this->role->newQuery()->firstOrCreate(['name' => DefaultName::User->value]);

        if ($user->wasRecentlyCreated) {
            $user->givePermissionTo(['web.*']);
        }

        $api = $this->role->newQuery()->firstOrCreate(['name' => DefaultName::Api->value]);

        if ($api->wasRecentlyCreated) {
            $api->givePermissionTo(['api.*']);
        }
    }
}
