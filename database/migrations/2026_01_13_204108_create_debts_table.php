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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('creditor'); // À qui vous devez (nom de la personne/entreprise)
            $table->decimal('total_amount', 10, 2); // Montant total de la dette
            $table->decimal('paid_amount', 10, 2)->default(0); // Montant déjà payé
            $table->date('due_date')->nullable(); // Date d'échéance
            $table->text('description')->nullable(); // Description
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending'); // Statut
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
