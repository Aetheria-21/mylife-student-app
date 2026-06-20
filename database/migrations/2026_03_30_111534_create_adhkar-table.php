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
        Schema::create('adhkar', function (Blueprint $table) {
    $table->id();
    $table->string('category'); // morning, evening, sleep...
    $table->text('text');       // Arabic
    $table->text('translation')->nullable();
    $table->string('audio')->nullable();
    $table->integer('repeat')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
