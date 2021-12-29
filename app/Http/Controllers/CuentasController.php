<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuenta;
use Auth;
use App\Lote;

// Controlador para cuentas bancarias.
class CuentasController extends Controller
{
    // Función para retornar las cuentas bancarias.
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar=='')
            $cuentas = Cuenta::orderBy('num_cuenta','asc')->paginate(8);
        else
            $cuentas = Cuenta::where($criterio, 'like', '%'. $buscar . '%')->orderBy('num_cuenta','asc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $cuentas->total(),
                'current_page'  => $cuentas->currentPage(),
                'per_page'      => $cuentas->perPage(),
                'last_page'     => $cuentas->lastPage(),
                'from'          => $cuentas->firstItem(),
                'to'            => $cuentas->lastItem(),
            ],
            'cuentas' => $cuentas
        ];
    }

    // Función que retorna los folios de los créditos puente en un fraccionamiento.
    public function selectCreditoPuente(Request $request){
        $fraccionamiento = $request->fraccionamiento;
        $creditos = Lote::select('credito_puente')->where('fraccionamiento_id','=',$fraccionamiento)
        ->where('credito_puente','!=',NULL)->orderBy('credito_puente','asc')->distinct()->get();

        return['creditos'=>$creditos];
    }

    // Función para registrar una nueva cuenta bancaria.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $cuenta = new Cuenta();
        $cuenta->num_cuenta = $request->num_cuenta;
        $cuenta->sucursal = $request->sucursal;
        $cuenta->banco = $request->banco;
        $cuenta->empresa = $request->empresa;
        $cuenta->save();
    }

    // Función para actualizar una cuenta bancaria.
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $cuenta = Cuenta::findOrFail($request->id);
        $cuenta->num_cuenta = $request->num_cuenta;
        $cuenta->sucursal = $request->sucursal;
        $cuenta->banco = $request->banco;
        $cuenta->empresa = $request->empresa;
        $cuenta->save();
    }

    // Función para eliminar una cuenta bancaria
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $cuenta = Cuenta::findOrFail($request->id);
        $cuenta->delete();
    }

    // Función que retorna las cuentas bancarias para select, se puede filtrar por empresa.
    public function selectCuenta(Request $request){
        if(!$request->ajax())return redirect('/');
        $cuentas = Cuenta::select('num_cuenta','sucursal','banco','empresa')
        ->where('empresa','like','%'.$request->empresa.'%')
        ->orderBy('banco','asc')->get();

        return ['cuentas' => $cuentas];
    }
}
 