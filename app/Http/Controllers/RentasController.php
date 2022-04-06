<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use App\Licencia;
use App\Arrendador;
use App\Renta;
use App\Pago_renta;
use DB;

class RentasController extends Controller
{
    //Función para obtener los arrendadores dados de alta
    public function getArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        return Arrendador::orderBy('tipo','asc')->get();
    }
    //Función para actualizar la informacion de renta para un lote.
    public function updateDatosRenta(Request $request){
        if(!$request->ajax())return redirect('/');
        $lote = Lote::findOrFail($request->id);
        $lote->precio_renta = $request->precio_renta;
        $lote->comentarios = $request->comentarios;
        $lote->save();

        $licencia = Licencia::findOrFail($request->id);
        $licencia->duenio_id = $request->duenio_id;
        $licencia->save();
    }
    //Función para registrar un nuevo arrendador
    public function storeArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        $arrendador = new Arrendador();
        $arrendador->nombre = $request->nombre;
        $arrendador->tipo = $request->tipo;
        $arrendador->rfc = $request->rfc;
        $arrendador->direccion = $request->direccion;
        $arrendador->cp = $request->cp;
        $arrendador->colonia = $request->colonia;
        $arrendador->municipio = $request->municipio;
        $arrendador->estado = $request->estado;
        $arrendador->save();
    }
    //Función para actualizar los datos de un arrendador.
    public function updateArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        $arrendador = Arrendador::findOrFail($request->id);
        $arrendador->nombre = $request->nombre;
        $arrendador->tipo = $request->tipo;
        $arrendador->rfc = $request->rfc;
        $arrendador->direccion = $request->direccion;
        $arrendador->cp = $request->cp;
        $arrendador->colonia = $request->colonia;
        $arrendador->municipio = $request->municipio;
        $arrendador->estado = $request->estado;
        $arrendador->save();
    }
    //Funcion para retornar los lotes con disponibilidad para renta
    public function getRentasDisponibles(Request $request){
        return $lote = Lote::select('id','lotes.calle','lotes.numero','lotes.interior')
                ->where('etapa_id','=',$request->etapa_id)
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->where('habilitado','=', 1)
                ->where('casa_renta','=', 1)
                ->where('contrato','=',0)
                ->orderBy('id','asc')
                ->get();
    }
    //Función que retorna la informacion de un lote en renta
    public function getDatosLoteRenta(Request $request){
        return $lote = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                        ->select('modelos.nombre as modelo','lotes.precio_renta')
                        ->where('lotes.id','=',$request->id)
                        ->first();
    }
    //Funcion para registrar una nueva renta
    public function storeRenta(Request $request){
        $datosRenta = $request->datosRenta;
        $pagos = $datosRenta['pagares'];

        try {
            DB::beginTransaction();
            //Registro para renta
            $renta = new Renta();
            $renta->lote_id = $datosRenta['lote_id'];
            //Arrendatario
            $renta->tipo_arrendatario = $datosRenta['tipo_arrendatario'];
            $renta->nombre_arrendatario = $datosRenta['nombre_arrendatario'];
            $renta->tel_arrendatario = $datosRenta['tel_arrendatario'];
            $renta->clv_lada_arr = $datosRenta['clv_lada_arr'];
            //Moral arrendatario
            $renta->representante_arrendatario = $datosRenta['representante_arrendatario'];
            $renta->dir_arrendatario = $datosRenta['dir_arrendatario'];
            $renta->cp_arrendatario = $datosRenta['cp_arrendatario'];
            $renta->col_arrendatario = $datosRenta['col_arrendatario'];
            $renta->estado_arrendatario = $datosRenta['estado_arrendatario'];
            $renta->municipio_arrendatario = $datosRenta['municipio_arrendatario'];
            $renta->rfc_arrendatario = $datosRenta['rfc_arrendatario'];
            //Aval (Fiador)
            $renta->tipo_aval = $datosRenta['tipo_aval'];
            $renta->nombre_aval = $datosRenta['nombre_aval'];
            $renta->tel_aval = $datosRenta['tel_aval'];
            $renta->clv_lada_aval = $datosRenta['clv_lada_aval'];
                //Moral aval
            $renta->representante_aval = $datosRenta['representante_aval'];
            $renta->dir_aval = $datosRenta['dir_aval'];
            $renta->cp_aval = $datosRenta['cp_aval'];
            $renta->col_aval = $datosRenta['col_aval'];
            $renta->estado_aval = $datosRenta['estado_aval'];
            $renta->municipio_aval = $datosRenta['municipio_aval'];
            //Testigo
            $renta->nombre = $datosRenta['nombre'];
            //Datos contrato
            $renta->monto_renta = $datosRenta['monto_renta'];
            $renta->fecha_ini = $datosRenta['fecha_ini'];
            $renta->fecha_fin = $datosRenta['fecha_fin'];
            $renta->num_meses = $datosRenta['num_meses'];
            $renta->save();
            //Se accede al registro del lote para indicar que ha sido rentado
            $lote = Lote::findOrFail($datosRenta['lote_id']);
            $lote->contrato = 1;
            $lote->save();
            //Se crean los pagares de la renta
            foreach($pagos as $pago){
                //Llamada a la función privada que genera el pagare.
                $this->storePagare($renta->id,
                    $pago['num_pago'],
                    $pago['fecha'],
                    $pago['importe']
                );
            }
        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    //Función privada que registra un pagare para una renta
    private function storePagare($renta_id, $num_pago, $fecha, $importe){
        $pago = new Pago_renta();
        $pago->renta_id = $renta_id;
        $pago->num_pago = $num_pago;
        $pago->fecha = $fecha;
        $pago->importe = $importe;
        $pago->save();
    }
    //Funcion que retorna el listado de contratos de renta registrados
    public function indexRentas(Request $request){
        $rentas = Renta::join('lotes','rentas.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->select('rentas.id','rentas.num_meses','rentas.fecha_fin',
                        'rentas.status', 'rentas.monto_renta', 'rentas.nombre_arrendatario',
                        'lotes.calle', 'lotes.numero', 'lotes.interior', 'etapas.num_etapa as etapa',
                        'fraccionamientos.nombre as proyecto', 'modelos.nombre as modelo'
                    );
                //Filtros de busqueda
                if($request->b_proyecto != '')//Busqueda por proyecto
                    $rentas = $rentas->where('lotes.fraccionamiento_id','=',$request->b_proyecto);
                if($request->b_etapa != '')//Busqueda por etapa
                    $rentas = $rentas->where('lotes.etapa_id','=',$request->b_etapa);
                if($request->b_direccion != '')//Busqueda por dirección oficial
                    $rentas = $rentas->where(DB::raw("CONCAT(lotes.calle,' Num',lotes.numero)"), 'like', '%'. $request->b_direccion . '%');
                if($request->b_cliente != '')//Busqueda por arrendatarios
                    $rentas = $rentas->where('rentas.nombre_arrendatario','like','%'.$request->b_cliente.'%');   
                    
            $rentas = $rentas->orderBy('rentas.id','desc')->paginate(6);
        return $rentas;       
    }

    public function getDatosRenta(Request $request){
        $renta = Renta::join('lotes','rentas.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->select('rentas.*',
                'lotes.fraccionamiento_id',
                'lotes.etapa_id',
                'lotes.calle', 'lotes.numero', 'lotes.interior',
                'fraccionamientos.nombre as proyecto',
                'etapas.num_etapa as etapa',
                'modelos.nombre as modelo')
                ->where('rentas.id','=',$request->id)
                ->first();

            $renta->pagares = Pago_renta::where('renta_id','=',$renta->id)->get();

        return $renta;
    }
}
