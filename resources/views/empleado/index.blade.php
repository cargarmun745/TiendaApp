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
            <h6 class="text-white text-capitalize" style="margin-bottom: 0; padding: 5px 10px;">Todos los empleados</h6>
            @if(auth()->user()->rol == 'admin')
            <a href="{{ url('empleado/create') }}"><h6 class="text-white text-capitalize" style=" background: #d81b60; margin-bottom: 0; margin-right: 180px; border-radius: 3px; cursor: pointer; padding: 5px 30px; box-sizing: border-box;">Añadir empleados</h6></a>
            @endif
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-hover">
              <thead>
                <tr>
                  <th></th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Nombre</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Apellidos</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">DNI</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Fecha de Contratación</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Cargo</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Usuario</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Emial</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($empleados as $empleado)
                    <tr>
                      <td></td>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <!--<img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">-->
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <p class="text-xs font-weight-bold mb-0">{{ $empleado->nombre }}</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $empleado->apellidos }}</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $empleado->DNI }}</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">{{ $empleado->fechacontrato }}</p>
                      </td>
                      
                      @if(isset($empleado->IDCargo))
            				    @foreach($cargos as $cargo)
    		                  @if( $empleado->IDCargo == $cargo->id )
    		                    <td><p class="text-xs font-weight-bold mb-0">{{ $cargo->nombre }}</p></td>
    		                  @endif
        					      @endforeach
    					        @else 
    						            <td><p class="text-xs font-weight-bold mb-0 alert alert-warning">No definido</p></td>
    	                @endif
                      
                  @if(isset($empleado->IDUsuario))
            				@foreach($users as $usuario)
	                    @if( $empleado->IDUsuario == $usuario->id )
	                    	<td><p class="text-xs font-weight-bold mb-0">{{ $usuario->name }}</p></td>
	                    	<td><p class="text-xs font-weight-bold mb-0">{{ $usuario->email }}</p></td>
	                    @endif
        					  @endforeach
    					    @else 
        						<td><p class="text-xs font-weight-bold mb-0 alert alert-warning">No definido</p></td>
        						<td><p class="text-xs font-weight-bold mb-0 alert alert-warning">No definido</p></td>
    	            @endif
                    <td class="pull-right" style="width: 320px">
                      @if(auth()->user()->rol == 'admin')
                      <a href="javascript: void(0);" data-name="{{ $empleado->nombre }}" data-table="empleado" data-url="{{ url('empleado/' . $empleado->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                      <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('empleado/'.$empleado->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                      @elseif(auth()->user()->id == $empleado->IDUsuario)
                      <a href="javascript: void(0);" data-name="{{ $empleado->nombre }}" data-table="empleado" data-url="{{ url('empleadodelete') }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                      <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('empleadoedit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                      @endif
                      <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('empleado/'.$empleado->id) }}">
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
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-6 col-md-9 mt-4 mb-4">
      <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg" style="display: flex; justify-content: space-between; padding: 25px 15px; box-sizing: border-box;">
            <h6 class="text-white text-capitalize" style="margin-bottom: 0; padding: 5px 10px;">Cargo de empleado</h6>
            <a href="{{ url('cargo/create') }}"><h6 class="text-white text-capitalize" style=" background: #d81b60; margin-bottom: 0; margin-right: 20px; border-radius: 3px; cursor: pointer; padding: 5px 30px; box-sizing: border-box;">Añadir cargo</h6></a>
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
                @foreach ($cargos as $cargo)
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <!--<img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">-->
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $cargo->nombre }}</h6>
                          </div>
                        </div>
                      </td>
                      
                      <td class="pull-right" style="width: 250px">
                        @if(auth()->user()->rol == 'admin')
                        <a href="javascript: void(0);" data-name="{{ $cargo->nombre }}" data-table="cargo de empleado" data-url="{{ url('cargo/' . $cargo->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('cargo/'.$cargo->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                        @endif
                        <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('cargo/'.$cargo->id) }}">
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
    </div>
  </div>
@endsection


@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ url('assets/js/deleteItem.js') }}"></script>
@endsection