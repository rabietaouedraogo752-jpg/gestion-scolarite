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
        Schema::table('emplois_du_temps', function (Blueprint $table) {
            // Ajouter niveau_id et enseignant_id
            $table->foreignId('niveau_id')->after('filiere_id')->constrained('niveaux')->onDelete('cascade');
            $table->foreignId('enseignant_id')->after('niveau_id')->nullable()->constrained('enseignants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emplois_du_temps', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['niveau_id']);
            $table->dropForeignKeyIfExists(['enseignant_id']);
            $table->dropColumn(['niveau_id', 'enseignant_id']);
        });
    }
};
