<?php
// database/migrations/xxxx_create_hobbies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table already created by the previous migration — only add missing columns
        if (!Schema::hasTable('hobbies')) {
            return;
        }

        Schema::table('hobbies', function (Blueprint $table) {
            if (!Schema::hasColumn('hobbies', 'status')) {
                $table->string('status')->default('active')->after('frequency');
            }
            if (!Schema::hasColumn('hobbies', 'progress')) {
                $table->integer('progress')->nullable()->default(0)->after('emoji');
            }
            if (!Schema::hasColumn('hobbies', 'description')) {
                $table->text('description')->nullable()->after('progress');
            }
        });
    }

    public function down()
    {
        Schema::table('hobbies', function (Blueprint $table) {
            $table->dropColumn(array_filter(
                ['status', 'progress', 'description'],
                fn($col) => Schema::hasColumn('hobbies', $col)
            ));
        });
    }
};