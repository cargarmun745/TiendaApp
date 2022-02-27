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
            <h6 class="text-white text-capitalize ps-3">Creacion de un nuevo perfil de cliente</h6>
          </div>
        <!--</div>-->
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('cliente') }}" method="post" enctype="multipart/form-data">
        			@csrf
					<fieldset>
						
						<!-- Name input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="nombre">Nombre</label>
							<div class="col-md-9">
								<input value="{{ old('nombre') }}" id="nombre" name="nombre" type="text" class="form-control">
								@error('nombre')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Apellidos input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="apellidos">Apellidos</label>
							<div class="col-md-9">
								<input value="{{ old('apellidos') }}" id="apellidos" name="apellidos" type="text" class="form-control">
								@error('apellidos')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- DNI disponibles input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="DNI">DNI</label>
							<div class="col-md-9">
								<input value="{{ old('DNI') }}" id="DNI" name="DNI" type="text" class="form-control">
								@error('DNI')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Dirección disponibles input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="direccion">Dirección</label>
							<div class="col-md-9">
								<input value="{{ old('direccion') }}" id="direccion" name="direccion" type="text" class="form-control">
								@error('direccion')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						
                    	<div class="input-group input-group-outline mb-3">
					        <label for="name" class="col-md-3 control-label">Usuario</label>
					
					        <div class="col-md-9">
					            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
					
					            @error('name')
					                <span class="invalid-feedback" role="alert">
					                    <strong>{{ $message }}</strong>
					                </span>
					            @enderror
					        </div>
					    </div>
					
					    <div class="input-group input-group-outline mb-3">
					        <label for="email" class="col-md-3 control-label">Email</label>
					
					        <div class="col-md-9">
					            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
					
					            @error('email')
					                <span class="invalid-feedback" role="alert">
					                    <strong>{{ $message }}</strong>
					                </span>
					            @enderror
					        </div>
					    </div>
					
					    <div class="input-group input-group-outline mb-3">
					        <label for="password" class="col-md-3 control-label">Contraseña</label>
					
					        <div class="col-md-9">
					            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
					
					            @error('password')
					                <span class="invalid-feedback" role="alert">
					                    <strong>{{ $message }}</strong>
					                </span>
					            @enderror
					        </div>
					    </div>
					
					    <div class="input-group input-group-outline mb-3">
					        <label for="password-confirm" class="col-md-3 control-label">Confirmación de contraseña</label>
					
					        <div class="col-md-9">
					            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
					        </div>
					    </div>
							
						
						<!-- Form actions -->
						<div class="form-group">
							<div class="col-md-12 widget-right">
								<button type="submit" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
								    Crear
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
          <a class="nav-link text-white active bg-gradient-primary" href="{{ url('cliente') }}">
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