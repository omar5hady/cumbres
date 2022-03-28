<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;
use App\User;
use App\Personal;
use App\Rol;
//Controlador para el modelo Lugar_contacto.
class NotificationController extends Controller
{
    //Funcion que retorna todas las notificaciones sin leer
    public function get(Request $request){
        // return Notification::all();
        $unreadNotifications = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');
        //Cuando muestran todas
        if($request->op == 1)
        //Se recorren las notificaciones 
        foreach($unreadNotifications as $notification){
            //la notificacion se marca como vista.
            if($fechaActual != $notification->created_at->toDateString()){
                $notification->markAsRead();
            }
            $notification->markAsRead();
        }
        return Auth::user()->unreadNotifications;
    }

    //Funcion que retorna las notificaciones que han sido leidas.
    public function getListado(){
        return Auth::user()->readNotifications;
    }

    //Funcion que retorna todos los roles dados de alta
    public function getRol(){
        $rol = Rol::select('id','nombre')->get();
        return['rol'=>$rol];
    }
    //Funcion que retorna los usuarios por rol.
    public function getUser(Request $request){
        $id = $request->id;
        $user=Rol::join('users','roles.id','=','users.rol_id')
                ->leftJoin('vendedores','users.id','=','vendedores.id')
                ->join('personal','users.id','=','personal.id')
                ->select('personal.nombre','personal.apellidos','personal.id')
                ->where('roles.id','=',$id);

                if($id == 2)//Si la busqueda es por rol de asesor
                    $user = $user->where('vendedores.tipo','=',0);//Se retornan solo los asesores internos
                $user = $user->where('users.condicion','=',1)
                ->get();
        return['personal'=> $user];

    }

}
