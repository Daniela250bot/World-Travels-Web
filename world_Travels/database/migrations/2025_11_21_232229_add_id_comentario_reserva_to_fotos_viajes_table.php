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
        Schema::table('fotos_viajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_comentario_reserva')->nullable()->after('id_usuario');
            $table->foreign('id_comentario_reserva')->references('id')->on('comentarios_reservas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fotos_viajes', function (Blueprint $table) {
            $table->dropForeign(['id_comentario_reserva']);
            $table->dropColumn('id_comentario_reserva');
        });
    }
};
