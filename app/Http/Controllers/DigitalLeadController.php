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
        $leads = Digital_lead::orderBy('nombre','asc')
                        ->orderBy('apellidos','asc')
                        ->paginate(10);

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
        $lead->proyecto = $request->proyecto;
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
