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
        Schema::table('comentarios_reservas', function (Blueprint $table) {
            $table->tinyInteger('calificacion')->unsigned()->nullable()->after('comentario'); // 1-5 estrellas
            $table->boolean('aprobado')->default(false)->after('calificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comentarios_reservas', function (Blueprint $table) {
            $table->dropColumn(['calificacion', 'aprobado']);
        });
    }
};
