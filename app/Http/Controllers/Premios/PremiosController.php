<?php

namespace App\Http\Controllers\Premios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Personal;
use DB;
use App\Cliente;
use App\Premios;
use App\Digital_lead;
use Carbon\Carbon;


class PremiosController extends Controller
{
    public function ruleta(Request $request){

       $lead_id=$request->gc;
       $nombre=$request->d;


       $con_premio=Premios::select('id','lead_id', 'premio', 'RFC', 'status','descripcion')->where('lead_id','=',$lead_id)->get();

        $search = Digital_lead::select('rfc','id')->where('id','=' ,$lead_id)->where('name_user','=',$nombre)->get();

        if (sizeof($search)==1){
            if(sizeof($con_premio) == 0){
                return view('premios/ruleta',['lead'=>$lead_id,'conPrem'=>$con_premio]);
            }else
                return view('premios/premios',['cpremio'=>$con_premio]);
        }else{
            return view('premios/InvalidURL');
        }
    }


    public function compPremio(Request $request){
        $premio =Premios:: findOrFail($request->id);

        $premio->premio=$request->premio;
        $premio->descripcion=$request->descripcion;
        $premio->save();
    }



    public function storePremio(Request $request){

       $lead_id =$request->lead_id;

       $registrado='0';

       $lead = Digital_lead::findOrFail($lead_id);
       $lead->nombre = $request->nombres;
       $lead->apellidos = $request->apep . ' ' .$request->apem;
       $lead->f_nacimiento =$request->fenac;
       $lead->rfc = $request->rfc;
       $lead->save();


       // clientes- :clasificacion - no viable :1 --  vendedor cliente descartado !=  104
        $persona = Personal::join('clientes','personal.id','=','clientes.id')
        ->where('personal.rfc','=',$request->rfc)
        ->where('clientes.clasificacion','!=','1')
        ->where('clientes.vendedor_id','!=','104')
        ->get();  // verificar



            if(sizeof($persona) !=0){
                $registrado='1';
            }

            $new_premio= new Premios;
            $new_premio->lead_id=$lead_id;
            $new_premio->RFC=$request->rfc;
            $new_premio->save();


        return ['registrado'=>$registrado, 'folio'=>$new_premio->id];
    }

    public function cuponPDF(Request $request){

        $lead_id=$request->gc;
        $nombre=$request->d;

        $search = Digital_lead::join('premios','digital_leads.id','=','premios.lead_id')
        ->select('premios.id','digital_leads.nombre', 'digital_leads.apellidos', 'digital_leads.f_nacimiento', 'premios.premio','premios.created_at')
        ->where('digital_leads.id','=' ,$lead_id)
        ->where('digital_leads.name_user','=',$nombre)
        ->get();





        if (sizeof($search)==1){
            $search[0]->folio = str_pad($search[0]->id, 6, "0", STR_PAD_LEFT);

            setlocale(LC_TIME, 'es_MX.utf8');
            // Se da formato a la fecha de creación del contrato
            $tiempo = new Carbon($search[0]->f_nacimiento);

            $nuevaFecha = Carbon::parse($search[0]->created_at)->addMonths(3)->toDateString();
            $fe_cad = new Carbon($nuevaFecha);
            $search[0]->f_nacimiento = $tiempo->formatLocalized('%d de %B de %Y');
            $search[0]->f_caducidad = $fe_cad->formatLocalized('%d de %B de %Y');
            $pdf = \PDF::loadview('premios.cuponPDF',['lead'=>$search]);
            return $pdf->stream('CUPON.pdf');

        }else{
            return view('premios/InvalidURL');
        }



    }


    public function regCorrWhats(Request $request){
        $lead_id=$request->lead_id;
        $reg_correo=$request->reg_correo;
        $reg_whts=$request->reg_whts;

        $lead = Digital_lead::findOrFail($lead_id);
        if($request->send == 'email'){
            $lead->s_email=1;
            $lead->s_whats=0;
        }
        if($request->send == 'whats'){
            $lead->s_email=0;
            $lead->s_whats=1;
        }
        if($reg_correo !='' )
            $lead->email=$reg_correo;
        if($reg_whts !=''){
            $lead->celular=$reg_whts;
        }
        $lead->save();


    }

}

