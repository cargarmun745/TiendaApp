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
        <div class="alert alert-danger" role="alert">
            {{ session()->get('message') }}
        </div>
    @endif

  <div class="row">
      <div class="card card-plain">
        <div class="card-header">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Edición del pedido con fecha de realización de {{$pedido->fechaPedido}}</h6> <!-- $pedido->hora -->
          </div>
        <!--</div>-->
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('pedido/'.$pedido->id) }}" method="post" enctype="multipart/form-data">
        			@csrf
        			@method('put')
					<fieldset>
						
						<!-- fechaPedido input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="fechaPedido">Fecha de Pedido</label>
							<div class="col-md-9">
								<input value="{{ old('fechaPedido', $pedido->fechaPedido) }}" id="fechaPedido" name="fechaPedido" type="date" class="form-control">
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
									<option value=""  @if (old('metodoDePago', $pedido->metodoDePago) == "") selected @endif>&nbsp;</option>
						            @foreach ($metodos as $metodo)
						                    <option value="{{ $metodo }}" @if(old('metodoDePago', $pedido->metodoDePago) == $metodo) selected @endif >{{ $metodo }}</option>
						            @endforeach
						        </select>
								@error('metodoDePago')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- cliente input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDCliente">Cliente</label>
							<div class="col-md-9">
								<select name="IDCliente" class="form-control">
									<option value=""  @if (old('IDCliente', $pedido->IDCliente) == "") selected @endif>&nbsp;</option>
						            @foreach ($clientes as $cliente)
						                    <option value="{{ $cliente->id }}" @if(old('IDCliente', $pedido->IDCliente) == $cliente->id) selected @endif >{{ $cliente->nombre }} {{ $cliente->apellidos}}</option>
						            @endforeach
						        </select>
								@error('IDCliente')
						            <div class="alert alert-danger">{{ $message }}</div>
						        @enderror
							</div>
						</div>
						
						<!-- Empleado input-->
						<div class="input-group input-group-outline mb-3">
							<label class="col-md-3 control-label" for="IDEmpleado">Empleado</label>
							<div class="col-md-9">
								<select name="IDEmpleado" class="form-control">
									<option value=""  @if (old('IDEmpleado', $pedido->IDEmpleado) == "") selected @endif>&nbsp;</option>
						            @foreach ($empleados as $empleado)
						                    <option value="{{ $empleado->id }}" @if(old('IDEmpleado', $pedido->IDEmpleado) == $empleado->id) selected @endif >{{ $empleado->nombre }} {{ $empleado->apellidos }}</option>
						            @endforeach
						        </select>
								@error('IDEmpleado')
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
								<button type="button" id="btproducto" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0">
								    Añadir producto
								</button>
							</div>
						</div>
						
					</fieldset>
						
                		@foreach ($pedidosProductos as $pedidosProducto)
	                		<div class="input-group input-group-outline mb-3 mt-4" style="display: flex; align-items: center; justify-content: space-between; width: 725px">
	                			
								<div style="display: flex; align-items: center; justify-content: space-between; width: 200px; margin-right: 80px">
									<label class="col-md-1 control-label font-weight-bold" style="width: 100px">Producto</label>
									<label class="col-md-1 control-label" style="width: 100px" for="productoCreado[]">{{$pedidosProducto->nombreProducto}}</label>
						 			<input class="productoCreado" type="hidden" name="productoCreado[]" value="{{$pedidosProducto->id}}"/>
								</div>
								<div style="display: flex; align-items: center; justify-content: space-between; width: 200px">
						 			<label class="col-md-1 control-label font-weight-bold" style="width: 70px" for="cantidadCreada[]">Cantidad</label>
						 			<div class="col-md-4"  style="width: 100px">
						 				<input  min="1" value="{{ old('cantidadCreada[]', $pedidosProducto->cantidad) }}" id="cantidadCreada" name="cantidadCreada[]" type="number" class="form-control cantidadCreada">
						 				@error('cantidadCreada')
						 		            <div class="alert alert-danger">{{ $message }}</div>
						 		        @enderror
						 			</div>
								</div>
								<a href="javascript: void(0);" data-name="{{ $pedidosProducto->nombreProducto }}" data-table="producto del pedido" data-url="{{ url('pedidoproducto/' . $pedidosProducto->id) }}" data-bs-toggle="modal" data-bs-target="#modalDelete"  class="btn btn-link text-danger text-gradient px-3 mb-0" ><i class="material-icons text-sm me-2">delete</i>Delete</a>
						 	</div>
						 	
                		@endforeach
					
						<div id="divClone1" class="input-group input-group-outline mb-3 mt-4" style="display:none;align-items: center; justify-content: space-between; width: 1000px">
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
						
						<button type="button" class="btn btn-lg bg-gradient-primary btn-lg mt-4 mb-0 btproductoBorrar" style="margin: 0 !important; border-radius: 7px;">
						    Eliminar producto
						</button>
				 	</div>
	
				</form>
				<p style="margin-top: 80px;">* Aquellos campos que no se pueden editar (cantidad de un producto ya insertado con anterioridad), significa que el producto se ha descatalogado. Solo se permite borrar el producto del pedido</p>
        </div>
        </div>				
					
<script>
    let count = 0;
    let btAñadirProducto = document.getElementById('btproducto');
    let btBorrarProducto = document.getElementsByClassName('btproductoBorrar');
    let div = document.getElementById('divClone1');
    
    let cantidad = document.getElementsByClassName('ultimosCantidad');
    let producto = document.getElementsByClassName('ultimosProductos');
    
    let cantidadCreada = document.getElementsByClassName('cantidadCreada');
    let productoCreado = document.getElementsByClassName('productoCreado');
   
	cantidad[0].addEventListener('focusout', function(e){
		console.log(producto[0].options[producto[0].selectedIndex].value);
		var idProducto = producto[0].options[producto[0].selectedIndex].value;
        comprobacionCantidad(idProducto, cantidad[0].value, cantidad[0]);
    });
    
	for (var i = 0; i < cantidadCreada.length; i++) {
    	cantidadCreada[i].outerHTML = cantidadCreada[i].outerHTML;
    	comprobacionProducto(productoCreado[i].value, cantidadCreada[i]);
    	
	    	cantidadCreada[i].addEventListener('focusout', function(){
	    	// console.log(this);
		    	for (var j = 0; j < cantidadCreada.length; j++) {
					if(this == cantidadCreada[j]){
						// console.log(producto[j].options[producto[j].selectedIndex].value);
						var idPedidoProducto = productoCreado[j].value;
		    			console.log(this, idPedidoProducto);
						
		        		comprobacionCantidadDos(idPedidoProducto, this.value, cantidadCreada[j]);
					}
				}
				
	    	})
    	
    }
   
   
    btAñadirProducto.addEventListener('click', function(){
    	console.log('click');
	    if(count == 0){
	        count ++;
		    div.style.display = "flex";
	    	console.log('primera');
    	
	            
	    }else {
	    	
    		console.log('otras');
		    let ultimosProductos = document.getElementsByClassName('ultimosProductos');
		    ultimosProductos = ultimosProductos[ultimosProductos.length-1];
		    let valorInput = ultimosProductos.options[ultimosProductos.selectedIndex].value;
		    console.log(count);
	        if(valorInput != ''){
	           
	            let divClone = div.cloneNode(true);
	            divClone.removeAttribute('id');
	           
	            divClone.id = 'divClone' +  count;
	            div.parentNode.appendChild(divClone);
	            
	            count++;
	            
	        	let ultimosCantidad = document.getElementsByClassName('ultimosCantidad');
	    		ultimosCantidad = ultimosCantidad[ultimosCantidad.length-1];
	        	ultimosCantidad.value = 1;
	        	
	        }
	    }
	    for (var i = 0; i < cantidad.length; i++) {
	    	// if(i != cantidadNu){
	    	// 	console.log('dentro');
		    	// cantidad[i].outerHTML = cantidad[i].outerHTML;
		    	
		    	cantidad[i].addEventListener('focusout', function(){
		    	
		    	añadirEventos(this);
	    		
	    	})
	    
			
	    	btBorrarProducto[i].addEventListener('click', function(){
	    		// let primerBoton = document.getElementsByClassName('ultimosProductos');
	    		var contador = 0;
	    		for (var j = 0; j < btBorrarProducto.length; j++) {
					if(this == btBorrarProducto[j]){
						contador = j;
				// console.log(contador);
					}
				}
	    		if(contador == 0){
		        	// console.log('es el primero');
		        	div.style.display = "none";
				    let ultimosProductos = document.getElementsByClassName('ultimosProductos');
				    ultimosProductos = ultimosProductos[0];
				    ultimosProductos.selectedIndex= 0;
		            count = 0;
		        }else if(contador > 0){
		        	// console.log('es otro');
		        	this.parentNode.remove();
		        	if(count != 0){
		            	count--;
		        		
		        	}
		        } 
	    		// console.log('extoy', this);
	    		// this.parentNode.remove();
	    	})	
	    }
    });
   
    
    
    function añadirEventos(evento){
    	console.log(evento);
    	for (var j = 0; j < cantidad.length; j++) {
			if(evento == cantidad[j]){
				// console.log(producto[j].options[producto[j].selectedIndex].value);
				var idProducto = producto[j].options[producto[j].selectedIndex].value;
				var cantidadAñadir = evento.value;
        		comprobacionCantidad(idProducto, evento.value, cantidad[j]);
			}
		}
    }
    
   
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
    
   
    function comprobacionCantidadDos(idPedidoProducto, cantidad, elemento){
        let url="public/ajax/producto2?pedidoproducto=" + idPedidoProducto+"&cantidad="+ cantidad;
        fetch(url)
        .then(function(response){
            return response.json();
        }).then(function(jsonData){
            // alert(jsonData.respuesta);
            if(!jsonData.respuesta){
            	console.log('cantidadMaxima' +jsonData.cantidadMaxima);
            	// console.log(elemento);
                elemento.value= jsonData.cantidadMaxima;
            }
        }).catch(function(){
           //error
        });
    }
    
   
    function comprobacionProducto(idPedidoProducto, elemento){
        let url="public/ajax/productoCorrecto?pedidoproducto=" + idPedidoProducto;
        fetch(url)
        .then(function(response){
            return response.json();
        }).then(function(jsonData){
            if(jsonData.respuesta == "incorrecta"){
                elemento.readOnly = true;
            	elemento.outerHTML = elemento.outerHTML;
            	
            }
            
        }).catch(function(){
           //error
        });
    }
</script>
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
          <a class="nav-link text-white active bg-gradient-primary" href="{{ url('pedidoindex') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">view_in_ar</i>
            </div>
            <span class="nav-link-text ms-1">Pedidos</span>
          </a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-primary" href="{{ url('pedido') }}">
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