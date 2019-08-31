<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaluo;
use App\Contrato;
use App\Avaluo_status;
use App\Gasto_admin;
use Carbon\Carbon;
use App\User;
use DB;
use App\Notifications\NotifyAdmin;
use File;


class AvaluoController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
        $avaluo = new Avaluo();
        $avaluo->contrato_id = $request->folio;
        $avaluo->fecha_solicitud = $request->fecha_solicitud;
        $avaluo->valor_requerido = $request->valor_requerido;
        $avaluo->save();

        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = $request->fecha_solicitud;
        $contrato->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "Nueva solicitud de avaluo para el contrato #" . $request->folio;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Venta :)'
                    ]
                ];


                User::findOrFail(3)->notify(new NotifyAdmin($arregloAceptado));


    }

    public function noAplicaAvaluo(Request $request){
        if(!$request->ajax())return redirect('/');
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = "0000-01-01";
        $contrato->save();
    }

    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        if($buscar == ''){
            $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->orderBy('contratos.id','asc')
                    ->paginate(25);

        }
        else{
            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('contratos.id','asc')
                        ->paginate(25);

                    }
                    break;
                }
                case 'licencias.visita_avaluo':{
                    $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('licencias.visita_avaluo','=',$buscar)
                    ->orderBy('contratos.id','asc')
                    ->paginate(25);
                    break;
                }
            }
        }
        
        return [
            'pagination' => [
            'total'         => $avaluos->total(),
            'current_page'  => $avaluos->currentPage(),
            'per_page'      => $avaluos->perPage(),
            'last_page'     => $avaluos->lastPage(),
            'from'          => $avaluos->firstItem(),
            'to'            => $avaluos->lastItem(),
        ],'avaluos' => $avaluos];
    }

    public function setFechaSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        $avaluo_id = $request->avaluoId;

        $avaluo=Avaluo::findOrFail($avaluo_id);
        $avaluo->fecha_ava_sol = $request->fecha_ava_sol;
        $avaluo->save();
    }

    public function setFechaPago(Request $request){
        if(!$request->ajax())return redirect('/');
        $avaluo_id = $request->avaluoId;

        $avaluo=Avaluo::findOrFail($avaluo_id);
        $avaluo->fecha_pago = $request->fecha_pago;
        $avaluo->save();
    }

    public function setFechaConcluido(Request $request){
        if(!$request->ajax())return redirect('/');
        $avaluo_id = $request->avaluoId;

        try{
            DB::beginTransaction();
            if($request->costo >0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $request->id;
                $gasto->concepto = 'Avaluo';
                $gasto->costo = $request->costo;
                $gasto->fecha = $request->fecha_concluido;
                $gasto->observacion = '';
                $gasto->save();
            }

            $avaluo=Avaluo::findOrFail($avaluo_id);
            $avaluo->costo = $request->costo;
            $avaluo->fecha_concluido = $request->fecha_concluido;
            $avaluo->resultado = $request->resultado;
            $avaluo->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo = $contrato->saldo + $request->costo;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function updateFechaConcluido(Request $request){
        if(!$request->ajax())return redirect('/');

        try{
            DB::beginTransaction();
            if($request->costo > 0){
                $gasto = Gasto_admin::findOrFail($request->gasto_id);
                $costo_ant = $gasto->costo;
                $contrato_id = $gasto->contrato_id;
                $gasto->costo = $request->costo;
                $gasto->fecha = $request->fecha_concluido;
                $gasto->save();
            }
            

            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $avaluo->costo = $request->costo;
            $avaluo->fecha_concluido = $request->fecha_concluido;
            $avaluo->resultado = $request->resultado;
            $avaluo->save();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = $contrato->saldo - $costo_ant + $request->costo;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function enviarVentas(Request $request){
        if(!$request->ajax())return redirect('/');
        $avaluo_id = $request->id;
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $avaluo=Avaluo::findOrFail($avaluo_id);
        $avaluo->fecha_recibido = $hoy;
        $avaluo->save();
    }

    public function formSubmitAvaluo(Request $request, $id)
    {
        if(!$request->ajax())return redirect('/');
        $pdfAnterior = Avaluo::select('pdf', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($pdfAnterior->isEmpty() == 1) {
            $fileName = time() . '.' . $request->pdf->getClientOriginalExtension();
            $moved =  $request->pdf->move(public_path('/files/avaluos'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $predial = Avaluo::findOrFail($request->id);
                $predial->pdf = $fileName;
                $predial->save(); //Insert

            }

            return back();
        } else {
            $pathAnterior = public_path() . '/files/avaluos/' . $pdfAnterior[0]->pdf;
            File::delete($pathAnterior);

            $fileName = time() . '.' . $request->pdf->getClientOriginalExtension();
            $moved =  $request->pdf->move(public_path('/files/avaluos'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $predial = Avaluo::findOrFail($request->id);
                $predial->pdf = $fileName;
                $predial->save(); //Insert

            }

            return back();
        }
    }

    public function downloadFile($fileName)
    {

        $pathtoFile = public_path() . '/files/avaluos/' . $fileName;
        return response()->download($pathtoFile);
    }
}
