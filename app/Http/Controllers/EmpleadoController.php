<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\CargoEmpleado;
use App\Models\Pedido;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests\EmpleadoCreateRequest;
use App\Http\Requests\EmpleadoEditRequest;
use App\Http\Requests\EmpleadoEditUserRequest;

class EmpleadoController extends Controller
{
    
    public function __construct(){
        $this->middleware('empleado')->only('usershow', 'useredit', 'userupdate', 'userdelete');
        $this->middleware('jefe')->only('index', 'show');
        $this->middleware('admin')->only('create', 'store', 'edit', 'update', 'destroy' );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $cargos = CargoEmpleado::all();
        $data['cargos']=$cargos;
        $users = User::all();
        $data['users']=$users;
        return view('empleado/index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cargos = CargoEmpleado::all();
        $data['cargos']=$cargos;
        return view('empleado.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadoCreateRequest $request)
    {
        $register = new RegisterController();
        // DB::beginTransaction();
        // $cliente = new Cliente($request->all());
        // dd($producto, $request);
        try{
            $result = $register->registerEmpleados($request);
            // $cliente->save();
            $data['message'] = 'Se ha creado correctamente un nuevo empleado';
            return redirect('empleado')->with($data);
            
        }catch(\Exception $e){
            // dd($e);
            $data['message'] = 'Ha habido un error al intentar guardar el nuevo empleado';
            return back()->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        $data['empleado']=$empleado;
        $cargos = CargoEmpleado::all();
        $data['cargos']=$cargos;
        $users = User::all();
        $data['users']=$users;
        return view('empleado/show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
        $data['empleado']=$empleado;
        $cargos = CargoEmpleado::all();
        $data['cargos']=$cargos;
        $users = User::all();
        $data['users']=$users;
        return view('empleado/edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(EmpleadoEditRequest $request, Empleado $empleado)
    {
        DB::beginTransaction();
        $data = ['message' => 'Se ha editado correctamente el empleado'];
        $user = User::find($empleado->IDUsuario)->first();
        try{
            $result = $empleado->update($request->all()) ;
            // dd($result);
        }catch(\Exception $e){
            $result=false;
            DB::rollBack();
            return back()->withInput()->withError(['generico' => 'No se ha podido modificar los datos del empleado']);
        }
        
        
        if($request->oldpassword != null && $request->password != null){
            // Se cambia todo
            if(Hash::check($request->oldpassword, auth()->user()->password)){
                $requestData = $request->except(['password']);
            }else{
                DB::rollBack();
                return back()->withInput()->withError(['oldpassword' => 'La clave de acceso anterior no es correcta']);
            }
            
        }elseif($request->oldpassword == null && $request->password == null){
            $requestData = $request->all();
            $requestData->password = Hash::make($request->input('password'));

        }else{
            DB::rollBack();
            return back()->withInput()->withError(['generico' => 'Hay algún dato incorrecto']);
        }
        
        try{
            $user->update($requestData);
        } catch(\Exception $e){
            $data['message'] = 'El empleado ' . $empleado->nombre . ' no se ha podido modificar.';
            $data['type'] = 'danger';
        }

        
        // if($result){
        //     $data = ['message' => 'todo bien'];
        // }else{
        //     $data = ['message' => 'todo mal'];
        // }
        
        
        DB::commit();
        return redirect(url('empleado'))->with($data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empleado $empleado)
    {
        $data = [];
        $user = User::find($empleado->IDUsuario);
        DB::beginTransaction();
        $data['message'] = 'El empleado ' . $empleado->nombre .' y su usuario se han borrado correctamente';
        $data['type'] = 'success';
        
        $pedidos = DB::select('SELECT id FROM pedidos WHERE IDEmpleado = ' . $empleado->id);
        
        foreach($pedidos as $pedido){
            $pedidoModificado = Pedido::find($pedido->id);
            $pedidoModificado->IDEmpleado = null;
            try{
                $result = $pedidoModificado->update();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos';
                return back()->withInput()->with($data);
            }
        }
        
        try{
            $result = $empleado->delete();
            $result = $user->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El empleado ' . $empleado->nombre .' y su usuario no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('empleado'))->with($data);
    }
    
    
    public function usershow()
    {
        $empleado = Empleado::where(auth()->user()->id, 'IDUsuario');
        if(isset($empleado->IDCargo)){
            $empleado = DB::table('users')->where('users.id',  auth()->user()->id)
            ->join('empleados', 'users.id', '=', 'empleados.IDUsuario')
            ->join('cargo_empleados', 'empleados.IDCargo', '=', 'cargo_empleados.id')
            ->select('empleados.nombre', 'empleados.apellidos', 'empleados.DNI', 'empleados.fechacontrato', 'empleados.IDCargo','users.rol', 'users.name', 'users.email', 'cargo_empleados.nombre as nombrecargo')->first();
        }else{
            $empleado = DB::table('users')->where('users.id',  auth()->user()->id)
            ->join('empleados', 'users.id', '=', 'empleados.IDUsuario')
            ->select('empleados.nombre', 'empleados.apellidos', 'empleados.DNI', 'empleados.fechacontrato', 'empleados.IDCargo','users.rol', 'users.name', 'users.email')->first();
        }
        $data['empleado']=$empleado;
        return view('empleado/showpropio')->with($data);
    }
    
    
    public function useredit(){
        
        $empleado = Empleado::where(auth()->user()->id, 'IDUsuario');
        if(isset($empleado->IDCargo)){
            $empleado = DB::table('users')->where('users.id',  auth()->user()->id)
        ->join('empleados', 'users.id', '=', 'empleados.IDUsuario')
        ->join('cargo_empleados', 'empleados.IDCargo', '=', 'cargo_empleados.id')
        ->select('empleados.nombre', 'empleados.apellidos', 'empleados.DNI', 'empleados.fechacontrato', 'empleados.IDCargo','users.rol', 'users.name', 'users.email', 'cargo_empleados.nombre as nombrecargo')->first();
        }else{
            $empleado = DB::table('users')->where('users.id',  auth()->user()->id)
        ->join('empleados', 'users.id', '=', 'empleados.IDUsuario')
        ->select('empleados.nombre', 'empleados.apellidos', 'empleados.DNI', 'empleados.fechacontrato', 'empleados.IDCargo','users.rol', 'users.name', 'users.email')->first();
        }
        
        
        // dd($empleado);
        $cargos = CargoEmpleado::all();
        $data['cargos']=$cargos;
        
        $data['empleado']=$empleado;
        return view('empleado/editpropio')->with($data);
    }

    public function userupdate(EmpleadoEditUserRequest $request){
        if($request->password != null && $request->oldpassword != null){
            $r = Hash::check($request->oldpassword, auth()->user()->password);
            if($r){
                $result = $this->userSave($request, true);
            }
            else{
                return back()->withInput()->withErrors(['oldpassword' => 'The previous password is NOT correct.']);
            }
        }
        elseif($request->password == null && $request->oldpassword == null){
            $result = $this->userSave($request, false);
        }
        else{
            return back()->withInput()->withErrors(['generic' => 'Either all fields must be empty or all must be full.']);
        }
        if($result){
            $data = ['message' => 'Se ha editado correctamente.'];
        }
        else{
            $data = ['message' => 'Ha habido un error en el guardado.'];
        }
        return redirect(url('/home'))->with($data);
    }
    
    
    public function userSave(Request $request, $isNewPassword){
        $result = true;
        $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
        
            // $empleado=$request->all();
        // dd($cliente);
        try{
            $empleado->update($request->all());
        }catch(\Exception $e){
            $result = false;
        }
        
        // $user = auth()->user(); //auth()->user()->id
        
        $user = auth()->user();
        // $user = auth()->user(); //auth()->user()->id
        $user->name = $request->input('name');
        
        if($user->email != $request->input('email')){
            $user->email = $request->input('email');  
            $user->email_verified_at = null;
        }
        
        if($isNewPassword){
            $user->password = Hash::make($request->input('password'));
        }
        
        try{
            $user->update();
            $user->sendEmailVerificationNotification();
        }catch(\Exception $e){
            $result = false;
        }
        return $result;
    }
    
    
    public function userdelete()
    {
        $data = [];
        $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
        
        $user = auth()->user();
        DB::beginTransaction();
        $data['message'] = 'El empleado ' . $empleado->nombre .' y su usuario se han borrado correctamente';
        $data['type'] = 'success';
        
        $pedidos = DB::select('SELECT id FROM pedidos WHERE IDEmpleado = ' . $empleado->id);
        
        foreach($pedidos as $pedido){
            $pedidoModificado = Pedido::find($pedido->id);
            $pedidoModificado->IDEmpleado = null;
            try{
                $result = $pedidoModificado->update();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos';
                return back()->withInput()->with($data);
            }
        }
        
        try{
            $result = $empleado->delete();
            $result = $user->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El empleado ' . $empleado->nombre .' y su usuario no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('login'))->with($data);
    }
}
