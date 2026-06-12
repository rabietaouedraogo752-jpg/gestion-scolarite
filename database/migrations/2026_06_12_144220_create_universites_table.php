<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universites', function (Blueprint $table) {
            $table->id();
            $table->string('code_univ', 50)->unique(); // Ex: UVBF, UJKZ
            $table->string('nom_universite', 255);
            $table->string('ville', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('universites');
    }
};