<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unites_enseignement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiere_id')->constrained('filieres')->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained('niveaux');
            $table->string('code_ue', 50)->unique(); // Ex: UE_INF31
            $table->string('libelle', 150);
            $table->enum('semestre', ['S1', 'S2', 'S3', 'S4', 'S5', 'S6']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unites_enseignement');
    }
};