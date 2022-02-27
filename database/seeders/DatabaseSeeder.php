<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CargoEmpleado::factory(50)->create();
        \App\Models\Empleado::factory(50)->create();
        \App\Models\TipoProducto::factory(50)->create();
        \App\Models\MarcaProducto::factory(50)->create();
        \App\Models\Producto::factory(50)->create();
        \App\Models\Cliente::factory(50)->create();
        \App\Models\Pedido::factory(50)->create();
        \App\Models\PedidoProducto::factory(50)->create();
    }
}
