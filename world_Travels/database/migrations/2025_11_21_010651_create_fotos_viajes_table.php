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
        Schema::create('fotos_viajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->string('ruta_imagen', 500);
            $table->enum('privacidad', ['publico', 'privado'])->default('publico');
            $table->timestamp('fecha_subida')->useCurrent();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->index(['id_usuario', 'fecha_subida']);
            $table->index('privacidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos_viajes');
    }
};
