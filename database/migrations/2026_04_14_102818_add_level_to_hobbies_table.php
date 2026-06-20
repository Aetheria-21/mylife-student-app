<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hobbies', function (Blueprint $table) {
            if (!Schema::hasColumn('hobbies', 'level')) {
                $table->string('level')->nullable();
            }
            if (!Schema::hasColumn('hobbies', 'frequency')) {
                $table->string('frequency')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('hobbies', function (Blueprint $table) {
            $cols = array_filter(['level', 'frequency'], fn($c) => Schema::hasColumn('hobbies', $c));
            if ($cols) $table->dropColumn(array_values($cols));
        });
    }
};
