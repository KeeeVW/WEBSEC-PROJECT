<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add color column to roles table if it doesn't exist
        if (!Schema::hasColumn('roles', 'color')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('color')->nullable()->after('name');
            });
        }
        
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create or get the three main roles
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        
        // Set their colors
        $customerRole->color = 'green';
        $customerRole->save();
        
        $employeeRole->color = 'yellow';
        $employeeRole->save();
        
        $adminRole->color = 'red';
        $adminRole->save();
        
        // Find and remove any 'user' role
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            // Get all users with the user role
            $users = User::role('user')->get();
            
            // Change them to customer role
            foreach ($users as $user) {
                $user->removeRole('user');
                $user->assignRole('customer');
            }
            
            // Delete the user role
            $userRole->delete();
        }
        
        // Remove any roles that aren't one of our three main roles
        $rolesToDelete = Role::whereNotIn('name', ['customer', 'employee', 'admin'])->get();
        foreach ($rolesToDelete as $role) {
            // First, reassign all users with this role to customer
            $users = User::role($role->name)->get();
            foreach ($users as $user) {
                $user->removeRole($role->name);
                $user->assignRole('customer');
            }
            
            // Then delete the role
            $role->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't actually reverse this migration
    }
}; 