<?php

namespace App\Http\Controllers\Form;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contrato;

class EncuestaController extends Controller
{
    public function showEncuesta1(){
        return view('form/formulario');
    }

    // public function getDatosContrato(Request $request){
    //     $datos = Contrato::join('creditos','contratos.id','=','creditos.id')
    //             ->join('')
    // }
}
