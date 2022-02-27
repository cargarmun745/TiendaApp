<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\ClienteCreateRequest;
use App\Http\Requests\EmpleadoCreateRequest;

use App\Models\Cliente;
use App\Models\Empleado;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
    
       // dd($data, 'uno');
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($data, 'dos');
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    public function register(ClienteCreateRequest $request)
    {
        DB::beginTransaction();
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        //$this->guard()->login($user);
        $request->session()->flash('register', true);
        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        
        $cliente = new Cliente($request->all());
        $cliente->IDUsuario = $user->id;
        // dd($cliente);
        try{
            $cliente->save();
            // $data['message'] = 'Se ha creado correctamente el usuario';
            // return redirect('home')->with($data);
            
        }catch(\Exception $e){
            DB::rollBack();
            $data['message'] = 'Ha habido un error al crear el usuario';
            // dd($e);
            return back()->withInput()->with($data);
        }
        
        
        DB::commit();
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
    
    public function registerEmpleados(EmpleadoCreateRequest $request)
    {
            // dd($empleado, 'estoy 1');
        DB::beginTransaction();
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        //$this->guard()->login($user);
        $request->session()->flash('register', true);
        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        if(isset($request->rol)){
            $user->rol = $request->rol;
        }else{
            $user->rol = 'empleado';
        }
        
        
        // dd($request, $user);
        $user->update();
        
        $empleado = new Empleado($request->all());
        $empleado->IDUsuario = $user->id;
        // dd($empleado, 'estoy 1');
        try{
            // dd($empleado, 'estoy 2');
            $empleado->save();
            // $data['message'] = 'Se ha creado correctamente el usuario';
            // return redirect('home')->with($data);
            
        }catch(\Exception $e){
            DB::rollBack();
            $data['message'] = 'Ha habido un error ';
            // dd($e);
            // dd($empleado, $e);
            return back()->withInput()->with($data);
        }
        
        
        DB::commit();
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
}
