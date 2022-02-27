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
            <h6 class="text-white text-capitalize ps-3">Creacion de un nuevo perfil de empleado</h6>
          </div>
        <!--</div>-->
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('empleado') }}" method="post" enctype="multipart/form-data">
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
						
						<!-- Fecha de contrato disponibles input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="fechacontrato">Fecha de Contrato</label>
							<div class="col-md-9">
								<input value="{{ old('fechacontrato') }}" id="fechacontrato" name="fechacontrato" type="date" class="form-control">
								@error('fechacontrato')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Cargo input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDCargo">Cargo del empleado</label>
							<div class="col-md-9">
								<select name="IDCargo" class="form-control">
									<option value=""  @if (old('IDCargo') == "") selected @endif>&nbsp;</option>
						            @foreach ($cargos as $cargo)
						                    <option value="{{ $cargo->id }}" @if(old('IDCargo') == $cargo->id) selected @endif >{{ $cargo->nombre }}</option>
						            @endforeach
						        </select>
								@error('IDCargo')
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
					        <label for="name" class="col-md-3 control-label">Rol</label>
					
					        <div class="col-md-9">
					            
								<select name="rol" class="form-control">
									<option value="empleado"  @if (old('rol') == "" && old('rol') == "empleado") selected @endif>empleado</option>
						            <option value="jefe" @if(old('rol') == 'jefe') selected @endif >jefe</option>
						            <option value="admin" @if(old('rol') == 'admin') selected @endif >admin</option>
						        </select>
						        
								@error('rol')
						            <div class="alert alert-danger">{{ $message }}</div>
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