<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Producto;
use App\Models\PedidoProducto;

class AjaxController extends Controller
{
    function email(Request $request){
        $response = false;
        $email = $request->email;
        if($email != null){
            $user = User::where('email', $email)->first();
            if($user == null){
                $response = true;
            }
        }
        //json
        return response()->json(["respuesta" => $response]);
    }
    
    function name(Request $request){
        $response = false;
        $name = $request->name;
        if($name != null){
            $user = User::where('name', $name)->first();
            if($user == null){
                $response = true;
            }
        }
        //json
        return response()->json(["respuesta" => $response]);
    }
    
    function producto(Request $request){
        // dd($request);
        
        $producto = $request->producto;
        $cantidad = $request->cantidad;
        $productoUnidades = Producto::find($producto, 'unidadesDisponibles')->unidadesDisponibles;
        if($productoUnidades > $cantidad){
           $response = true;
        }else{
            $response = false;
        }
        //json
        return response()->json(["respuesta" => $response, "cantidadMaxima" => $productoUnidades]);
    }
    
    function productoDos(Request $request){
        // dd($request);
        
        $pedidoproducto = $request->pedidoproducto;
        $cantidadRequest = $request->cantidad;
        $pedido = PedidoProducto::find($pedidoproducto, ['IDProducto', 'cantidad']);
        // dd($pedido);
        $productoUnidades = Producto::find($pedido->IDProducto, 'unidadesDisponibles')->unidadesDisponibles;
        $cantidadAñadir = $cantidadRequest - $pedido->cantidad;
        // dd($cantidadAñadir);
        
        $cantidadMaxima =0;
        if($productoUnidades > $cantidadAñadir){
           $response = true;
        }else{
            $response = false;
            $cantidadMaxima = $pedido->cantidad + $productoUnidades;
        }
        //json
        return response()->json(["respuesta" => $response, "cantidadMaxima" => $cantidadMaxima]);
    }
    
    function productoCorrecto(Request $request){
        // dd($request);
        
        $idproducto = $request->pedidoproducto;
        // dd($idproducto);
        $producto = Producto::find($idproducto, 'nombre');
        if($producto != null){
           $response = "correcta";
        }else{
            $response = "incorrecta";
        }
        //json
        return response()->json(["respuesta" => $response]);
    }
}