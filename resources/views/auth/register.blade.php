@extends('layouts.app')

@section('content')
<div>
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url( {{ url('assets/img/illustrations/illustration-signup.jpg') }}); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Registrarte</h4>
                  <p class="mb-0">Introduce los siguientes datos para registrarte</p>
                </div>
                <div class="card-body">
                  
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                  <form id="formulario" method="POST" action="{{ route('register') }}">
                    
                        @csrf
                    <div class="input-group input-group-outline mb-3 is-filled">
                        
                      <label for="nombre" class="form-label">Nombre</label>
                      <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>

                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="input-group input-group-outline mb-3 is-filled">
                        
                      <label for="apellidos" class="form-label">Apellidos</label>
                      <input id="apellidos" type="text" class="form-control @error('apellidos') is-invalid @enderror" name="apellidos" value="{{ old('apellidos') }}" required autocomplete="apellidos" autofocus>

                        @error('apellidos')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="input-group input-group-outline mb-3 is-filled">
                        
                      <label for="DNI" class="form-label">DNI</label>
                      <input id="DNI" type="text" class="form-control @error('DNI') is-invalid @enderror" name="DNI" value="{{ old('DNI') }}" required autocomplete="DNI" autofocus>

                        @error('DNI')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="input-group input-group-outline mb-3 is-filled">
                        
                      <label for="direccion" class="form-label">Dirección</label>
                      <input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" value="{{ old('direccion') }}" required autocomplete="direccion" autofocus>

                        @error('direccion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <hr>
                    <div class="input-group input-group-outline mb-3 is-filled">
                        
                      <label for="name" class="form-label">Usuario</label>
                      <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <p style="margin-bottom: 30px !important; background-color: orange; padding: 10px; display: none;" id="nameUsuario"></p>
                    
                    <div class="input-group input-group-outline mb-3 is-filled">
                      <label for="email" class="form-label">Email</label>
                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <p style="margin-bottom: 30px !important; background-color: orange; padding: 10px; display: none;" id="emailUsuario"></p>
                    
                    
                    <div class="input-group input-group-outline mb-3 is-filled">
                      <label for="password" class="form-label">Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    
                    <div class="input-group input-group-outline mb-3 is-filled">
                      <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                    
                    
                    <div class="text-center">
                        <button id="formRegister" disabled type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">
                            Registrarse
                        </button>
                      <!--<button type="button" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Sign Up</button>-->
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    ¿Ya tienes una sesión?
                    <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Inicio sesión</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



<script>
    let ok = false;
    let okname = false;
   
    let email = document.getElementById('email');
    email.addEventListener('blur', function(e){
        ok = false;
        llamadaAjaxCorreo();
    });
    let name = document.getElementById('name');
    name.addEventListener('blur', function(e){
        ok = false;
        llamadaAjaxUsuario();
    });
   
    let formRegister = document.getElementById('formRegister');
    formRegister.addEventListener('submit', function(e){
        if(ok){
            formRegister.disabled = false;
        }
    });
   
    function llamadaAjaxCorreo(){
        let mail = email.value;
        let url="ajax/email?email=" + mail;
        fetch(url)
        .then(function(response){
            return response.json();
        }).then(function(jsonData){
            // alert(jsonData.respuesta);
            if(jsonData.respuesta){
                ok = true;
                document.getElementById('emailUsuario').innerHTML="";
                document.getElementById('emailUsuario').style.display = 'none';
                if(okname){
                    formRegister.disabled = false;
                }
            }else{
                document.getElementById('emailUsuario').innerHTML="Este email ya tiene una cuenta";
                document.getElementById('emailUsuario').style.display = 'block';
                formRegister.disabled = true;
            }
        }).catch(function(){
           //error
        });
    }
    
   
    function llamadaAjaxUsuario(){
        let nameUsuario = name.value;
        let url="ajax/name?name=" + nameUsuario;
        fetch(url)
        .then(function(response){
            return response.json();
        }).then(function(jsonData){
            // alert(jsonData.respuesta);
            if(jsonData.respuesta){
                okname = true;
                document.getElementById('nameUsuario').innerHTML="";
                document.getElementById('nameUsuario').style.display = 'none';
                if(ok){
                    formRegister.disabled = false;
                }
            }else{
                document.getElementById('nameUsuario').innerHTML="Este nombre de usuario no esta disponible";
                document.getElementById('nameUsuario').style.display = 'block';
                formRegister.disabled = true;
            }
        }).catch(function(){
        });
    }
</script>
@endsection
