<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
  public function showLoginForm(){
      return view('auth.login');
  }  

  public function login(Request $request){
   
    $this->validateLogin($request);

        if(Auth::attempt(['usuario'=>$request->usuario, 'password' => $request->password,'condicion'=>1])){
            return redirect()->route('main');

        }

        return back()->withErrors(['usuario' => trans('auth.failed')])->withInput(request(['usuario']));
  }

  protected function validateLogin(Request $request){
    $this->validate($request,[
        'usuario' => 'required|string',
        'password' =>'required|string'
    ]);
  }

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
