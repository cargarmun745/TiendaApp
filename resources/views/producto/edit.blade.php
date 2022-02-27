@extends('admin.base')

@section('content')

    @if(Session::has('message'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('message') }}
        </div>
    @endif

  <div class="row">
      <div class="card card-plain">
        <div class="card-header">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Edición del producto {{$producto->nombre}}</h6>
          </div>
        <!--</div>-->
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('producto/'.$producto->id) }}" method="post" enctype="multipart/form-data">
        			@csrf
        			@method('put')
					<fieldset>
						
						<!-- Name input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="nombre">Nombre</label>
							<div class="col-md-9">
								<input value="{{ old('nombre', $producto->nombre) }}" id="nombre" name="nombre" type="text" placeholder="Nombre de empleado" class="form-control">
								@error('nombre')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Precio input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="precio">Precio</label>
							<div class="col-md-9">
								<input value="{{ old('precio', $producto->precio) }}" id="precio" name="precio" type="number" placeholder="Precio" class="form-control">
								@error('precio')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Unidades disponibles input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="unidadesDisponibles">Unidades disponibles</label>
							<div class="col-md-9">
								<input  readonly="readonly" value="{{ old('unidadesDisponibles', $producto->unidadesDisponibles) }}" id="unidadesDisponibles" name="unidadesDisponibles" type="number" placeholder="Unidades disponibles" class="form-control">
								@error('unidadesDisponibles')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						
						
						<!-- Tipo input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDTipo">Tipo de producto</label>
							<div class="col-md-9">
								<select name="IDTipo" class="form-control">
									<option value=""  @if (old('IDTipo', $producto->IDTipo) == "") selected @endif>&nbsp;</option>
						            @foreach ($tipos as $tipo)
						                    <option value="{{ $tipo->id }}" @if(old('IDTipo', $producto->IDTipo) == $tipo->id) selected @endif >{{ $tipo->nombre }}</option>
						            @endforeach
						        </select>
								@error('IDTipo')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						<!-- Marca input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDMarca">Marca del producto</label>
							<div class="col-md-9">
								<select name="IDMarca" class="form-control">
						            <option value="" selected="true"  @if (old('IDMarca', $producto->IDMarca) == "") selected @endif>&nbsp;</option>
						            @foreach ($marcas as $marca)
						                    <option value="{{ $marca->id }}" @if(old('IDMarca', $producto->IDMarca) == $marca->id) selected @endif >{{ $marca->nombre }}</option>
						            @endforeach
						        </select>
								@error('IDMarca')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Form actions -->
						<div class="form-group">
							<div class="col-md-12 widget-right">
								<button type="submit" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
								    Editar
								</button>
							</div>
						</div>
						
					</fieldset>
				</form>
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