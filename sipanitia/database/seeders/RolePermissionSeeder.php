<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'admin_task.read', 'admin_task.create', 'admin_task.update', 'admin_task.delete',
            'event_task.read', 'event_task.create', 'event_task.update', 'event_task.delete',
            'inventory.read', 'inventory.create', 'inventory.update', 'inventory.delete',
            'consumption.read', 'consumption.create', 'consumption.update', 'consumption.delete',
            'user.manage', 'divisi.manage', 'import.excel', 'dashboard.view'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        // super_admin permissions are handled via Gate::before, but we can give them anyway
        $superAdmin->givePermissionTo(Permission::all());

        $bph = Role::firstOrCreate(['name' => 'bph']);
        $bph->givePermissionTo([
            'admin_task.read', 'admin_task.create', 'admin_task.update', 'admin_task.delete',
            'dashboard.view',
            'event_task.read', 'inventory.read', 'consumption.read'
        ]);

        $koordinator = Role::firstOrCreate(['name' => 'koordinator_sie']);
        $koordinator->givePermissionTo([
            'dashboard.view',
            'admin_task.read', 'admin_task.create', 'admin_task.update', 'admin_task.delete',
            'event_task.read', 'event_task.create', 'event_task.update', 'event_task.delete',
            'inventory.read', 'inventory.create', 'inventory.update', 'inventory.delete',
            'consumption.read', 'consumption.create', 'consumption.update', 'consumption.delete',
        ]);

        $anggota = Role::firstOrCreate(['name' => 'anggota_sie']);
        $anggota->givePermissionTo([
            'dashboard.view',
            'admin_task.read', 'admin_task.update',
            'event_task.read', 'event_task.update',
            'inventory.read', 'inventory.update',
            'consumption.read', 'consumption.update',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->givePermissionTo(['dashboard.view']);
    }
}
