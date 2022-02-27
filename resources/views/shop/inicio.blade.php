@extends('admin.base')

@section('content')

<div class="row">
    <div class="row mt-4">
        <div class="col-12">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
              <label class="form-check-label" for="inlineRadio1"><a class="my-filter-btn" href="{{ route('producto.userindex', $ordernombreasc) }}">Nombre Ascendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
              <label class="form-check-label" for="inlineRadio2"><a class="my-filter-btn" href="{{ route('producto.userindex', $ordernombredesc) }}">Nombre Descendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
              <label class="form-check-label" for="inlineRadio3"><a class="my-filter-btn" href="{{ route('producto.userindex', $orderpreciodesc) }}">Precio más elevado</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="option4">
              <label class="form-check-label" for="inlineRadio4"><a class="my-filter-btn" href="{{ route('producto.userindex', $orderprecioasc) }}">Precio menos elevado</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio5" value="option5">
              <label class="form-check-label" for="inlineRadio5"><a class="my-filter-btn" href="{{ route('producto.userindex', $ordernombremarcaasc) }}">Nombre Marca Ascendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio6" value="option6">
              <label class="form-check-label" for="inlineRadio6"><a class="my-filter-btn" href="{{ route('producto.userindex', $ordernombremarcadesc) }}">Nombre Marca Descendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio7" value="option7">
              <label class="form-check-label" for="inlineRadio7"><a class="my-filter-btn" href="{{ route('producto.userindex', $ordernombretipoasc) }}">Nombre Tipo Ascendente</a></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio8" value="option8">
              <label class="form-check-label" for="inlineRadio8"><a class="my-filter-btn" href="{{ route('producto.userindex', $ordernombretipodesc) }}">Nombre Tipo Descendente</a></label>
            </div>
        </div>
        @foreach($productos as $producto)
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2 ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1" style="background-image: url('{{ asset('storage/images/' . $producto->filename) }}'); background-size: cover; ">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170" ></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 ">{{ $producto->nombre }}</h6>
              <p class="text-sm ">{{ $producto->precio }} €</p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <p class="mb-0 text-sm"><a href="{{ url('productoshow/'.$producto->id) }}">Mostrar más</a></p>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
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