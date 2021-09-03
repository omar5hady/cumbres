<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;
use App\User;
use App\Personal;
use App\Rol;

class NotificationController extends Controller
{
    public function get(Request $request){
        // return Notification::all();
        $unreadNotifications = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');
        
        if($request->op == 1)
        foreach($unreadNotifications as $notification){
            if($fechaActual != $notification->created_at->toDateString()){
                $notification->markAsRead();
            }
            $notification->markAsRead();
        }

        return Auth::user()->unreadNotifications;
    }

    public function getListado(){
        // $notificaciones = Notification::select('data')
        //                                ->where('notifiable_id','=',Auth::user()->id)
        //                                ->take(10)->get();
        // return response()->json($notificaciones);

        return Auth::user()->readNotifications;
    }

    public function getRol()
    {
        $rol =Rol::select('id','nombre')->get();

        return['rol'=>$rol];

    }

    public function getUser(Request $request){

        $id = $request -> id;

        $user=Rol::join('users','roles.id','=','users.rol_id')
                 ->join('personal','users.id','=','personal.id')
                 ->select('personal.nombre','personal.apellidos','personal.id')
                 ->where('roles.id','=',$id)
                 ->where('users.condicion','=',1)
                 ->get();

        return['personal'=> $user];

    }

}
