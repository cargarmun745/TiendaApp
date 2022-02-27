<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\UserEditRequest;
use App\Models\User;
use Carbon;


class UserController extends Controller
{
    public function __construct(){
        $this->middleware('verifed')->only('userupdate');
        $this->middleware('admin')->except('userupdate');
    }
    
    public function index()
    {
        //Paginacion
        //Ordenacion
        //Filtrado de datos
        //Registros por página
        return view('user.index', ['users' => User::all()]);
    }

    public function create()
    {
        return view('user.create', ['roles' => ['admin', 'advanced', 'user']]);
    }
    
    public function store(Request $request)
    {
        $user = new User($request->all());
        $user->password = Hash::make($request->input('password'));
        if($request->verify == 'true'){
               $user->email_verified_at = Carbon\Carbon::now();
        }
        
        try{
            $user->save();
            if($request->verify != 'true'){
                $user->sendEmailVerificationNotification();
            }
        }catch(\Exception $e){
            return back()->withInput();
        }
        return redirect('user');
        
    }

    public function show(User $user)
    {
        
    }
    
    public function edit(User $user)
    {
        return view('user.edit', ['roles' => ['admin', 'advanced', 'user'], 'user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        if($request->password != null){
            $data = $request->expect(['password']);
        }else{
            
            $data = $request->all();
            $data['password'] = Hash::make($request->input('password'));
        }
        try{
            $user->update($data);
            if($request->verify != 'true'){
                $user->sendEmailVerificationNotification();
            }
        }catch(\Exception $e){
            return back()->withInput();
        }
    }

    public function userupdate(UserEditRequest $request)
    {
        // dd($request->oldpassword);
        if($request->oldpassword != null && $request->password != null){
            // Se cambia todo
            if(Hash::check($request->oldpassword, auth()->user()->password)){
                $result = $this->userSave($request, true);
            }else{
                return back()->withInput()->withError(['oldpassword' => 'La clave de acceso anterior no es correcta']);
            }
            
        }elseif($request->oldpassword == null && $request->password == null){
            $result = $this->userSave($request, false);
        }else{
                return back()->withInput()->withError(['generico' => 'Hay algún dato incorrecto']);
        }
        
        if($result){
            $data = ['message' => 'todo bien'];
        }else{
            $data = ['message' => 'todo mal'];
        }
        return redirect(url('/home'))->with($data);
        
    }
    
    public function userSave(Request $request, $isNewPassword){
        $result = true;
        $user = auth()->user(); //auth()->user()->id
        $user->name = $request->input('name');
        
        if($user->email != $request->input('email')){
            $user->email = $request->input('email');  
            $user->email_verified_at = null;
        }
        
        if($isNewPassword){
            $user->password = Hash::make($request->input('password'));
        }
        
        try{
            $user->save();
            $user->sendEmailVerificationNotification();
        }catch(\Exception $e){
            $result = false;
        }
        
    }

    public function destroy(User $user)
    {
        
    }
}
