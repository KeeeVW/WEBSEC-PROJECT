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

        // Add category_id to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->after('description')->constrained('categories');
            }
        });

        // Update existing products to use the default category
        $defaultCategory = DB::table('categories')->first();
        if ($defaultCategory) {
            DB::table('products')->whereNull('category_id')->update([
                'category_id' => $defaultCategory->id
            ]);
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
}; 