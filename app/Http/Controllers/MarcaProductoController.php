<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarcaProducto;
use App\Models\Producto;
use App\Models\PedidoProducto;
use App\Http\Requests\MarcaCreateRequest;
use App\Http\Requests\MarcaEditRequest;
use DB;

class MarcaProductoController extends Controller
{

    public function __construct(){
        $this->middleware('jefe')->only('create', 'store', 'edit', 'update', 'destroy' );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productomarca.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarcaCreateRequest $request)
    {
        $marca = new MarcaProducto($request->all());
        // dd($producto, $request);
        try{
            $marca->save();
            $data['message'] = 'Se ha creado correctamente una nueva marca de producto';
            return redirect('producto')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar guardar';
            return back()->withInput()->with($data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(MarcaProducto $marca)
    {
        $data = [];
        $data['marca'] = $marca;
        return view('productomarca.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(MarcaEditRequest $request, MarcaProducto $marca)
    {
        // dd($producto, $request);
        
        
        try{
            $marca->update($request->all()) ;
            $data['message'] = 'Se ha editado correctamente la marca del producto ' . $marca->nombre;
            return redirect('producto')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar editar la marca del producto ' . $marca->nombre;
            return back()->withInput()->with($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarcaProducto $marca)
    {
        $data = [];
        $data['message'] = 'La marca de producto ' . $marca->nombre .' y las referencias a el mismo se han borrado correctamente';
        
        $productos = DB::select('SELECT id FROM productos WHERE IDMarca = ' . $marca->id);
        
            // dd($productos);
        DB::beginTransaction();
        foreach($productos as $producto){
            $productoBorrado = Producto::find($producto->id);
            
            $pedidosProductos = DB::select('SELECT id FROM pedido_productos WHERE IDProducto = ' . $productoBorrado->id);
            foreach($pedidosProductos as $pedidosProducto){
                $pedidosProductoModificado = PedidoProducto::find($pedidosProducto->id);
                $pedidosProductoModificado->IDProducto = null;
                try{
                    $result = $pedidosProductoModificado->update();
                } catch (\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos productos';
                    return back()->withInput()->with($data);
                }
            }
            
            try{
                $result = $productoBorrado->delete();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias del producto';
                return back()->withInput()->with($data);
            }
        }
        try{
            $result = $marca->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'La marca de producto ' . $marca->nombre .' y las referencias no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('producto'))->with($data);
    }
}
