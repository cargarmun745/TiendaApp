<?php

namespace App\Http\Controllers;

use App\Models\PedidoProducto;
use App\Models\Pedido;
use Illuminate\Http\Request;
use DB;
use App\Models\Producto;

class PedidoProductoController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PedidoProducto  $pedidoProducto
     * @return \Illuminate\Http\Response
     */
    public function destroy(PedidoProducto $pedidoProducto)
    {
        DB::beginTransaction();
        $data['message'] = 'El producto del pedido se han borrado correctamente';
        $data['type'] = 'success';
        
        $pedidoModificado = Pedido::find($pedidoProducto->IDPedido);
        $precioTotal = $pedidoProducto->precioProducto * $pedidoProducto->cantidad;
        $pedidoModificado->precioTotal = $pedidoModificado->precioTotal - $precioTotal;
        try{
            $result = $pedidoModificado->update();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos';
            return back()->withInput()->with($data);
        }
        
        if(isset($pedidoProducto->IDProducto)){
            $productoModificado = Producto::find($pedidoProducto->IDProducto);
            // dd($productoModificado);
            $productoModificado->unidadesDisponibles = $productoModificado->unidadesDisponibles + $pedidoProducto->cantidad;
            try{
                $result = $productoModificado->update();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias de los productos';
                return back()->withInput()->with($data);
            }
        }
            
        
        
        try{
            $result = $pedidoProducto->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'No se ha podido borrar correctamente el producto del pedido';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('pedido'))->with($data);
    }
}
