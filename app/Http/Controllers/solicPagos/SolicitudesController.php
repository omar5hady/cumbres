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
use Excel;
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

        $total = 0;

        $solicitudes = $this->querySolicitudes($request,$admin);
        $solicitudes = $solicitudes->paginate(12);

        foreach($solicitudes as $solicitud){
            $total += $solicitud->importe;
            $solicitud->detalle = SpDetalle::leftJoin('creditos','sp_detalles.contrato_id','=','creditos.id')
                ->leftJoin('lotes','sp_detalles.lote_id','=','lotes.id')
                ->select('sp_detalles.*','lotes.manzana','lotes.num_lote','lotes.sublote','creditos.id as folio')
                ->where('sp_detalles.solic_id','=',$solicitud->id)->get();
            $solicitud->obs = SpObservacion::where('solicitud_id','=',$solicitud->id)->get();
            $solicitud->files = DocSolicPagosResource::collection(
                    SpFile::where('solic_id','=',$solicitud->id)->get()
                );
        }

        return [
            'solicitudes' => $solicitudes,
            'admin' => $admin,
            'total' => $total
        ];
    }

    public function printExcel(Request $request){
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

        $total = 0;

        $solicitudes = $this->querySolicitudes($request,$admin);
        $solicitudes = $solicitudes->get();

        $title = 'Solicitudes';
        if($request->b_status == 0)
            $title = $title.' Nuevas';
        if($request->b_status == 1)
            $title = $title.' En Proceso';
        if($request->b_status == 2)
            $title = $title.' Aprobadas';
        if($request->b_status == 3)
            $title = $title.' Por Pagar';
        if($request->b_status == 4)
            $title = $title.' Pagadas';

        return Excel::create('Solicitudes de Pago', function($excel) use ($solicitudes,$title){
            $excel->sheet($title, function($sheet) use ($solicitudes){

                $sheet->row(1, [
                    'Empresa', 'Proveedor', 'Solicitante',
                    'Fecha de solicitud', 'Importe', 'Tipo de pago', 'Forma',
                    'Cuenta de salida', 'Cuenta destino', 'Folio/Num Cheque',
                    'Fecha de pago'
                ]);

                $sheet->setColumnFormat(array(
                    'E' => '$#,##0.00',
                ));


                $sheet->cells('A1:K1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Helvetica');

                    // Set font size
                    $cells->setFontSize(13);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });


                $cont=1;

                foreach($solicitudes as $index => $s) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $s->fecha_compra = new Carbon($s->fecha_compra);
                    $s->fecha_compra = $s->fecha_compra->formatLocalized('%d de %B de %Y');

                    if($s->fecha_pago){
                        $s->fecha_pago = new Carbon($s->fecha_pago);
                        $s->fecha_ava_sol = $s->fecha_pago->formatLocalized('%d de %B de %Y');
                    }

                    $tipo_pago = 'C.F.';
                    $forma_pago = '';
                    if($s->tipo_pago == 1)
                        $tipo_pago = 'Bancos';

                    if($s->forma_pago == 1)
                        $forma_pago = 'Cheque';
                    if($s->forma_pago == 0 && $s->tipo_pago == 1)
                        $forma_pago = 'Transferencia';

                    $sheet->row($index+2, [
                        $s->empresa_solic,
                        $s->proveedor,
                        $s->solicitante,
                        $s->fecha_compra,
                        $s->importe,
                        $tipo_pago,
                        $forma_pago,
                        $s->cuenta_pago,
                        $s->num_factura,
                        $s->fecha_pago
                    ]);
                }
                $num='A1:R' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
    )->download('xls');
    }

    public function deleteDetalle( $id){
        $detalle = SpDetalle::findOrFail($id);
        if($detalle->pendiente_id != null){
            $det = SpDetalle::findOrFail($detalle->pendiente_id);
            $det->status = 1;
            $det->save();
        }
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
        $b_tipo_pago = $request->b_tipo_pago;
        $b_forma_pago = $request->b_forma_pago;
        $b_cuenta_pago = $request->b_cuenta_pago;

        $encargado = Auth::user()->seg_pago;
        $dep = Personal::findOrFail(Auth::user()->id);

        $solicitudes = SpSolicitud::join('personal as pv','sp_solicituds.proveedor_id','=','pv.id')
            ->join('personal as prov','pv.id','=','prov.id')
            ->join('personal as user','sp_solicituds.solicitante_id','=','user.id')
            ->select('sp_solicituds.*',  DB::raw("CONCAT(prov.nombre,' ',prov.apellidos) AS proveedor"),
                'pv.rfc as rfc_prov',
                DB::raw("CONCAT(user.nombre,' ',user.apellidos) AS solicitante")
            );
            if($b_empresa != '')
                $solicitudes = $solicitudes->where('sp_solicituds.empresa_solic','=',$b_empresa);
            if($b_proveedor != '')
                $solicitudes = $solicitudes->where(DB::raw("CONCAT(prov.nombre,' ',prov.apellidos)"),'like','%'.$b_proveedor.'%');
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
            if($b_tipo_pago != '')
                $solicitudes = $solicitudes->where('sp_solicituds.tipo_pago','=',$b_tipo_pago);
            if($b_forma_pago != '')
                $solicitudes = $solicitudes->where('sp_solicituds.forma_pago','=',$b_forma_pago);
            if($b_cuenta_pago != '')
                $solicitudes = $solicitudes->where('sp_solicituds.cuenta_pago','like','%'.$b_cuenta_pago.'%');

            $solicitudes = $solicitudes->orderBy('sp_solicituds.status','asc')
            ->orderBy('sp_solicituds.id','asc');


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
        $prov = Proveedor::where('id','=',$solicitud['proveedor_id'])->get();

        $solic = new SpSolicitud();
        $solic->empresa_solic   = $solicitud['empresa_solic'];
        $solic->solicitante_id  = $p->id;
        $solic->departamento    = $p->departamento_id;
        $solic->proveedor_id    = $solicitud['proveedor_id'];
        $solic->importe         = $solicitud['importe'];
        $solic->tipo_pago       = $solicitud['tipo_pago'];
        $solic->forma_pago      = $solicitud['forma_pago'];
        $solic->status          = 0;
        $solic->fecha_compra    = Carbon::now();
        if(sizeof($prov))
        {
            $solic->banco           = $prov[0]->banco;
            $solic->num_cuenta      = $prov[0]->num_cuenta;
            $solic->clabe           = $prov[0]->clabe;
        }
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
                $detalle->status = 0;
                $detalle->cargo         = $det['cargo'];
                $detalle->pendiente_id  = $det['pendiente_id'];

                if($detalle->pendiente_id != ''){
                    $det2 = SpDetalle::findOrFail($detalle->pendiente_id );
                    $det2->status = 2;
                    $det2->save();
                }

                $detalle->lote_id       = $det['lote_id'];
                $detalle->contrato_id   = $det['contrato_id'];
                $detalle->save();
            }
        }
    }

    private function updateSolicitud($solicitud){
        $prov = Proveedor::where('id','=',$solicitud['proveedor_id'])->get();

        $solic = SpSolicitud::findOrFail($solicitud['id']);
        $solic->empresa_solic       = $solicitud['empresa_solic'];
        $solic->proveedor_id        = $solicitud['proveedor_id'];
        $solic->importe             = $solicitud['importe'];
        $solic->tipo_pago           = $solicitud['tipo_pago'];
        $solic->forma_pago          = $solicitud['forma_pago'];
        if(sizeof($prov))
        {
            $solic->banco           = $prov[0]->banco;
            $solic->num_cuenta      = $prov[0]->num_cuenta;
            $solic->clabe           = $prov[0]->clabe;
        }
        $solic->rechazado           = 0;
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
        if($request->cuenta_pago != ''){
            $solic->cuenta_pago = $request->cuenta_pago;
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
        }
        $this->liberaDetalles($request->id);
        $solic->save();
    }

    private function liberaDetalles($id){
        $detalles = SpDetalle::select('id')->where('solic_id','=',$id)->get();

        if(sizeof($detalles))
            foreach($detalles as $det){
                $detalle = SpDetalle::findOrFail($det->id);
                if($detalle->tipo_mov == 1){
                    $detalle->saldo = 0;
                    $detalle->status = 0;
                }
                else{
                    $detalle->saldo = $detalle->total - $detalle->pago;
                    if($detalle->saldo == 0)
                        $detalle->status = 0;
                    else
                        $detalle->status = 1;
                }
                if($detalle->pendiente_id != NULL){
                    $det2 = SpDetalle::findOrFail($detalle->id);
                    $det2->saldo = $det2->saldo - $detalle->pago;
                    if($det2->saldo == 0)
                        $det2->status = 0;
                    else
                        $det2->status = 1;
                    $det2->save();
                }
                $detalle->save();
            }
    }

    public function getDetallesPendientes(Request $request){
        return SpDetalle::join('sp_solicituds as solic','solic.id','=','sp_detalles.solic_id')
            ->select('sp_detalles.*')
            ->where('solic.solicitante_id','=',Auth::user()->id)
            ->where('solic.proveedor_id','=',$request->proveedor_id)
            ->where('sp_detalles.saldo','>',0)
            ->where('sp_detalles.status','=',1)
            ->get();
    }
}
