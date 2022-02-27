<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ClienteCreateRequest;
use App\Http\Requests\ClienteEditUserRequest;
use App\Http\Requests\ClienteEditRequest;

class ClienteController extends Controller
{
     
    public function __construct(){
        $this->middleware('userverified')->only('usershow', 'useredit', 'userupdate', 'userdelete');
        $this->middleware('empleado')->only('index', 'show');
        $this->middleware('jefe')->only('create', 'store', 'edit', 'update', 'destroy' );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     
    public function index()
    {
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $users = User::all();
        $data['users']=$users;
        return view('cliente/index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cliente.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteCreateRequest $request)
    {
        $register = new RegisterController();
        // DB::beginTransaction();
        // $cliente = new Cliente($request->all());
        // dd($producto, $request);
        try{
            $result = $register->register($request);
            // $cliente->save();
            $data['message'] = 'Se ha creado correctamente un nuevo cliente';
            return redirect('cliente')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar guardar el nuevo cliente';
            return back()->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        $data['cliente']=$cliente;
        $users = User::all();
        $data['users']=$users;
        return view('cliente/show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        
        $data['cliente']=$cliente;
        $users = User::all();
        $data['users']=$users;
        return view('cliente/edit')->with($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteEditRequest $request, Cliente $cliente)
    {
        $data = ['message' => 'Se ha editado correctamente el cliente'];
        DB::beginTransaction();
        
        $user = User::find($cliente->IDUsuario)->first();
        try{
            $result = $cliente->update($request->all()) ;
            // dd($result);
        }catch(\Exception $e){
            $result=false;
            DB::rollBack();
            return back()->withInput()->withError(['generico' => 'No se ha podido modificar los datos del cliente']);
        }
        
        
        if($request->oldpassword != null && $request->password != null){
            // Se cambia todo
            if(Hash::check($request->oldpassword, $user->password)){
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
            $data['message'] = 'El cliente ' . $cliente->nombre . ' no se ha podido modificar.';
            $data['type'] = 'danger';
        }
        
        
        DB::commit();
        return redirect(url('cliente'))->with($data);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $data = [];
        $user = User::find($cliente->IDUsuario);
        DB::beginTransaction();
        $data['message'] = 'El cliente ' . $cliente->nombre .' y su usuario se han borrado correctamente';
        $data['type'] = 'success';
        
        $pedidos = DB::select('SELECT id FROM pedidos WHERE IDCliente = ' . $cliente->id);
        
        foreach($pedidos as $pedido){
            $pedidoBorrar = Pedido::find($pedido->id);
            $pedidosProductos = DB::select('SELECT id FROM pedido_productos WHERE IDPedido = ' . $pedidoBorrar->id);
            
            foreach($pedidosProductos as $pedidosProducto){
                $pedidosProductoBorrar = PedidoProducto::find($pedidosProducto->id);
                try{
                    $result = $pedidosProductoBorrar->delete();
                } catch (\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos productos';
                    return back()->withInput()->with($data);
                }
            }
            try{
                $result = $pedidoBorrar->delete();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos';
                return back()->withInput()->with($data);
            }
        }
        
        try{
            $result = $cliente->delete();
            $result = $user->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El cliente ' . $cliente->nombre .' y su usuario no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('cliente'))->with($data);
    }
    
    
    public function usershow()
    {
        $cliente = DB::table('users')->where('users.id',  auth()->user()->id)
        ->join('clientes', 'users.id', '=', 'clientes.IDUsuario')
        ->select('clientes.nombre', 'clientes.apellidos', 'clientes.DNI', 'clientes.direccion', 'users.name', 'users.email')->first();
        
        $data['cliente']=$cliente;
        return view('cliente/showpropio')->with($data);
    }
    
    
    public function useredit(){
        
        $cliente = DB::table('users')->where('users.id',  auth()->user()->id)
        ->join('clientes', 'users.id', '=', 'clientes.IDUsuario')
        ->select('clientes.nombre', 'clientes.apellidos', 'clientes.DNI', 'clientes.direccion', 'users.name', 'users.email')->first();
        // dd($cliente);
        
        $data['cliente']=$cliente;
        return view('cliente/editpropio')->with($data);
    }

    public function userupdate(ClienteEditUserRequest $request){
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
        // dd($result);
        if($result){
            $data = ['message' => 'Se ha editado correctamente.'];
        }
        else{
            $data = ['message' => 'Ha habido un error en el guardado.'];
            return back()->withInput()->with($data);
        }
        return redirect(url('/home'))->with($data);
    }
    
    
    public function userSave(Request $request, $isNewPassword){
        $result = true;
        $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
        
        $cliente->nombre = $request->input('nombre');
        $cliente->apellidos = $request->input('apellidos');
        $cliente->DNI = $request->input('DNI');
        $cliente->direccion = $request->input('direccion');
        // dd($cliente);
        try{
            $cliente->save();
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
        // dd($result);
        return $result;
    }
    
    
    public function userdelete()
    {
        $data = [];
        $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
        
        $user = auth()->user();
        DB::beginTransaction();
        $data['message'] = 'El cliente ' . $cliente->nombre .' y su usuario se han borrado correctamente';
        $data['type'] = 'success';
        
        $pedidos = DB::select('SELECT id FROM pedidos WHERE IDCliente = ' . $cliente->id);
        
        foreach($pedidos as $pedido){
            $pedidoBorrar = Pedido::find($pedido->id);
            $pedidosProductos = DB::select('SELECT id FROM pedido_productos WHERE IDPedido = ' . $pedidoBorrar->id);
            
            foreach($pedidosProductos as $pedidosProducto){
                $pedidosProductoBorrar = PedidoProducto::find($pedidosProducto->id);
                try{
                    $result = $pedidosProductoBorrar->delete();
                } catch (\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos productos';
                    return back()->withInput()->with($data);
                }
            }
            try{
                $result = $pedidoBorrar->delete();
            } catch (\Exception $e){
                DB::rollBack();
                $data['message'] = 'No se ha podido borrar correctamente las referencias de los pedidos';
                return back()->withInput()->with($data);
            }
        }
        
        try{
            $result = $cliente->delete();
            $result = $user->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El cliente ' . $cliente->nombre .' y su usuario no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('login'))->with($data);
    }
}
