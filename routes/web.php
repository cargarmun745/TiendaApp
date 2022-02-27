<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TipoProductoController;
use App\Http\Controllers\MarcaProductoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CargoEmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoProductoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/homeDos', [App\Http\Controllers\HomeController::class, 'segundo'])->name('homeSegundo');

Route::resource('producto', ProductoController::class);
Route::get('productoindex', [ProductoController::class, 'userindex'])->name('producto.userindex');
Route::get('productoshow/{producto}', [ProductoController::class, 'usershow'])->name('producto.usershow');
Route::resource('marca', MarcaProductoController::class)->except('index', 'show');
Route::resource('tipo', TipoProductoController::class)->except('index', 'show');

Route::resource('empleado', EmpleadoController::class);
Route::get('empleadoshow', [EmpleadoController::class, 'usershow'])->name('empleado.usershow');
Route::get('empleadoedit', [EmpleadoController::class, 'useredit'])->name('empleado.useredit');
Route::put('empleadoupdate', [EmpleadoController::class, 'userupdate'])->name('empleado.userupdate');
Route::delete('empleadodelete', [EmpleadoController::class, 'userdelete'])->name('empleado.userdelete');
Route::resource('cargo', CargoEmpleadoController::class)->except('index');

Route::resource('cliente', ClienteController::class);
Route::get('clienteshow', [ClienteController::class, 'usershow'])->name('cliente.usershow');
Route::get('clienteedit', [ClienteController::class, 'useredit'])->name('cliente.useredit');
Route::put('clienterupdate', [ClienteController::class, 'userupdate'])->name('cliente.userupdate');
Route::delete('clientedelete', [ClienteController::class, 'userdelete'])->name('cliente.userdelete');

Route::resource('pedido', PedidoController::class);
Route::get('pedidoindex', [PedidoController::class, 'userindex'])->name('pedido.userindex');
Route::get('pedidoindexproductos', [PedidoController::class, 'userindexproductos'])->name('pedido.userindexproductos');
Route::get('pedidoshow/{pedido}', [PedidoController::class, 'usershow'])->name('pedido.usershow');
Route::post('pedidostore', [PedidoController::class, 'userstore'])->name('pedido.userstore');
Route::get('pedidocreate', [PedidoController::class, 'usercreate'])->name('pedido.usercreate');
Route::get('pedidoedit/{pedido}', [PedidoController::class, 'useredit'])->name('pedido.useredit');
Route::put('pedidoupdate/{pedido}', [PedidoController::class, 'userupdate'])->name('pedido.userupdate');
Route::delete('pedidodelete/{pedido}', [PedidoController::class, 'userdelete'])->name('pedido.userdelete');

Route::delete('pedidoproducto/{pedidoProducto}', [PedidoProductoController::class, 'destroy'])->name('pedidoproducto.destroy');

Route::get('ajax/email', [App\Http\Controllers\AjaxController::class, 'email']);
Route::get('ajax/name', [App\Http\Controllers\AjaxController::class, 'name']);
Route::get('ajax/producto', [App\Http\Controllers\AjaxController::class, 'producto']);
Route::get('ajax/producto2', [App\Http\Controllers\AjaxController::class, 'productoDos']);
Route::get('ajax/productoCorrecto', [App\Http\Controllers\AjaxController::class, 'productoCorrecto']);