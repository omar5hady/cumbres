<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solic_detalle;
use App\Descripcion_detalle;
use App\User;
use App\Notifications\NotifyAdmin;
use Auth;
use DB;

class SolicDetallesController extends Controller
{
    public function storeSolicitud(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
 
        try{
            DB::beginTransaction(); 
            $solicitud = new Solic_detalle();
            $solicitud->contrato_id = $request->folio;
            $solicitud->contratista_id = $request->contratista_id;
            $solicitud->cliente = $request->cliente;
            $solicitud->dias_entrega = $request->dias_entrega;
            $solicitud->lunes = $request->lunes;
            $solicitud->martes = $request->martes;
            $solicitud->miercoles = $request->miercoles;
            $solicitud->jueves = $request->jueves;
            $solicitud->viernes = $request->viernes;
            $solicitud->sabado = $request->sabado;
            $solicitud->horario = $request->horario;
            $solicitud->celular = $request->celular;
            
            $solicitud->save();
 
            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
 
            foreach($detalles as $ep=>$det)
            {
                $descripcion = new Descripcion_detalle();
                $descripcion->solicitud_id = $solicitud->id;
                $descripcion->detalle_id = $det['id_detalle'];
                $descripcion->garantia = $det['garantia'];
                $descripcion->observacion = $det['observacion'];
                $descripcion->detalle = $det['detalle'];
                $descripcion->subconcepto = $det['subconcepto'];
                $descripcion->general = $det['general'];
                $descripcion->save();
            }          
 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

}
