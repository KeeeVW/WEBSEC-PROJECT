<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, check if the products table exists
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->string('model');
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->string('photo');
                $table->text('description');
                $table->foreignId('category_id')->constrained('categories');
                $table->foreignId('grade_id')->nullable()->constrained('grades');
                $table->integer('stock')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // If table exists, modify it
            Schema::table('products', function (Blueprint $table) {
                // Drop old category column if it exists
                if (Schema::hasColumn('products', 'category')) {
                    $table->dropColumn('category');
                }

                // Add or modify category_id column
                if (!Schema::hasColumn('products', 'category_id')) {
                    $table->foreignId('category_id')->after('description')->constrained('categories');
                }

                // Add other columns if they don't exist
                if (!Schema::hasColumn('products', 'code')) {
                    $table->string('code')->after('id');
                }
                if (!Schema::hasColumn('products', 'model')) {
                    $table->string('model')->after('code');
                }
                if (!Schema::hasColumn('products', 'photo')) {
                    $table->string('photo')->after('price');
                }
                if (!Schema::hasColumn('products', 'grade_id')) {
                    $table->foreignId('grade_id')->nullable()->after('category_id')->constrained('grades');
                }
                if (!Schema::hasColumn('products', 'stock')) {
                    $table->integer('stock')->default(0)->after('grade_id');
                }
                if (!Schema::hasColumn('products', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('stock');
                }
            });
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['grade_id']);
            $table->dropColumn(['category_id', 'grade_id']);
            $table->string('category')->after('description');
        });
    }
}; 