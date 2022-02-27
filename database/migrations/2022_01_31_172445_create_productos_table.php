<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->decimal('precio', 9);
            $table->bigInteger('unidadesDisponibles');
            
            $table->string('mimetype');
            $table->string('filename');
            
            $table->bigInteger('IDMarca')->unsigned();
            $table->bigInteger('IDTipo')->unsigned()->nullable();
            
            $table->foreign('IDMarca')->references('id')->on('marca_productos');
            $table->foreign('IDTipo')->references('id')->on('tipo_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
