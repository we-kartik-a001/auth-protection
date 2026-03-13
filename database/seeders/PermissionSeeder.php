<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Create Users', 'slug' => 'create-users']);
        Permission::create(['name' => 'Edit Users', 'slug' => 'edit-users']);
        Permission::create(['name' => 'Delete Users', 'slug' => 'delete-users']);
        Permission::create(['name' => 'View Reports', 'slug' => 'view-reports']);
        Permission::create(['name' => 'Manage Roles', 'slug' => 'manage-roles']);
    }
}
