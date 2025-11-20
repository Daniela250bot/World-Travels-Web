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
        // Permitir valores NULL en idUsuario para actividades creadas por administradores
        Schema::table('actividades', function (Blueprint $table) {
            // Cambiar el campo para permitir NULL
            $table->unsignedBigInteger('idUsuario')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            // Restaurar la restricción NOT NULL
            $table->unsignedBigInteger('idUsuario')->nullable(false)->change();
        });
    }
};