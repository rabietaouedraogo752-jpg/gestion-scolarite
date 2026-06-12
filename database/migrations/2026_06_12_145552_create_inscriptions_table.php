<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained('filieres');
            $table->foreignId('niveau_id')->constrained('niveaux');
            $table->foreignId('annee_univ_id')->constrained('annees_universitaires');
            $table->enum('statut_inscription', ['valide', 'en_attente'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};