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
        // Superseded by 2026_03_30_123429_create_homeworks_table.php which
        // correctly creates the `homeworks` table with all required columns.
        // This is a no-op to avoid creating a stale `homework` (singular) table.
    }

    public function down(): void
    {
        // Nothing to roll back.
    }
};
