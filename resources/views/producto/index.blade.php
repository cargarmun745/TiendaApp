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
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
              <label class="form-check-label" for="inlineRadio1"><a class="my-filter-btn" href="{{ route('producto.index', $ordernombreasc) }}">Nombre Ascendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
              <label class="form-check-label" for="inlineRadio2"><a class="my-filter-btn" href="{{ route('producto.index', $ordernombredesc) }}">Nombre Descendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
              <label class="form-check-label" for="inlineRadio3"><a class="my-filter-btn" href="{{ route('producto.index', $orderpreciodesc) }}">Precio más elevado</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="option4">
              <label class="form-check-label" for="inlineRadio4"><a class="my-filter-btn" href="{{ route('producto.index', $orderprecioasc) }}">Precio menos elevado</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio5" value="option5">
              <label class="form-check-label" for="inlineRadio5"><a class="my-filter-btn" href="{{ route('producto.index', $ordernombremarcaasc) }}">Nombre Marca Ascendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio6" value="option6">
              <label class="form-check-label" for="inlineRadio6"><a class="my-filter-btn" href="{{ route('producto.index', $ordernombremarcadesc) }}">Nombre Marca Descendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio7" value="option7">
              <label class="form-check-label" for="inlineRadio7"><a class="my-filter-btn" href="{{ route('producto.index', $ordernombretipoasc) }}">Nombre Tipo Ascendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio8" value="option8">
              <label class="form-check-label" for="inlineRadio8"><a class="my-filter-btn" href="{{ route('producto.index', $ordernombretipodesc) }}">Nombre Tipo Descendente</a></label>
            </div>
          </div>
      
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg" style="display: flex; justify-content: space-between; padding: 25px 15px; box-sizing: border-box;">
            <h6 class="text-white text-capitalize" style="margin-bottom: 0; padding: 5px 10px;">Tabla Productos</h6>
            
            @if(auth()->user()->rol!='empleado' && auth()->user()->rol!='cliente')
            <a href="{{ url('producto/create') }}"><h6 class="text-white text-capitalize" style=" background: #d81b60; margin-bottom: 0; margin-right: 180px; border-radius: 3px; cursor: pointer; padding: 5px 30px; box-sizing: border-box;">Añadir productos</h6></a>
            @endif
          </div>
        </div>
      <!--  <div style="display: flex">-->
      <!--  <a class="my-filter-btn" href="{{ route('producto.index', $ordernombreasc) }}">A-Z</a>-->
      <!--  <a class="my-filter-btn" href="{{ route('producto.index', $ordernombredesc) }}">Z-A</a>-->
      <!--  <a class="my-filter-btn" href="{{ route('producto.index', $orderprecioasc) }}">Número ASC</a>-->
      <!--  <a class="my-filter-btn" href="{{ route('producto.index', $orderpreciodesc) }}">Número</a>-->
      <!--</div>-->
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
                @foreach ($productos as $producto)
                    <tr>
                      <td></td>
                      <td><img style="width: 75px; height: 75px" src="{{ asset('storage/images/' . $producto->filename) }}"></td>
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
                      
                       @if(isset($producto->nombretipo))
                              <td><p class="text-center text-xs font-weight-bold mb-0">{{ $producto->nombretipo }}</p></td>
              				 @else 
              						<td><p class="text-center text-xs font-weight-bold mb-0">No definido</p></td>
              	       @endif
              	       
                       @if(isset($producto->nombremarca))
                              <td><p class="text-center text-xs font-weight-bold mb-0">{{ $producto->nombremarca }}</p></td>
              				 @else 
              						<td><p class="text-center text-xs font-weight-bold mb-0">No definido</p></td>
              	       @endif
                      
                      <td class="pull-right" style="width: 320px">
                        
                        @if(auth()->user()->rol!='empleado' && auth()->user()->rol!='cliente')
                        <a href="javascript: void(0);" data-name="{{ $producto->nombre }}" data-table="producto" data-url="{{ url('producto/' . $producto->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('producto/'.$producto->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                        @endif
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('producto/'.$producto->id) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" fill="currentColor" class="me-2 bi bi-eye-fill" viewBox="0 0 16 16">
                              <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                              <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                            </svg>Show
                        </a>
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="pagination pagination-sm" style="display: flex; justify-content: center;">
          {{ $productos->links() }}
      </div>
    </div>
  </div>
  @if(auth()->user()->rol!='cliente')
  <div class="row mt-4">
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
      <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg" style="display: flex; justify-content: space-between; padding: 25px 15px; box-sizing: border-box;">
            <h6 class="text-white text-capitalize" style="margin-bottom: 0; padding: 5px 10px;">Tipo de producto</h6>
            @if(auth()->user()->rol!='empleado' && auth()->user()->rol!='cliente')
            <a href="{{ url('tipo/create') }}"><h6 class="text-white text-capitalize" style=" background: #d81b60; margin-bottom: 0; margin-right: 20px; border-radius: 3px; cursor: pointer; padding: 5px 30px; box-sizing: border-box;">Añadir tipo</h6></a>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-hover">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nombre</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody><tbody>
                @foreach ($tipos as $tipo)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <!--<img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">-->
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $tipo->nombre }}</h6>
                          </div>
                        </div>
                      </td>
                      
                      <td class="pull-right" style="width: 250px">
                        @if(auth()->user()->rol!='empleado')
                        <a href="javascript: void(0);" data-name="{{ $tipo->nombre }}" data-table="tipo de producto" data-url="{{ url('tipo/' . $tipo->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('tipo/'.$tipo->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                        @endif
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="pagination pagination-sm" style="display: flex; justify-content: center;">
          {{ $tipos->links() }}
      </div>
        
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
      <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg" style="display: flex; justify-content: space-between; padding: 25px 15px; box-sizing: border-box;">
            <h6 class="text-white text-capitalize" style="margin-bottom: 0; padding: 5px 10px;">Marcas de los productos</h6>
            @if(auth()->user()->rol!='empleado' && auth()->user()->rol!='cliente')
            <a href="{{ url('marca/create') }}"><h6 class="text-white text-capitalize" style=" background: #d81b60; margin-bottom: 0; margin-right: 20px; border-radius: 3px; cursor: pointer; padding: 5px 30px; box-sizing: border-box;">Añadir marcas</h6></a>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-hover">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nombre</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody><tbody>
                @foreach ($marcas as $marca)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <!--<img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">-->
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $marca->nombre }}</h6>
                          </div>
                        </div>
                      </td>
                      
                      <td class="pull-right" style="width: 250px">
                        
                        @if(auth()->user()->rol!='empleado')
                        <a href="javascript: void(0);" data-name="{{ $marca->nombre }}" data-table="marca de producto" data-url="{{ url('marca/' . $marca->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('marca/'.$marca->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                        @endif
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="pagination pagination-sm" style="display: flex; justify-content: center;">
          {{ $marcas->links() }}
      </div>
    </div>
    @endif
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

@section('search')
<form action="{{ $rutaSearch ?? '' }}" method="get">
  <!--<label class="form-label">Type here...</label>-->
  <input type="text" class="form-control"  value="{{ $appendData['search'] ?? '' }}" name="search" placeholder="Busqueda">
  @isset($appendData)
    @foreach($appendData as $name => $value)
      @if($name != 'search')
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
      @endif
    @endforeach
  @endisset
  <!--<button class="my-search-submit" type="submit"></button>-->
</form>
@endsection