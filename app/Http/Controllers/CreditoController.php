<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dato_extra;
use App\Credito;
use App\Personal;
use App\Cliente;
use App\Inst_seleccionada;
use App\Vendedor;
use DB;
use App\User;
use App\Notifications\NotifyAdmin;
use App\Obs_inst_selec;
use Auth;
use Carbon\Carbon;
use Excel;

class CreditoController extends Controller

{

    public function indexCreditos(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $creditos = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
            ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base','creditos.precio_obra_extra',
                'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                'datos_extra.mascota','datos_extra.num_perros','datos_extra.rang010','datos_extra.rang1120',
                'datos_extra.rang21','datos_extra.ama_casa','datos_extra.persona_discap','datos_extra.silla_ruedas',
                'datos_extra.num_vehiculos','creditos.costo_paquete','creditos.status','creditos.fraccionamiento')
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
        $prospecto=$request->prospecto_id;
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $credito = new Credito();
            $credito->prospecto_id = $prospecto;
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
            $credito->precio_obra_extra = $request->precio_obra_extra;
            $credito->promocion = $request->promocion;
            $credito->descripcion_promocion = $request->descripcionPromo;
            $credito->descuento_promocion = $request->descuentoPromo;
            $credito->paquete = $request->paquete_id; //
            $credito->descripcion_paquete = $request->descripcionPaquete;
            $credito->costo_paquete = $request->costoPaquete;
            $credito->precio_venta = $request->precioVenta;
            $credito->plazo = $request->plazo_credito;
            $credito->credito_solic = $request->monto_credito;
            $credito->lote_id = $request->lote_id;
            $credito->fraccionamiento = $request->fraccionamiento;
            $credito->vendedor_id = Auth::user()->id;


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

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $arregloSimPendientes = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => 'Creó una nueva simulacion',
                    'titulo' => 'Nueva simulacion'
                ]
            ];

            $users = User::select('id')->where('rol_id','=','1')
                ->orWhere('rol_id','=','6')
                ->orWhere('rol_id','=','4')->get();

            foreach($users as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloSimPendientes));
            }
            
            DB::commit();
                
    
        } catch (Exception $e){
            DB::rollBack();
        }  

    }
    public function selectTipCreditosSimulacion(Request $request){
        $simulacion= $request->simulacion_id;
        $creditos = Inst_seleccionada::select('id','tipo_credito','institucion','elegido','monto_credito','plazo_credito',
                        'status','fecha_ingreso','fecha_respuesta')
        ->where('credito_id','=',$simulacion)->get();

        foreach($creditos as $index => $credito) {
            $observacion = Obs_inst_selec::
            select('comentario','usuario','created_at')
                    ->where('inst_selec_id','=', $credito->id)->orderBy('created_at','desc')
                    ->first();
            if($observacion){
                $credito->observacion = $observacion->comentario;
                $credito->obs_user = $observacion->usuario;
                $credito->obs_fecha = $observacion->created_at;
            }
            else{
                $credito->observacion = '';
                $credito->obs_user = '';
                $credito->obs_fecha = '';
            }
        }

        return ['creditos_select' => $creditos];
    }

    public function storeCreditoSelect(Request $request){
        $inst_seleccionada = new Inst_seleccionada();
        $inst_seleccionada->credito_id = $request->credito_id;
        $inst_seleccionada->tipo_credito = $request->tipo_credito;
        $inst_seleccionada->institucion = $request->institucion;
        $inst_seleccionada->elegido = 0;
        $inst_seleccionada->plazo_credito = $request->plazo_credito;
        $inst_seleccionada->monto_credito = $request->monto_credito;
        $inst_seleccionada->save();
    }

    public function updateDatosCredito(Request $request){
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
        $inst_seleccionada = Inst_seleccionada::findOrFail($request->id);
        $inst_seleccionada->fecha_ingreso = $request->fecha_ingreso;
        $inst_seleccionada->status = $request->status;
        if($inst_seleccionada->status == 0 || $inst_seleccionada->status == 2 ){
            $inst_seleccionada->fecha_respuesta = Carbon::now();
            }else{
                $inst_seleccionada->fecha_respuesta = null;
            }
        $inst_seleccionada->plazo_credito = $request->plazo_credito;
        $inst_seleccionada->monto_credito = $request->monto_credito;
        $inst_seleccionada->save();

        $observacion = new Obs_inst_selec();
        $observacion->inst_selec_id = $inst_seleccionada->id;
        $observacion->comentario = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
             
        DB::commit();

                
    
        } catch (Exception $e){
            DB::rollBack();
        }  

    }

    public function seleccionarCredito(Request $request){
        if(!$request->ajax())return redirect('/');
        $inst_seleccionada = Inst_seleccionada::findOrFail($request->id);
        $inst_seleccionada->elegido = 1;
        $inst_seleccionada->save();

        $simulacion= $request->simulacion_id;

        $credito = Credito::findOrFail( $simulacion);
        $credito->plazo = $inst_seleccionada->plazo_credito;
        $credito->credito_solic = $inst_seleccionada->monto_credito;
        $credito->save();

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

        $cliente = Cliente::select('vendedor_id')->where('id','=',$simulacion->prospecto_id)->get();

        $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $msj = "Se ha rechazado el credito # " . $simulacion->id;
            $arregloAceptado = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => $msj,
                    'titulo' => 'Rechazado'
                ]
            ];

            User::findOrFail($cliente[0]->vendedor_id)->notify(new NotifyAdmin($arregloAceptado));
    }

    public function aceptarSolicitud(Request $request){
        $simulacion = Credito::findOrFail($request->id);
        $simulacion->status=2;
        $simulacion->save();

        $cliente = Cliente::select('vendedor_id')->where('id','=',$simulacion->prospecto_id)->get();

        $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $msj = "Se ha aprobado el credito # " . $simulacion->id;
            $arregloAceptado = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => $msj,
                    'titulo' => 'Aprobado'
                ]
            ];

            User::findOrFail($cliente[0]->vendedor_id)->notify(new NotifyAdmin($arregloAceptado));
            
        

    }


    public function indexHistorial(Request $request){
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        if($buscar==''){
            $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('personal','creditos.prospecto_id','=','personal.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal as v','clientes.vendedor_id','v.id')
            ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito','inst_seleccionadas.id as inst_credito',
                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','creditos.fraccionamiento',
                'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                ->where('creditos.status','!=','1')
                ->where('inst_seleccionadas.elegido','=','1')
                ->orderBy('id','desc')->paginate(8);

                $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('personal','creditos.prospecto_id','=','personal.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal as v','clientes.vendedor_id','v.id')
            ->select('creditos.id')
                ->where('inst_seleccionadas.elegido','=','1')
                ->orderBy('id','desc')->count();
        }
        else{
            switch($criterio){
                case 'personal.nombre':
                {
                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','creditos.prospecto_id','=','personal.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal as v','clientes.vendedor_id','v.id')
                    ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                        'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                        'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                        'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                        'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                        'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                        'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                        'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','creditos.fraccionamiento',
                        'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                    
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);

                        $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->count();
                    break;
                }
                case 'v.nombre':
                {
                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','creditos.prospecto_id','=','personal.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal as v','clientes.vendedor_id','v.id')
                    ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                        'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                        'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                        'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                        'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                        'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                        'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                        'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','creditos.fraccionamiento',
                        'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                    
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orWhere('v.apellidos', 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);

                        $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orWhere('v.apellidos', 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->count();
                    break;
                }
                case 'inst_seleccionadas.tipo_credito':
                {
                    $creditos = Credito::join('datos_extra','creditos.id','=','datos_extra.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','creditos.prospecto_id','=','personal.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal as v','clientes.vendedor_id','v.id')
                    ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                        'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                        'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                        'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                        'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                        'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                        'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                        'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','creditos.fraccionamiento',
                        'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);

                        $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->count();
                    break;
                }
                case 'creditos.id':
                {
                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','creditos.prospecto_id','=','personal.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal as v','clientes.vendedor_id','v.id')
                    ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                        'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                        'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                        'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                        'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                        'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                        'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                        'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','creditos.fraccionamiento',
                        'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);

                        $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->count();
                    break;
                }
                case 'creditos.fraccionamiento':
                {
                    if($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                            'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                            'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                            'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                            'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                            'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                            'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                            'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                            ->where('creditos.status','!=','1')
                            ->where('inst_seleccionadas.elegido','=','1')
                            ->where('creditos.fraccionamiento', '=',  $buscar)
                            ->where('creditos.etapa','=',$b_etapa)
                            ->where('creditos.manzana', '=', $b_manzana)
                            ->where('creditos.num_lote','=',$b_lote)
                            ->orderBy('id','desc')->paginate(8);

                            $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('creditos.fraccionamiento', '=',  $buscar)
                        ->where('creditos.etapa','=',$b_etapa)
                        ->where('creditos.manzana', '=', $b_manzana)
                        ->where('creditos.num_lote','=',$b_lote)
                        ->orderBy('id','desc')->count();
                    
                    }
                    else{
                        if($b_etapa != '' && $b_manzana != ''){
                            $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->join('personal','creditos.prospecto_id','=','personal.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal as v','clientes.vendedor_id','v.id')
                            ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                                'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                                'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                                'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                                'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
                                'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                ->where('creditos.status','!=','1')
                                ->where('inst_seleccionadas.elegido','=','1')
                                ->where('creditos.fraccionamiento', '=',  $buscar)
                                ->where('creditos.etapa','like','%'.$b_etapa.'%')
                                ->where('creditos.manzana', '=', $b_manzana)
                                ->orderBy('id','desc')->paginate(8);

                                $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('creditos.fraccionamiento', '=',  $buscar)
                        ->where('creditos.etapa','like','%'.$b_etapa.'%')
                        ->where('creditos.manzana', '=', $b_manzana)
                        ->orderBy('id','desc')->count();
                        }
                        else{
                            if($b_etapa != ''){
                                $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('personal','creditos.prospecto_id','=','personal.id')
                                ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                ->join('personal as v','clientes.vendedor_id','v.id')
                                ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                                    'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                                    'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                    'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                                    'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                                    'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                    'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                                    'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
                                    'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                    ->where('creditos.status','!=','1')
                                    ->where('inst_seleccionadas.elegido','=','1')
                                    ->where('creditos.fraccionamiento', '=',  $buscar)
                                    ->where('creditos.etapa','like','%'.$b_etapa.'%')
                                    ->orderBy('id','desc')->paginate(8);

                                    $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                        ->select('creditos.id')
                        ->where('creditos.status','!=','1')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('creditos.fraccionamiento', '=',  $buscar)
                        ->where('creditos.etapa','like','%'.$b_etapa.'%')
                        ->orderBy('id','desc')->count();
                            }
                            else{
                                if($b_manzana != ''){
                                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                    ->join('personal','creditos.prospecto_id','=','personal.id')
                                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                    ->join('personal as v','clientes.vendedor_id','v.id')
                                    ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                                        'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                                        'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                                        'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                        'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                                        'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                                        'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                        'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
                                        'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                        ->where('creditos.status','!=','1')
                                        ->where('inst_seleccionadas.elegido','=','1')
                                        ->where('creditos.fraccionamiento', '=',  $buscar)
                                        ->where('creditos.manzana', '=', $b_manzana)
                                        ->orderBy('id','desc')->paginate(8);

                                        $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                        ->join('personal','creditos.prospecto_id','=','personal.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal as v','clientes.vendedor_id','v.id')
                                        ->select('creditos.id')
                                        ->where('creditos.status','!=','1')
                                        ->where('inst_seleccionadas.elegido','=','1')
                                        ->where('creditos.fraccionamiento', '=',  $buscar)
                                        ->where('creditos.manzana', '=', $b_manzana)
                                        ->orderBy('id','desc')->count();
                                }
                                else{
                                    if($b_lote != ''){
                                        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                        ->join('personal','creditos.prospecto_id','=','personal.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal as v','clientes.vendedor_id','v.id')
                                        ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                                            'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                                            'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                                            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                            'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                                            'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                                            'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                            'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                                            'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
                                            'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                            ->where('creditos.status','!=','1')
                                            ->where('inst_seleccionadas.elegido','=','1')
                                            ->where('creditos.fraccionamiento', '=',  $buscar)
                                            ->where('creditos.num_lote','=',$b_lote)
                                            ->orderBy('id','desc')->paginate(8);

                                            $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                        ->join('personal','creditos.prospecto_id','=','personal.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal as v','clientes.vendedor_id','v.id')
                                        ->select('creditos.id')
                                        ->where('creditos.status','!=','1')
                                        ->where('inst_seleccionadas.elegido','=','1')
                                        ->where('creditos.fraccionamiento', '=',  $buscar)
                                        ->where('creditos.num_lote','=',$b_lote)
                                        ->orderBy('id','desc')->count();
                                    }
                                    else{
                                        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                        ->join('personal','creditos.prospecto_id','=','personal.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal as v','clientes.vendedor_id','v.id')
                                        ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
                                            'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
                                            'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
                                            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                            'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
                                            'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
                                            'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                            'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito',
                                            'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
                                            'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                            ->where('creditos.status','!=','1')
                                            ->where('inst_seleccionadas.elegido','=','1')
                                            ->where('creditos.fraccionamiento', '=',  $buscar)
                                            ->orderBy('id','desc')->paginate(8);
                                            
                                            $contadorCreditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                            ->join('personal','creditos.prospecto_id','=','personal.id')
                                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                            ->join('personal as v','clientes.vendedor_id','v.id')
                                            ->select('creditos.id')
                                            ->where('creditos.status','!=','1')
                                            ->where('inst_seleccionadas.elegido','=','1')
                                            ->where('creditos.fraccionamiento', '=',  $buscar)
                                            ->orderBy('id','desc')->count();
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            }

        }

    
        return[
            'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],'creditos' => $creditos, 'contadorHistSim' => $contadorCreditos ];
    }

    public function HistorialDeCreditos (Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar == ''){
        $Historialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal','clientes.id','=','personal.id')
                                        ->join('personal as v','clientes.vendedor_id','=','v.id')
                                        ->select('inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion','inst_seleccionadas.fecha_ingreso',
                                                 'personal.nombre as nombre', 'personal.apellidos as apellidos','inst_seleccionadas.id','inst_seleccionadas.status',
                                                 'v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos','inst_seleccionadas.monto_credito','inst_seleccionadas.plazo_credito',
                                                 'creditos.id as id_credito')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->orderBy('id','desc')->paginate(8);

                                        $contadorHistorialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal','clientes.id','=','personal.id')
                                        ->join('personal as v','clientes.vendedor_id','=','v.id')
                                        ->select('inst_seleccionadas.id')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->orderBy('id','desc')->count();
        } 
        
        if($criterio == 'personal.nombre'){

            $Historialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->join('personal as v','clientes.vendedor_id','=','v.id')
            ->select('inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion','inst_seleccionadas.fecha_ingreso',
                     'personal.nombre as nombre', 'personal.apellidos as apellidos','inst_seleccionadas.id','inst_seleccionadas.status',
                     'v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos','inst_seleccionadas.monto_credito','inst_seleccionadas.plazo_credito',
                     'creditos.id as id_credito')
            ->where('inst_seleccionadas.status','=',1)
            ->where($criterio,'like','%'.$buscar.'%')
            ->where('inst_seleccionadas.status','=',1)
            ->orwhere('personal.apellidos','like','%'.$buscar.'%')
            ->where('inst_seleccionadas.status','=',1)
            ->orderBy('id','desc')->paginate(8);

            $contadorHistorialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal','clientes.id','=','personal.id')
                                        ->join('personal as v','clientes.vendedor_id','=','v.id')
                                        ->select('inst_seleccionadas.id')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->where($criterio,'like','%'.$buscar.'%')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->orwhere('personal.apellidos','like','%'.$buscar.'%')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->orderBy('id','desc')->count();

        }
        if($criterio == 'v.nombre'){

            $Historialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->join('personal as v','clientes.vendedor_id','=','v.id')
            ->select('inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion','inst_seleccionadas.fecha_ingreso',
                     'personal.nombre as nombre', 'personal.apellidos as apellidos','inst_seleccionadas.id','inst_seleccionadas.status',
                     'v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos','inst_seleccionadas.monto_credito','inst_seleccionadas.plazo_credito',
                     'creditos.id as id_credito')
            ->where('inst_seleccionadas.status','=',1)
            ->where($criterio,'like','%'.$buscar.'%')
            ->where('inst_seleccionadas.status','=',1)
            ->orwhere('v.apellidos','like','%'.$buscar.'%')
            ->where('inst_seleccionadas.status','=',1)
            ->orderBy('id','desc')->paginate(8);

            $contadorHistorialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal','clientes.id','=','personal.id')
                                        ->join('personal as v','clientes.vendedor_id','=','v.id')
                                        ->select('inst_seleccionadas.id')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->where($criterio,'like','%'.$buscar.'%')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->orwhere('v.apellidos','like','%'.$buscar.'%')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->orderBy('id','desc')->count();

        }
        else{
            $Historialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->join('personal as v','clientes.vendedor_id','=','v.id')
            ->select('inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion','inst_seleccionadas.fecha_ingreso',
                     'personal.nombre as nombre', 'personal.apellidos as apellidos','inst_seleccionadas.id','inst_seleccionadas.status',
                     'v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos','inst_seleccionadas.monto_credito','inst_seleccionadas.plazo_credito',
                     'creditos.id as id_credito')
            ->where('inst_seleccionadas.status','=',1)
            ->where($criterio,'like','%'.$buscar.'%')
            ->orderBy('id','desc')->paginate(8);

            $contadorHistorialcreditos = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('personal','clientes.id','=','personal.id')
                                        ->join('personal as v','clientes.vendedor_id','=','v.id')
                                        ->select('inst_seleccionadas.id')
                                        ->where('inst_seleccionadas.status','=',1)
                                        ->where($criterio,'like','%'.$buscar.'%')
                                        ->orderBy('id','desc')->count();
        }
        
        return[
            'pagination' => [
                'total'         => $Historialcreditos->total(),
                'current_page'  => $Historialcreditos->currentPage(),
                'per_page'      => $Historialcreditos->perPage(),
                'last_page'     => $Historialcreditos->lastPage(),
                'from'          => $Historialcreditos->firstItem(),
                'to'            => $Historialcreditos->lastItem(),
            ],'Historialcreditos' => $Historialcreditos, 'contadorHistCred' => $contadorHistorialcreditos ];
    }

    public function ExportarHistorialSimulacion (){
        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
        ->join('personal','creditos.prospecto_id','=','personal.id')
        ->join('clientes','creditos.prospecto_id','=','clientes.id')
        ->join('personal as v','clientes.vendedor_id','v.id')
        ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
            'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
            'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
            'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
            'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
            'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
            'creditos.costo_paquete','creditos.status','inst_seleccionadas.tipo_credito','inst_seleccionadas.id as inst_credito',
            'inst_seleccionadas.institucion','personal.nombre','personal.apellidos', 'creditos.fraccionamiento as proyecto',
            'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
            ->where('creditos.status','!=','1')
            ->where('inst_seleccionadas.elegido','=','1')
            ->orderBy('id','desc')->get();

                   

        
            return Excel::create('Historial de simulaciones', function($excel) use ($creditos){
                $excel->sheet('creditos', function($sheet) use ($creditos){
                    
                    $sheet->row(1, [
                        '# Folio', 'Cliente', 'Vendedor', 'Proyecto', 'Etapa', 'Manazana',
                        '# Lote', 'Modelo', 'Precio venta','Credito solicitado','Plazo',
                        'Institucion','Tipo de credito','Status'
                    ]);


                    $sheet->cells('A1:N1', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(13);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    
                    $cont=1;
                    
                    $sheet->setColumnFormat(array(
                        'J' => '$#,##0.00',
                        'I' => '$#,##0.00',
                    ));

                    

                    foreach($creditos as $index => $credito) {
                        if($credito->status == 2){
                            $status = 'Aprobado';
                        } else {
                            $status = 'Rechazado';
                        }
                       
                        $cont++;
                        
                        $sheet->row($index+2, [
                            $credito->id,
                            $credito->nombre.' '.$credito->apellidos,
                            $credito->vendedor_nombre.' '.$credito->vendedor_apellidos,
                            $credito->proyecto,
                            $credito->etapa,
                            $credito->manzana,
                            $credito->num_lote,
                            $credito->modelo,
                            $credito->precio_venta,
                            $credito->credito_solic,
                            $credito->plazo.' '.'Años',
                            $credito->institucion,
                            $credito->tipo_credito,
                            $status,
                           
                        ]);	
                    }


                    $num='A1:N' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
            
            )->download('xls');
        
      
    }

}
