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
        Schema::table('users', function (Blueprint $table) {
            $table->text('biografia')->nullable()->after('foto_perfil');
            $table->enum('privacidad_perfil', ['publico', 'privado'])->default('publico')->after('biografia');
            $table->timestamp('ultima_actividad')->nullable()->after('privacidad_perfil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['biografia', 'privacidad_perfil', 'ultima_actividad']);
        });
    }
};
