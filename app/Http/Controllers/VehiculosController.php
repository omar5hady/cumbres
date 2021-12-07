<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehiculo;
use App\Personal;

class VehiculosController extends Controller
{
    public function getMarcas(Request $request){
        $marcas = [
            'Audi',
            'Buick',
            'BMW',
            'Cacillac',
            'Chrysler',
            'Chevrolet',
            'Dodge',
            'Ford',
            'GMC',
            'Honda',
            'Hyundai',
            'Jeep',
            'Kia',
            'Mazda',
            'Nissan',
            'Suzuki',
            'Volkswagen'
        ];

        return ['marcas'=>$marcas];
    }

    public function index(Request $request){
        $vehiculos = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                ->select('vehiculos.*','personal.nombre','personal.apellidos');
        if($request->b_vehiculo != '')
            $vehiculos = $vehiculos->where('vehiculos.vehiculo','like','%'.$request->b_vehiculo.'%');

        if($request->b_empresa != '')
            $vehiculos = $vehiculos->where('vehiculos.empresa','=',$request->b_empresa);

        if($request->b_marca != '')
            $vehiculos = $vehiculos->where('vehiculos.marca','=',$request->b_marca);
        $vehiculos = $vehiculos->orderBy('vehiculos.empresa','asc')
                ->orderBy('vehiculos.marca','asc')
                ->orderBy('vehiculos.vehiculo','asc')
                ->orderBy('vehiculos.modelo','asc')
                ->paginate(10);

        return $vehiculos;
    }

    public function store(Request $request){
        $vehiculo = new Vehiculo();
        $vehiculo->vehiculo =  $request->vehiculo;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca =  $request->marca;
        $vehiculo->clave =  $request->clave;
        $vehiculo->placas =  $request->placas;
        $vehiculo->numero_serie =  $request->numero_serie;
        $vehiculo->numero_motor =  $request->numero_motor;
        $vehiculo->responsable_id = $request->responsable_id;
        $vehiculo->empresa = $request->empresa;

        $vehiculo->save();
    }

    public function update(Request $request){

        $vehiculo = Vehiculo::findOrFail($request->id);
        $vehiculo->vehiculo =  $request->vehiculo;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca =  $request->marca;
        $vehiculo->clave =  $request->clave;
        $vehiculo->placas =  $request->placas;
        $vehiculo->numero_serie =  $request->numero_serie;
        $vehiculo->numero_motor =  $request->numero_motor;
        $vehiculo->responsable_id = $request->responsable_id;
        $vehiculo->empresa = $request->empresa;

        $vehiculo->save();
    }
}
