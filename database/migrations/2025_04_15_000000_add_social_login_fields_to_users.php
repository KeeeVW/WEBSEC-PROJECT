<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add social login fields if they don't exist
            if (!Schema::hasColumn('users', 'facebook_id')) {
                $table->string('facebook_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'twitter_id')) {
                $table->string('twitter_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'linkedin_id')) {
                $table->string('linkedin_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable();
            }
            if (!Schema::hasColumn('users', 'social_avatar')) {
                $table->string('social_avatar')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_id',
                'twitter_id',
                'linkedin_id',
                'google_id',
                'social_avatar'
            ]);
        });
    }
}; 