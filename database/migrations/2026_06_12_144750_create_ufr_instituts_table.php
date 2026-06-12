<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ufr_instituts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('universite_id')->constrained('universites')->onDelete('cascade');
            $table->string('code', 50); // Ex: UFR_SEA
            $table->string('nom', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ufr_instituts');
    }
};