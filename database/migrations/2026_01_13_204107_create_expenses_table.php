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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nom de la dépense
            $table->decimal('amount', 10, 2); // Montant
            $table->date('date'); // Date de la dépense
            $table->text('description')->nullable(); // Description optionnelle
            $table->string('category')->default('other'); // Catégorie (food, transport, entertainment, bills, shopping, health, other)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
