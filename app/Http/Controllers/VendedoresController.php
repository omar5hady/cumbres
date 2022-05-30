<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Lote;
use App\User;
use App\Cliente;
use App\Vendedor;
use Carbon\Carbon;
use Auth;
use File;

class VendedoresController extends Controller
{
    //hace la peticion de la informacion de los vendedores 
    public function ventasVendedorReporte(Request $request){
        $vendedores = User::join('personal','users.id','=','personal.id')
                ->join('vendedores','personal.id','vendedores.id')
                ->select('personal.id',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"),
                        'vendedores.tipo','vendedores.inmobiliaria')
                ->orderBy('vendedor','asc')->get();


        if(sizeOf($vendedores)){ // cuenta los clientes asignados a ese vendedor
            foreach ($vendedores as $ve => $vendedor) {
                $vendedor->atendio = Cliente::where('vendedor_id','=',$vendedor->id)
                        ->where('clasificacion','!=',7)->count();
            }
        }

        
        return['vendedores'=>$vendedores];
    }

    //funcion para subir el comprovante de domicilio del vendedor
    public function formSubmitComprobante(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $comprobanteAnt = Vendedor::select('doc_comprobante', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($comprobanteAnt->isEmpty() == 1) { // verifica si el campo esta vacio 
            $fileName = uniqid() . time() . '.' . $request->doc_comprobante->getClientOriginalExtension();
            $moved =  $request->doc_comprobante->move(public_path('/files/vendedores'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $vendedor = Vendedor::findOrFail($request->id);
                $vendedor->doc_comprobante = $fileName;
                $vendedor->save(); //Insert

            }

            return response()->json(['success'=>$fileName]);
        } else {
            $pathAnterior = public_path() . '/files/vendedores/' . $comprobanteAnt[0]->doc_comprobante;
            File::delete($pathAnterior); // elimina el archivo anterior

            $fileName = uniqid() . time() . '.' . $request->doc_comprobante->getClientOriginalExtension();
            $moved =  $request->doc_comprobante->move(public_path('/files/vendedores'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $vendedor = Vendedor::findOrFail($request->id);
                $vendedor->doc_comprobante = $fileName;
                $vendedor->save(); //Insert

            }

            return response()->json(['success'=>$fileName]);
        }
    }

    //Funcion para subir el archivo INE del vendedor
    public function formSubmitINE(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $comprobanteAnt = Vendedor::select('doc_ine', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($comprobanteAnt->isEmpty() == 1) {
            $fileName = uniqid() . time() . '.' . $request->doc_ine->getClientOriginalExtension();
            $moved =  $request->doc_ine->move(public_path('/files/vendedores'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $vendedor = Vendedor::findOrFail($request->id);
                $vendedor->doc_ine = $fileName;
                $vendedor->save(); //Insert

            }

            return response()->json(['success'=>$fileName]);
        } else {
            $pathAnterior = public_path() . '/files/vendedores/' . $comprobanteAnt[0]->doc_ine;
            File::delete($pathAnterior);

            $fileName = uniqid() . time() . '.' . $request->doc_ine->getClientOriginalExtension();
            $moved =  $request->doc_ine->move(public_path('/files/vendedores'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $vendedor = Vendedor::findOrFail($request->id);
                $vendedor->doc_ine = $fileName;
                $vendedor->save(); //Insert

            }

            return response()->json(['success'=>$fileName]);
        }
    }

    // Funcion para subir el documento CV del vendedor
    public function formSubmitCV(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $comprobanteAnt = Vendedor::select('curriculum', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($comprobanteAnt->isEmpty() == 1) {
            $fileName = uniqid() . time() . '.' . $request->curriculum->getClientOriginalExtension();
            $moved =  $request->curriculum->move(public_path('/files/vendedores'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $vendedor = Vendedor::findOrFail($request->id);
                $vendedor->curriculum = $fileName;
                $vendedor->save(); //Insert

            }

            return response()->json(['success'=>$fileName]);
        } else {
            $pathAnterior = public_path() . '/files/vendedores/' . $comprobanteAnt[0]->curriculum;
            File::delete($pathAnterior);

            $fileName = uniqid() . time() . '.' . $request->curriculum->getClientOriginalExtension();
            $moved =  $request->curriculum->move(public_path('/files/vendedores'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $vendedor = Vendedor::findOrFail($request->id);
                $vendedor->curriculum = $fileName;
                $vendedor->save(); //Insert

            }

            return response()->json(['success'=>$fileName]);
        }
    }

    // elimina elarchivo seleccionado
    public function downloadFile($fileName)
    {

        $pathtoFile = public_path() . '/files/vendedores/' . $fileName;
        return response()->file($pathtoFile);
    }

    //funcion para crear un rango de fechas ( periodo vacacional del vendedor)
    public function actPeriodoVacacional(Request $request){
        $vendedor = Vendedor::findOrFail($request->id);
        $vendedor->ini_vacaciones = $request->ini_vacaciones;
        $vendedor->fin_vacaciones = $request->fin_vacaciones;
        $vendedor->save();
    }
}
