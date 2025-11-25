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
        Schema::create('likes_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_foto_viaje');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_foto_viaje')->references('id')->on('fotos_viajes')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['id_foto_viaje', 'id_usuario']); // Un like por usuario por foto
            $table->index(['id_foto_viaje', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes_fotos');
    }
};
