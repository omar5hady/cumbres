<?php

namespace App\Http\Controllers\Form;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EventoRegistro;
use App\Cliente;
use DB;


class EventoController extends Controller
{
    public function store(Request $request){
        $registro = new EventoRegistro();
        $registro->nombre       = $request->nombre;
        $registro->ap_paterno   = $request->ap_paterno;
        $registro->ap_materno   = $request->ap_materno;
        $registro->clv_lada     = $request->clv_lada;
        $registro->celular      = $request->celular;
        $registro->f_nacimiento = $request->f_nacimiento;
        $registro->email        = $request->email;
        $registro->asist_adult  = $request->asist_adult;
        $registro->asist_kid    = $request->asist_kid;
        $registro->rfc          = $request->rfc;
        $registro->is_cliente   = $request->is_cliente;
        if($registro->is_cliente == 1)
            $registro->cliente_id   = $this->findRFC($request->rfc);
        $registro->fraccionamiento = $request->fraccionamiento;
        $registro->evento       = $request->evento;
        $registro->save();

        return 'https://siicumbres.com/invitacion/print?id='.$registro->id;
    }

    private function findRFC($rfc){
        $cliente = Cliente::join('personal','personal.id','=','clientes.id')
            ->select('clientes.id','personal.rfc')
            ->where('personal.rfc','=',$rfc)->get();

            if(sizeof($cliente))
                return $cliente[0]->id;
            else
                return NULL;


    }

    public function printInvitacion(Request $request){
        $invitado = EventoRegistro::where('id','=',$request->id)->first();
        $pdf = \PDF::loadview('pdf.evento.invitacion',['invitado'=> $invitado,
            'qr' => '<h1>Oso PArdo</h1>'
        ]);
            return $pdf->stream('invitacion.pdf');
    }

    public function getEvento(Request $request){
        return EventoRegistro::where('id','=',$request->id)->first();
    }

    public function enterPage(Request $request){
        $invitado = EventoRegistro::where('id','=',$request->id)->first();
        return view('contenido/evento',['invitado'=>$invitado]);
    }

    public function confirmAsist(Request $request){
        $invitado = EventoRegistro::findOrFail($request->id);
        $invitado->status = 1;
        $invitado->extra_adult = $request->asist_adult - $invitado->asist_adult;
        $invitado->extra_kid = $request->asist_kid - $invitado->asist_kid;
        $invitado->save();
    }

    public function findInvitado(Request $request){
        return EventoRegistro::where(
            DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno)"), 'like', '%'. $request->nombre . '%'
        )->first();
    }
}
