<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dato_extra;
use App\Credito;
use App\Personal;
use App\Cliente;
use DB;

class CreditoController extends Controller

{

    public function updateDatosCliente(Request $request){

           //datos del cliente que se guardan en la tabla personal
           $personal = Personal::findOrFail($request->id);
           $personal->direccion = $request->direccion;
           $personal->cp = $request->cp;
           $personal->colonia = $request->colonia; 

           $cliente = Cliente::findOrFail($request->id);
           $cliente->ciudad = $request->ciudad;
           $cliente->estado = $request->estado;
           $cliente->nacionalidad = $request->nacionalidad;
           $cliente->puesto = $request->puesto;
           $cliente->direccion_coa = $request->direccion_coa;
           $cliente->colonia_coa = $request->colonia_coa;
           $cliente->cp_coa = $request->cp_coa;
           $cliente->ciudad_coa = $request->ciudad_coa;
           $cliente->estado_coa = $request->estado_coa;
           $cliente->empresa_coa = $request->empresa_coa;
           $cliente->nacionalidad_coa = $request->nacionalidad_coa;
           
           $personal->save();
           $cliente->save();

    }

    public function store (Request $request)
    {

    try{
        DB::beginTransaction();
        $credito = new Credito();
        $credito->prospecto_id = $request->prospecto_id;
        $credito->num_dep_economicos = $request->dep_economicos;
        $credito->tipo_economia = $request->tipo_economia;
        $credito->nombre_primera_ref = $request->nombre_referencia1;
        $credito->telefono_primera_ref = $request->telefono_referencia1;
        $credito->celular_primera_ref = $request->celular_referencia1;
        $credito->nombre_segunda_ref = $request->nombre_referencia2;
        $credito->telefono_segunda_ref = $request->telefono_referencia2;
        $credito->celular_segunda_ref = $request->celular_referencia2;
        $credito->etapa = $request->etapa;
        $credito->manzana = $request->manzana;
        $credito->num_lote = $request->lote;
        $credito->modelo = $request->modelo;
        $credito->precio_base = $request->precioBase;
        $credito->superficie = $request->superficie;
        $credito->terreno_excedente = $request->terreno_tam_excedente;
        $credito->precio_terreno_excedente = $request->precioExcedente;
        $credito->promocion = $request->promocion;
        $credito->descripcion_promocion = $request->descripcionPromo;
        $credito->descuento_promocion = $request->descuentoPromo;
        $credito->paquete = $request->paquete_id; //
        $credito->descripcion_paquete = $request->descripcionPaquete;
        $credito->costo_paquete = $request->costoPaquete;
        $credito->precio_venta = $request->precioVenta;
        $credito->plazo = $request->plazo_credito;
        $credito->credito_solic = $request->monto_credito;
        $credito->save();

        $datos_extra = new Dato_extra();
        $datos_extra->id = $credito->id;
        $datos_extra->mascota = $request->mascotas;
        $datos_extra->num_perros = $request->num_perros;
        $datos_extra->rang010 = $request->rang0_10;
        $datos_extra->rang1120 = $request->rang11_20;
        $datos_extra->rang21 = $request->rang21;
        $datos_extra->ama_casa = $request->ama_casa;
        $datos_extra->persona_discap = $request->discapacidad;
        $datos_extra->silla_ruedas = $request->silla_ruedas;
        $datos_extra->num_vehiculos = $request->num_vehiculos;
        $datos_extra->save();
        
        DB::commit();

            
 
    } catch (Exception $e){
        DB::rollBack();
    }  
        

    }
}
