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
        Schema::table('ufr_instituts', function (Blueprint $table) {
            // Ajoute la colonne user_id après la colonne id
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ufr_instituts', function (Blueprint $table) {
            // Supprime d'abord la clé étrangère, puis la colonne
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};