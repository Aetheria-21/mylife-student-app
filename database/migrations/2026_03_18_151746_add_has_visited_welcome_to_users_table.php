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
        if (! Schema::hasColumn('users', 'has_visited_welcome')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('has_visited_welcome')->default(false)->after('google_refresh_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'has_visited_welcome')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('has_visited_welcome');
            });
        }
    }
};

