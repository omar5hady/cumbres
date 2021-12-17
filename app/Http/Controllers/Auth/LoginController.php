<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //Funcion para retornar la vista de logueo
    public function showLoginForm(){
        return view('auth.login');
    }  

    //Funcion para iniciar sesión
    public function login(Request $request){
    
        //Se llama a la función de validacion
        $this->validateLogin($request);

            //Revisa que el usuario y contraseña coincidan con la BD y el usuario este activo.
            if(Auth::attempt(['usuario'=>$request->usuario, 'password' => $request->password,'condicion'=>1])){
                //Si se cumple se redirige a la vista principal del sistema
                return redirect()->route('main');

            }
            //Caso contrario, se retorna a la vista de login con el error de logueo.
            return back()->withErrors(['usuario' => trans('auth.failed')])->withInput(request(['usuario']));
    }

    //Funcion para validar los datos que necesita el login
    protected function validateLogin(Request $request){
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' =>'required|string'
        ]);
    }

    //Funcion para terminar sesión
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');

    }


    /**
     * Envía la respuesta después de que el usuario se autentifique.
     * Elimina el resto de sesiones de este usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $previous_session = Auth::User()->session_id;
        if ($previous_session) {
            Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id = Session::getId();
        Auth::user()->save();
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

}
