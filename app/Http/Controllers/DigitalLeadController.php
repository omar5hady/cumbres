<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Digital_lead;
use App\Obs_lead;
use Auth;
use DB;
use Carbon\Carbon;

class DigitalLeadController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $campania = $request->campania;
        $status = $request->status;
        $asesor = $request->asesor;
        $leads = Digital_lead::join('campanias as c','digital_leads.campania_id','=','c.id')
                        ->leftJoin('fraccionamientos as f','digital_leads.proyecto_interes','=','f.id')
                        ->leftJoin('personal as p','digital_leads.vendedor_asign','=','p.id')
                        ->select(
                                DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS vendedor"),
                                'c.nombre_campania','c.medio_digital','f.nombre as proyecto','digital_leads.*');
                        if(Auth::user()->rol_id == 2){
                            $leads = $leads->where('vendedor_asign','=',Auth::user()->id);
                        }

                        if($buscar != ''){
                            $leads = $leads->where(DB::raw("CONCAT(digital_leads.nombre,' ',digital_leads.apellidos)"), 'like', '%'. $buscar . '%');
                        }

                        if($status != ''){
                            $leads = $leads->where('digital_leads.status','=',$status);
                        }

                        if($campania != ''){
                            $leads = $leads->where('digital_leads.campania_id','=',$campania);
                        }

                        if($asesor != ''){
                            $leads = $leads->where('digital_leads.vendedor_asign','=',$asesor);
                        }

                        $leads = $leads->orderBy('nombre','asc')
                        ->orderBy('apellidos','asc')
                        ->paginate(10);

        if(sizeof($leads))
        foreach($leads as $index => $persona){
            $date = Carbon::parse($persona->fecha_update);
            $now = Carbon::now();
            $persona->diferencia = $date->diffInDays($now);
        }
                        
        return $leads;
    }

    public function store(Request $request){
        $fecha = Carbon::now();
        $lead = new Digital_lead();
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->telefono = $request->telefono;
        $lead->celular = $request->celular;
        $lead->campania_id = $request->campania_id;
        $lead->medio_contacto = $request->medio_contacto;
        $lead->proyecto_interes = $request->proyecto_interes;
        $lead->tipo_uso = $request->tipo_uso;
        $lead->modelo_interes = $request->modelo_interes;
        $lead->rango1 = $request->rango1;
        $lead->rango2 = $request->rango2;
        $lead->email = $request->email;
        $lead->zona_interes = $request->zona_interes;

        if($request->vendedor_asign!= 0){
            $lead->vendedor_asign = $request->vendedor_asign;
        }
        $lead->fecha_update = $fecha;

        /////////////// PASO 2 ////////////////
        $lead->rfc = $request->rfc;
        $lead->nss = $request->nss;
        $lead->sexo = $request->sexo;
        $lead->f_nacimiento = $request->f_nacimiento;
        $lead->edo_civil = $request->edo_civil;
        $lead->hijos = $request->hijos;
        $lead->num_hijos = $request->num_hijos;
        $lead->empresa = $request->empresa;
        $lead->ingresos = $request->ingresos;

        ////////////// Paso 3 /////////////////
        $lead->mascotas = $request->mascotas;
        $lead->tam_mascota = $request->tam_mascota;
        $lead->num_mascotas = $request->num_mascotas;
        $lead->tipo_credito = $request->tipo_credito;
        $lead->coacreditado = $request->coacreditado;
        $lead->num_autos = $request->num_autos;
        $lead->autos = $request->autos;
        $lead->amenidad_priv = $request->amenidad_priv;
        $lead->detalle_casa = $request->detalle_casa;
        $lead->perfil_cliente = $request->perfil_cliente;
        $lead->save();
        
        $obs = new Obs_lead();
        $obs->lead_id = $lead->id;
        $obs->comentario = 'Lead registrado';
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function update(Request $request){
        $fecha = Carbon::now();
        $lead = Digital_lead::findOrFail($request->id);
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->telefono = $request->telefono;
        $lead->celular = $request->celular;
        $lead->campania_id = $request->campania_id;
        $lead->medio_contacto = $request->medio_contacto;
        $lead->proyecto_interes = $request->proyecto_interes;
        $lead->tipo_uso = $request->tipo_uso;
        $lead->modelo_interes = $request->modelo_interes;
        $lead->rango1 = $request->rango1;
        $lead->rango2 = $request->rango2;
        $lead->email = $request->email;
        $lead->zona_interes = $request->zona_interes;
        $lead->fecha_update = $fecha;
        if($request->vendedor_asign!= 0){
            $lead->vendedor_asign = $request->vendedor_asign;
        }

        /////////////// PASO 2 ////////////////
        $lead->rfc = $request->rfc;
        $lead->nss = $request->nss;
        $lead->sexo = $request->sexo;
        $lead->f_nacimiento = $request->f_nacimiento;
        $lead->edo_civil = $request->edo_civil;
        $lead->hijos = $request->hijos;
        $lead->num_hijos = $request->num_hijos;
        $lead->empresa = $request->empresa;
        $lead->ingresos = $request->ingresos;

        ////////////// Paso 3 /////////////////
        $lead->mascotas = $request->mascotas;
        $lead->tam_mascota = $request->tam_mascota;
        $lead->num_mascotas = $request->num_mascotas;
        $lead->tipo_credito = $request->tipo_credito;
        $lead->coacreditado = $request->coacreditado;
        $lead->num_autos = $request->num_autos;
        $lead->autos = $request->autos;
        $lead->amenidad_priv = $request->amenidad_priv;
        $lead->detalle_casa = $request->detalle_casa;
        $lead->perfil_cliente = $request->perfil_cliente;
        $lead->save();

        $obs = new Obs_lead();
        $obs->lead_id = $lead->id;
        $obs->comentario = 'Se actualiza información del Lead';
        $obs->usuario = Auth::user()->usuario;
        $obs->save();

    }

    public function storeObs(Request $request){
        $fecha = Carbon::now();
        $obs = new Obs_lead();
        $obs->lead_id = $request->lead_id;
        $obs->comentario = $request->comentario;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();

        $lead = Digital_lead::findOrFail($request->lead_id);
        $lead->fecha_update = $fecha;
        $lead->save();
        
    }

    public function getObs(Request $request){
        $obs = Obs_lead::where('lead_id','=',$request->id)->paginate(15);
        return $obs;
    }

    public function asignarLead(Request $request){
        $lead = Digital_lead::findOrFail($request->id);
    }
}
