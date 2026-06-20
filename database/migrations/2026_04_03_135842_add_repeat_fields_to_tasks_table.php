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
        Schema::table('tasks', function (Blueprint $table) {
    $table->string('repeat_type')->nullable(); // daily, weekly, none
    $table->json('repeat_days')->nullable();   // ["Saturday","Sunday"]
    $table->boolean('is_important')->default(false); // for wheel 🎡
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
