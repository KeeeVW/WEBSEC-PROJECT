<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First ensure categories table exists
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Check if we have any categories
        $categoryCount = DB::table('categories')->count();
        if ($categoryCount === 0) {
            // Insert a default category
            DB::table('categories')->insert([
                'name' => 'Default Category',
                'slug' => 'default-category',
                'description' => 'Default category for products',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Now ensure products table has the correct structure
        Schema::table('products', function (Blueprint $table) {
            // Drop the old category column if it exists
            if (Schema::hasColumn('products', 'category')) {
                $table->dropColumn('category');
            }

            // Add category_id if it doesn't exist
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->after('description')->constrained('categories');
            }

            // Make sure other required columns exist
            if (!Schema::hasColumn('products', 'code')) {
                $table->string('code')->after('id');
            }
            if (!Schema::hasColumn('products', 'model')) {
                $table->string('model')->after('code');
            }
            if (!Schema::hasColumn('products', 'photo')) {
                $table->string('photo')->after('price');
            }
        });
    }

    public function down()
    {
        // We don't want to remove the default category
        // Just drop the foreign key constraint
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    }
}; 