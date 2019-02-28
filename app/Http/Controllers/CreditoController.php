<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dato_extra;
use App\Credito;
use App\Personal;
use App\Cliente;
use App\Inst_seleccionada;
use DB;

class CreditoController extends Controller

{

    public function indexCreditos(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $creditos = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
            ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                'datos_extra.mascota','datos_extra.num_perros','datos_extra.rang010','datos_extra.rang1120',
                'datos_extra.rang21','datos_extra.ama_casa','datos_extra.persona_discap','datos_extra.silla_ruedas',
                'datos_extra.num_vehiculos','creditos.costo_paquete','creditos.status')
                ->where('creditos.prospecto_id','=',$request->prospecto_id)->get();
        
                foreach($creditos as $index => $credito) {
                    $prospecto=[];
                    $prospecto = Cliente::join('personal','clientes.id','=','personal.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->select('personal.nombre','personal.apellidos','clientes.sexo','personal.telefono','personal.celular',
                                 'personal.email','personal.direccion','personal.cp','personal.colonia','clientes.ciudad','clientes.estado',
                                 'personal.f_nacimiento','clientes.nacionalidad','clientes.curp','personal.rfc','personal.homoclave',
                                 'clientes.nss','clientes.empresa','clientes.puesto','clientes.email_institucional','clientes.tipo_casa',
                                 'clientes.edo_civil','clientes.coacreditado','clientes.nombre_coa','clientes.apellidos_coa',
                                 'clientes.f_nacimiento_coa','clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa',
                                 'clientes.cp_coa','clientes.colonia_coa','clientes.estado_coa','clientes.ciudad_coa','clientes.celular_coa',
                                 'clientes.telefono_coa','clientes.email_coa','clientes.email_institucional_coa',
                                 'clientes.empresa_coa','fraccionamientos.nombre as proyecto','clientes.curp_coa','clientes.nss_coa',
                                 'clientes.nacionalidad_coa')
                        ->where('clientes.id','=',$credito->prospecto_id)->get();
                    
                    $institucion=[];
                    $institucion=Inst_seleccionada::select('tipo_credito','institucion','elegido')
                        ->where('credito_id','=',$credito->id)
                        ->where('elegido','=',1)->get();
                    if(sizeof($prospecto) > 0){
                        $credito->nombre = $prospecto[0]->nombre;
                        $credito->apellidos = $prospecto[0]->apellidos;
                        $credito->sexo = $prospecto[0]->sexo;
                        $credito->telefono = $prospecto[0]->telefono;
                        $credito->celular = $prospecto[0]->celular;
                        $credito->email = $prospecto[0]->email;
                        $credito->direccion = $prospecto[0]->direccion;
                        $credito->cp = $prospecto[0]->cp;
                        $credito->colonia = $prospecto[0]->colonia;
                        $credito->ciudad = $prospecto[0]->ciudad;
                        $credito->estado = $prospecto[0]->estado;
                        $credito->f_nacimiento = $prospecto[0]->f_nacimiento;
                        $credito->nacionalidad = $prospecto[0]->nacionalidad;
                        $credito->curp = $prospecto[0]->curp;
                        $credito->rfc = $prospecto[0]->rfc;
                        $credito->homoclave = $prospecto[0]->homoclave;
                        $credito->nss = $prospecto[0]->nss;
                        $credito->empresa = $prospecto[0]->empresa;
                        $credito->puesto = $prospecto[0]->puesto;
                        $credito->email_institucional = $prospecto[0]->email_institucional;
                        $credito->tipo_casa = $prospecto[0]->tipo_casa;
                        $credito->edo_civil = $prospecto[0]->edo_civil;
                        $credito->coacreditado = $prospecto[0]->coacreditado;
                        $credito->nombre_coa = $prospecto[0]->nombre_coa;
                        $credito->apellidos_coa = $prospecto[0]->apellidos_coa;
                        $credito->f_nacimiento_coa = $prospecto[0]->f_nacimiento_coa;
                        $credito->rfc_coa = $prospecto[0]->rfc_coa;
                        $credito->curp_coa = $prospecto[0]->curp_coa;
                        $credito->nss_coa = $prospecto[0]->nss_coa;
                        $credito->homoclave_coa = $prospecto[0]->homoclave_coa;
                        $credito->direccion_coa = $prospecto[0]->direccion_coa;
                        $credito->cp_coa = $prospecto[0]->cp_coa;
                        $credito->nacionalidad_coa = $prospecto[0]->nacionalidad_coa;
                        $credito->colonia_coa = $prospecto[0]->colonia_coa;
                        $credito->estado_coa = $prospecto[0]->estado_coa;
                        $credito->ciudad_coa = $prospecto[0]->ciudad_coa;
                        $credito->celular_coa = $prospecto[0]->celular_coa;
                        $credito->telefono_coa = $prospecto[0]->telefono_coa;
                        $credito->email_coa = $prospecto[0]->email_coa;
                        $credito->email_institucional_coa = $prospecto[0]->email_institucional_coa;
                        $credito->empresa_coa = $prospecto[0]->empresa_coa;
                        $credito->proyecto = $prospecto[0]->proyecto;
                        
                        $credito->tipo_credito = $institucion[0]->tipo_credito;
                        $credito->institucion = $institucion[0]->institucion;
                        $credito->elegido = $institucion[0]->elegido;
                    }
                    
                }

        
        return['creditos' => $creditos];
    }

    public function updateDatosCliente(Request $request){
            if (!$request->ajax()) return redirect('/');

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
        if (!$request->ajax()) return redirect('/');
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

            $inst_seleccionada = new Inst_seleccionada();
            $inst_seleccionada->credito_id = $credito->id;
            $inst_seleccionada->tipo_credito = $request->tipo_credito;
            $inst_seleccionada->institucion = $request->inst_financiera;
            $inst_seleccionada->elegido = 1;
            $inst_seleccionada->save();
            
            DB::commit();

                
    
        } catch (Exception $e){
            DB::rollBack();
        }  

    }
    public function selectTipCreditosSimulacion(Request $request){
        $simulacion= $request->simulacion_id;
        $creditos = Inst_seleccionada::select('id','tipo_credito','institucion','elegido')
        ->where('credito_id','=',$simulacion)->get();

        return ['creditos_select' => $creditos];
    }

    public function storeCreditoSelect(Request $request){
        $inst_seleccionada = new Inst_seleccionada();
        $inst_seleccionada->credito_id = $request->credito_id;
        $inst_seleccionada->tipo_credito = $request->tipo_credito;
        $inst_seleccionada->institucion = $request->institucion;
        $inst_seleccionada->elegido = 0;
        $inst_seleccionada->save();
    }

    public function seleccionarCredito(Request $request){
        if(!$request->ajax())return redirect('/');
        $inst_seleccionada = Inst_seleccionada::findOrFail($request->id);
        $inst_seleccionada->elegido = 1;
        $inst_seleccionada->save();

        $simulacion= $request->simulacion_id;
        $seleccionados =  Inst_seleccionada::select('id','elegido')
                                             ->where('credito_id','=',$simulacion)
                                             ->where('id','!=',$request->id)
                                             ->get();
        foreach($seleccionados as $index => $seleccion) {
            $seleccion->elegido = 0;
            $seleccion->save();
        }

    }

    public function rechazarSolicitud(Request $request){
        $simulacion = Credito::findOrFail($request->id);
        $simulacion->status=0;
        $simulacion->save();
    }

    public function aceptarSolicitud(Request $request){
        $simulacion = Credito::findOrFail($request->id);
        $simulacion->status=2;
        $simulacion->save();
    }

}
