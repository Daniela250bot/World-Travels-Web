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
        Schema::table('actividades', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['idCategoria']);

            // Add the new foreign key constraint pointing to 'categories' table
            $table->foreign('idCategoria')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['idCategoria']);

            // Add back the old foreign key constraint pointing to 'categorias__actividades' table
            $table->foreign('idCategoria')->references('id')->on('categorias__actividades')->onDelete('cascade');
        });
    }
};
