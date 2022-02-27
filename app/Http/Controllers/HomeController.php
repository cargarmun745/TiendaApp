<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        
        $this->middleware('userverified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->rol == 'cliente'){
            return redirect('productoindex');
        } else{
            return view('admin/home');
        }
    }
    public function segundo()
    {
        if(auth()->user()->rol == 'cliente'){
            return view('home');
        } else{
            return view('admin/home');
        }
    }
}
