<?php

namespace App\Http\Controllers\Lotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EquipLote;
use App\ObsEquipLote;
use Carbon\Carbon;

use Auth;
use DB;

class EquipLoteController extends Controller
{
    public function index(Request $request){
        $solicitudes = EquipLote::join('lotes as l','equip_lotes.lote_id','=','l.id')
                ->join('fraccionamientos as p','l.fraccionamiento_id','=','p.id')
                ->join('etapas as e','l.etapa_id','=','e.id')
                ->join('modelos as m','l.modelo_id','=','m.id')
                ->join('equipamientos as eq','equip_lotes.equipamiento_id','=','eq.id')
                ->join('proveedores as pro','eq.proveedor_id','=','pro.id')
                ->select('equip_lotes.*',
                    'p.nombre as proyecto', 'e.num_etapa as etapa', 'm.nombre as modelo',
                    'l.num_lote', 'l.sublote','l.manzana', 'eq.equipamiento','eq.proveedor_id',
                    'eq.tipoRecepcion', 'pro.proveedor',
                    DB::raw('DATEDIFF(current_date,equip_lotes.fecha_anticipo) as diferenciaIni'),
                    DB::raw('DATEDIFF(equip_lotes.fin_instalacion,equip_lotes.fecha_anticipo) as diferenciaFin')
                );
                if($request->b_proyecto != '')
                    $solicitudes = $solicitudes->where('l.fraccionamiento_id','=',$request->b_proyecto);
                if($request->b_etapa != '')
                    $solicitudes = $solicitudes->where('l.etapa_id','=',$request->b_etapa);
                if($request->b_modelo != '')
                    $solicitudes = $solicitudes->where('l.modelo_id','=',$request->b_modelo);
                if($request->b_manzana != '')
                    $solicitudes = $solicitudes->where('l.manzana','=',$request->b_manzana);
                if($request->b_lote != '')
                    $solicitudes = $solicitudes->where('l.num_lote','=',$request->b_lote);
                if($request->b_status != '')
                    $solicitudes = $solicitudes->where('equip_lotes.status','=',$request->b_status);
                if($request->b_empresa != '')
                    $solicitudes = $solicitudes->where('l.emp_constructora','=',$request->b_empresa);

                if(Auth::user()->rol_id == 10)
                    $solicitudes = $solicitudes->where('eq.proveedor_id','=',Auth::user()->id);

        $solicitudes = $solicitudes->paginate(15);

        foreach($solicitudes as $s)
            $s->obs = ObsEquipLote::where('solicitud_id','=',$s->id)->orderBy('created_at','desc')->get();

        return $solicitudes;
    }

    public function store(Request $request){
        $datos = $request->datosSolicitud;
        $equipamiento = new EquipLote();
        $equipamiento->lote_id = $datos['lote_id'];
        $equipamiento->equipamiento_id = $datos['equipamiento_id'];
        $equipamiento->fecha_solicitud = new Carbon();
        $equipamiento->save();

        $this->guardarObs( $equipamiento->id, 'Solicitud creada' );
    }

    public function storeObservacion(Request $request){
        $this->guardarObs( $request->solicitud_id, $request->observacion );
    }

    private function guardarObs($solicitud_id, $observacion){
        $obs = new ObsEquipLote();
        $obs->solicitud_id = $solicitud_id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function update(Request $request){
        $solicitud = $request->solicitud;
        switch($request->accion){
            case 'updateCosto':{
                $this->guardarObs( $solicitud['id'], 'Se ha registrado el costo del equipamiento');
                break;
            }
            case 'updateAnticipo':{
                $this->guardarObs( $solicitud['id'], 'Se ha registrado el anticipo por' .$solicitud['anticipo'] );
                break;
            }
            case 'updateColocacion':{
                $solicitud['status'] = 2;
                break;
            }
            case 'updateFinInst':{
                $solicitud['status'] = 3;
                $this->guardarObs( $solicitud['id'], 'Se ha terminado la instalación del equipamiento');
                break;
            }
        }

        $this->updateInformacion($solicitud);
        if($request->observacion != '')
            $this->guardarObs( $solicitud['id'], $request->observacion );

    }

    private function updateInformacion($solicitud){

        $equipamiento = EquipLote::findOrFail($solicitud['id']);
        $equipamiento->costo                = $solicitud['costo'];
        $equipamiento->fecha_colocacion     = $solicitud['fecha_colocacion'];
        $equipamiento->status               = $solicitud['status'];
        $equipamiento->anticipo             = $solicitud['anticipo'];
        $equipamiento->fecha_anticipo       = $solicitud['fecha_anticipo'];
        $equipamiento->liquidacion          = $solicitud['liquidacion'];
        $equipamiento->fecha_liquidacion    = $solicitud['fecha_liquidacion'];
        $equipamiento->fin_instalacion      = $solicitud['fin_instalacion'];
        $equipamiento->num_factura          = $solicitud['num_factura'];
        $equipamiento->control              = $solicitud['control'];
        $equipamiento->recepcion            = $solicitud['recepcion'];
        $equipamiento->anticipo_cand        = $solicitud['anticipo_cand'];
        $equipamiento->liquidacion_cand     = $solicitud['liquidacion_cand'];
        $equipamiento->comp_pago_1          = $solicitud['comp_pago_1'];
        $equipamiento->comp_pago_2          = $solicitud['comp_pago_2'];
        $equipamiento->save();
    }
}
