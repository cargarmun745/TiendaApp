<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CargoEmpleado;
use App\Models\Empleado;
use App\Http\Requests\CargoCreateRequest;
use App\Http\Requests\CargoEditRequest;
use DB;

class CargoEmpleadoController extends Controller
{
    
    
    public function __construct(){
        $this->middleware('jefe')->only('index', 'show');
        $this->middleware('admin')->only('create', 'store', 'edit', 'update', 'destroy' );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empleadocargo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoCreateRequest $request)
    {
        $cargo = new CargoEmpleado($request->all());
        // dd($producto, $request);
        try{
            $cargo->save();
            $data['message'] = 'Se ha creado correctamente un nuevo cargo de empleado';
            return redirect('empleado')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar guardar';
            return back()->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(CargoEmpleado $cargo)
    {
        $data = [];
        
        $empleados = DB::table('cargo_empleados')->where('cargo_empleados.id',  $cargo->id)
        ->join('empleados', 'empleados.IDCargo', '=', 'cargo_empleados.id')
        ->join('users', 'users.id', '=', 'empleados.IDUsuario')
        ->select('empleados.nombre', 'empleados.apellidos', 'users.rol')->get();
        
        
        $data['empleados'] = $empleados;
        $data['cargo'] = $cargo;
        return view('empleadocargo.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(CargoEmpleado $cargo)
    {
        $data = [];
        $data['cargo'] = $cargo;
        return view('empleadocargo.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(CargoEditRequest $request, CargoEmpleado $cargo)
    {
        // dd($producto, $request);
        
        
        try{
            $cargo->update($request->all()) ;
            $data['message'] = 'Se ha editado correctamente el cargo ' . $cargo->nombre;
            return redirect('empleado')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar editar el cargo ' . $cargo->nombre;
            return back()->withInput()->with($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(CargoEmpleado $cargo)
    {
        $data = [];
        $data['message'] = 'El cargo de empleado ' . $cargo->nombre .' y las referencias a el mismo se han borrado correctamente';
        
        $empleados = DB::select('SELECT id FROM empleados WHERE IDCargo = ' . $cargo->id);
        
        DB::beginTransaction();
        foreach($empleados as $empleado){
            $empleadoBorrar = Empleado::find($empleado->id);
            $empleadoBorrar->IDCargo = null;
            try{
                $result = $empleadoBorrar->update();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias';
                return back()->withInput()->with($data);
            }
        }
        try{
            $result = $cargo->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El cargo de empleado ' . $cargo->nombre .' no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('empleado'))->with($data);
    }
}
