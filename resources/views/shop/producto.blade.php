@extends('admin.base')

@section('content')

<div class="row">
        
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1" style="background-image: url('{{ asset('storage/images/' . $producto->filename) }}'); background-size: cover; ">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170" ></canvas>
                </div>
              </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-6 mt-4 mb-4">
          <div class="card z-index-2 ">
            <div class="card-body">
              <table class="table align-items-center mb-0 table-hover">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nombre</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Precio</th>
                  <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tipo de producto</th>
                  <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Marca</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <!--<img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">-->
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $producto->nombre }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $producto->precio }} €</p>
                      </td>
                      
                         @if(isset($producto->IDTipo))
            				@foreach($tipos as $tipo)
    		                    @if( $producto->IDTipo == $tipo->id )
    		                    	<td><p class="text-center text-xs font-weight-bold mb-0">{{ $tipo->nombre }}</p></td>
    		                    @endif
        					@endforeach
    					@else 
    						<td><p class="text-center text-xs font-weight-bold mb-0">No definido</p></td>
    	                @endif
                      
                         @if(isset($producto->IDMarca))
            				@foreach($marcas as $marca)
    		                    @if( $producto->IDMarca == $marca->id )
    		                    	<td><p class="text-center text-xs font-weight-bold mb-0">{{ $marca->nombre }}</p></td>
    		                    @endif
        					@endforeach
    					@else 
    						<td><p class="text-center text-xs font-weight-bold mb-0">No definido</p></td>
    	                @endif
                      
                      <!--<td>-->
                      <!--  <p class="text-xs font-weight-bold mb-0">{{ $producto->IDTipo }}</p>-->
                      <!--</td>-->
                      <td class="pull-right" style="width: 320px">
                        @if(auth()->user()->rol != 'empleado')
                        <a href="javascript: void(0);" data-name="{{ $producto->nombre }}" data-table="producto" data-url="{{ url('producto/' . $producto->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('producto/'.$producto->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                        @endif
                      </td>
                    </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>
      <a href="{{ url('productoindex') }}" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
					Volver atras
		</a>
    </div>
</div>
@endsection

@section ('header')
        
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('homeDos') }}">
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
          <a class="nav-link text-white active bg-gradient-primary" href="{{ url('productoindex') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">view_in_ar</i>
            </div>
            <span class="nav-link-text ms-1">Productos</span>
          </a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-primary" href="{{ url('producto') }}">
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
        
        @if(auth()->user()->rol != 'cliente')
        <li class="nav-item">
          <a class="nav-link text-white " href="{{ url('extras') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">notifications</i>
            </div>
            <span class="nav-link-text ms-1">Extras</span>
          </a>
        </li>
        @endif
@endsection