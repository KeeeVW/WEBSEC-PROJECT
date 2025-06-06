<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_inventories', function (Blueprint $table) {
            // $table->string('location')->after('quantity');
            // $table->text('notes')->nullable()->after('location');
        });
    }

    public function down()
    {
        Schema::table('product_inventories', function (Blueprint $table) {
            $table->dropColumn(['location', 'notes']);
        });
    }
}; 