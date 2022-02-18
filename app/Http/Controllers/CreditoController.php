<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\ReubicacionController;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\Notifications\NotifyAdmin;

use App\Dato_extra;
use App\Credito;
use App\Personal;
use App\Cliente;
use App\inst_seleccionada;
use App\Vendedor;
use App\Cliente_observacion;
use App\Dev_virtual;
use App\Devolucion;
use App\Deposito_gcc;
use App\Deposito_conc;
use App\Dep_credito;
use App\Deposito;
use App\User;
use App\Cuenta;
use App\Pago_contrato;
use App\Obs_inst_selec;
use Carbon\Carbon;
use App\Contrato;
use Excel;
use DB;
use Auth;

// Controlador para créditos (Simulación de credito).
class CreditoController extends Controller
{
    //Función privada para obtener cuentas bancarias por empresa.
    private function getCuentas(){
        $cuentas = Cuenta::select('num_cuenta','banco')->where('empresa','=','Grupo Constructor Cumbres')->get();
        $arrayCuentas = [];

        foreach($cuentas as $index => $cuenta){
            array_push($arrayCuentas,$cuenta->num_cuenta.'-'.$cuenta->banco);
        }
        return $arrayCuentas;
    }

    // Función para obtener las simulaciones de crédito por prospecto.
    public function indexCreditos(Request $request){
        if (!$request->ajax()) return redirect('/');
        $creditos = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->select('creditos.*',
                        'datos_extra.mascota','datos_extra.num_perros','datos_extra.rang010','datos_extra.rang1120',
                        'datos_extra.rang21','datos_extra.ama_casa','datos_extra.persona_discap','datos_extra.silla_ruedas',
                        'datos_extra.num_vehiculos',
                        'lotes.fraccionamiento_id',

                        'personal.nombre','personal.apellidos','clientes.sexo','personal.telefono','personal.celular',
                        'personal.email','personal.direccion','personal.cp','personal.colonia','clientes.ciudad','clientes.estado',
                        'personal.f_nacimiento','clientes.nacionalidad','clientes.curp','personal.rfc','personal.homoclave',
                        'personal.clv_lada',
                        'clientes.nss','clientes.empresa','clientes.puesto','clientes.email_institucional','clientes.tipo_casa',
                        'clientes.edo_civil','clientes.coacreditado','clientes.nombre_coa','clientes.apellidos_coa',
                        'clientes.f_nacimiento_coa','clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa',
                        'clientes.cp_coa','clientes.colonia_coa','clientes.estado_coa','clientes.ciudad_coa','clientes.celular_coa',
                        'clientes.telefono_coa','clientes.email_coa','clientes.email_institucional_coa',
                        'clientes.empresa_coa','fraccionamientos.nombre as proyecto','clientes.curp_coa','clientes.nss_coa',
                        'clientes.nacionalidad_coa',

                        'inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion','inst_seleccionadas.elegido'
                    )
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('creditos.prospecto_id','=',$request->prospecto_id)->get();
        
        return['creditos' => $creditos];
    }

    // Función para actualizar los datos del cliente.
    public function updateDatosCliente(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

           // Datos del cliente que se guardan en la tabla personal
           $personal = Personal::findOrFail($request->id);
           $personal->direccion = $request->direccion;
           $personal->cp = $request->cp;
           $personal->colonia = $request->colonia; 

           // Datos del cliente que se guardan en la tabla clientes
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
           $cliente->nss = $request->nss;
           $cliente->curp = $request->curp;
           $cliente->empresa = $request->empresa;
           
           $personal->save();
           $cliente->save();

    }

    // Función para guardar el registro de la simulación de crédito
    public function store (Request $request)
    {
        $prospecto=$request->prospecto_id;
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();

            $asesor = Cliente::findOrFail($prospecto);
            // Crea registro en la BD de Créditos
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
            $credito->vendedor_id = $asesor->vendedor_id;
            $credito->save();

            // Se crea el registro con los Datos extra.
            $id_credito = $credito->id;
            $datos_extra = new Dato_extra();
            $datos_extra->id = $id_credito;
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

            // Se crea el registro para la institución de financiamiento.
            $inst_seleccionada = new inst_seleccionada();
            $inst_seleccionada->credito_id = $id_credito;
            $inst_seleccionada->tipo_credito = $request->tipo_credito;
            if($request->tipo_credito == "Crédito Directo"){
                $inst_seleccionada->status = 2;
            }
            $inst_seleccionada->institucion = $request->inst_financiera;
            $inst_seleccionada->monto_credito = $request->monto_credito;
            $inst_seleccionada->plazo_credito = $request->plazo_credito;
            $inst_seleccionada->elegido = 1;
            $inst_seleccionada->save();

            // Se envia la notificación a los usuarios de ventas.
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

    // Función para retornar los tipos de financiamientos creados para la simulación de crédito.
    public function selectTipCreditosSimulacion(Request $request){
        if(!$request->ajax())return redirect('/');
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

    // Función para registarr un nuevo financiamiento para la simulación de crédito.
    public function storeCreditoSelect(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $inst_seleccionada = new inst_seleccionada();
        $inst_seleccionada->credito_id = $request->credito_id;
        $inst_seleccionada->tipo_credito = $request->tipo_credito;
        if($request->tipo_credito == "Crédito Directo"){
            $inst_seleccionada->status = 2;
        }
        $inst_seleccionada->institucion = $request->institucion;
        $inst_seleccionada->elegido = 0;
        $inst_seleccionada->plazo_credito = $request->plazo_credito;
        $inst_seleccionada->monto_credito = $request->monto_credito;
        $inst_seleccionada->save();
    }

    // Función para actualizar los datos del financiamiento.
    public function updateDatosCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $inst_seleccionada = inst_seleccionada::findOrFail($request->id);
            $inst_seleccionada->fecha_ingreso = $request->fecha_ingreso;
            $inst_seleccionada->status = $request->status;
            $inst_seleccionada->institucion = $request->inst_financiera;
            $inst_seleccionada->tipo_credito = $request->tipo_credito;
            if($inst_seleccionada->status == 0 || $inst_seleccionada->status == 2 ){
                $inst_seleccionada->fecha_respuesta = Carbon::now();
            }else{
                $inst_seleccionada->fecha_respuesta = null;
            }
            $inst_seleccionada->plazo_credito = $request->plazo_credito;
            $inst_seleccionada->monto_credito = $request->monto_credito;
            if($request->fecha_vigencia != ""){
                $inst_seleccionada->fecha_vigencia = $request->fecha_vigencia;
            }
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

    // Función para elegir el financiamiento para la simulación de crédito.
    public function seleccionarCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // El campo elegido queda activado para indicar que es el financiamiento elegido.
        $inst_seleccionada = inst_seleccionada::findOrFail($request->id);
        $inst_seleccionada->elegido = 1;
        $inst_seleccionada->save();

        $simulacion= $request->simulacion_id;
        // En la simulación se actualiza el plazo y monto financiado.
        $credito = Credito::findOrFail( $simulacion);
        $credito->plazo = $inst_seleccionada->plazo_credito;
        $credito->credito_solic = $inst_seleccionada->monto_credito;
        $credito->save();

        $seleccionados =  inst_seleccionada::select('id','elegido')
                                             ->where('credito_id','=',$simulacion)
                                             ->where('id','!=',$request->id)
                                             ->get();
        // Se desactivan los otros financiamientos.
        foreach($seleccionados as $index => $seleccion) {
            $seleccion->elegido = 0;
            $seleccion->save();
        }
    }

    // Función para rechazar la simulaciín de credito
    public function rechazarSolicitud(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Simulación en estatus 0, para indicar como cancelado.
        $simulacion = Credito::findOrFail($request->id);
        $simulacion->status=0;
        $simulacion->save();

        $cliente = Cliente::select('vendedor_id')->where('id','=',$simulacion->prospecto_id)->get();

            // Se envia notificación al vendedor.
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

    // Función para aprobar una simulación de crédito
    public function aceptarSolicitud(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $simulacion = Credito::findOrFail($request->id);
        // Estatus 2, para indicar la aprobación
        $simulacion->status=2;
        $simulacion->save();

        $cliente = Cliente::select('vendedor_id')->where('id','=',$simulacion->prospecto_id)->get();
        // Se envia notificación al vendedor.
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

            $personal = Personal::select('email')->where('id','=',$cliente[0]->vendedor_id)->get();
            $correo = $personal[0]->email;
            //Mail::to($correo)->send(new NotificationReceived($msj));

            User::findOrFail($cliente[0]->vendedor_id)->notify(new NotifyAdmin($arregloAceptado));
    }

    /* Función para retornar las manzanas (Distintas) correspondientes a las simulaciones 
        de crédito creadas.
    */
    public function selectManzana(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar=$request->buscar;
        $buscar2=$request->buscar2;

        $manzanas=Credito::select('manzana')
                    ->where('fraccionamiento','=',$buscar)
                    ->where('etapa','=',$buscar2)
                    ->distinct()->orderBy('manzana','asc')->get();
            
        return['manzanas' => $manzanas];
    }

    // Función para retornar todas las simulaciones de crédito hechas.
    public function indexHistorial(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $criterio2 = $request->criterio2;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        //Llamada a la funcion privada que retorna la query
        $creditos = $this->getHIstCreditos();
        
            // Filtros de busqueda
            if($buscar != ''){
                switch($criterio){
                    case 'personal.nombre':// Filtro por nombre de cliente
                    {
                        $creditos = $creditos
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'v.nombre': // Filtro por nombre de asesor.
                    {
                        $creditos = $creditos
                            ->where(DB::raw("CONCAT(v.nombre,' ',v.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'inst_seleccionadas.tipo_credito': // Filtro por tipo de financiamiento.
                    {
                        $creditos = $creditos
                            ->where($criterio, 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'creditos.id': // Filtro por # de folio.
                    {
                        $creditos = $creditos
                            ->where($criterio, '=',$buscar);
                        break;
                    }
                    case 'creditos.fraccionamiento': // Filtro por proyecto.
                    {
                        $creditos = $creditos
                            ->where('lotes.fraccionamiento_id', '=',  $buscar);
                            if($b_etapa != '') //Etapa
                                $creditos = $creditos->where('creditos.etapa','=',$b_etapa);
                            if($b_manzana != '') //Manzana
                                $creditos = $creditos->where('creditos.manzana', '=', $b_manzana);
                            if($b_lote != '') // Numero de lote
                                $creditos = $creditos->where('creditos.num_lote','=',$b_lote);
                        break;
                    }
                }
            }

            if($criterio2 != '') // Estatus del crédito.
                $creditos = $creditos->where('creditos.status','=','1');

            $creditos = $creditos->where('inst_seleccionadas.elegido','=','1')
                ->orderBy('id','desc')->paginate(8);

        return[
            'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],'creditos' => $creditos, 'contadorHistSim' => $creditos->total() ];
    }

    // Función para retornar todas las simulaciones de crédito hechas, 
    // para una vista rapido y aprobar o rechazar.
    public function HistorialDeCreditos (Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;

        //Llamada a la funcion privada que retorna la query.
        $Historialcreditos = $this->getHIstCreditos();

        if($buscar2 != ''){ // Filtro por estatus del crédito.
                $Historialcreditos = $Historialcreditos
                    ->where('inst_seleccionadas.status','=',$buscar2);
        } 
        if($buscar != ''){
            switch($criterio){
                case 'personal.nombre':{ //Filtro por nombre del cliente.
                    $Historialcreditos = $Historialcreditos 
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'v.nombre':{ //Filtro por nombre de asesor de ventas.
                    $Historialcreditos = $Historialcreditos
                        ->where(DB::raw("CONCAT(v.nombre,' ',v.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                default: {
                    $Historialcreditos = $Historialcreditos
                        ->where($criterio,'like','%'.$buscar.'%');
                    break;
                }

            }
        }
        $Historialcreditos = $Historialcreditos->orderBy('id','desc')->paginate(8);
        
        return[
            'pagination' => [
                'total'         => $Historialcreditos->total(),
                'current_page'  => $Historialcreditos->currentPage(),
                'per_page'      => $Historialcreditos->perPage(),
                'last_page'     => $Historialcreditos->lastPage(),
                'from'          => $Historialcreditos->firstItem(),
                'to'            => $Historialcreditos->lastItem(),
            ],'Historialcreditos' => $Historialcreditos, 'contadorHistCred' => $Historialcreditos->total() ];
    }

    // Función privada para retornar query de créditos.
    private function getHIstCreditos(){
        return Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
        ->join('personal','creditos.prospecto_id','=','personal.id')
        ->join('clientes','creditos.prospecto_id','=','clientes.id')
        ->join('personal as v','clientes.vendedor_id','v.id')
        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
        ->select('creditos.id','creditos.prospecto_id','creditos.num_dep_economicos','creditos.tipo_economia',
            'creditos.nombre_primera_ref','creditos.telefono_primera_ref','creditos.celular_primera_ref',
            'creditos.nombre_segunda_ref','creditos.telefono_segunda_ref','creditos.celular_segunda_ref',
            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
            'creditos.superficie','creditos.terreno_excedente','creditos.precio_terreno_excedente',
            'creditos.promocion','creditos.descripcion_promocion','creditos.descuento_promocion','creditos.paquete',
            'creditos.descripcion_paquete','creditos.precio_venta','creditos.plazo','creditos.credito_solic',
            'creditos.costo_paquete','inst_seleccionadas.status','inst_seleccionadas.tipo_credito',
            'inst_seleccionadas.institucion','personal.nombre','personal.apellidos',
            'inst_seleccionadas.credito_id as id_credito', 'inst_seleccionadas.monto_credito',
            'inst_seleccionadas.plazo_credito', 'inst_seleccionadas.fecha_ingreso', 'inst_seleccionadas.fecha_vigencia',
            'creditos.fraccionamiento','clientes.id as prospecto_id','v.nombre as vendedor_nombre',
            'v.apellidos as vendedor_apellidos');
    }

    // Función para exportar simulaciones de crédito a excel.
    public function ExportarHistorialSimulacion (){
        $creditos = $this->getHIstCreditos();
        $creditos = $creditos->where('creditos.status','!=','1')
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

    // Función para invertir titular por coacreditado.
    public function cambiarTitularCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $credito_id = $request->id;
        $titular_id = $request->cliente_id;
        $rfc_coa = $request->rfc_coa;

        // Se busca el registro del coacreditado actual en la BD de Personal.
        $persona = Personal::select('id')->where('rfc','=',$rfc_coa)->first();
        $id_coa = $persona->id;
        // Se accede a la información del titular actual
        $titularOld = Cliente::join('personal', 'clientes.id','=','personal.id')
                        ->select('clientes.f_nacimiento_coa','clientes.homoclave_coa','clientes.direccion_coa',
                                 'clientes.colonia_coa','clientes.cp_coa','clientes.telefono_coa','clientes.celular_coa',
                                 'clientes.email_coa','clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa',
                                 'clientes.empresa_coa','clientes.lugar_nacimiento_coa','clientes.estado_coa','clientes.ciudad_coa',
                                 'clientes.nacionalidad_coa','clientes.parentesco_coa','clientes.estado','clientes.ciudad','clientes.curp',
                                 'clientes.email_institucional','clientes.tipo_casa','clientes.edo_civil','clientes.nacionalidad',
                                 'clientes.nss','clientes.publicidad_id','clientes.nombre_recomendado',
 
                                 'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc','personal.homoclave',
                                 'personal.direccion','personal.colonia','personal.colonia','personal.cp','personal.telefono',
                                 'personal.celular','personal.email')
                        ->where('personal.id','=',$titular_id)->first();

        try{
            DB::beginTransaction();
 
            // La clasificación del Titular anterior se cambia a coacreditado
            $newClienteCoa = Cliente::findOrFail($titular_id);
            $newClienteCoa->clasificacion = 7;
            

            // Los datos que estaban como coacreditado se actualizan para el nuevo titular
            // Datos tabla personal
            $newTitularP = Personal::findOrFail($id_coa);
            $newTitularP->f_nacimiento = $titularOld->f_nacimiento_coa;
            $newTitularP->homoclave = $titularOld->homoclave_coa;
            $newTitularP->direccion = $titularOld->direccion_coa;
            $newTitularP->colonia = $titularOld->colonia_coa;
            $newTitularP->cp = $titularOld->cp_coa;
            $newTitularP->telefono = $titularOld->telefono_coa;
            $newTitularP->celular = $titularOld->celular_coa;
            $newTitularP->email = $titularOld->email_coa;
            $newTitularP->save();

            // Datos tabla clientes
            $newClienteTitular = Cliente::findOrFail($id_coa);
            $newClienteTitular->clasificacion = 2;
            $newClienteTitular->edo_civil = $titularOld->edo_civil_coa;
            $newClienteTitular->nss = $titularOld->nss_coa;
            $newClienteTitular->curp = $titularOld->curp_coa;
            $newClienteTitular->empresa = $titularOld->empresa_coa;
            $newClienteTitular->coacreditado = 1;
            $newClienteTitular->lugar_nacimiento = $titularOld->lugar_nacimiento_coa;
            $newClienteTitular->estado = $titularOld->estado_coa;
            $newClienteTitular->ciudad = $titularOld->ciudad_coa;
            $newClienteTitular->nacionalidad = $titularOld->nacionalidad_coa;
            $newClienteTitular->publicidad_id = $titularOld->publicidad_id;
            if($newClienteTitular->publicidad_id == 1){
                $newClienteTitular->nombre_recomendado = $titularOld->nombre_recomendado;
            }
            else{
                $newClienteTitular->nombre_recomendado = NULL;
            }
            $newClienteTitular->nacionalidad = $titularOld->nacionalidad_coa;

            $newClienteTitular->sexo_coa = $titularOld->sexo;
            $newClienteTitular->tipo_casa_coa = $titularOld->tipo_casa;
            $newClienteTitular->email_institucional_coa = $titularOld->email_institucional;
            $newClienteTitular->empresa_coa = $titularOld->empresa;
            $newClienteTitular->edo_civil_coa = $titularOld->edo_civil;
            $newClienteTitular->nss_coa = $titularOld->nss;
            $newClienteTitular->curp_coa = $titularOld->curp;
            $newClienteTitular->nombre_coa = $titularOld->nombre;
            $newClienteTitular->apellidos_coa = $titularOld->apellidos;
            $newClienteTitular->f_nacimiento_coa = $titularOld->f_nacimiento;
            $newClienteTitular->nacionalidad_coa = $titularOld->nacionalidad;
            $newClienteTitular->lugar_nacimiento_coa = $newClienteCoa->lugar_nacimiento;
            $newClienteTitular->rfc_coa = $titularOld->rfc;
            $newClienteTitular->homoclave_coa = $titularOld->homoclave;
            $newClienteTitular->direccion_coa = $titularOld->direccion;
            $newClienteTitular->colonia_coa = $titularOld->colonia;
            $newClienteTitular->ciudad_coa = $titularOld->ciudad;
            $newClienteTitular->estado_coa = $titularOld->estado;
            $newClienteTitular->cp_coa = $titularOld->cp;
            $newClienteTitular->telefono_coa = $titularOld->telefono;
            $newClienteTitular->celular_coa = $titularOld->celular;
            $newClienteTitular->email_coa = $titularOld->email;
            $newClienteTitular->parentesco_coa = $newClienteCoa->parentesco_coa;

            $credito = Credito::findOrFail($credito_id);
            $institucion = inst_seleccionada::select('tipo_credito','institucion')
                        ->where('credito_id','=',$credito->id)
                        ->where('elegido','=',1)->first();

            // Se crea un registro en reubicaciones para indicar el cambio de titular.
            $reubicacion = new ReubicacionController();
            $reubicacion->createReubicacion(
                                $credito->id,
                                $credito->lote_id,
                                $credito->prospecto_id,
                                $credito->vendedor_id,
                                $credito->promocion,
                                $institucion->tipo_credito,
                                $institucion->institucion,
                                $credito->valor_terreno,
                                'Se cambia de titular por '.$newTitularP->nombre.' '.$newTitularP->apellidos,
                                ''
                            );
            
            $credito->prospecto_id = $id_coa;

            // Se agrega comentario al nuevo coacreditado
            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $titular_id;
            $observacion->comentario = "Se cambió a coacreditado";
            $observacion->usuario = Auth::user()->usuario;
            // Se agrega comentario al nuevo titular
            $observacion2 = new Cliente_observacion();
            $observacion2->cliente_id = $id_coa;
            $observacion2->comentario = "Se cambió a titular";
            $observacion2->usuario = Auth::user()->usuario;

            $newClienteCoa->save();
            $newClienteTitular->save();
            $credito->save();
            $observacion->save();
            $observacion2->save();

            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }

    }

    // Función para actualizar la fecha de vigencia para el financiamiento
    public function updateFechaVigencia (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $inst = inst_seleccionada::select('id')
                ->where('credito_id','=',$request->folio)
                ->where('elegido','=',1)
                ->get();
        
        $instSel = inst_seleccionada::findOrFail($inst[0]->id);
        $instSel->fecha_vigencia = $request->fecha_vigencia;
        $instSel->save();
    }

    // Función para insertar el costo del descuento para la venta.
    public function updateCostoDescuento(Request $request){
        $creditos = Credito::findOrFail($request->id);
        $creditos->costo_descuento = $request->monto;
        $creditos->save();
    }

    // Función para insertar el costo del descuento para el terreno.
    public function updateDescuentoTerreno(Request $request){
        $creditos = Credito::findOrFail($request->id);
        $creditos->descuento_terreno = $request->monto;
        $creditos->save();
    }

    // Función para insertar el costo de la alarma para el lote
    public function updateCostoAlarma(Request $request){
        $creditos = Credito::findOrFail($request->id);
        $creditos->costo_alarma = $request->monto;
        $creditos->save();
    }

    // Función para insertar el costo de cuota de mantenimiento
    public function updateCostoCuotaMant(Request $request){
        $creditos = Credito::findOrFail($request->id);
        $creditos->costo_cuota_mant = $request->monto;
        $creditos->save();
    }

    // Función para insertar costo de protecciones en la casa
    public function updateCostoProtecciones(Request $request){
        $creditos = Credito::findOrFail($request->id);
        $creditos->costo_protecciones = $request->monto;
        $creditos->save();
    }

    /*  Función para obtener el  estado de cuenta del terreno para ventas
        por alianza
    */
    public function indexEdoCuentaTerreno(Request $request){
        // Se obtienen los contratos de alianza firmados o pendientes.
        $contratos = $this->edoCuentaActivos($request);
        $contratos = $contratos->paginate(10);

        foreach ($contratos as $key => $contrato) {
            $contrato->pendiente = 0;
            if($contrato->saldo <= 0){
                // Se verifica si existe algun deposito sin transpasar a cumbres.
                $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                            ->select('depositos.id')
                            ->where('depositos.monto_terreno','!=',0)
                            ->where('depositos.fecha_ingreso_concretania','=',NULL)
                            ->where('pagos_contratos.contrato_id','=',$contrato->id)->get();
                
                $dep_creditos = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                            //->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                            ->select('dep_creditos.id')
                            ->where('dep_creditos.monto_terreno','!=',0)
                            ->where('dep_creditos.fecha_ingreso_concretania','=',NULL)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.credito_id','=',$contrato->id)
                            ->get();

                if(sizeof($dep_creditos) == 0 && sizeof($depositos) == 0)
                    $contrato->pendiente = 1;
            }
        }

        return [
            'pagination' => [
                'total'         => $contratos->total(),
                'current_page'  => $contratos->currentPage(),
                'per_page'      => $contratos->perPage(),
                'last_page'     => $contratos->lastPage(),
                'from'          => $contratos->firstItem(),
                'to'            => $contratos->lastItem(),
            ],
            'contratos'=>$contratos
        ];
    }

    // Función privada para obtener los contratos por alianza firmados o pendientes.
    private function edoCuentaActivos(Request $request){
        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->leftJoin('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->select('contratos.id','creditos.saldo_terreno','creditos.precio_venta',
                            'creditos.valor_terreno as monto_terreno', 'contratos.status',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana',
                            'expedientes.valor_escrituras','contratos.saldo','creditos.lote_id',
                            'creditos.num_lote','personal.nombre','personal.apellidos');

        if($request->buscar != '')                            
            switch($request->criterio){
                case 'cliente':{
                    $contratos = $contratos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                    break;
                }
                case 'fraccionamiento':{
                    $contratos = $contratos->where('lotes.fraccionamiento_id','=',$request->buscar);
                    if($request->etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id','=',$request->etapa);
                    if($request->manzana != '')
                        $contratos = $contratos->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                    if($request->lote != '')
                        $contratos = $contratos->where('lotes.num_lote','=',$request->lote);
                    break;
                }
            }

        $contratos = $contratos->where('lotes.emp_constructora','=','CONCRETANIA')
                    ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                    ->where('contratos.status','!=',0)
                    ->where('contratos.status','!=',2)
                    ->whereRaw('creditos.valor_terreno > creditos.saldo_terreno')
                    ->orderBy('contratos.id');

        return $contratos;
    }

    // Función privada para obtener los contratos por alianza cancelados.
    private function edoCuentaCancelados(Request $request){
        // Se obtienen las cuentas cumbres
        $cuentas = $this->getCuentas();

        //Query para contratos cancelados
        $cancelados = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->leftJoin('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->select('contratos.id','creditos.saldo_terreno','creditos.precio_venta',
                            'creditos.valor_terreno as monto_terreno', 'contratos.status',
                            'creditos.lote_id',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana',
                            'expedientes.valor_escrituras', 'inst_seleccionadas.tipo_credito',
                            'expedientes.liquidado','expedientes.fecha_firma_esc',
                            'creditos.num_lote','personal.nombre','personal.apellidos');

        if($request->buscar != '')                            
            switch($request->criterio){
                case 'cliente':{
                    $cancelados = $cancelados->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                    break;
                }
                case 'fraccionamiento':{
                    $cancelados = $cancelados->where('lotes.fraccionamiento_id','=',$request->buscar);
                    if($request->etapa != '')
                        $cancelados = $cancelados->where('lotes.etapa_id','=',$request->etapa);
                    if($request->manzana != '')
                        $cancelados = $cancelados->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                    if($request->lote != '')
                        $cancelados = $cancelados->where('lotes.num_lote','=',$request->lote);
                    break;
                }
            }
        
        $cancelados = $cancelados->where('lotes.emp_constructora','=','CONCRETANIA')
                    ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                    ->where('contratos.status','=',0)
                    // con saldo por alianza transferido.
                    ->where('creditos.saldo_terreno','!=',0)
                    ->where('inst_seleccionadas.elegido', '=', '1')
                    ->orderBy('contratos.id')->get();

        if(sizeof($cancelados)){
            foreach ($cancelados as $key => $cancelado) {  
                $cancelado->devuelto = 0; 
                $cancelado->devueltoVirtual = 0;
                $cancelado->transferido = 0;

                //Se obtienen las devoluciones que salieron por cuenta de cumbres
                $devoluciones = Devolucion::whereIn('devoluciones.cuenta',$cuentas)
                                ->where('devoluciones.contrato_id','=',$cancelado->id)
                                ->get();

                // Se obtienen las devoluciones virtuales para ese contrato
                $dev_virtuales = Dev_virtual::where('contrato_id','=',$cancelado->id)
                                ->get();

                // Depositos transferidos a cumbres.
                $depositos_pagado = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                ->select('depositos.id','depositos.fecha_ingreso_concretania','depositos.cuenta',
                        'depositos.monto_terreno','depositos.num_recibo')
                ->where('pagos_contratos.contrato_id','=',$cancelado->id)
                ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                ->where('depositos.lote_id','=',$cancelado->lote_id)
                ->get();

                
                $depositoGCC = Deposito_gcc::select('id','fecha as fecha_ingreso_concretania',
                        'cuenta','monto as monto_terreno','cheque as num_recibo')
                    ->where('depositos_gcc.contrato_id','=',$cancelado->id)
                    ->where('depositos_gcc.lote_id','=',$cancelado->lote_id)
                    ->get();

                // Se realiza la sumatoria de los resultados
                if(sizeof($dev_virtuales)){
                    $cancelado->dev_virtuales = $dev_virtuales;
                    foreach ($dev_virtuales as $key => $dev) {
                        $cancelado->devueltoVirtual += $dev->monto;
                    }
                }

                if(sizeof($devoluciones)){
                    $cancelado->devoluciones = $devoluciones;
                    foreach ($devoluciones as $key => $dev) {
                        $cancelado->devuelto += $dev->devolver;
                    }
                }

                if(sizeof($depositos_pagado)){
                        $cancelado->depositos_transferidos = collect($depositos_pagado)->merge(collect($depositoGCC));
                    foreach($depositos_pagado as $key => $deposito) {
                        $cancelado->transferido += $deposito->monto_terreno;
                    }
                }

                // Depositos reubicados a cumbres
                $depositoGCC = Deposito_gcc::select(DB::raw("SUM(depositos_gcc.monto) as suma"))
                    ->where('depositos_gcc.contrato_id','=',$cancelado->id)
                    ->where('depositos_gcc.lote_id','=',$cancelado->lote_id)
                    ->get();
                    if($depositoGCC[0]->suma == NULL){
                        $depositoGCC[0]->suma = 0;
                    }

                // Depositos reubicados a concretania para sumatoria
                $depositoConc = Deposito_conc::select(DB::raw("SUM(depositos_conc.monto) as suma"))
                    ->where('depositos_conc.contrato_id','=',$cancelado->id)
                    ->where('depositos_conc.lote_id','=',$cancelado->lote_id)
                    ->where('depositos_conc.devolucion','=',1)
                    ->get();
                    if($depositoConc[0]->suma == NULL){
                        $depositoConc[0]->suma = 0;
                    }

                // Depositos reubicados a concretania
                $depositoConcTransf = Deposito_conc::select('id','fecha',
                            'cuenta','monto','cheque')
                    ->where('depositos_conc.contrato_id','=',$cancelado->id)
                    ->where('depositos_conc.lote_id','=',$cancelado->lote_id)
                    ->where('depositos_conc.devolucion','=',1)
                    ->get();

                    if(sizeof($depositoConcTransf)){
                        $cancelado->depositoConcTransf = $depositoConcTransf;
                    }
                    
                

                    $cancelado->gcc = $depositoGCC[0]->suma;
                    $cancelado->transferido =  $cancelado->transferido +  $cancelado->gcc;

                    $cancelado->conc =  $depositoConc[0]->suma;
                }

                
        }

        return $cancelados;
    }

    // Función privada para obtener los contratos por alianza liquidados.
    private function edoCuentaLiquidados(Request $request){
        $liquidados = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->leftJoin('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->select('contratos.id','creditos.saldo_terreno','creditos.precio_venta',
                            'creditos.valor_terreno as monto_terreno', 'contratos.status',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana',
                            'expedientes.valor_escrituras', 'inst_seleccionadas.tipo_credito',
                            'expedientes.liquidado','expedientes.fecha_firma_esc',
                            'creditos.num_lote','personal.nombre','personal.apellidos');

        if($request->buscar != '')                            
            switch($request->criterio){
                case 'cliente':{
                    $liquidados = $liquidados->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                    break;
                }
                case 'fraccionamiento':{
                    $liquidados = $liquidados->where('lotes.fraccionamiento_id','=',$request->buscar);
                    if($request->etapa != '')
                        $liquidados = $liquidados->where('lotes.etapa_id','=',$request->etapa);
                    if($request->manzana != '')
                        $liquidados = $liquidados->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                    if($request->lote != '')
                        $liquidados = $liquidados->where('lotes.num_lote','=',$request->lote);
                    break;
                }
            }

        $liquidados = $liquidados->where('lotes.emp_constructora','=','CONCRETANIA')
                    ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                    ->where('contratos.status','!=',0)
                    ->where('contratos.status','!=',2)
                    ->where('inst_seleccionadas.elegido', '=', '1')
                    ->where('creditos.saldo_terreno', '>', 0)
                    ->whereRaw('creditos.valor_terreno = creditos.saldo_terreno')
                    ->orderBy('contratos.id')->get();

        if(sizeof($liquidados)){
            foreach ($liquidados as $key => $liquidado) {
                $liquidado->devuelto = 0;
                if($liquidado->tipo_credito == 'Crédito Directo' && $liquidado->liquidado == 1 || 
                    $liquidado->tipo_credito != 'Crédito Directo' && $liquidado->fecha_firma_esc != NULL
                )
                    $liquidado->status = 4;
            }
        }

        return $liquidados;
    }

    // Funcion para exportar el estado de cuenta de ventas por alianza para saldo de terreno activos a excel
    public function indexEdoCuentaTerrenoExcel(Request $request){

        $contratos = $this->edoCuentaActivos($request);

        $contratos = $contratos->get();
                    
        return Excel::create('Relación de los ingresos de Concretania', function($excel) use ($contratos){
            $excel->sheet('Estado de cuenta', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    'Fraccionamiento', 'Etapa', 'Manzana', '# Lote', 'Cliente', 'Valor de venta', 'Valor de escrituracion',
                    'Valor de terreno', 'Total pagado', 'Por pagar', 'Estatus'
                ]);

                $sheet->cells('A1:K1', function ($cells) {
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
                    'F' => '$#,##0.00',
                    'G' => '$#,##0.00',
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                ));

                foreach($contratos as $index => $contrato) {

                    if($contrato->status == 1)
                        $contrato->status = "Pendiente";
                    elseif($contrato->status == 3)
                        $contrato->status = "Firmado";
                    else
                        $contrato->status = "";
                   
                    $cont++;
                    
                    $sheet->row($index+2, [
                        $contrato->fraccionamiento,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->nombre.' '.$contrato->apellidos,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->monto_terreno,
                        $contrato->saldo_terreno,
                        $contrato->monto_terreno-$contrato->saldo_terreno,
                        $contrato->status,
                    ]);	
                }
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        })->download('xls');
    }

    // Funcion para exportar el estado de cuenta de ventas por alianza para saldo de terreno cancelados a excel
    public function indexTerrenosCerrados(Request $request){
        $cancelados = $this->edoCuentaCancelados($request);
        $liquidados = $this->edoCuentaLiquidados($request);

        $cerrados = collect($cancelados)->merge(collect($liquidados));

        return $cerrados;
        // return ['cancelados' => $cancelados,
        //         'liquidados' => $liquidados
        //     ];
    }

}
