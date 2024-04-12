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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Desactiva la verificación de claves foráneas
        Schema::disableForeignKeyConstraints();
    
        // Elimina la tabla categories
        Schema::dropIfExists('categories');
    
        // Reactiva la verificación de claves foráneas
        Schema::enableForeignKeyConstraints();
    }
    
};
