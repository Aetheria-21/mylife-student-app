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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('source'); // Source du revenu (Salaire, Freelance, etc.)
            $table->decimal('amount', 10, 2); // Montant
            $table->date('date'); // Date du revenu
            $table->text('description')->nullable(); // Description optionnelle
            $table->string('category')->default('other'); // Catégorie (salary, freelance, investment, other)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
