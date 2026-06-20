<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // lessons.user_id manque actuellement (d'après l'erreur + migrations existantes)
        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
                $table->index('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

