<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20);
            $table->string('apellidos', 50);
            $table->string('DNI', 9)->unique();
            $table->date('fechacontrato')->useCurrent();
            
            $table->bigInteger('IDCargo')->unsigned()->nullable();
            $table->bigInteger('IDUsuario')->unsigned();
            
            $table->foreign('IDCargo')->references('id')->on('cargo_empleados');
            $table->foreign('IDUsuario')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
