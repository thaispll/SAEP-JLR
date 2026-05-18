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
        Schema::create('movimentos', function (Blueprint $table) {
            $table->id();
            //        'produto_id', 'quantidade', 'tipo'
            $table->foreignId('produto_id')->constraint()->cascadeOnDelete();
            //migration que cria uma foreign key com comportamento automático de exclusão em cascata
            //Exclusão em cascata: se um registro da tabela produtos for excluído, todos 
            //os registros da tabela atual que referenciam esse produto_id também serão
            //apagados automaticamente
            $table->integer('quantidade');
            $table->enum('tipo', ['entrada', 'saida']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentos');
    }
};
