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

    @if(Session::has('message'))
        <div class="alert alert-light" role="alert">
            {{ session()->get('message') }}
        </div>
    @endif


  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg" style="display: flex; justify-content: space-between; padding: 25px 15px; box-sizing: border-box;">
            <h6 class="text-white text-capitalize" style="margin-bottom: 0; padding: 5px 10px;">Productos de los pedidos relacionados con usted</h6>
            <a href="{{ url('pedidocreate') }}"><h6 class="text-white text-capitalize" style=" background: #d81b60; margin-bottom: 0; margin-right: 180px; border-radius: 3px; cursor: pointer; padding: 5px 30px; box-sizing: border-box;">Añadir pedidos</h6></a>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            @if(isset($productos))
            <table class="table align-items-center mb-0 table-hover">
              <thead>
                <tr>
                  <th></th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Nombre del producto</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Cantidad</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($productos as $producto)
                    <tr>
                      <td></td>
                      <td>
                        {{ $producto[0] }}
                      </td>
                      <td>
                        {{ $producto[1] }}
                      </td>
                      <td></td>
                    </tr>
                @endforeach
                
                  
              </tbody>
            </table>
            @else
              <p>No existen todavia productos</p>
              @endif
          </div>
        </div>
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

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ url('assets/js/deleteItem.js') }}"></script>
@endsection