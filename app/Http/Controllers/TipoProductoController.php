<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoProducto;
use App\Models\Producto;
use DB;

use App\Http\Requests\TipoCreateRequest;
use App\Http\Requests\TipoEditRequest;

class TipoProductoController extends Controller
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
        return view('productotipo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoCreateRequest $request)
    {
        $tipo = new TipoProducto($request->all());
        // dd($producto, $request);
        try{
            $tipo->save();
            $data['message'] = 'Se ha creado correctamente un nuevo cargo de empleado';
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
    public function edit(TipoProducto $tipo)
    {
        $data = [];
        $data['tipo'] = $tipo;
        return view('productotipo.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(TipoEditRequest $request, TipoProducto $tipo)
    {
        // dd($producto, $request);
        
        try{
            $tipo->update($request->all()) ;
            $data['message'] = 'Se ha editado correctamente el tipo de producto ' . $tipo->nombre;
            return redirect('producto')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar editar el tipo de producto ' . $tipo->nombre;
            return back()->withInput()->with($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoProducto $tipo)
    {
        $data = [];
        $data['message'] = 'El tipo de producto ' . $tipo->nombre .' y las referencias a el mismo se han borrado correctamente';
        
        $productos = DB::select('SELECT id FROM productos WHERE IDTipo = ' . $tipo->id);
        
        DB::beginTransaction();
        foreach($productos as $producto){
            $productoModificar = Producto::find($producto->id);
            $productoModificar->IDTipo = null;
            try{
                $result = $productoModificar->update();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias';
                return back()->withInput()->with($data);
            }
        }
        try{
            $result = $tipo->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El tipo de producto ' . $tipo->nombre .' y las referencias no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('producto'))->with($data);
    }
}
