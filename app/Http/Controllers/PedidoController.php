<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\PedidoProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use DB;

use App\Http\Requests\PedidoCreateRequest;
use App\Http\Requests\PedidoEditRequest;

class PedidoController extends Controller
{
    
    public function __construct(){
        $this->middleware('userverified')->only('userindex','usercreate','userstore','usershow', 'useredit', 'userupdate', 'userdelete');
        $this->middleware('admin')->only('create', 'store');
        $this->middleware('jefe')->only('index', 'show', 'edit', 'update', 'destroy' );
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
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $pedidos = Pedido::all();
        $data['pedidos']=$pedidos;
        return view('pedido/index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $todosProductos = Producto::all();
        $data['todosProductos']=$todosProductos;
        $metodos = ['efectivo', 'tarjeta'];
        $data['metodos'] = $metodos;
        return view('pedido.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PedidoCreateRequest $request)
    {
        // dd($request);
        $pedido = new Pedido($request->all());
        $pedido->precioTotal = 0;
        // dd($pedido, $request);
        DB::beginTransaction();
        try{
            $pedido->save();
            
        }catch(\Exception $e){
            DB::rollBack();
            $data['message'] = 'Ha habido un error al intentar guardar';
            return back()->withInput()->with($data);
        }
            // dd($request);
        $precioNuevoTotal = $this->crearPedidoProducto($request, $pedido);
        
        
        if(isset($precioNuevoTotal['message'])){
            dd($precioNuevoTotal['message']);
            DB::rollBack();
            return back()->withInput()->with($precioNuevoTotal);
        }else{
            $pedido->precioTotal = $precioNuevoTotal['precioTotal'];
            $pedido->update();
        }
        // dd($pedido);
        DB::commit();
            $data['message'] = 'Se ha creado correctamente un nuevo pedido';
        return redirect('pedido')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $data['pedido']=$pedido;
        
        $pedidoproductos = PedidoProducto::where('IDPedido', $pedido->id)->get(['nombreProducto', 'precioProducto', 'cantidad', 'IDProducto', 'id']);
        // dd($pedidoproductos->count());
        if($pedidoproductos->count() != 0){
            // dd($pedidoproductos);
            $data['pedidoproductos']=$pedidoproductos;
        }
        
        return view('pedido/show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $data['pedido']=$pedido;
        $metodos = ['efectivo', 'tarjeta'];
        $data['metodos'] = $metodos;
        
        $pedidosProductos = PedidoProducto::where('IDPedido', $pedido->id)->get(['id', 'nombreProducto', 'cantidad', 'IDProducto']);
        $data['pedidosProductos'] = $pedidosProductos;
        
        $todosProductos = Producto::all();
        $data['todosProductos']=$todosProductos;
        
        return view('pedido.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(PedidoEditRequest $request, Pedido $pedido)
    {
        
        $precioNuevoTotal = 0;
        // dd($request);
        DB::beginTransaction();
        try{
            $pedido->update($request->all()) ;
            $data['message'] = 'Se ha editado correctamente el pedido ' . $pedido->nombre;
            
        }catch(\Exception $e){
            DB::rollBack();
            $data['message'] = 'Ha habido un error al intentar editar el pedido ' . $pedido->nombre;
            return back()->withInput()->with($data);
        }
            // dd($request);
            // dd($request->input('productoCreado'));
        if($request->input('productoCreado') != null){
            $size = count($request->input('productoCreado'));
            // dd($request);
        }else{
            $size = 0;
        }
        
        for($i = 0; $i < $size; $i++){
            $pedidoProducto = PedidoProducto::find($request->input('productoCreado')[$i]);
            // dd($pedidoProducto);
            $productos = Producto::find($pedidoProducto->IDProducto);
            if($productos != null){
                $pedidoProducto->nombreProducto = $productos->nombre;
                $pedidoProducto->precioProducto = $productos->precio;
                
                try{
                    $productos->unidadesDisponibles = $productos->unidadesDisponibles - $request->input('cantidadCreada')[$i] + $pedidoProducto->cantidad;
                    $productos->update();
                    // dd($pedidoProducto->cantidad,$pedidoProducto->precioProducto, $precioNuevoTotal);
                
                }catch(\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'Ha habido un error al intentar guardar';
                    return $data;
                }
                
                $pedidoProducto->cantidad = $request->input('cantidadCreada')[$i];
                
                try{
                    $pedidoProducto->update();
                    $precioNuevoTotal += $pedidoProducto->cantidad * $pedidoProducto->precioProducto;
                    // dd($pedidoProducto->cantidad,$pedidoProducto->precioProducto, $precioNuevoTotal);
                    $data['message'] = 'Se ha creado correctamente un nuevo pedido';
                
                }catch(\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'Ha habido un error al intentar guardar';
                    return back()->withInput()->with($data);
                }
            }
        }
        
        // dd($request->input('producto')[0]);
        if($request->input('producto')[0] != null){
            // dd('perro');
            $precioNuevoTotalNuevos = $this->crearPedidoProducto($request, $pedido);
            
            if(isset($precioNuevoTotalNuevos['message'])){
                // dd($precioNuevoTotalNuevos['message']);
                DB::rollBack();
                return back()->withInput()->with($precioNuevoTotalNuevos);
            }else{
                $pedido->precioTotal = $precioNuevoTotalNuevos['precioTotal'] + $precioNuevoTotal;
                $pedido->update();
            }
        }else{
            $pedido->precioTotal = $precioNuevoTotal;
            $pedido->update();
        }
        
        DB::commit();
        return redirect('pedido')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        
        DB::beginTransaction();
        $data['message'] = 'El pedido con fecha de realización de ' . $pedido->fechaPedido .' se han borrado correctamente';
        $data['type'] = 'success';
        $pedidosProductos = DB::select('SELECT id FROM pedido_productos WHERE IDPedido = ' . $pedido->id);
            
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
            $result = $pedido->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'No se ha podido borrar correctamente el pedidos';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('pedido'))->with($data);
            
    }
    
    public function crearPedidoProducto(Request $request, $pedido){
        $precioNuevoTotal = 0;
        DB::beginTransaction();
        if($request->input('producto')[0] != null){
            $size = count($request->input('producto'));
        }else{
            $size = 0;
        }
        
        for($i = 0; $i < $size; $i++){
            $productos = Producto::find( $request->input('producto')[$i]);
            if($productos != null){
                $pedidoProducto = new PedidoProducto;
                $pedidoProducto->IDPedido = $pedido->id;
                $pedidoProducto->IDProducto = $request->input('producto')[$i];
                
                // dd($pedidoProducto->IDProducto);
                $pedidoProducto->nombreProducto = $productos->nombre;
                $pedidoProducto->precioProducto = $productos->precio;
                $pedidoProducto->cantidad = $request->input('cantidad')[$i];
                
                
                try{
                    $productos->unidadesDisponibles = $productos->unidadesDisponibles - $request->input('cantidad')[$i];
                    $productos->update();
                    // dd($pedidoProducto->cantidad,$pedidoProducto->precioProducto, $precioNuevoTotal);
                
                }catch(\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'Ha habido un error al intentar guardar';
                    return $data;
                }
                
            }
            
            try{
                $pedidoProducto->save();
                $precioNuevoTotal += $pedidoProducto->cantidad * $pedidoProducto->precioProducto;
                // dd($pedidoProducto->cantidad,$pedidoProducto->precioProducto, $precioNuevoTotal);
            
            }catch(\Exception $e){
                DB::rollBack();
                $data['message'] = 'Ha habido un error al intentar guardar';
                return $data;
            }
        }
        DB::commit();
        $data['precioTotal'] = $precioNuevoTotal;
        return $data;
    }
    
    
    
    
    
    
    
    public function userindex()
    {
        if(auth()->user()->rol == 'cliente'){
            $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
            $pedidos = Pedido::where('IDCliente', $cliente->id)->get();
            $data['cliente']=$cliente;
        }else{
            $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
            $pedidos = Pedido::where('IDEmpleado', $empleado->id)->get();
            $data['empleado']=$empleado;
        }
        
        
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        // $pedidos = Pedido::all();
        $data['pedidos']=$pedidos;
        return view('pedido/indexpropio')->with($data);
    }
    
    
    
    
    public function userindexproductos()
    {
        $data = [];
        if(auth()->user()->rol == 'cliente'){
            $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
            $pedidos = Pedido::where('IDCliente', $cliente->id)->get();
            $data['cliente']=$cliente;
        }else{
            $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
            $pedidos = Pedido::where('IDEmpleado', $empleado->id)->get();
            $data['empleado']=$empleado;
        }
        $productos = [];
        foreach($pedidos as $pedido){
            $pedidoProductos = PedidoProducto::where('IDPedido', $pedido->id)->get();
            
            foreach($pedidoProductos as $pedidoProducto){
                if(isset($productos[$pedidoProducto->IDProducto])){
                    $productos[$pedidoProducto->IDProducto] += $pedidoProducto->cantidad;
                }else{
                    $productos[$pedidoProducto->IDProducto] = $pedidoProducto->cantidad;
                }
            }
            
        }
        
        $contador=0;
        $productosFinal = [];
        // dd($productos);
        foreach($productos as $i => $producto){
            
            $productoNuevo = Producto::where('id', $i)->first()->nombre;
            $productosFinal[$contador] = [$productoNuevo, $producto];
            if($contador==0){
                
            }
            $contador++;
        }
        
        if($productosFinal != []){
            $data['productos']=$productosFinal;
        }
        
        return view('pedido/indexpropioproductos')->with($data);
    }
    
    
    public function usershow(Pedido $pedido)
    {
        if(auth()->user()->rol == 'cliente'){
            if($pedido->IDCliente == auth()->user()->id){
                $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
                $data['cliente']=$cliente;
                // dd($pedido);
            }else{
                $data['message'] = 'No puedes acceder a un pedido que no es tuyo';
                return back()->withInput()->with($data);
            }
        }else{
            $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
            $data['empleado']=$empleado;
        }
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $data['pedido']=$pedido;
        
        $pedidoproductos = PedidoProducto::where('IDPedido', $pedido->id)->get(['nombreProducto', 'precioProducto', 'cantidad', 'IDProducto', 'id']);
        // dd($pedidoproductos->count());
        if($pedidoproductos->count() != 0){
            // dd($pedidoproductos);
            $data['pedidoproductos']=$pedidoproductos;
        }
        
        return view('pedido/show')->with($data);
    }
    public function useredit(Pedido $pedido)
    {
        if(auth()->user()->rol == 'cliente'){
            if($pedido->IDCliente == auth()->user()->id){
                $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
                $data['cliente']=$cliente;
                // dd($pedido);
            }else{
                $data['message'] = 'No puedes acceder a un pedido que no es tuyo';
                return back()->withInput()->with($data);
            }
        }else{
            $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
            $data['empleado']=$empleado;
        }
        
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $data['pedido']=$pedido;
        $metodos = ['efectivo', 'tarjeta'];
        $data['metodos'] = $metodos;
        
        $pedidosProductos = PedidoProducto::where('IDPedido', $pedido->id)->get(['id', 'nombreProducto', 'cantidad', 'IDProducto']);
        $data['pedidosProductos'] = $pedidosProductos;
        
        $todosProductos = Producto::all();
        $data['todosProductos']=$todosProductos;
        
        return view('pedido.editpropio')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function userupdate(PedidoEditRequest $request, Pedido $pedido)
    {
        
        if(auth()->user()->rol == 'cliente'){
            if($pedido->IDCliente == auth()->user()->id){
            }else{
                $data['message'] = 'No puedes acceder a un pedido que no es tuyo';
                return back()->withInput()->with($data);
            }
        }
        
        $precioNuevoTotal = 0;
        // dd($request);
        DB::beginTransaction();
        try{
            $pedido->update($request->all()) ;
            $data['message'] = 'Se ha editado correctamente el pedido ' . $pedido->nombre;
            
        }catch(\Exception $e){
            DB::rollBack();
            $data['message'] = 'Ha habido un error al intentar editar el pedido ' . $pedido->nombre;
            return back()->withInput()->with($data);
        }
            // dd($request);
            // dd($request->input('productoCreado'));
        if($request->input('productoCreado') != null){
            $size = count($request->input('productoCreado'));
            // dd($request);
        }else{
            $size = 0;
        }
        
        for($i = 0; $i < $size; $i++){
            $pedidoProducto = PedidoProducto::find($request->input('productoCreado')[$i]);
            // dd($pedidoProducto);
            $productos = Producto::find($pedidoProducto->IDProducto);
            if($productos != null){
                $pedidoProducto->nombreProducto = $productos->nombre;
                $pedidoProducto->precioProducto = $productos->precio;
                
                try{
                    $productos->unidadesDisponibles = $productos->unidadesDisponibles - $request->input('cantidadCreada')[$i] + $pedidoProducto->cantidad;
                    $productos->update();
                    // dd($pedidoProducto->cantidad,$pedidoProducto->precioProducto, $precioNuevoTotal);
                
                }catch(\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'Ha habido un error al intentar guardar';
                    return $data;
                }
                
                $pedidoProducto->cantidad = $request->input('cantidadCreada')[$i];
                
                try{
                    $pedidoProducto->update();
                    $precioNuevoTotal += $pedidoProducto->cantidad * $pedidoProducto->precioProducto;
                    // dd($pedidoProducto->cantidad,$pedidoProducto->precioProducto, $precioNuevoTotal);
                    $data['message'] = 'Se ha creado correctamente un nuevo pedido';
                
                }catch(\Exception $e){
                    DB::rollBack();
                    $data['message'] = 'Ha habido un error al intentar guardar';
                    return back()->withInput()->with($data);
                }
            }
        }
        
        // dd($request->input('producto')[0]);
        if($request->input('producto')[0] != null){
            // dd('perro');
            $precioNuevoTotalNuevos = $this->crearPedidoProducto($request, $pedido);
            
            if(isset($precioNuevoTotalNuevos['message'])){
                // dd($precioNuevoTotalNuevos['message']);
                DB::rollBack();
                return back()->withInput()->with($precioNuevoTotalNuevos);
            }else{
                $pedido->precioTotal = $precioNuevoTotalNuevos['precioTotal'] + $precioNuevoTotal;
                $pedido->update();
            }
        }else{
            $pedido->precioTotal = $precioNuevoTotal;
            $pedido->update();
        }
        
        DB::commit();
        return redirect('pedidoindex')->with($data);
    }
    
    
    public function usercreate()
    {
        
        if(auth()->user()->rol == 'cliente'){
            $cliente = Cliente::where('IDUsuario', auth()->user()->id)->first();
            $data['cliente']=$cliente;
        }else{
            $empleado = Empleado::where('IDUsuario', auth()->user()->id)->first();
            $data['empleado']=$empleado;
        }
        $clientes = Cliente::all();
        $data['clientes']=$clientes;
        $empleados = Empleado::all();
        $data['empleados']=$empleados;
        $todosProductos = Producto::all();
        $data['todosProductos']=$todosProductos;
        $metodos = ['efectivo', 'tarjeta'];
        $data['metodos'] = $metodos;
        return view('pedido.createpropio')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userstore(PedidoCreateRequest $request)
    {
        // dd($request);
        $pedido = new Pedido($request->all());
        $pedido->precioTotal = 0;
        // dd($pedido, $request);
        DB::beginTransaction();
        try{
            $pedido->save();
            
        }catch(\Exception $e){
            DB::rollBack();
            $data['message'] = 'Ha habido un error al intentar guardar';
            return back()->withInput()->with($data);
        }
            // dd($request);
        $precioNuevoTotal = $this->crearPedidoProducto($request, $pedido);
        
        
        if(isset($precioNuevoTotal['message'])){
            dd($precioNuevoTotal['message']);
            DB::rollBack();
            return back()->withInput()->with($precioNuevoTotal);
        }else{
            $pedido->precioTotal = $precioNuevoTotal['precioTotal'];
            $pedido->update();
        }
        // dd($pedido);
        DB::commit();
        $data['message'] = 'Se ha creado correctamente un nuevo pedido';
        if(auth()->user()->rol == 'jefe'){
            return redirect('pedido')->with($data);
        }else{
            return redirect('pedidoindex')->with($data);
        }
    }

}
