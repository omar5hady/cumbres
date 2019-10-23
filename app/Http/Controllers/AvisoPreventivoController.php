<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aviso_preventivo;
use App\Contrato;
use DB;
use Carbon\Carbon;
use Auth;

class AvisoPreventivoController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $folio = $request->folio;
        
        $aviso = new Aviso_preventivo();
        $aviso->contrato_id = $folio;
        $aviso->notaria_id = $request->notaria_id;
        $aviso->fecha_solicitud = $request->fecha_solicitud;
        $aviso->save();

        $contrato = Contrato::findOrFail($folio);
        $contrato->aviso_prev = $request->fecha_solicitud;
        $contrato->save();
     }

     public function noAplicaAviso(Request $request){
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->aviso_prev = "0000-01-01";
        $contrato->save();
    }

    public function registrarFechaRecibido(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avisoid = Aviso_preventivo::select('id')->where('contrato_id','=',$request->folio)->get();
        $fecha = Aviso_preventivo::findOrFail($avisoid[0]->id);
        $fecha->fecha_recibido = $request->fecha_recibido ;
        $fecha->fecha_vencimiento = $request->fecha_vencimiento ;
        $fecha->save();
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->aviso_prev_venc=$request->fecha_vencimiento;
        $contrato->save();

    }

    public function solicitudPDF($id){

        $solicitud = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('personal as v', 'vendedores.id', '=', 'v.id')
        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
        ->join('aviso_preventivos', 'contratos.id', '=', 'aviso_preventivos.contrato_id')
        ->join('notarias', 'aviso_preventivos.notaria_id', '=', 'notarias.id')
        ->select(
            'contratos.id as folio',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'contratos.fecha_status',
            'i.tipo_credito',
            'i.institucion',
            'contratos.avaluo_preventivo',
            'contratos.aviso_prev',
            'contratos.aviso_prev_venc',
            'lotes.credito_puente',
            'lotes.calle',
            'lotes.numero',
            'lotes.interior',
            'notarias.notaria',
            'notarias.titular',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
            'clientes.coacreditado',
            'contratos.integracion',
            'lotes.fraccionamiento_id'
        )
        ->where('i.elegido', '=', 1)
        ->where('contratos.status', '!=', 0)
        ->where('contratos.status', '!=', 2)
        ->where('aviso_preventivos.contrato_id', '=', $id)
        ->get();

        setlocale(LC_TIME, 'es_MX.utf8');
        $fechaAviso = new Carbon($solicitud[0]->aviso_prev);
        $solicitud[0]->aviso_prev = $fechaAviso->formatLocalized('%d/%B/%Y');

        $pdf = \PDF::loadview('pdf.solicitudAvisosPreventivos',['solicitud' => $solicitud]);
        return $pdf->stream('solicitud_de_avisos_preventivos.pdf');
    }
    
}
