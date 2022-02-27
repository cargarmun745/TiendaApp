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
            <h6 class="text-white text-capitalize ps-3">Empleado  {{ $empleado->nombre }} {{ $empleado->apellidos }}</h6>
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
    						            <td><p class="text-xs font-weight-bold mb-0">No definido</p></td>
    	                @endif
                      
                  @if(isset($empleado->IDUsuario))
            				@foreach($users as $usuario)
    		                    @if( $empleado->IDUsuario == $usuario->id )
    		                    	<td><p class="text-xs font-weight-bold mb-0">{{ $usuario->name }}</p></td>
    		                    	<td><p class="text-xs font-weight-bold mb-0">{{ $usuario->email }}</p></td>
    		                    @endif
        					@endforeach
    					    @else 
        						<td><p class="text-xs font-weight-bold mb-0">No definido</p></td>
        						<td><p class="text-xs font-weight-bold mb-0">No definido</p></td>
    	            @endif
                    
                  <td>
                    @if(auth()->user()->rol == 'admin')
                      <a href="javascript: void(0);" data-name="{{ $empleado->nombre }}" data-table="empleado" data-url="{{ url('empleado/' . $empleado->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                      <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('empleado/'.$empleado->id.'/edit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                    @elseif(auth()->user()->id == $empleado->IDUsuario)
                      <a href="javascript: void(0);" data-name="{{ $empleado->nombre }}" data-table="empleado" data-url="{{ url('empleadodelete') }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
                      <a class="btn btn-link text-dark px-3 mb-0" href="{{ url('empleadoedit') }}"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                    @endif
                  </td>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <a href="{{ url('empleado') }}" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
					Volver atras
			</a>
    </div>
  </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ url('assets/js/deleteItem.js') }}"></script>
@endsection