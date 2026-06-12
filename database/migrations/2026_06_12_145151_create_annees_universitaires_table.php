<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annees_universitaires', function (Blueprint $table) {
            $table->id();
            $table->string('code_annee', 50)->unique(); // Ex: 2025-2026
            $table->enum('statut', ['en_cours', 'cloturee'])->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annees_universitaires');
    }
};