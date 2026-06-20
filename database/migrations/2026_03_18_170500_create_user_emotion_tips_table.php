<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_emotion_tips')) {
            Schema::create('user_emotion_tips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('emotion', 20);
                $table->unsignedTinyInteger('tip_order');
                $table->string('tip_text', 255);
                $table->timestamps();

                $table->unique(['user_id', 'emotion', 'tip_order']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_emotion_tips')) {
            Schema::dropIfExists('user_emotion_tips');
        }
    }
};