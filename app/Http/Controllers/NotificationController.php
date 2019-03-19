<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function get(){
        // return Notification::all();
        $unreadNotifications = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');

        foreach($unreadNotifications as $notification){
            if($fechaActual != $notification->created_at->toDateString()){
                $notification->markAsRead();
            }
            $notification->markAsRead();
        }

        return Auth::user()->unreadNotifications;
    }

    public function getListado(){
        $notificaciones = Notification::select('data')
                                       ->where('notifiable_id','=',Auth::user()->id)
                                       ->take(10)->get();
        return ['notificaciones' => $notificaciones];
    }

}
