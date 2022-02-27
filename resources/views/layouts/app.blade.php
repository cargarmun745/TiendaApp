<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ url('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ url('assets/img/favicon.png') }}">
  <title>
    Sport Titan
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ url('assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />
	@yield('css')
</head>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
          
        @section('header')
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
              <div class="container-fluid ps-2 pe-0">
                <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href=" {{ url('/') }}">
                  Sport Titan
                </a>
                <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon mt-2">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                  </span>
                </button>
                <div class="collapse navbar-collapse" id="navigation">
                  <ul class="navbar-nav mx-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                              <a class="nav-link me-2" href="{{ route('login') }}">
                                <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                Iniciar sesión
                              </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                              <a class="nav-link me-2" href="{{ route('register') }}">
                                <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                Registrarse
                              </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                          <a class="nav-link me-2"  role="button" href="#"> Eres el usuario: 
                            {{ Auth::user()->name }}
                          </a>
                        </li>
                        <li class="nav-item">
                            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">-->
                                <a class="nav-link me-2" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Salir de la sesión
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            <!--</div>-->
                        </li>
                    @endguest
                    
                  </ul>
                </div>
              </div>
            </nav>
            <!-- End Navbar -->
		@show
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
        @yield('content')
        
        <footer class="footer position-absolute bottom-2 py-2 w-100">
          <div class="container">
            <div class="row align-items-center justify-content-lg-between">
              <div class="col-12 col-md-6 my-auto">
                <div class="copyright text-center text-sm text-white text-lg-start">
                  Creado por Carmen García Muñoz
                </div>
              </div>
              <div class="col-12 col-md-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com" class="nav-link text-white" target="_blank">Creative Tim</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-white" target="_blank">About Us</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com/blog" class="nav-link text-white" target="_blank">Blog</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-white" target="_blank">License</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="{{ url('assets/js/core/popper.min.js')}}"></script>
  <script src="{{ url('assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{ url('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{ url('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ url('assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
	@yield('js')
</body>

</html>