<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view_products',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_products',
            'manage_product_inventory'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $managerRole = Role::findByName('manager');
        $supportRole = Role::findByName('support');

        // Admin gets all permissions
        $adminRole->givePermissionTo($permissions);

        // Manager gets all permissions except delete
        $managerRole->givePermissionTo([
            'view_products',
            'add_products',
            'edit_products',
            'manage_products',
            'manage_product_inventory'
        ]);

        // Support can only view products
        $supportRole->givePermissionTo(['view_products']);
    }
} 