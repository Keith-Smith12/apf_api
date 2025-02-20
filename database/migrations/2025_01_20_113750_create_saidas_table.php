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
        Schema::create('saidas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao');
            $table->float('valor');
            $table->date('data_saida');
            $table->foreignId('id_users')->constrained('users');
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->foreignId('id_subcategoria')->nullable()->constrained('sub_categorias');
            $table->foreignId('id_meta')->nullable()->constrained('metas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saidas');
    }
};
