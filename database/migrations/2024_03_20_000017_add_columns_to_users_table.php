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
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'phone' => 'string',
                'address' => 'string',
                'city' => 'string',
                'state' => 'string',
                'country' => 'string',
                'postal_code' => 'string',
                'security_question' => 'string',
                'security_answer' => 'string',
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('users', $column)) {
                    $table->$type($column)->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'phone',
                'address',
                'city',
                'state',
                'country',
                'postal_code',
                'security_question',
                'security_answer',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 