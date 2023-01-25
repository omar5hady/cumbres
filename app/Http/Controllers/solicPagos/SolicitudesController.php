<?php

namespace App\Http\Controllers\solicPagos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocSolicPagosResource;
use App\SpCatalogo;
use App\SpDetalle;
use App\SpSolicitud;
use App\SpObservacion;
use App\Personal;
use App\Proveedor;
use App\SpFile;
use Carbon\Carbon;
use Auth;
use DB;

class SolicitudesController extends Controller
{
    public function getCargos(Request $request){
        return SpCatalogo::select('cargo')->distinct()->get();
    }

    public function changeCuenta(Request $request){
        $solicitud = SpSolicitud::findOrFail($request->id);
        $solicitud->banco = $request->banco;
        $solicitud->num_cuenta = $request->num_cuenta;
        $solicitud->clabe = $request->clabe;
        $solicitud->save();

    }

    public function getConceptos(Request $request){
        return SpCatalogo::select('id','concepto')->where('cargo','=',$request->cargo)->get();
    }

    public function index(Request $request){
        $admin = 0;
        $usuario = Auth::user()->usuario;
        if( $usuario == 'shady'
            || $usuario == 'uriel.al'
        )$admin = 1;
        if( $usuario == 'shady'
            || $usuario == 'alejandro.pe'
            || $usuario == 'ing_david'
        )$admin = 2;
        if(
            $usuario == 'jorge.diaz'
            || $usuario == 'dora.m'
        )$admin = 3;

        $solicitudes = $this->querySolicitudes($request,$admin);
        foreach($solicitudes as $solicitud){
            $solicitud->detalle = SpDetalle::where('solic_id','=',$solicitud->id)->get();
            $solicitud->obs = SpObservacion::where('solicitud_id','=',$solicitud->id)->get();
            $solicitud->files = DocSolicPagosResource::collection(
                    SpFile::where('solic_id','=',$solicitud->id)->get()
                );
        }

        return [
            'solicitudes' => $solicitudes,
            'admin' => $admin
        ];
    }

    public function deleteDetalle( $id){
        $detalle = SpDetalle::findOrFail($id);
        $detalle->delete();
    }

    private function querySolicitudes(Request $request, $admin){
        $b_proveedor = $request->b_proveedor;
        $b_solicitante = $request->b_solicitante;
        $b_status = $request->b_status;
        $b_fecha1 = $request->b_fecha1;
        $b_fecha2 = $request->b_fecha2;
        $b_vbgerente = $request->b_vbgerente;
        $b_vbdireccion = $request->b_vbdireccion;
        $b_rechazado = $request->b_rechazado;
        $b_empresa = $request->b_empresa;

        $encargado = Auth::user()->seg_pago;
        $dep = Personal::findOrFail(Auth::user()->id);

        $solicitudes = SpSolicitud::join('personal as pv','sp_solicituds.proveedor_id','=','pv.id')
            ->join('proveedores as prov','pv.id','=','prov.id')
            ->join('personal as user','sp_solicituds.solicitante_id','=','user.id')
            ->select('sp_solicituds.*', 'prov.proveedor','pv.rfc as rfc_prov', 'prov.const_fisc',
                DB::raw("CONCAT(user.nombre,' ',user.apellidos) AS solicitante")
            );
            if($b_empresa != '')
                $solicitudes = $solicitudes->where('sp_solicituds.empresa_solic','=',$b_empresa);
            if($b_proveedor != '')
                $solicitudes = $solicitudes->where('prov.proveedor','like','%'.$b_proveedor.'%');
            if($b_solicitante != '')
                $solicitudes = $solicitudes->where(DB::raw("CONCAT(user.nombre,' ',user.apellidos)"), 'like', '%'. $b_solicitante . '%');
            if($b_fecha1 != '' && $b_fecha2 != '')
                $solicitudes = $solicitudes->whereBetween(
                    'sp_solicituds.created_at',[$b_fecha1.' 00:00:00',$b_fecha2.' 23:59:59']
                );
            if($b_status != '')
                $solicitudes = $solicitudes->where('sp_solicituds.status','=', $b_status);
            if($b_vbgerente != '')
                $solicitudes = $solicitudes->where('sp_solicituds.vb_gerente','=', $b_vbgerente);
            if($b_vbdireccion != '')
                $solicitudes = $solicitudes->where('sp_solicituds.vb_direccion','=', $b_vbdireccion);
            if($admin == 0){
                if($encargado == 0)
                    $solicitudes = $solicitudes->where('sp_solicituds.solicitante_id','=',Auth::user()->id);
                if($encargado == 1)
                    $solicitudes = $solicitudes->where('sp_solicituds.departamento','=',$dep->departamento_id);
            }
            if($b_rechazado != ''){
                $solicitudes = $solicitudes->where('sp_solicituds.rechazado','=', 1);
            }
            else{
                $solicitudes = $solicitudes->where('sp_solicituds.rechazado','=', 0);
            }

            $solicitudes = $solicitudes->orderBy('sp_solicituds.status','asc')
            ->orderBy('sp_solicituds.id','asc')
            ->paginate(12);

        return $solicitudes;
    }

    public function changeVbGerente(Request $request, $id){
        $solic = SpSolicitud::findOrFail($request->id);
        $solic->vb_gerente = $request->estado;
        if($solic->vb_gerente == 2)
            $solic->status = 1;
        $solic->save();

        if($request->estado == 1)
            $this->createObs($id, "La solicitud ha sido revisada.");
        if($request->estado == 2)
            $this->createObs($id, "Solicitud autorizada por gerente.");
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
        $solic->status          = 0;
        $solic->fecha_compra    = Carbon::now();
        $solic->banco           = $prov->banco;
        $solic->num_cuenta      = $prov->num_cuenta;
        $solic->clabe           = $prov->clabe;
        $solic->save();

        return $solic->id;
    }

    private function storeDetalle($id, $detalles){
        foreach($detalles as $det){
            if($det['id'] == '')
            {
                $detalle = new SpDetalle();
                $detalle->solic_id      = $id;
                $detalle->obra          = $det['obra'];
                $detalle->sub_obra      = $det['sub_obra'];
                $detalle->cargo         = $det['cargo'];
                $detalle->concepto      = $det['concepto'];
                $detalle->observacion   = $det['observacion'];
                $detalle->tipo_mov      = $det['tipo_mov'];
                $detalle->total         = $det['total'];
                $detalle->pago          = $det['pago'];
                $detalle->saldo         = $det['saldo'];
                if($detalle->saldo == 0)
                    $detalle->status = 0;
                else
                    $detalle->status = 1;
                $detalle->cargo         = $det['cargo'];
                $detalle->pendiente_id  = $det['pendiente_id'];
                $detalle->save();
            }
        }
    }

    private function updateSolicitud($solicitud){
        $prov = Proveedor::findOrFail($solicitud['proveedor_id']);

        $solic = SpSolicitud::findOrFail($solicitud['id']);
        $solic->empresa_solic   = $solicitud['empresa_solic'];
        $solic->proveedor_id    = $prov->id;
        $solic->importe         = $solicitud['importe'];
        $solic->tipo_pago       = $solicitud['tipo_pago'];
        $solic->forma_pago      = $solicitud['forma_pago'];
        $solic->banco           = $prov->banco;
        $solic->num_cuenta      = $prov->num_cuenta;
        $solic->clabe           = $prov->clabe;
        $solic->rechazado       = 0;
        $solic->save();

        return $solic->id;
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

    public function update(Request $request){
        try{
            DB::beginTransaction();

            $solicitud = $request->solicitud;
            $id = $this->updateSolicitud($solicitud);
            $this->storeDetalle($id, $solicitud['detalle']);
            $this->createObs($id, "Solicitud actualizada.");

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function destroy(Request $request){
        $solicitud = SpSolicitud::findOrFail($request->id);
        $solicitud->delete();

        $detalles = SpDetalle::select('id')->where('solic_id','=',$request->id)->get();
        foreach($detalles as $det){
            $this->deleteDetalle($det->id);
        }
    }

    public function changeVbTesoreria(Request $request){
        $solic = SpSolicitud::findOrFail($request->id);
        if($request->estado == 1){
            $solic->vb_tesoreria = $request->estado;
            $solic->status = 2;
        }
        else{
            $solic->rechazado = 1;
            $this->createObs($solic->id, "Solicitud rechazada: ".$request->motivo);

        }
        $solic->save();
    }
    public function changeVbDireccion(Request $request){
        $solic = SpSolicitud::findOrFail($request->id);
        if($request->estado == 1){
            $solic->vb_direccion = $request->estado;
            $this->createObs($solic->id, "Solicitud revisada para autorización de dirección");
            $solic->status = 2;
        }
        else{
            $solic->rechazado = 1;
            $this->createObs($solic->id, "Solicitud rechazada: ".$request->motivo);
        }
        $solic->save();
    }

    public function autorizarDireccion(Request $request){
        $solic = SpSolicitud::findOrFail($request->id);
        $solic->status = 3;
        $this->createObs($solic->id, "Solicitud Autorizada por Dirección");
        $solic->save();
    }

    public function generarPago(Request $request){
        $solic = SpSolicitud::findOrFail($request->id);
        $solic->fecha_pago = $request->fecha_pago;
        $solic->num_factura = $request->num_factura;
        $solic->cuenta_pago = $request->cuenta_pago;
        $solic->beneficiario = $request->beneficiario;
        if($solic->forma_pago == 0 && $solic->tipo_pago == 1){
            $solic->status = 4;
            $solic->entrega_pago = Carbon::now();
            $this->liberaDetalles($request->id);
        }
        $solic->save();
    }

    private function liberaDetalles($id){
        $detalles = SpDetalle::select('id')->where('solic_id','=',$id)->get();

        if(sizeof($detalles))
            foreach($detalles as $det){
                $detalle = SpDetalle::findOrFail($det->id);
                if($detalle->tipo_mov != 0){
                    $detalle->saldo = 0;
                    $detalle->status = 0;
                    if($detalle->pendiente_id != NULL){
                        $det2 = SpDetalle::findOrFail($detalle->id);
                        $det2->status = 0;
                        $det2->saldo = 0;
                        $det2->save();
                    }
                }
                else{
                    $detalle->saldo = $detalle->total - $detalle->pago;
                    $detalle->status = 1;
                }
                $detalle->save();
            }
    }
}
