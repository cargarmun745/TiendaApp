<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->decimal('precioTotal', 9)->default(0);
            $table->enum('metodoDePago', ['efectivo', 'tarjeta'])->default('efectivo');
            $table->date('fechaPedido')->useCurrent();
            
            $table->bigInteger('IDCliente')->unsigned();
            $table->bigInteger('IDEmpleado')->unsigned()->nullable();
            // ATRIBUTO QUE PREGUNTE SI YA SE HA ENVIADO EL PEDIDO Y SINO A LOS 3 DÍAS SE PONE EN TRUE, LOS EMPLEADOS PUEDEN GESTIONAR EL
                // ESTADO DEL PEDIDIO
            
            $table->foreign('IDCliente')->references('id')->on('clientes');
            $table->foreign('IDEmpleado')->references('id')->on('empleados');
            
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
        Schema::dropIfExists('pedidos');
    }
}
