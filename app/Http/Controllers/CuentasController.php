<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuenta;

class CuentasController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $cuentas = Cuenta::orderBy('num_cuenta','asc')->paginate(8);
        }
        else{
            $cuentas = Cuenta::where($criterio, 'like', '%'. $buscar . '%')->orderBy('num_cuenta','asc')->paginate(8);
        }

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

    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $cuenta = new Cuenta();
        $cuenta->num_cuenta = $request->num_cuenta;
        $cuenta->sucursal = $request->sucursal;
        $cuenta->banco = $request->banco;
        $cuenta->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $cuenta = Cuenta::findOrFail($request->id);
        $cuenta->num_cuenta = $request->num_cuenta;
        $cuenta->sucursal = $request->sucursal;
        $cuenta->banco = $request->banco;
        $cuenta->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $cuenta = Cuenta::findOrFail($request->id);
        $cuenta->delete();
    }

    public function selectCuenta(Request $request){
        $cuentas = Cuenta::select('num_cuenta','sucursal','banco')->get();

        return ['cuentas' => $cuentas];
    }
}
 