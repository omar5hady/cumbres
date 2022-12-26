<?php

namespace App\Http\Controllers\solicPagos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SpCatalogo;
use App\SpDetalle;
use App\SpSolicitud;
use App\SpObservacion;
use App\Personal;
use App\Proveedor;
use Carbon\Carbon;
use Auth;
use DB;

class SolicitudesController extends Controller
{
    public function getCargos(Request $request){
        return SpCatalogo::select('cargo')->distinct()->get();
    }

    public function getConceptos(Request $request){
        return SpCatalogo::select('id','concepto')->where('cargo','=',$request->cargo)->get();
    }

    public function index(Request $request){
        $solicitudes = $this->querySolicitudes($request);
        foreach($solicitudes as $solicitud){
            $solicitud->detalle = SpDetalle::where('solic_id','=',$solicitud->id)->get();
            $solicitud->obs = SpObservacion::where('solicitud_id','=',$solicitud->id)->get();
        }
        return $solicitudes;
    }

    public function deleteDetalle($id){
        $detalle = SpDetalle::findOrFail($id);
        $detalle->delete();
    }

    private function querySolicitudes(Request $request){
        $solicitudes = SpSolicitud::join('personal as pv','sp_solicituds.proveedor_id','=','pv.id')
                    ->join('proveedores as prov','pv.id','=','prov.id')
                    ->join('personal as user','sp_solicituds.solicitante_id','=','user.id')
                    ->select('sp_solicituds.*', 'prov.proveedor','pv.rfc as rfc_prov', 'prov.const_fisc',
                        DB::raw("CONCAT(user.nombre,' ',user.apellidos) AS solicitante")
                    )
                    ->orderBy('sp_solicituds.status','asc')
                    ->orderBy('sp_solicituds.id','asc')
                    ->paginate(12);

        return $solicitudes;
    }

    private function storeSolicitud($solicitud){
        $p = Personal::findOrFail(Auth::user()->id);
        $prov = Proveedor::findOrFail($solicitud['proveedor_id']);

        $solic = new SpSolicitud();
        $solic->empresa_solic   = $solicitud['empresa_solic'];
        $solic->solicitante_id  = $p->id;
        $solic->departamento    = $p->departamento_id;
        $solic->proveedor_id    = $prov->id;
        $solic->importe         = $solicitud['importe'];
        $solic->tipo_pago       = $solicitud['tipo_pago'];
        $solic->forma_pago      = $solicitud['forma_pago'];
        $solic->status          = 1;
        $solic->fecha_compra    = Carbon::now();
        $solic->banco           = $prov->banco;
        $solic->num_cuenta      = $prov->num_cuenta;
        $solic->clabe           = $prov->clabe;
        $solic->save();

        return $solic->id;
    }

    private function storeDetalle($id, $detalles){
        foreach($detalles as $det){
            $detalle = new SpDetalle();
            $detalle->solic_id  = $id;
            $detalle->obra      = $det['obra'];
            $detalle->sub_obra  = $det['sub_obra'];
            $detalle->cargo     = $det['cargo'];
            $detalle->concepto  = $det['concepto'];
            $detalle->observacion = $det['observacion'];
            $detalle->tipo_mov  = $det['tipo_mov'];
            $detalle->total     = $det['total'];
            $detalle->pago      = $det['pago'];
            $detalle->saldo     = $det['saldo'];
            if($detalle->saldo == 0)
                $detalle->status = 0;
            else
                $detalle->status = 1;
            $detalle->cargo     = $det['cargo'];
            $detalle->save();
        }
    }

    private function createObs($solicitud_id, $observacion){
        $obs = new SpObservacion();
        $obs->comentario = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->solicitud_id = $solicitud_id;
        $obs->save();
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();

            $solicitud = $request->solicitud;
            $id = $this->storeSolicitud($solicitud);
            $this->storeDetalle($id, $solicitud['detalle']);
            $this->createObs($id, "Solicitud creada.");

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
}
