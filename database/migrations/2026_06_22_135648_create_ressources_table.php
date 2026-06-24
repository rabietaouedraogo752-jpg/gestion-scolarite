<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
    Schema::create('ressources', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->string('chemin_fichier');
    $table->foreignId('filiere_id')->nullable()->constrained('filieres')->onDelete('set null');
    $table->foreignId('niveau_id')->nullable()->constrained('niveaux')->onDelete('set null');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ressources');
    }
};
