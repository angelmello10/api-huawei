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
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('tipo');
        $table->text('mensaje')->nullable();
        $table->text('foto_url')->nullable();
        $table->decimal('latitud', 10,7)->nullable();
        $table->decimal('longitud', 10,7)->nullable();
        $table->timestamp('creado_en')->useCurrent();
        $table->timestamp('expira_en')->nullable();
        $table->enum('estado', ['activo','expirado'])->default('activo');
        $table->bigInteger('user_id')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
