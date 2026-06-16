<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropForeign(['ufr_id']);
        });

        DB::statement('ALTER TABLE filieres MODIFY ufr_id BIGINT UNSIGNED NULL');

        Schema::table('filieres', function (Blueprint $table) {
            $table->foreign('ufr_id')->nullable()->references('id')->on('ufr_instituts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            $table->dropForeign(['ufr_id']);
        });

        DB::statement('ALTER TABLE filieres MODIFY ufr_id BIGINT UNSIGNED NOT NULL');

        Schema::table('filieres', function (Blueprint $table) {
            $table->foreign('ufr_id')->references('id')->on('ufr_instituts')->onDelete('cascade');
        });
    }
};
