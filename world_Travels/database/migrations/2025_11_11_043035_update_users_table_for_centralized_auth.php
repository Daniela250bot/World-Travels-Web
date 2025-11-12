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
            // Agregar campos para autenticación centralizada
            $table->string('userable_type')->nullable()->after('role'); // Para polimorfismo
            $table->unsignedBigInteger('userable_id')->nullable()->after('userable_type'); // Para polimorfismo

            // Campos adicionales para JWT y gestión de usuarios
            $table->string('codigo_verificacion')->nullable()->after('userable_id');
            $table->timestamp('codigo_verificacion_expires_at')->nullable()->after('codigo_verificacion');
            $table->boolean('verificado')->default(false)->after('codigo_verificacion_expires_at');
            $table->string('fcm_token')->nullable()->after('verificado'); // Para notificaciones push
            $table->string('foto_perfil')->nullable()->after('fcm_token');

            // Índices para polimorfismo
            $table->index(['userable_type', 'userable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['userable_type', 'userable_id']);
            $table->dropColumn([
                'userable_type',
                'userable_id',
                'codigo_verificacion',
                'codigo_verificacion_expires_at',
                'verificado',
                'fcm_token',
                'foto_perfil'
            ]);
        });
    }
};
