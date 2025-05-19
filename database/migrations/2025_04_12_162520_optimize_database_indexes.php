<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to frequently searched columns for better performance
        
        // Users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('is_admin');
        });
        
        // Permissions and roles tables (from spatie package)
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->index('name');
            });
        }
        
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->index('name');
            });
        }
        
        // Grades table
        if (Schema::hasTable('grades')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->index('term');
            });
        }
        
        // Questions table
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                // No specific index needed for questions as the primary key is sufficient
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop added indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_admin']);
        });
        
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropIndex(['name']);
            });
        }
        
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropIndex(['name']);
            });
        }
        
        if (Schema::hasTable('grades')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->dropIndex(['term']);
            });
        }
    }
};
