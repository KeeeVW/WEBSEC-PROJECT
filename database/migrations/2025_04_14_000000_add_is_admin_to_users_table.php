<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAdminToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(0)->after('password');
            }
            
            if (!Schema::hasColumn('users', 'security_question')) {
                $table->string('security_question')->nullable()->after('is_admin');
            }
            
            if (!Schema::hasColumn('users', 'security_answer')) {
                $table->string('security_answer')->nullable()->after('security_question');
            }
            
            if (!Schema::hasColumn('users', 'is_using_temp_password')) {
                $table->boolean('is_using_temp_password')->default(0)->after('security_answer');
            }
            
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('is_using_temp_password');
            }
            
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('provider_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin',
                'security_question',
                'security_answer',
                'is_using_temp_password',
                'provider',
                'provider_id',
                'avatar'
            ]);
        });
    }
} 