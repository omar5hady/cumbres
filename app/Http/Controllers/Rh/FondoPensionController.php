<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\PensionFondo;
use App\PensionFondoPago;
use App\Http\Resources\PensionFondoResource;

use Auth;
use DB;

class FondoPensionController extends Controller
{
    public function index(Request $request){

        return PensionFondoResource::collection(PensionFondo::where('user_id','=',$request->user_id)->get());
    }

    public function store(Request $request){
        $fondo_id = $request->fondo_id;
        if($request->fondo_id == ''){
            $fondo_id = $this->storeFondo($request);
        }

        $pago = new PensionFondoPago();
        $pago->monto = $request->monto;
        $pago->tipo_movimiento = $request->tipo_movimiento;
        $pago->fecha_movimiento = $request->fecha_movimiento;
        $pago->fondo_id = $fondo_id;
        if($request->tipo_movimiento == 1){
            $pago->concepto = $request->concepto;
            $pago->status = 1;
            $this->updateStatusPagos($fondo_id, '2000-01-01', $request->fecha_movimiento);
        }
        else{
            $pago->concepto = 'Empresa: '.$request->concepto;
        }
        $pago->save();

        if($request->tipo_movimiento == 0){
            $pago2 = new PensionFondoPago();
            $pago2->monto = $request->monto;
            $pago2->tipo_movimiento = 0;
            $pago2->fecha_movimiento = $request->fecha_movimiento;
            $pago2->fondo_id = $fondo_id;
            $pago2->concepto = 'Empleado: '.$request->concepto;
            $pago2->save();
        }

        $this->updateSaldo($fondo_id);

    }

    public function update(Request $request){
        $pago = PensionFondoPago::findOrFail($request->id);
        $pago->monto = $request->monto;
        $pago->fecha_movimiento = $request->fecha_movimiento;
        $pago->concepto = $request->concepto;
        $pago->save();

        $this->updateSaldo($request->fondo_id);
    }

    public function destroy(Request $request){
        $pago = PensionFondoPago::findOrFail($request->id);
        $pago->delete();

        $this->updateSaldo($request->fondo_id);
    }


    private function updateStatusPagos($fondo_id, $fecha_ini, $fecha_fin){
        $pagos = PensionFondoPago::select('id')
            ->where('fondo_id','=',$fondo_id)
            ->where('status','=',0)
            ->where('tipo_movimiento','=',0)
            ->whereBetween('fecha_movimiento', [$fecha_ini, $fecha_fin])
            ->get();

        if(sizeOf($pagos)){
            foreach($pagos as $index => $pago){
                $p = PensionFondoPago::FindOrFail($pago->id);
                $p->status = 1;
                $p->save();
            }
        }
    }

    private function storeFondo(Request $request){
        $fondo = new PensionFondo();
        $fondo->user_id = $request->user_id;
        $fondo->saldo = 0;
        $fondo->save();

        return $fondo->id;
    }

    private function updateSaldo($fondo_id){

        $pagos = PensionFondoPago::select(DB::raw("SUM(monto) as saldo"))
            ->where('fondo_id','=',$fondo_id)
            ->where('status','=',0)
            ->where('tipo_movimiento','=',0)
            ->first();

        $saldo = $pagos->saldo ? $pagos->saldo : 0;

        $fondo = PensionFondo::findOrFail($fondo_id);
        $fondo->saldo = $saldo;
        $fondo->save();
    }
}
