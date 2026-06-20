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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_name'); // Nom de l'article
            $table->decimal('price', 10, 2)->nullable(); // Prix estimé
            $table->string('priority')->default('medium'); // Priorité (low, medium, high)
            $table->text('description')->nullable(); // Description
            $table->string('url')->nullable(); // Lien vers le produit
            $table->boolean('purchased')->default(false); // Acheté ou non
            $table->date('purchased_date')->nullable(); // Date d'achat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
