<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Digital_lead;
use App\Obs_lead;
use Auth;
use DB;

class DigitalLeadController extends Controller
{
    public function index(Request $request){
        $leads = Digital_lead::join('campanias as c','digital_leads.campania_id','=','c.id')
                        ->leftJoin('fraccionamientos as f','digital_leads.proyecto_interes','=','f.id')
                        ->select('digital_leads.nombre','digital_leads.apellidos','digital_leads.email','digital_leads.campania_id',
                                'digital_leads.celular','digital_leads.telefono','digital_leads.proyecto_interes','digital_leads.modelo_interes',
                                'digital_leads.rango1','digital_leads.rango2','digital_leads.tipo_uso', 'digital_leads.medio_contacto',
                                'digital_leads.rfc', 'digital_leads.nss', 'digital_leads.sexo', 'digital_leads.f_nacimiento',
                                'digital_leads.edo_civil', 'digital_leads.hijos', 'digital_leads.num_hijos',
                                'digital_leads.mascotas', 'digital_leads.tam_mascota',
                                'digital_leads.edo_civil','digital_leads.edo_civil','digital_leads.edo_civil',
                                'digital_leads.edo_civil','digital_leads.edo_civil','digital_leads.edo_civil',
                                'c.nombre_campania','c.medio_digital','f.nombre as proyecto','digital_leads.status')
                        ->orderBy('nombre','asc')
                        ->orderBy('apellidos','asc')
                        ->paginate(1);

        return $leads;
    }

    public function store(Request $request){
        $lead = new Digital_lead();
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->celular = $request->celular;
        $lead->nss = $request->nss;
        $lead->curp = $request->curp;
        $lead->rfc = $request->rfc;
        $lead->f_nacimiento = $request->f_nacimiento;
        $lead->email = $request->email;
        $lead->sexo = $request->sexo;
        $lead->proyecto_interes = $request->proyecto;
        $lead->save();
    }

    public function update(Request $request){
        $lead = Digital_lead::findOrFail($request->id);
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->celular = $request->celular;
        $lead->nss = $request->nss;
        $lead->curp = $request->curp;
        $lead->rfc = $request->rfc;
        $lead->f_nacimiento = $request->f_nacimiento;
        $lead->email = $request->email;
        $lead->sexo = $request->sexo;
        $lead->proyecto = $request->proyecto;
        $lead->save();
    }

    public function asignarLead(Request $request){

        $lead = Digital_lead::findOrFail($request->id);

        
        
    }
}
