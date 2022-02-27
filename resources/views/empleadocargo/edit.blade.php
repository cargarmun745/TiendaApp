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
            <h6 class="text-white text-capitalize ps-3">Edición del cargo del empleado {{$cargo->nombre}}</h6>
          </div>
        <!--</div>-->
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('cargo/'.$cargo->id) }}" method="post" enctype="multipart/form-data">
        			@csrf
        			@method('put')
					<fieldset>
						
						<!-- Name input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="nombre">Nombre</label>
							<div class="col-md-9">
								<input value="{{ old('nombre', $cargo->nombre) }}" id="nombre" name="nombre" type="text" placeholder="Descripcion del cargo" class="form-control">
								@error('nombre')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Name input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="nombre">Descripción</label>
							<div class="col-md-9">
								<input value="{{ old('descripcion', $cargo->descripcion) }}" id="descripcion" name="descripcion" type="text" placeholder="Descripcion del cargo" class="form-control">
								@error('descripcion')
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