<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombreProducto', 50);
            $table->decimal('precioProducto', 9);
            $table->bigInteger('cantidad')->default(1);
            
            $table->bigInteger('IDPedido')->unsigned();
            $table->bigInteger('IDProducto')->unsigned()->nullable();
            
            $table->foreign('IDPedido')->references('id')->on('pedidos');
            $table->foreign('IDProducto')->references('id')->on('productos');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_productos');
    }
}
