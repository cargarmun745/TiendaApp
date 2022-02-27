@extends('admin.base')

@section('content')
  
  <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm delete</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"> 
          <p>¿Quieres borrar el <span id="nombreItem">XXX</span> <span id="deleteItem">XXX</span>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form id="modalDeleteResourceForm" action="" method="post">
            @method('delete')
            @csrf
            <button type="submit" class="btn mb-2 btn-primary">Delete item</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Producto {{ $producto->nombre }}</h6>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-hover">
              <thead>
                <tr>
                  <th></th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Imagen</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nombre</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Precio</th>
                  <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Tipo de producto</th>
                  <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Marca</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                    <tr>
                      <td></td>
                      <td><img style="width: 155px; height: 155px" src="{{ asset('storage/images/' . $producto->filename) }}"></td>
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
      <a href="{{ url('producto') }}" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
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
        
@endsection
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ url('assets/js/deleteItem.js') }}"></script>
@endsection