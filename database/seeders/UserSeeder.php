<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        // Attendance
        Permission::create(['name' => 'scan attendance']);
        Permission::create(['name' => 'view own attendance']);

        // Management (HR)
        Permission::create(['name' => 'view all attendance']);
        Permission::create(['name' => 'manage employees']);
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'export reports']);

        // Admin only
        Permission::create(['name' => 'generate qr code']);
        Permission::create(['name' => 'manage departments']);
        Permission::create(['name' => 'manage office locations']);
        Permission::create(['name' => 'manage roles']);

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles and assign created permissions
        $employeePermissions = Permission::whereIn('name', ['scan attendance', 'view own attendance'])->get();
        $role = Role::create(['name' => 'employee']);
        // $role->syncPermissions($employeePermissions);
        $role->givePermissionTo(['scan attendance', 'view own attendance']);

        $hrPermissions = Permission::whereIn('name', ['view all attendance', 'manage employees', 'view reports', 'export reports'])->get();
        $role = Role::create(['name' => 'hr']);
        // $role->syncPermissions($hrPermissions);
        $role->givePermissionTo(['view all attendance', 'manage employees', 'view reports', 'export reports']);

        $role = Role::create(['name' => 'admin']);
        // $role->syncPermissions(Permission::all());
        $role->givePermissionTo(Permission::all());

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create default users
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@attendease.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $hr = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@attendease.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $hr->assignRole('hr');

        $employee = User::create([
            'name' => 'Karyawan Demo',
            'email' => 'karyawan@attendease.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $employee->assignRole('employee');
    }
}
