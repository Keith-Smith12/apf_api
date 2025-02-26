<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('meta_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_meta')->constrained('metas');
            $table->decimal('valor', 10, 2);
            $table->enum('tipo', ['entrada', 'saida']);
            $table->foreignId('id_entrada')->nullable()->constrained('entradas');
            $table->timestamp('data');
            $table->timestamps();
        });


        Schema::create('categoria_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->decimal('valor', 10, 2);
            $table->enum('tipo', ['entrada', 'saida']);
            $table->foreignId('id_entrada')->nullable()->constrained('entradas');
            $table->timestamp('data');
            $table->timestamps();
        });
    }






    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_historicos');
        Schema::dropIfExists('categoria_historico');
    }
};
