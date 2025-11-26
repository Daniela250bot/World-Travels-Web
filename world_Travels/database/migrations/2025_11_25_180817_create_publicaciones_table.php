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
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('imagen')->nullable();
            $table->enum('privacidad', ['publico', 'privado'])->default('publico');
            $table->timestamps();

            $table->index(['id_usuario', 'privacidad']);
        });

        // Tabla para likes de publicaciones
        Schema::create('likes_publicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_publicacion')->constrained('publicaciones')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['id_usuario', 'id_publicacion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes_publicaciones');
        Schema::dropIfExists('publicaciones');
    }
};
