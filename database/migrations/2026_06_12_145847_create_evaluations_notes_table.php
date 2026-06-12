<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('annee_univ_id')->constrained('annees_universitaires');
            $table->decimal('note_devoir', 4, 2)->nullable();
            $table->decimal('note_examen', 4, 2)->nullable();
            $table->decimal('note_rattrapage', 4, 2)->nullable();
            $table->decimal('moyenne_matiere', 4, 2)->nullable();
            $table->boolean('statut_valide')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations_notes');
    }
};