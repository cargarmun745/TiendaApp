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
            <h6 class="text-white text-capitalize ps-3">Creacion de un nuevo pedido</h6>
          </div>
        <!--</div>-->
        <div class="card-body">
            <form id="formulario" class="form-horizontal" action="{{ url('pedidostore') }}" method="post" enctype="multipart/form-data">
        			@csrf
					<fieldset>
						
						<!-- fechaPedido input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="fechaPedido">Fecha de Pedido</label>
							<div class="col-md-9">
								<input value="{{ old('fechaPedido') }}" id="fechaPedido" name="fechaPedido" type="date" class="form-control">
								@error('fechaPedido')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Cargo input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="metodoDePago">Metodo de pago</label>
							<div class="col-md-9">
								<select name="metodoDePago" class="form-control">
									<option value=""  @if (old('metodoDePago') == "") selected @endif>&nbsp;</option>
						            @foreach ($metodos as $metodo)
						                    <option value="{{ $metodo }}" @if(old('metodoDePago') == $metodo) selected @endif >{{ $metodo }}</option>
						            @endforeach
						        </select>
								@error('metodoDePago')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- cliente input-->
						@if(isset($cliente))
							<input value="{{ $cliente->id }}" name="IDCliente" type="hidden" class="form-control">
						@else
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDCliente">Cliente</label>
							<div class="col-md-9">
								<select name="IDCliente" class="form-control">
									<option value=""  @if (old('IDCliente') == "") selected @endif>&nbsp;</option>
						            @foreach ($clientes as $cliente)
						                    <option value="{{ $cliente->id }}" @if(old('IDCliente') == $cliente->id) selected @endif >{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
						            @endforeach
						        </select>
								@error('IDCliente')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Empleado input-->
						@if(isset($empleado))
							<input value="{{ $empleado->id }}" name="IDEmpleado" type="hidden" class="form-control">
						@else
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDEmpleado">Empleado</label>
							<div class="col-md-9">
								<select name="IDEmpleado" class="form-control">
									<option value=""  @if (old('IDEmpleado') == "") selected @endif>&nbsp;</option>
						            @foreach ($empleados as $empleado)
						                    <option value="{{ $empleado->id }}" @if(old('IDEmpleado') == $empleado->id) selected @endif >{{ $empleado->nombre }} {{ $empleado->apellidos }}</option>
						            @endforeach
						        </select>
								@error('IDEmpleado')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						@endif
						@endif
						
						<!-- Form actions -->
						<div class="form-group">
							<div class="col-md-12 widget-right">
								<button type="submit" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
								    Crear
								</button>
								<button type="button" id="btproducto" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
								    Añadir producto
								</button>
							</div>
						</div>
						
					</fieldset>
					<div id="cartaProducto" class="input-group input-group-outline mb-3 mt-4" style="display: flex; align-items: center; justify-content: space-between; width: 725px">
						<div style="display: flex; align-items: center; justify-content: space-between; width: 450px">
							<label class="col-md-1 control-label" style="width: 100px" for="producto[]">Producto</label>
				 			<div class="col-md-4" style="margin-right: 35px; width: 300px;">
				 				<select name="producto[]" class="form-control ultimosProductos">
				 					<option value=""  @if (old('producto[]') == "") selected @endif>&nbsp;</option>
				 		            @foreach ($todosProductos as $todosProducto)
				 		            	@if($todosProducto->unidadesDisponibles > 0)
				 		                    <option value="{{ $todosProducto->id }}" @if(old('producto[]') == $todosProducto->id) selected @endif >{{ $todosProducto->nombre }}</option>
				 		                @endif
				 		            @endforeach
				 		        </select>
				 				@error('producto')
				 		            <div class="alert alert-danger">{{ $message }}</div>
				 		        @enderror
				 			</div>
						</div>
						<div style="display: flex; align-items: center; justify-content: space-between; width: 200px">
				 			<label class="col-md-1 control-label" style="width: 70px" for="cantidad[]">Cantidad</label>
				 			<div class="col-md-4"  style="width: 100px">
				 				<input min="1" value="{{ old('cantidad[]', 1) }}" id="cantidad" name="cantidad[]" type="number" class="form-control ultimosCantidad">
				 				@error('cantidad')
				 		            <div class="alert alert-danger">{{ $message }}</div>
				 		        @enderror
				 			</div>
						</div>
						
						<button type="button" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0 btproductoBorrar" style="display: none;margin: 0 !important; border-radius: 7px;">
						    Eliminar producto
						</button>
				 	</div>
				</form>
        </div>
    </div>
    

<script>
    let count = 1;
    let btAñadirProducto = document.getElementById('btproducto');
    let btBorrarProducto = document.getElementsByClassName('btproductoBorrar');
    let div = document.getElementById('cartaProducto');
    
    
    let cantidad = document.getElementsByClassName('ultimosCantidad');
    let producto = document.getElementsByClassName('ultimosProductos');
    
    
	cantidad[0].addEventListener('focusout', function(e){
		console.log(producto[0].options[producto[0].selectedIndex].value);
		var idProducto = producto[0].options[producto[0].selectedIndex].value;
        comprobacionCantidad(idProducto, cantidad[0].value, cantidad[0]);
    });
	
   
    btAñadirProducto.addEventListener('click', function(){
    let ultimosProductos = document.getElementsByClassName('ultimosProductos');
    ultimosProductos = ultimosProductos[ultimosProductos.length-1];
    
    
    let valorInput = ultimosProductos.options[ultimosProductos.selectedIndex].value;
        if(valorInput != ''){
            count++;
           
            let divClone = div.cloneNode(true);
            divClone.removeAttribute('id');
           
            divClone.id = 'divClone' +  count;
            div.parentNode.appendChild(divClone);
            console.log('si',divClone);
            divClone.style.width = '1000px';
            divClone.lastChild.previousSibling.style.display = 'flex';
            
        	let ultimosCantidad = document.getElementsByClassName('ultimosCantidad');
    		ultimosCantidad = ultimosCantidad[ultimosCantidad.length-1];
        	ultimosCantidad.value = 1;
        	
		    for (var i = 0; i < cantidad.length; i++) {
		    	
		    	cantidad[i].addEventListener('focusout', function(){
		    		añadirEventos(this);
		    	})
		    	btBorrarProducto[i].addEventListener('click', function(){
		    		console.log('extoy', this.parentNode);
		    		this.parentNode.remove();
		    	})
		    }
        }
   
    });
    
    function añadirEventos(evento){
    	for (var j = 0; j < cantidad.length; j++) {
			if(evento == cantidad[j]){
				// console.log(producto[j].options[producto[j].selectedIndex].value);
				var idProducto = producto[j].options[producto[j].selectedIndex].value;
        		comprobacionCantidad(idProducto, evento.value, cantidad[j]);
			}
		}
    }
   
    // btBorrarProducto[0].addEventListener('click', function(){
    //     if(count > 1){
    //         let idToSelect = 'divClone' +  count;
    //         let divCloneToRemove = document.getElementById(idToSelect);
    //         div.parentNode.removeChild(divCloneToRemove);
    //         count--;
    //     }
    // });
    
   
    function comprobacionCantidad(idProducto, cantidad, elemento){
        let url="public/ajax/producto?producto=" + idProducto+"&cantidad="+ cantidad;
        fetch(url)
        .then(function(response){
            return response.json();
        }).then(function(jsonData){
            // alert(jsonData.respuesta);
            if(!jsonData.respuesta){
            	console.log(jsonData.cantidadMaxima);
            	console.log(elemento);
                elemento.value= jsonData.cantidadMaxima;
            }
        }).catch(function(){
           //error
        });
    }
    
</script>
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