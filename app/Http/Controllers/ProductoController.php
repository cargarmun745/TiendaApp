<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\TipoProducto;
use App\Models\PedidoProducto;
use App\Models\MarcaProducto;
use Illuminate\Http\Request;
use DB;

use App\Http\Requests\ProductoCreateRequest;
use App\Http\Requests\ProductoEditRequest;

class ProductoController extends Controller
{
    
    
    public function __construct(){
        $this->middleware('userverified')->only('index', 'show', 'userindex');
        $this->middleware('jefe')->only('create', 'store', 'edit', 'update', 'destroy' );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    private function verifySort($sort) {
        if($sort == null) {
            return $sort;
        } else if($sort == 'precio') {
            return $sort;
        } else if($sort == 'nombre') {
            return $sort;
        } else if($sort == 'nombremarca') {
            return $sort;
        } else if($sort == 'nombretipo') {
            return $sort;
        }
        return null;
    }
    private function verifyOrder($order) {
        if($order == null) {
            return $order;
        } else if($order == 'desc') {
            return $order;
        } else {
            return 'asc';
        }
    }
     
    public function index(Request $request)
    {
        //select *
        //from producto p
        //left join marca_productos mp on p.IDMarca = mp.id
        //left join tipo_productos tp on p.IDTipo = tp.id
        
        $search = $request->input('search');
        $products = DB::table('productos')
        ->join('marca_productos', 'productos.IDMarca', '=', 'marca_productos.id')
        ->join('tipo_productos', 'productos.IDTipo', '=', 'tipo_productos.id')
        ->select('productos.id', 'productos.nombre', 'productos.precio', 'productos.unidadesDisponibles', 'productos.filename', 'marca_productos.nombre as nombremarca', 'tipo_productos.nombre as nombretipo')
        
        ;
        
        $data = [];
        // $producto = new Producto();
        $tipo = new TipoProducto();
        $marca = new MarcaProducto();
        $sort = $this->verifySort($request->input('sort'));
        $order = $this->verifyOrder($request->input('order'));
        
        $sortData = [];
        $appendData = [];
        $searchData = [];
        
        if($search != null) {
            $products = $products->where('productos.nombre', 'like', '%' . $search . '%')
                ->orWhere('marca_productos.nombre', 'like', '%' . $search . '%')
                ->orWhere('tipo_productos.nombre', 'like', '%' . $search . '%');
            $searchData['search'] = $search;
        }
        // dd($products);
        // if($search != null) {
        //     $producto = $producto->where('name', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
        // }
        
        // dd($sort, $order);
        if($sort != null && $order != null) {
            $products = $products->orderBy($sort, $order);
            $sortData = [
                'sort' => $sort,
                'order' => $order,
            ];
            // dd($sort, $order,$products);
        }
        
        $data['ordernombreasc'] = ['sort' => 'nombre', 'order' => 'asc', 'search' => $search];
        $data['ordernombredesc'] = ['sort' => 'nombre', 'order' => 'desc', 'search' => $search];
        $data['orderprecioasc'] = ['sort' => 'precio', 'order' => 'asc', 'search' => $search];
        $data['orderpreciodesc'] = ['sort' => 'precio', 'order' => 'desc', 'search' => $search];
        $data['ordernombremarcaasc'] = ['sort' => 'nombremarca', 'order' => 'asc', 'search' => $search];
        $data['ordernombremarcadesc'] = ['sort' => 'nombremarca', 'order' => 'desc', 'search' => $search];
        $data['ordernombretipoasc'] = ['sort' => 'nombretipo', 'order' => 'asc', 'search' => $search];
        $data['ordernombretipodesc'] = ['sort' => 'nombretipo', 'order' => 'desc', 'search' => $search];
        
        $appendData = array_merge($appendData, $sortData);
        $appendData = array_merge($appendData, $searchData);
        
        $data['appendData'] = $appendData;
        
        $products = $products->paginate(5)->appends($appendData);
        $tipos = $tipo->orderBy('nombre', 'asc')->paginate(2,  ['*'], 'pageTipo')->appends($appendData);
        $marcas = $marca->orderBy('nombre', 'asc')->paginate(2,  ['*'], 'pageMarca')->appends($appendData);
        $data['productos'] = $products;
        
        
        // $productos = Producto::all();
        // $data['productos']=$productos;
        // $tipos = TipoProducto::all();
        $data['tipos']=$tipos;
        // $marcas = MarcaProducto::all();
        $data['marcas']=$marcas;
        return view('producto/index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $tipos = TipoProducto::all();
        $data['tipos']=$tipos;
        $marcas = MarcaProducto::all();
        $data['marcas']=$marcas;
        return view('producto.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductoCreateRequest $request)
    {
        $producto = new Producto($request->all());
        // dd($producto, $request);
        if($request->hasFile('fotoProducto') && $request->file('fotoProducto')->isValid()){
            $file = $request->file('fotoProducto');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('public/images/', $filename);
              //  $mimetype = $request->file->getMimeType();
            $producto->filename = $filename;
            $producto->mimetype = $file->getMimeType();
        }
        try{
            $producto->save();
            $data['message'] = 'Se ha creado correctamente un nuevo producto';
            return redirect('producto')->with($data);
            
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
    public function show(Producto $producto)
    {
        $data = [];
        $tipos = TipoProducto::all();
        $data['tipos']=$tipos;
        $marcas = MarcaProducto::all();
        $data['marcas']=$marcas;
        $data['producto'] = $producto;
        return view('producto.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $data = [];
        $tipos = TipoProducto::all();
        $data['tipos']=$tipos;
        $marcas = MarcaProducto::all();
        $data['marcas']=$marcas;
        $data['producto'] = $producto;
        return view('producto.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(ProductoEditRequest $request, Producto $producto)
    {
        // dd($producto, $request);
        
        try{
            $producto->update($request->all()) ;
            $data['message'] = 'Se ha editado correctamente el producto ' . $producto->nombre;
            return redirect('producto')->with($data);
            
        }catch(\Exception $e){
            $data['message'] = 'Ha habido un error al intentar editar el producto ' . $producto->nombre;
            return back()->withInput()->with($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $data = [];
        DB::beginTransaction();
        $data['message'] = 'El producto ' . $producto->nombre .' y sus dependencias se han borrado correctamente';
        $data['type'] = 'success';
        
        $pedidosProductos = DB::select('SELECT id FROM pedido_productos WHERE IDProducto = ' . $producto->id);
        
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
            $result = $producto->delete();
        } catch (\Exception $e){
            DB::rollBack();
            $data['message'] = 'El producto ' . $producto->nombre .' y su usuario no se han borrado correctamente';
            return back()->withInput()->with($data);
        }
        DB::commit();
        return redirect(url('producto'))->with($data);
    }
    
    
    public function userindex(Request $request)
    {
        //select *
        //from producto p
        //left join marca_productos mp on p.IDMarca = mp.id
        //left join tipo_productos tp on p.IDTipo = tp.id
        
        $search = $request->input('search');
        $products = DB::table('productos')
        ->join('marca_productos', 'productos.IDMarca', '=', 'marca_productos.id')
        ->join('tipo_productos', 'productos.IDTipo', '=', 'tipo_productos.id')
        ->select('productos.id', 'productos.nombre', 'productos.precio', 'productos.unidadesDisponibles', 'productos.filename', 'marca_productos.nombre as nombremarca', 'tipo_productos.nombre as nombretipo')
        
        ;
        
        $data = [];
        $sort = $this->verifySort($request->input('sort'));
        $order = $this->verifyOrder($request->input('order'));
        
        $sortData = [];
        $appendData = [];
        $searchData = [];
        
        if($search != null) {
            $products = $products->where('productos.nombre', 'like', '%' . $search . '%')
                ->orWhere('marca_productos.nombre', 'like', '%' . $search . '%')
                ->orWhere('tipo_productos.nombre', 'like', '%' . $search . '%');
            $searchData['search'] = $search;
        }
        // dd($products);
        // if($search != null) {
        //     $producto = $producto->where('name', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
        // }
        
        // dd($sort, $order);
        if($sort != null && $order != null) {
            $products = $products->orderBy($sort, $order);
            $sortData = [
                'sort' => $sort,
                'order' => $order,
            ];
            // dd($sort, $order,$products);
        }
        
        $data['ordernombreasc'] = ['sort' => 'nombre', 'order' => 'asc', 'search' => $search];
        $data['ordernombredesc'] = ['sort' => 'nombre', 'order' => 'desc', 'search' => $search];
        $data['orderprecioasc'] = ['sort' => 'precio', 'order' => 'asc', 'search' => $search];
        $data['orderpreciodesc'] = ['sort' => 'precio', 'order' => 'desc', 'search' => $search];
        $data['ordernombremarcaasc'] = ['sort' => 'nombremarca', 'order' => 'asc', 'search' => $search];
        $data['ordernombremarcadesc'] = ['sort' => 'nombremarca', 'order' => 'desc', 'search' => $search];
        $data['ordernombretipoasc'] = ['sort' => 'nombretipo', 'order' => 'asc', 'search' => $search];
        $data['ordernombretipodesc'] = ['sort' => 'nombretipo', 'order' => 'desc', 'search' => $search];
        
        $appendData = array_merge($appendData, $sortData);
        $appendData = array_merge($appendData, $searchData);
        
        $data['appendData'] = $appendData;
        
        $products = $products->paginate(5)->appends($appendData);
        $data['productos'] = $products;
        
        
        return view('shop/inicio')->with($data);
    }
    
    
    public function usershow(Producto $producto)
    {
        $data = [];
        $tipos = TipoProducto::all();
        $data['tipos']=$tipos;
        $marcas = MarcaProducto::all();
        $data['marcas']=$marcas;
        $data['producto'] = $producto;
        return view('shop.producto', $data);
    }
}
