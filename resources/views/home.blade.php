@extends('admin.base')


@section('content')
<div class="container">
    
    @if(Session::has('message'))
        <div class="alert alert-light" role="alert">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card" style="margin: 40px auto 60px">
                <!--<div class="card-header">{{ __('Dashboard') }}</div>-->

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        
            <!--TIENDA QUE NOS SALE CON MINIS CARTAS QUE CON UN O VARIOS SELECTS SE DETERMINARÁN LA ORDENACION-->
            @if(auth()->user()->rol == 'cliente')
                <div class="card z-index-2 " style="margin-bottom: 60px">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                      <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Gestión de mi usuario</h6>
                      </div>
                    </div>
                    <div class="card-body">
                        <a href="{{ url('clienteshow') }}">Mostrar mi perfil</a><br><br>
                        <a class="nav-link pl-3" href="{{ url('clienteedit') }}">Editar mi perfil</a>
                    </div>
                </div>
                
                <div class="card z-index-2 " style="margin-bottom: 60px">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                      <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Gestión de mis compras</h6>
                      </div>
                    </div>
                    <div class="card-body">
                        <a href="{{ url('pedidocreate') }}">Crear un pedido</a><br><br>
                        <!--CREAR ESTA RUTA QUE NOS MUESTRA LOS PEDIDOS SIN RECIBIR-->
                        <a href="{{ url('pedidoindex') }}">Mostrar todos los pedidos realizados</a><br><br>
                        <a href="{{ url('pedidoindexproductos') }}">Mostrar todos los productos comprados</a>
                    </div>
                </div>
            @endif
        </div>
        </div>
    </div>
@endsection


@section ('header')
        
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-primary" href="{{ url('homeDos') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Perfil</span>
          </a>
        </li>
        
        @if(auth()->user()->rol != 'cliente' && auth()->user()->rol != 'empleado')
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('empleado') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Empleados</span>
          </a>
        </li>
        @endif
        
        @if(auth()->user()->rol != 'cliente')
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('cliente') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">receipt_long</i>
            </div>
            <span class="nav-link-text ms-1">Clientes</span>
          </a>
        </li>
        @endif
        
        @if(auth()->user()->rol == 'cliente')
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('productoindex') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">view_in_ar</i>
            </div>
            <span class="nav-link-text ms-1">Productos</span>
          </a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('producto') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">view_in_ar</i>
            </div>
            <span class="nav-link-text ms-1">Productos</span>
          </a>
        </li>
        @endif
        
        @if(auth()->user()->rol == 'cliente' || auth()->user()->rol == 'empleado')
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('pedidoindex') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">view_in_ar</i>
            </div>
            <span class="nav-link-text ms-1">Pedidos</span>
          </a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('pedido') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
            </div>
            <span class="nav-link-text ms-1">Pedidos</span>
          </a>
        </li>
        @endif
        
@endsection