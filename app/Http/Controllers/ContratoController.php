<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotifyAdmin;
use App\Mail\NotificationReceived;
use App\Http\Controllers\ReubicacionController;
use Carbon\Carbon;
use App\Credito;
use App\Contrato;
use App\Pago_contrato;
use App\Apartado;
use App\Pagos_lotes;
use App\Cotizacion_lotes;
use App\datos_calc_lotes;
use App\Precio_etapa;
use App\Precio_modelo;
use App\Sobreprecio_modelo;
use App\Solic_equipamiento;
use App\inst_seleccionada;
use App\Cliente;
use App\Personal;
use App\Vendedor;
use App\Licencia;
use App\Expediente;
use App\Gasto_admin;
use App\Deposito;
use App\Dep_credito;
use App\Deposito_gcc;
use App\Deposito_conc;
use App\Precios_terreno;
use App\Lote;
use App\Modelo;
use App\Cuenta;
use App\Avaluo;
use App\User;
use NumerosEnLetras;
use DB;
use Auth;
use Excel;
use File;

// Controlador para contratos.
class ContratoController extends Controller
{
    // Función para retornar los contratos registrados.
    public function indexContrato(Request $request)
    {
        // Se verifica que la petición sea ajax
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $b_modelo = $request->b_modelo;
        $b_status = $request->b_status;
        $f_ini = $request->f_ini;
        $f_fin = $request->f_fin;
        $publicidad = $request->publicidad;

        //Llamada a la función privada que retorna la query
        $contratos = $this->getContratos();
        //Muestra la institución de financiamiento elegida.
        $contratos = $contratos->where('inst_seleccionadas.elegido', '=', '1');

        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        if($b_status != '')
            $contratos= $contratos->where('contratos.status','=',$b_status);
        if(Auth::user()->rol_id == 2)
            $contratos= $contratos->where('creditos.vendedor_id', '=', Auth::user()->id);

        if($request->b_empresa_cliente != '')
            $contratos= $contratos->where('clientes.empresa', 'like', '%'.$request->b_empresa_cliente.'%');

        if($request->b_institucion != '')
            $contratos= $contratos->where('inst_seleccionadas.institucion', 'like', '%'.$request->b_institucion.'%');
        
        if($buscar != '') {
            switch ($criterio) {
                case 'personal.nombre': {
                    $contratos = $contratos
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'v.nombre': {
                    $contratos = $contratos
                        ->where(DB::raw("CONCAT(v.nombre,' ',v.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'inst_seleccionadas.tipo_credito': {
                    $contratos = $contratos
                        ->where($criterio, 'like', '%' . $buscar . '%');
                    break;
                }
                case 'creditos.id': {
                    $contratos = $contratos
                        ->where($criterio, 'like', '%' . $buscar . '%');
                    break;
                }
                case 'creditos.vendedor_id': {
                    $contratos = $contratos
                        ->where($criterio, '=',$buscar);
                        if($buscar3 != "")
                            $contratos = $contratos->where('lotes.fraccionamiento_id','=',$buscar3);
                        if($b_etapa != "")
                            $contratos = $contratos->where('lotes.etapa_id','=',$b_etapa);
                        if($f_ini !='' && $f_fin !='')
                            $contratos = $contratos->whereBetween('contratos.fecha', [$f_ini, $f_fin]);
                    break;
                }
                case 'contratos.fecha': {
                    $contratos = $contratos
                        ->whereBetween($criterio, [$buscar, $buscar3]);
                    break;
                }
                case 'contratos.fecha_status': {
                    $contratos = $contratos
                        ->whereBetween($criterio, [$buscar,  $buscar3]);
                    break;
                }
                case 'creditos.fraccionamiento': {
                    
                    $contratos = $contratos->where('lotes.fraccionamiento_id', '=',  $buscar);

                        if($publicidad != '')
                            $contratos  = $contratos->where('contratos.publicidad_id', '=',  $publicidad);
                        if($b_modelo != '')
                            $contratos  = $contratos->where('lotes.modelo_id', '=',  $b_modelo);
                        if($b_etapa != '')
                            $contratos  = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                        if($b_manzana != '')
                            $contratos  = $contratos->where('lotes.manzana', '=', $b_manzana);
                        if($b_lote != '')
                            $contratos  = $contratos->where('lotes.num_lote', '=', $b_lote);
                        if($f_ini != '' && $f_fin != '')
                            $contratos  = $contratos->whereBetween('contratos.fecha', [$f_ini, $f_fin]);

                    break;
                }  
            }
        } 

        $contratos = $contratos->orderBy('id', 'desc')->paginate(20);
        
        if(sizeOf($contratos)){
            //Se recorren los resultados de contratos para verificar si la casa se ha individualizado.
            foreach($contratos as $index => $contrato) 
            {
                if($contrato->tipo_credito == 'Crédito Directo' && $contrato->liquidado == 1){
                    $contrato->status2 = 1;
                }
                elseif($contrato->tipo_credito != 'Crédito Directo' && $contrato->fecha_firma_esc != NULL){
                    $contrato->status2 = 1;
                }
                else{
                    $contrato->status2 = 0;
                }

                $expediente = Expediente::select('liquidado','fecha_firma_esc')->where('id','=',$contrato->id)->get();

                if(sizeOf($expediente)){
                    $contrato->liquidado = $expediente[0]->liquidado;
                    $contrato->fecha_firma_esc = $expediente[0]->fecha_firma_esc;
                }
                
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
            ], 'contratos' => $contratos, 'contadorContrato' => $contratos->total()
        ];
    }

    // Función privada para obtener la query de contratos.
    private function getContratos()
    {
        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as v', 'creditos.vendedor_id', 'v.id')
                
                ->select(
                    'creditos.id',
                    'creditos.prospecto_id',
                    'creditos.num_dep_economicos',
                    'creditos.tipo_economia',
                    'creditos.nombre_primera_ref',
                    'creditos.telefono_primera_ref',
                    'creditos.celular_primera_ref',
                    'creditos.nombre_segunda_ref',
                    'creditos.telefono_segunda_ref',
                    'creditos.celular_segunda_ref',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.valor_terreno',
                    'lotes.sobreprecio',
                    'creditos.modelo',
                    'creditos.precio_base',
                    'creditos.superficie',
                    'creditos.terreno_excedente',
                    'creditos.precio_terreno_excedente',
                    'creditos.promocion',
                    'creditos.descripcion_promocion',
                    'creditos.descuento_promocion',
                    'creditos.paquete',
                    'creditos.descripcion_paquete',
                    'creditos.precio_venta',
                    'creditos.plazo',
                    'creditos.credito_solic',
                    'creditos.costo_paquete',
                    'inst_seleccionadas.tipo_credito',
                    'inst_seleccionadas.id as inst_credito',
                    'creditos.precio_obra_extra',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.lote_id',
                    
                    'creditos.email_fisc',
                    'creditos.tel_fisc',
                    'creditos.nombre_fisc',
                    'creditos.direccion_fisc', 'creditos.col_fisc',
                    'creditos.cp_fisc',
                    'creditos.rfc_fisc',
                    'creditos.archivo_fisc',
                    'cfi_fisc',
                    'creditos.regimen_fisc',
                    'creditos.banco_fisc',
                    'creditos.num_cuenta_fisc',
                    'creditos.clabe_fisc',

                    'contratos.publicidad_id as publicidadId','medios_publicitarios.nombre as publicidad',
                        'clientes.nombre_recomendado',

                    'inst_seleccionadas.institucion',
                    'personal.nombre',
                    'personal.apellidos',
                    'personal.telefono',
                    'personal.celular',
                    'personal.clv_lada',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'personal.f_nacimiento',
                    'personal.rfc',
                    'personal.homoclave',
                    'creditos.fraccionamiento',
                    'clientes.id as prospecto_id',
                    'clientes.edo_civil',
                    'clientes.nss',
                    'clientes.curp',
                    'clientes.empresa',
                    'clientes.coacreditado',
                    'clientes.estado',
                    'clientes.ciudad',
                    'clientes.puesto',
                    'clientes.nacionalidad',
                    'clientes.sexo',
                    'clientes.sexo_coa',
                    'clientes.email_institucional_coa',
                    'clientes.empresa_coa',
                    'clientes.edo_civil_coa',
                    'clientes.nss_coa',
                    'clientes.curp_coa',
                    'clientes.nombre_coa',
                    'clientes.apellidos_coa',
                    'clientes.f_nacimiento_coa',
                    'clientes.nacionalidad_coa',
                    'clientes.rfc_coa',
                    'clientes.homoclave_coa',
                    'clientes.direccion_coa',
                    'clientes.colonia_coa',
                    'clientes.ciudad_coa',
                    'clientes.estado_coa',
                    'clientes.cp_coa',
                    'clientes.telefono_coa',
                    'clientes.ext_coa',
                    'clientes.celular_coa',
                    'clientes.email_coa',
                    'clientes.parentesco_coa',
                    'clientes.lugar_nacimiento_coa',
                    'v.nombre as vendedor_nombre',
                    'v.apellidos as vendedor_apellidos',

                    'contratos.id as contratoId',
                    'contratos.infonavit',
                    'contratos.fovisste',
                    'contratos.comision_apertura',
                    'clientes.lugar_nacimiento',
                    'contratos.investigacion',
                    'contratos.avaluo',
                    'contratos.prima_unica',
                    'contratos.escrituras',
                    'contratos.credito_neto',
                    'contratos.status',
                    'contratos.fecha_status',
                    'contratos.avaluo_cliente',
                    'contratos.fecha',
                    'contratos.direccion_empresa',
                    'contratos.cp_empresa',
                    'contratos.colonia_empresa',
                    'contratos.estado_empresa',
                    'contratos.ciudad_empresa',
                    'contratos.telefono_empresa',
                    'contratos.ext_empresa',
                    'contratos.direccion_empresa_coa',
                    'contratos.cp_empresa_coa',
                    'contratos.colonia_empresa_coa',
                    'contratos.estado_empresa_coa',
                    'contratos.ciudad_empresa_coa',
                    'contratos.telefono_empresa_coa',
                    'contratos.ext_empresa_coa',
                    'contratos.total_pagar',
                    'contratos.detenido',
                    'contratos.monto_total_credito',
                    'contratos.enganche_total',
                    'contratos.avance_lote',
                    'contratos.observacion',
                    'contratos.exp_bono',
                    'lotes.fraccionamiento_id',
                    'lotes.sublote'
        );
        return $contratos;
    }

    // Función para obtener las simulacipnes de creditos aprobados sin contrato.
    public function indexCreditosAprobados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        $creditos = Credito::join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.precio_venta',
                'creditos.plazo',
                'creditos.credito_solic',
                'creditos.status',
                'inst_seleccionadas.tipo_credito',
                'inst_seleccionadas.id as inst_credito',
                'inst_seleccionadas.institucion',
                'personal.nombre',
                'personal.apellidos',
                'creditos.fraccionamiento as proyecto',
                'clientes.id as prospecto_id',
                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos'
            )
            ->where('creditos.status', '=', '2')
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->where('creditos.contrato', '=', '0');
        
        if($buscar != '') {
            switch ($criterio) {
                case 'personal.nombre': {
                    $creditos = 
                        $creditos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'v.nombre': {
                    $creditos = $creditos->where(DB::raw("CONCAT(v.nombre,' ',v.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'creditos.fraccionamiento': {
                    $creditos = $creditos
                        ->where('lotes.fraccionamiento_id', '=',  $buscar);
                        if ($b_etapa != '' )
                            $creditos = $creditos->where('lotes.etapa_id', '=', $b_etapa);
                        if ($b_manzana != '')
                            $creditos = $creditos->where('lotes.manzana', '=', $b_manzana);
                        if ($b_lote != '')
                            $creditos = $creditos->where('lotes.num_lote', '=', $b_lote);
                    break;
                }
                default:{
                    $creditos = $creditos
                        ->where($criterio, 'like', '%' . $buscar . '%');
                    break;
                }
            }
        }

        $creditos = $creditos->orderBy('id', 'desc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ], 'creditos' => $creditos
        ];
    }

    // Funcion para obtener los datos de la simulación
    public function getDatosCredito(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $folio = $request->folio;
        $creditos = Credito::join('datos_extra', 'creditos.id', '=', 'datos_extra.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('inst_seleccionadas','creditos.id','inst_seleccionadas.credito_id')
            ->select(
                'lotes.sobreprecio', 'lotes.fraccionamiento_id', 'creditos.id',
                'creditos.prospecto_id', 'creditos.num_dep_economicos', 'creditos.tipo_economia',
                'creditos.nombre_primera_ref', 'creditos.telefono_primera_ref', 'creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref', 'creditos.telefono_segunda_ref', 'creditos.celular_segunda_ref',
                'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 'creditos.modelo',
                'creditos.precio_base', 'creditos.precio_obra_extra', 'creditos.superficie',
                'creditos.terreno_excedente', 'creditos.precio_terreno_excedente', 'creditos.promocion',
                'creditos.descripcion_promocion', 'creditos.descuento_promocion', 'creditos.paquete',
                'creditos.descripcion_paquete', 'creditos.precio_venta', 'creditos.plazo',
                'creditos.credito_solic', 'creditos.lote_id', 'creditos.fraccionamiento as proyecto',
                'creditos.costo_paquete', 'creditos.status', 'personal.nombre', 'personal.apellidos',
                'clientes.sexo', 'clientes.nombre_recomendado', 'clientes.publicidad_id as publicidadId',
                'personal.telefono', 'personal.celular', 'personal.email',
                'personal.direccion', 'personal.cp', 'personal.clv_lada',
                'personal.colonia', 'clientes.ciudad', 'clientes.estado',
                'personal.f_nacimiento', 'clientes.nacionalidad', 'clientes.curp',
                'personal.rfc', 'personal.homoclave', 'clientes.nss',
                'clientes.empresa', 'clientes.puesto', 'clientes.email_institucional',
                'clientes.tipo_casa', 'clientes.edo_civil', 'clientes.coacreditado',
                'clientes.nombre_coa', 'clientes.apellidos_coa', 'clientes.f_nacimiento_coa',
                'clientes.rfc_coa', 'clientes.homoclave_coa', 'clientes.direccion_coa',
                'clientes.cp_coa', 'clientes.colonia_coa', 'clientes.estado_coa',
                'clientes.ciudad_coa', 'clientes.celular_coa', 'clientes.telefono_coa',
                'clientes.email_coa', 'clientes.email_institucional_coa', 'clientes.parentesco_coa',
                'clientes.empresa_coa', 'clientes.curp_coa', 'clientes.nss_coa',
                'clientes.nacionalidad_coa',
                'clientes.lugar_nacimiento',
                'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                'inst_seleccionadas.elegido'
            )
            ->where('inst_seleccionadas.elegido', '=', 1)
            ->where('creditos.id', '=', $folio)->get();

        return ['creditos' => $creditos];
    }

    // Función para actualizar los datos almacenados en la tabla Creditos
    public function updateDatosCredito(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        //datos del cliente que se guardan en la tabla personal
        $personal = Personal::findOrFail($request->prospecto_id);
        $personal->apellidos = $request->apellidos;
        $personal->nombre = $request->nombre;
        $personal->f_nacimiento = $request->f_nacimiento;
        $personal->rfc = $request->rfc;
        $personal->homoclave = $request->homoclave;
        $personal->direccion = $request->direccion;
        $personal->cp = $request->cp;
        $personal->colonia = $request->colonia;
        $personal->telefono = $request->telefono;
        $personal->clv_lada = $request->clv_lada;
        $personal->celular = $request->celular;
        $personal->email = $request->email;

        $cliente = Cliente::findOrFail($request->prospecto_id);
        $cliente->sexo = $request->sexo;
        $cliente->email_institucional = $request->email_institucional;
        $cliente->edo_civil = $request->edo_civil;
        $cliente->nss = $request->nss;
        $cliente->curp = $request->curp;
        $cliente->empresa = $request->empresa;
        $cliente->coacreditado = $request->coacreditado;
        $cliente->ciudad = $request->ciudad;
        $cliente->estado = $request->estado;
        $cliente->nacionalidad = $request->nacionalidad;
        $cliente->puesto = $request->puesto;
        $cliente->sexo_coa = $request->sexo_coa;
        $cliente->direccion_coa = $request->direccion_coa;
        $cliente->email_institucional_coa = $request->email_institucional_coa;
        $cliente->edo_civil_coa = $request->edo_civil_coa;
        $cliente->nss_coa = $request->nss_coa;
        $cliente->curp_coa = $request->curp_coa;
        $cliente->nombre_coa = $request->nombre_coa;
        $cliente->apellidos_coa = $request->apellidos_coa;
        $cliente->f_nacimiento_coa = $request->f_nacimiento_coa;
        $cliente->colonia_coa = $request->colonia_coa;
        $cliente->cp_coa = $request->cp_coa;
        $cliente->rfc_coa = $request->rfc_coa;
        $cliente->homoclave_coa = $request->homoclave_coa;
        $cliente->ciudad_coa = $request->ciudad_coa;
        $cliente->estado_coa = $request->estado_coa;
        $cliente->empresa_coa = $request->empresa_coa;
        $cliente->nacionalidad_coa = $request->nacionalidad_coa;
        $cliente->telefono_coa = $request->telefono_coa;
        $cliente->celular_coa = $request->celular_coa;
        $cliente->email_coa = $request->email_coa;
        $cliente->parentesco_coa = $request->parentesco_coa;
        $cliente->lugar_nacimiento = $request->lugar_nacimiento;
        $cliente->publicidad_id = $request->publicidad_id;
        $cliente->nombre_recomendado = $request->nombre_recomendado;

        $credito = Credito::findOrFail($request->id);
        $credito->num_dep_economicos =  $request->num_dep_economicos;
        $credito->credito_solic = $request->credito_solic;
        $credito->contrato = 1;

        $inst_sel = inst_seleccionada::select('id')
                    ->where('credito_id','=',$request->id)
                    ->where('elegido','=',1)->get();
        
        $credito_sol = inst_seleccionada::findOrFail($inst_sel[0]->id);
        $credito_sol->monto_credito = $request->credito_solic;
        $credito_sol->save();

        $lote = Lote::findOrFail($request->lote_id);
        $lote->contrato = 1;

        //////
            //Calcular porcentaje correspondiente de terreno y construcción
            $loteId = $credito->lote_id;
            //Guardar el costo del lote
            $etapa = Lote::join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->select('etapas.id', 'lotes.terreno','etapas.terreno_m2 as etapaTerreno','modelos.tipo','lotes.indivisos')
                        ->where('lotes.id', '=', $loteId)->get();

                //Precio del terreno por m2
                $precioT = Precios_terreno::where('etapa_id', '=', $etapa[0]->id)
                    ->where('estatus', '=', 1)
                ->first();

                if($precioT){
                    //Departamento
                    if($etapa[0]->tipo == 2)
                        $credito->valor_terreno = ($etapa[0]->etapaTerreno*($etapa[0]->indivisos/100))*$precioT->precio_m2;
                    //Casa
                    else
                        $credito->valor_terreno = ($precioT->precio_m2* $etapa[0]->terreno) + $precioT->total_gastos;
                    //Calculo del procentaje.
                    $credito->porcentaje_terreno = ((($credito->valor_terreno)*100)/$credito->precio_venta);
                    
                }
            //Guardar el costo del lote
            $credito->save();
        /////
        $lote->save();
        $credito->save();
        $personal->save();
        $cliente->save();
    }

    // Función para crear un contrato.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $id = $request->id;
        //Se obtiene el avance de construcción del lote.
        $lote = Licencia::select('avance')
            ->where('id', '=', $request->lote_id)->first();
        
        try {
            DB::beginTransaction();
            $contrato = new Contrato();
            $contrato->id = $request->id;
            $contrato->infonavit = $request->infonavit;
            $contrato->fovisste = $request->fovisste;
            $contrato->comision_apertura = $request->comision_apertura;
            $contrato->investigacion = $request->investigacion;
            $contrato->avaluo = $request->avaluo;
            $contrato->prima_unica = $request->prima_unica;
            $contrato->escrituras = $request->escrituras;
            $contrato->credito_neto = $request->credito_neto;
            $contrato->avaluo_cliente = $request->avaluo_cliente;
            $contrato->fecha = $request->fecha;
            $contrato->direccion_empresa = $request->direccion_empresa;
            $contrato->cp_empresa = $request->cp_empresa;
            $contrato->colonia_empresa = $request->colonia_empresa;
            $contrato->estado_empresa = $request->estado_empresa;
            $contrato->ciudad_empresa = $request->ciudad_empresa;
            $contrato->telefono_empresa = $request->telefono_empresa;
            $contrato->ext_empresa = $request->ext_empresa;
            $contrato->direccion_empresa_coa = $request->direccion_empresa_coa;
            $contrato->cp_empresa_coa = $request->cp_empresa_coa;
            $contrato->colonia_empresa_coa = $request->colonia_empresa_coa;
            $contrato->estado_empresa_coa = $request->estado_empresa_coa;
            $contrato->ciudad_empresa_coa = $request->ciudad_empresa_coa;
            $contrato->telefono_empresa_coa = $request->telefono_empresa_coa;
            $contrato->ext_empresa_coa = $request->ext_empresa_coa;
            $contrato->total_pagar = $request->total_pagar;
            $contrato->monto_total_credito = $request->monto_total_credito;
            $contrato->enganche_total = $request->enganche_total;
            $contrato->avance_lote = $lote->avance;
            $contrato->observacion = $request->observacion;

            $credito = Credito::findOrFail($request->id);

            $cliente = Cliente::findOrFail($credito->prospecto_id);
            $contrato->publicidad_id = $cliente->publicidad_id;

            $vendedor_aux = Cliente::join('personal','clientes.vendedor_aux','=','personal.id')
                        ->select('personal.nombre','personal.apellidos')
                        ->where('clientes.id','=',$credito->prospecto_id)->first();

            if($vendedor_aux)
                $contrato->vendedor_aux = $vendedor_aux->nombre.' '.$vendedor_aux->apellidos;
            
            $vendedor = Vendedor::findOrFail($credito->vendedor_id);
            $contrato->saldo = $request->precio_venta;
            
            $credito->paquete =  $request->paquete;
            $credito->descripcion_paquete = $request->descripcion_paquete;
            $credito->costo_paquete = $request->costo_paquete;
            $credito->precio_venta = $request->precio_venta;
            
            $loteId = $credito->lote_id;
            //Calcular porcentaje correspondiente de terreno y construcción
                $etapa = Lote::join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->select('etapas.id', 'lotes.terreno','etapas.terreno_m2 as etapaTerreno','modelos.tipo','lotes.indivisos')
                ->where('lotes.id', '=', $loteId)->get();
            //Precio del terreno por m2
                $precioT = Precios_terreno::where('etapa_id', '=', $etapa[0]->id)
                    ->where('estatus', '=', 1)
                ->first();

                if($precioT){
                    //Departamento
                    if($etapa[0]->tipo == 2)
                        $credito->valor_terreno = ($etapa[0]->etapaTerreno*($etapa[0]->indivisos/100))*$precioT->precio_m2;
                    //Casa
                    else
                        $credito->valor_terreno = ($precioT->precio_m2* $etapa[0]->terreno) + $precioT->total_gastos;
                    //Calculo de porcentaje
                    $credito->porcentaje_terreno = ((($credito->valor_terreno)*100)/$credito->precio_venta);
                    
                }
            //Guardar el costo del lote
            $credito->save();

            //Vendedor externo
            if($vendedor->tipo == 1){
                $contrato->porcentaje_exp = 100;
            }

            $contrato->save();

            $pagos = $request->data; //Pagares del contrato
            //Recorro todos los elementos
            foreach ($pagos as $ep => $det) {
                $pagos = new Pago_contrato();
                $pagos->contrato_id = $id;
                $pagos->num_pago = $ep;
                $pagos->monto_pago = $det['monto_pago'];
                $pagos->restante = $det['monto_pago'];
                $pagos->fecha_pago = $det['fecha_pago'];
                $pagos->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para retornar los pagares del contrato.
    public function listarPagos(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $pagos = Pago_contrato::select('id', 'num_pago', 'monto_pago', 'fecha_pago','restante')
            ->where('contrato_id', '=', $request->contrato_id)
            ->where('tipo_pagare','=',0)
            ->orderBy('fecha_pago', 'ASC')
            ->get();

        return ['pagos' => $pagos];
    }

    // Función privada para obtener los datos del contrato
    private function getDatosContrato($id){
        return Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'creditos.vendedor_id', 'v.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('medios_publicitarios', 'contratos.publicidad_id', '=', 'medios_publicitarios.id')
            ->select(
                'creditos.id', 'creditos.prospecto_id', 'creditos.num_dep_economicos',
                'creditos.tipo_economia', 'creditos.nombre_primera_ref', 'creditos.telefono_primera_ref',
                'creditos.celular_primera_ref', 'creditos.nombre_segunda_ref',
                'creditos.telefono_segunda_ref', 'creditos.celular_segunda_ref',
                'creditos.etapa', 'creditos.manzana', 'creditos.num_lote',
                'creditos.modelo', 'creditos.precio_base', 'creditos.superficie',
                'creditos.terreno_excedente', 'creditos.precio_terreno_excedente',
                'creditos.porcentaje_terreno', 'creditos.promocion',
                'creditos.descripcion_promocion', 'creditos.descuento_promocion',
                'creditos.paquete', 'creditos.descripcion_paquete',
                'creditos.precio_venta', 'creditos.plazo', 'creditos.credito_solic',
                'creditos.costo_paquete', 'inst_seleccionadas.tipo_credito',
                'inst_seleccionadas.id as inst_credito', 'creditos.precio_obra_extra',
                'creditos.fraccionamiento as proyecto', 'creditos.lote_id',
                'fraccionamientos.tipo_proyecto',

                'lotes.calle', 'lotes.numero', 'lotes.interior',
                'lotes.terreno', 'lotes.construccion', 
                'lotes.sublote',
                'lotes.sobreprecio', 'lotes.fecha_termino_ventas',
                'medios_publicitarios.nombre as medio_publicidad',
                'lotes.ajuste', 'lotes.emp_constructora', 'lotes.emp_terreno',

                'inst_seleccionadas.institucion',
                'personal.nombre', 'personal.apellidos', 'personal.telefono',
                'personal.celular', 'personal.clv_lada', 'personal.email',
                'clientes.email_institucional', 'personal.direccion',
                'personal.cp', 'personal.colonia', 'personal.f_nacimiento',
                'personal.rfc', 'personal.homoclave', 'creditos.fraccionamiento',
                'clientes.id as prospecto_id', 'clientes.edo_civil',
                'clientes.nss', 'clientes.curp', 'clientes.empresa',
                'clientes.coacreditado', 'clientes.estado', 'clientes.ciudad',
                'clientes.puesto', 'clientes.nacionalidad', 'clientes.sexo',
                'clientes.sexo_coa', 'clientes.email_institucional_coa',
                'clientes.empresa_coa', 'clientes.edo_civil_coa', 'clientes.nss_coa',
                'clientes.curp_coa', 'clientes.nombre_coa', 'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa', 'clientes.nacionalidad_coa',
                'clientes.rfc_coa', 'clientes.homoclave_coa', 'clientes.direccion_coa',
                'clientes.colonia_coa', 'clientes.ciudad_coa', 'clientes.estado_coa',
                'clientes.cp_coa', 'clientes.telefono_coa', 'clientes.ext_coa',
                'clientes.celular_coa', 'clientes.email_coa', 'clientes.parentesco_coa',
                'clientes.lugar_nacimiento_coa', 'clientes.nombre_recomendado',
                'v.nombre as vendedor_nombre', 'v.apellidos as vendedor_apellidos',

                'contratos.infonavit', 'contratos.vendedor_aux',
                'contratos.fovisste', 'contratos.comision_apertura',
                'clientes.lugar_nacimiento', 'contratos.investigacion',
                'contratos.avaluo', 'contratos.prima_unica',
                'contratos.escrituras', 'contratos.credito_neto',
                'contratos.status', 'contratos.avaluo_cliente',
                'contratos.fecha', 'contratos.direccion_empresa',
                'contratos.cp_empresa', 'contratos.colonia_empresa',
                'contratos.estado_empresa', 'contratos.ciudad_empresa',
                'contratos.telefono_empresa', 'contratos.ext_empresa',
                'contratos.direccion_empresa_coa', 'contratos.cp_empresa_coa',
                'contratos.colonia_empresa_coa', 'contratos.estado_empresa_coa',
                'contratos.ciudad_empresa_coa', 'contratos.telefono_empresa_coa',
                'contratos.ext_empresa_coa', 'contratos.total_pagar',
                'contratos.monto_total_credito', 'contratos.enganche_total',
                'contratos.avance_lote', 'contratos.observacion',
                'contratos.exp_bono'
            )
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->where('contratos.id', '=', $id)
            ->orderBy('id', 'desc')->get();
    }

    // Función para imprimir la solicitud de compra en PDF
    public function contratoCompraVentaPdf(Request $request, $id)
    {
        $contratos = $this->getDatosContrato($id);

        //Si la venta es con Coacreditado se obtienen los datos necesarios
        if($contratos[0]->rfc_coa != NULL){
            $coa = Cliente::join('personal','clientes.id','=','personal.id')
                ->select('personal.rfc', 'personal.homoclave', 'clientes.puesto','clientes.edo_civil')
                ->where('personal.rfc','like','%'.$contratos[0]->rfc_coa.'%')
                //->where('personal.homoclave','like','%'.$contratos[0]->homoclave_coa.'%')
                ->first();

            $contratos[0]->puesto_coa = $coa['puesto'];
            $contratos[0]->edo_civil_coa = $coa['edo_civil'];

        }

            // if($contratos[0]->institucion == 'Gamu' && $contratos[0]->tipo_credito == 'INFONAVIT-FOVISSSTE' || $contratos[0]->institucion == 'Crea Más' && $contratos[0]->tipo_credito == 'INFONAVIT-FOVISSSTE'){
            //     $contratos[0]->institucion = 'INFONAVIT';
            // }

        // Se cambia a infonavit si la institución de financiamiento es GAMU
        if($contratos[0]->institucion == 'Gamu' && $contratos[0]->tipo_credito == 'INFONAVIT-FOVISSSTE'){
            $contratos[0]->institucion = 'INFONAVIT';
        }

        setlocale(LC_TIME, 'es_MX.utf8');
        // Se da formato a la fecha de creación del contrato
        $tiempo = new Carbon($contratos[0]->fecha);
        $contratos[0]->fecha = $tiempo->formatLocalized('%d de %B de %Y');
        // Se da formato mes, año a la fecha de termino de la vivienda
        $fecha_termino_ventas = new Carbon($contratos[0]->fecha_termino_ventas);
        $contratos[0]->fecha_termino_ventas = $fecha_termino_ventas->formatLocalized('%B de %Y');
        // Se da formato mes, año a la fecha de nacimiento del cliente
        $fecha_nac = new Carbon($contratos[0]->f_nacimiento);
        $contratos[0]->f_nacimiento = $fecha_nac->formatLocalized('%d-%m-%Y');
        // Se da formato mes, año a la fecha de nacimiento del coacreditado
        $fecha_nac_coa = new Carbon($contratos[0]->f_nacimiento_coa);
        $contratos[0]->f_nacimiento_coa = $fecha_nac_coa->formatLocalized('%d-%m-%Y');
        // Se calcula el precio base de la casa
        $contratos[0]->precio_base = $contratos[0]->precio_base - $contratos[0]->descuento_promocion;
        $descuentoPromo = $contratos[0]->descuento_promocion;

        // Se da formato numerico
        $contratos[0]->precio_base = number_format((float)$contratos[0]->precio_base, 2, '.', ',');
        $contratos[0]->credito_solic = number_format((float)$contratos[0]->credito_solic, 2, '.', ',');
        $contratos[0]->precio_terreno_excedente = number_format((float)$contratos[0]->precio_terreno_excedente, 2, '.', ',');
        $contratos[0]->comision_apertura = number_format((float)$contratos[0]->comision_apertura, 2, '.', ',');
        $contratos[0]->precio_obra_extra = number_format((float)$contratos[0]->precio_obra_extra, 2, '.', ',');
        $contratos[0]->investigacion = number_format((float)$contratos[0]->investigacion, 2, '.', ',');
        $contratos[0]->sobreprecio = number_format((float)$contratos[0]->sobreprecio, 2, '.', ',');
        $contratos[0]->avaluo = number_format((float)$contratos[0]->avaluo, 2, '.', ',');
        $contratos[0]->costo_paquete = number_format((float)$contratos[0]->costo_paquete, 2, '.', ',');
        $contratos[0]->prima_unica = number_format((float)$contratos[0]->prima_unica, 2, '.', ',');
        $contratos[0]->descuento_promocion = number_format((float)$contratos[0]->descuento_promocion, 2, '.', ',');
        $contratos[0]->escrituras = number_format((float)$contratos[0]->escrituras, 2, '.', ',');
        $contratos[0]->precio_venta = number_format((float)$contratos[0]->precio_venta, 2, '.', ',');
        $contratos[0]->credito_neto = number_format((float)$contratos[0]->credito_neto, 2, '.', ',');
        $contratos[0]->monto_total_credito = number_format((float)$contratos[0]->monto_total_credito, 2, '.', ',');
        $contratos[0]->total_pagar = number_format((float)$contratos[0]->total_pagar, 2, '.', ',');
        $contratos[0]->avaluo_cliente = number_format((float)$contratos[0]->avaluo_cliente, 2, '.', ',');
        $contratos[0]->enganche_total = number_format((float)$contratos[0]->enganche_total, 2, '.', ',');
        $contratos[0]->infonavit = number_format((float)$contratos[0]->infonavit, 2, '.', ',');
        $contratos[0]->fovisste = number_format((float)$contratos[0]->fovisste, 2, '.', ',');

        if($contratos[0]->terreno_excedente <= 0){
            $contratos[0]->terreno_excedente = 0;
        }

        // Se obtienen los pagares del contrato y se da formato numerico al monto.
        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')->where('contrato_id', '=', $id)
        ->where('tipo_pagare','=',0)
        ->orderBy('fecha_pago', 'asc')->get();
        for ($i = 0; $i < count($pagos); $i++) {
            $pagos[$i]->monto_pago = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');
            $fecha_pago = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $fecha_pago->formatLocalized('%d-%m-%Y');
        }

        //return $contratos;
        //Llamada al pdf para venta de casa
        if($contratos[0]->modelo != 'Terreno' || ($contratos[0]->modelo == 'Terreno' && $contratos[0]->tipo_credito == 'Crediterreno'))
            $pdf = \PDF::loadview('pdf.contratos.contratoCompraVenta', ['contratos' => $contratos, 'pagos' => $pagos]);
        // PDF para venta de Terreno
        else{
            // Se obtiene la cotización del terreno
            $cotizacion = Cotizacion_lotes::join('clientes', 'cotizacion_lotes.cliente_id', '=', 'clientes.id')
                ->join('personal', 'clientes.id', '=', 'personal.id')
                ->join('lotes', 'cotizacion_lotes.lotes_id', '=', 'lotes.id')
                ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
                ->select(
                    'cotizacion_lotes.id', 'cotizacion_lotes.cliente_id', 'cotizacion_lotes.lotes_id',
                    'cotizacion_lotes.valor_venta', 'cotizacion_lotes.valor_descuento',
                    'cotizacion_lotes.created_at', 'cotizacion_lotes.updated_at', 'cotizacion_lotes.fecha',
                    'cotizacion_lotes.mensualidades', 'cotizacion_lotes.interes',
    
                    'personal.apellidos', 'personal.nombre', 
                    
                    'clientes.id as cliente_personal_id',
    
                    'lotes.num_lote', 'lotes.sublote',
                    'lotes.terreno as terreno_m2',
                    'etapas.num_etapa',
                    'fraccionamientos.nombre as fraccionamiento'
                )
                ->where('cotizacion_lotes.num_contrato', '=', $id)
                
            ->first();

            // Valor por m2
            $cotizacion->m2 = $cotizacion->valor_venta/$cotizacion->terreno_m2;
            // Valor de venta sin descuento
            $cotizacion->valorVenta2 = $cotizacion->valor_venta + $descuentoPromo;
            $contratos[0]->m2 = number_format((float)$cotizacion->m2, 2, '.', ',');
            $contratos[0]->interes = $cotizacion->interes;
            $contratos[0]->mensualidades = $cotizacion->mensualidades;
            $contratos[0]->valor_venta = $cotizacion->valor_venta - $cotizacion->valor_descuento;

            $contratos[0]->valor_base = number_format((float)$cotizacion->valor_venta, 2, '.', ',');
            $contratos[0]->valor_base2 = number_format((float)$cotizacion->valorVenta2, 2, '.', ',');
            $contratos[0]->valor_venta = number_format((float)$contratos[0]->valor_venta, 2, '.', ',');
            $contratos[0]->valor_descuento = number_format((float)$cotizacion->valor_descuento, 2, '.', ',');
            
            $pago = Pagos_lotes::where('cotizacion_lotes_id', '=', $cotizacion->id)
                ->orderBy('folio')
            ->get();
            
    
            if(sizeof($pago)){
                $totalDePagos = count($pago);
                $pago[0]->numPagos= $totalDePagos;

                $totalP1 = 0;
                $totalP2 = 0;
                $totalP3 = 0;
                $totalP4 = 0;

                foreach ($pago as $index => $p) {
                    $totalP1+= $p->cantidad;
                    $totalP2+= $p->descuento;
                    $totalP3+= $p->interes_monto;
                    $totalP4+= $p->total_a_pagar;
                    
                    $p->cantidad = number_format((float)$p->cantidad, 2, '.', ',');
                    $p->descuento = number_format((float)$p->descuento, 2, '.', ',');
                    $p->interes_monto = number_format((float)$p->interes_monto, 2, '.', ',');
                    $p->total_a_pagar = number_format((float)$p->total_a_pagar, 2, '.', ',');
                    $p->saldo = number_format((float)$p->saldo, 2, '.', ',');
                    
                    $fecha_pago = new Carbon($p->fecha);
                    $p->fecha = $fecha_pago->formatLocalized('%d/%m/%Y');

                    
                }

                $totalP1 = number_format((float)$totalP1, 2, '.', ',');
                $totalP2 = number_format((float)$totalP2, 2, '.', ',');
                $totalP3 = number_format((float)$totalP3, 2, '.', ',');
                $totalP4 = number_format((float)$totalP4, 2, '.', ',');
            }
            $pdf = \PDF::loadview('pdf.contratos.contratoCompraVentaTerreno', ['contratos' => $contratos, 'pago' => $pago, 
                        'totalP1' => $totalP1,
                        'totalP2' => $totalP2,
                        'totalP3' => $totalP3,
                        'totalP4' => $totalP4,
                        
            ]);
        }
        return $pdf->stream('ContratoCompraVenta.pdf');
    }

    // Función para imprimir los pagares
    public function pagareContratopdf(Request $request, $id)
    {

        $cliente = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->select(
                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.email',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'clientes.estado',
                'creditos.modelo',
                'clientes.ciudad',
                'lotes.emp_constructora',
                'lotes.emp_terreno',
                'contratos.fecha',
                'lotes.emp_constructora',
                'lotes.emp_terreno',
                'modelos.nombre as modelo'
            )
            ->where('contratos.id', '=', $id)->get();

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();

        setlocale(LC_TIME, 'es_MX.utf8');



        for ($i = 0; $i < count($pagos); $i++) {
            $pagos[$i]->monto_pago1 = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');
            $tiempo = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $tiempo->formatLocalized('%d de %B de %Y');
            $pagos[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagos[$i]->monto_pago, 'Pesos', true, 'Centavos');
        }


        $tiempo = new Carbon($cliente[0]->fecha);
        $cliente[0]->fecha = $tiempo->formatLocalized('%d de %B de %Y');

        $pdf = \PDF::loadview('pdf.contratos.pagaresContratos', ['pagos' => $pagos, 'cliente' => $cliente]);
        return $pdf->stream('pagare.pdf');
        // return ['cabecera' => $cabecera];
    }

    // Función para impriimr contratos de compra Directa
    public function contratoConReservaDeDominio(Request $request, $id)
    {

        $contratosDom = $this->datosContratoImprimir($id);

        // Calculo para obtener el valor de construcción de la casa
        $contratosDom[0]->valor_construccion = $contratosDom[0]->enganche_total - $contratosDom[0]->valor_terreno;

        setlocale(LC_TIME, 'es_MX.utf8');
        $contratosDom[0]->engacheTotalLetra = NumerosEnLetras::convertir($contratosDom[0]->enganche_total, 'Pesos', true, 'Centavos');
        $contratosDom[0]->enganche_total = number_format((float)$contratosDom[0]->enganche_total, 2, '.', ',');
        $contratosDom[0]->valorTerrenoLetra = NumerosEnLetras::convertir($contratosDom[0]->valor_terreno, 'Pesos', true, 'Centavos');
        $contratosDom[0]->valorConstruccionLetra = NumerosEnLetras::convertir($contratosDom[0]->valor_construccion, 'Pesos', true, 'Centavos');

        $fechaContrato = new Carbon($contratosDom[0]->fecha);
        $contratosDom[0]->fecha = $fechaContrato->formatLocalized('%d días de %B de %Y');

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')
        ->where('tipo_pagare', '=', 0)->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();

        $totalDePagos = count($pagos);
        $pagos[0]->totalDePagos = NumerosEnLetras::convertir($totalDePagos, false, false, false);

        $posMayor=0;
        for ($i = 0; $i < count($pagos); $i++) {
            if($contratosDom[0]->avaluo_cliente < $pagos[$i]->monto_pago)
            {
                $posMayor = $i;
            }
        }

        if($posMayor != 0)
            $pagos[$posMayor]->monto_pago =  $pagos[$posMayor]->monto_pago - $contratosDom[0]->avaluo_cliente;
        else{
            $pagos[0]->monto_pago =  $pagos[0]->monto_pago - $contratosDom[0]->avaluo_cliente;
            if($pagos[0]->monto_pago<0)
                $pagos[0]->monto_pago = 0;
        }

        for ($i = 0; $i < count($pagos); $i++) {
            $tiempo = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $tiempo->formatLocalized('%d de %B de %Y');
            $pagos[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagos[$i]->monto_pago, 'Pesos', true, 'Centavos');
            $pagos[$i]->monto_pago = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');

            switch ($i) {
                case (0): {
                        $pagos[$i]->numeros = 'primero';
                        break;
                    }
                case (1): {
                        $pagos[$i]->numeros = 'segundo';
                        break;
                    }
                case (2): {
                        $pagos[$i]->numeros = 'tercero';
                        break;
                    }
                case (3): {
                        $pagos[$i]->numeros = 'cuarto';
                        break;
                    }
                case (4): {
                        $pagos[$i]->numeros = 'quinto';
                        break;
                    }
                case (5): {
                        $pagos[$i]->numeros = 'sexto';
                        break;
                    }
                case (6): {
                        $pagos[$i]->numeros = 'septimo';
                        break;
                    }
                case (7): {
                        $pagos[$i]->numeros = 'octavo';
                        break;
                    }
                case (8): {
                        $pagos[$i]->numeros = 'noveno';
                        break;
                    }
                case (9): {
                        $pagos[$i]->numeros = 'decimo';
                        break;
                    }
                case (10): {
                        $pagos[$i]->numeros = 'onceavo';
                        break;
                    }
            }
        }

        // Contrato para ventas sin alianza
        if($contratosDom[0]->emp_constructora == $contratosDom[0]->emp_terreno)
            $pdf = \PDF::loadview('pdf.contratos.contratoConReservaDeDominio', ['contratosDom' => $contratosDom, 'pagos' => $pagos]);
        // Contrato para ventas por alianza
        else
            $pdf = \PDF::loadview('pdf.contratos.contratoContado', ['contratosDom' => $contratosDom, 'pagos' => $pagos]);
        return $pdf->stream('contrato_reserva_de_dominio.pdf');
    }

    // Función privada para obtener la query con los datos para imprimir.
    private function datosContratoImprimir($id){
        $datos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.superficie',
                'creditos.valor_terreno',
                'creditos.porcentaje_terreno',
                'inst_seleccionadas.id as inst_credito',
                'inst_seleccionadas.tipo_credito',
                'creditos.precio_venta',
                'inst_seleccionadas.institucion',
                'creditos.fraccionamiento as proyecto',
                'lotes.construccion',
                'lotes.sublote',
                'lotes.regimen_condom',
                'lotes.emp_constructora',
                'lotes.emp_terreno',
                'lotes.indivisos',
                'modelos.tipo',
                'modelos.nombre as modelo',

                'fraccionamientos.ciudad as ciudad_proy',
                'fraccionamientos.estado as estado_proy',
                'fraccionamientos.calle as direccionProyecto',

                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'creditos.fraccionamiento',
                'clientes.id as prospecto_id',
                'clientes.edo_civil',
                'clientes.nss',
                'clientes.curp',
                'clientes.empresa',
                'clientes.coacreditado',
                'clientes.nombre_coa',
                'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa',
                'clientes.nacionalidad_coa',
                'clientes.estado',
                'clientes.ciudad',

                'contratos.monto_total_credito',
                'contratos.enganche_total',
                'contratos.fecha',
                'contratos.infonavit',
                'contratos.fovisste',
                'contratos.avaluo_cliente',
                'contratos.credito_neto',
                'contratos.total_pagar'
            )
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->where('contratos.id', '=', $id)
            ->get();

        return $datos;
    }

    // Función para impriimr contratos de compra financiamiento bancario.
    public function contratoDePromesaCredito(Request $request, $id)
    {

        //Llamada a la función privada que obtiene los datos del contrato.
        $contratoPromesa = $this->datosContratoImprimir($id);

        //En caso de no tener enganche el credito total es la diferencia entre el credito y el monto del avaluo por parte del cliente.
        if($contratoPromesa[0]->enganche_total == 0){
            $contratoPromesa[0]->credito_neto = $contratoPromesa[0]->credito_neto - $contratoPromesa[0]->avaluo_cliente;
        }

        //Valor de construcción de la vivienda.
        $contratoPromesa[0]->valor_construccion = $contratoPromesa[0]->precio_venta - $contratoPromesa[0]->valor_terreno;

        setlocale(LC_TIME, 'es_MX.utf8');
        //Se obtiene el valor de enganche en letra
        if($contratoPromesa[0]->avaluo_cliente>=0 && $contratoPromesa[0]->enganche_total >0){
            $contratoPromesa[0]->engancheTotalLetra = NumerosEnLetras::convertir(($contratoPromesa[0]->enganche_total - $contratoPromesa[0]->avaluo_cliente), 'Pesos', true, 'Centavos');
        }
        else{
            $contratoPromesa[0]->engancheTotalLetra = NumerosEnLetras::convertir($contratoPromesa[0]->avaluo_cliente, 'Pesos', true, 'Centavos');
        }
        $contratoPromesa[0]->avaluoLetra = NumerosEnLetras::convertir($contratoPromesa[0]->avaluo_cliente, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->precioVentaLetra = NumerosEnLetras::convertir($contratoPromesa[0]->precio_venta, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->valorTerrenoLetra = NumerosEnLetras::convertir($contratoPromesa[0]->valor_terreno, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->valorConstruccionLetra = NumerosEnLetras::convertir($contratoPromesa[0]->valor_construccion, 'Pesos', true, 'Centavos');
        //$contratoPromesa[0]->precio_venta = number_format((float)$contratoPromesa[0]->precio_venta, 2, '.', ',');

        // if($contratoPromesa[0]->total_pagar <0)
        //     $contratoPromesa[0]->credito_neto=$contratoPromesa[0]->credito_neto - $contratoPromesa[0]->total_pagar;

        
        $contratoPromesa[0]->montoTotalCreditoLetra = NumerosEnLetras::convertir($contratoPromesa[0]->credito_neto, 'Pesos', true, 'Centavos');
        //$contratoPromesa[0]->credito_neto = number_format((float)$contratoPromesa[0]->credito_neto, 2, '.', ',');

        $contratoPromesa[0]->infonavitLetra = NumerosEnLetras::convertir($contratoPromesa[0]->infonavit, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->infonavit = number_format((float)$contratoPromesa[0]->infonavit, 2, '.', ',');

        $contratoPromesa[0]->fovissteLetra = NumerosEnLetras::convertir($contratoPromesa[0]->fovisste, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->fovisste = number_format((float)$contratoPromesa[0]->fovisste, 2, '.', ',');

        $fechaContrato = new Carbon($contratoPromesa[0]->fecha);
        $contratoPromesa[0]->fecha = $fechaContrato->formatLocalized('%d días de %B de %Y');

        //Se obtinene los pagares del contrato.
        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')
        ->where('tipo_pagare', '=', 0)->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();


        if($contratoPromesa[0]->institucion == 'Gamu' && $contratoPromesa[0]->tipo_credito == 'INFONAVIT-FOVISSSTE' || $contratoPromesa[0]->institucion == 'Crea Más' && $contratoPromesa[0]->tipo_credito == 'INFONAVIT-FOVISSSTE'){
            $contratoPromesa[0]->institucion = 'INFONAVIT';
        }
        
        $totalDePagos = count($pagos);
        $pagos[0]->totalDePagos = NumerosEnLetras::convertir($totalDePagos, false, false, false);

        $posMayor=0;
        for ($i = 0; $i < count($pagos); $i++) {
            if($contratoPromesa[0]->avaluo_cliente < $pagos[$i]->monto_pago)
            {
                $posMayor = $i;
            }
        }

        if($posMayor != 0)
            $pagos[$posMayor]->monto_pago =  $pagos[$posMayor]->monto_pago - $contratoPromesa[0]->avaluo_cliente;
        else{
            $pagos[0]->monto_pago =  $pagos[0]->monto_pago - $contratoPromesa[0]->avaluo_cliente;
            if($pagos[0]->monto_pago<0)
                $pagos[0]->monto_pago = 0;
        }

        for ($i = 0; $i < count($pagos); $i++) {
            $tiempo = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $tiempo->formatLocalized('%d de %B de %Y');
            if($pagos[$i]->monto_pago != 0)
                $pagos[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagos[$i]->monto_pago, 'Pesos', true, 'Centavos');
            else
                $pagos[$i]->montoPagoLetra = ' $0.00 (CERO PESOS 00/100 M.N.)';
            $pagos[$i]->monto_pago = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');

            switch ($i) {
                case (0): {
                        $pagos[$i]->numeros = 'primero';
                        break;
                    }
                case (1): {
                        $pagos[$i]->numeros = 'segundo';
                        break;
                    }
                case (2): {
                        $pagos[$i]->numeros = 'tercero';
                        break;
                    }
                case (3): {
                        $pagos[$i]->numeros = 'cuarto';
                        break;
                    }
                case (4): {
                        $pagos[$i]->numeros = 'quinto';
                        break;
                    }
                case (5): {
                        $pagos[$i]->numeros = 'sexto';
                        break;
                    }
                case (6): {
                        $pagos[$i]->numeros = 'septimo';
                        break;
                    }
                case (7): {
                        $pagos[$i]->numeros = 'octavo';
                        break;
                    }
                case (8): {
                        $pagos[$i]->numeros = 'noveno';
                        break;
                    }
                case (9): {
                        $pagos[$i]->numeros = 'decimo';
                        break;
                    }
                case (10): {
                        $pagos[$i]->numeros = 'onceavo';
                        break;
                    }
            }
        }

        //Contrato para departamentos.
        if($contratoPromesa[0]->tipo == 2){
            $pdf = \PDF::loadview('pdf.contratos.contratoCreditoDepartamento', ['contratoPromesa' => $contratoPromesa, 'pagos' => $pagos]);   
        }
        //Contratos para viviendas
        else{
            if($contratoPromesa[0]->emp_constructora == $contratoPromesa[0]->emp_terreno)
                $pdf = \PDF::loadview('pdf.contratos.contratoDePromesaCredito', ['contratoPromesa' => $contratoPromesa, 'pagos' => $pagos]);
            //Contrato alianza
            else
                $pdf = \PDF::loadview('pdf.contratos.contratoDePromesaCredito2', ['contratoPromesa' => $contratoPromesa, 'pagos' => $pagos]);
        }
            
        return //['contratoPromesa' => $contratoPromesa, 'pagos' => $pagos];
        $pdf->stream('contrato_promesa_credito.pdf');
    }

    // Función para impriimr contratos de compra de terrenos
    public function contratoLote(Request $request, $id)
    {

        //Se obtiene la cotización 
        $cotizacion = Cotizacion_lotes::select('id','mensualidades','interes')->where('num_contrato','=',$id)->first();
        //Se obtienen los pagos de la cotización
        $p_lote = Pagos_lotes::where('cotizacion_lotes_id','=',$cotizacion->id)->orderBy('folio','asc')->get();
        //Datos sobre %interes y %descuento
        $opc_lotes = datos_calc_lotes::get();
        //Llamada a la función privada que obtiene los datos del contrato.
        $contratoPromesa = $this->datosContratoImprimir($id);

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')
            ->where('tipo_pagare', '=', 0)->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();
        
        setlocale(LC_TIME, 'es_MX.utf8');

        $contratoPromesa[0]->mensualidades = $cotizacion->mensualidades;
        $contratoPromesa[0]->interes = $cotizacion->interes;
        $contratoPromesa[0]->porcentajeValor = ($p_lote[0]->total_a_pagar * 100)/$contratoPromesa[0]->precio_venta;
        $contratoPromesa[0]->porcentajeValor = round($contratoPromesa[0]->porcentajeValor,2);
        
        $contratoPromesa[0]->precioVentaLetra = NumerosEnLetras::convertir($contratoPromesa[0]->precio_venta, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->valorTerrenoLetra = NumerosEnLetras::convertir($contratoPromesa[0]->valor_terreno, 'Pesos', true, 'Centavos');

        $contratoPromesa[0]->montoTotalCreditoLetra = NumerosEnLetras::convertir($contratoPromesa[0]->credito_neto, 'Pesos', true, 'Centavos');

        $fechaContrato = new Carbon($contratoPromesa[0]->fecha);
        $contratoPromesa[0]->fecha = $fechaContrato->formatLocalized('%d días de %B de %Y');
        $contratoPromesa[0]->fecha2 = $fechaContrato->formatLocalized('%d de %B de %Y');
        
        $totalDePagos = count($p_lote);
        $p_lote[0]->numPagos= $totalDePagos;
        $p_lote[0]->totalDePagos = NumerosEnLetras::convertir($totalDePagos, false, false, false);

        $enganche = $p_lote[0]->total_a_pagar;
        

        for ($i = 0; $i < count($p_lote); $i++) {
            $tiempo = new Carbon($p_lote[$i]->fecha);
            $p_lote[$i]->fecha = $tiempo->formatLocalized('%d de %B de %Y');
            $p_lote[$i]->totalLetra = NumerosEnLetras::convertir($p_lote[$i]->total_a_pagar, 'Pesos', true, 'Centavos');
            $p_lote[$i]->total_a_pagar = number_format((float)$p_lote[$i]->total_a_pagar, 2, '.', ',');

            $p_lote[$i]->interesLetra = NumerosEnLetras::convertir($p_lote[$i]->interes_monto, 'Pesos', true, 'Centavos');
            $p_lote[$i]->interes_monto = number_format((float)$p_lote[$i]->interes_monto, 2, '.', ',');

            $p_lote[$i]->cantidadLetra = NumerosEnLetras::convertir($p_lote[$i]->cantidad, 'Pesos', true, 'Centavos');
            $p_lote[$i]->cantidad = number_format((float)$p_lote[$i]->cantidad, 2, '.', ',');
        }

       
        $pdf = \PDF::loadview('pdf.contratos.contratoLote', ['contratoPromesa' => $contratoPromesa, 'pagos' => $p_lote]);
        return //['contratoPromesa' => $contratoPromesa, 'pagos' => $p_lote];
        $pdf->stream('contrato_promesa_credito.pdf');
    }

    //Función para cambiar el estatus del contrato
    /*
        0 = Cancelado
        1 = Pendiente
        2 = No firmado
        3 = Firmado
    */
    public function statusContrato(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        // try{
        //     DB::beginTransaction();
            $id_lote = $request->lote_id;
            $equipo='';
            $datosFiscales = $request->datosFiscales;

            // Equipamientos instalados
            // Costo total de equipamiento instalado
            $equipamientosCost = Solic_equipamiento::join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                ->select(DB::raw("SUM(solic_equipamientos.costo) as sumCosto"))
                ->where('lotes.id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','!=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();
            
            $equipamientosInst = Solic_equipamiento::join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                ->join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                ->select('equipamientos.equipamiento','solic_equipamientos.id')
                ->where('lotes.id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','!=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();

            //Equipamiento con pago de anticipo
            $equipamientosAntc = Solic_equipamiento::select('id')
                ->where('solic_equipamientos.lote_id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','=',NULL)
                ->where('solic_equipamientos.fecha_anticipo','!=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();
            
            //Equipamientos solicitados sin instalarse ni pago de anticipo
            $equipamientosCancel = Solic_equipamiento::select('id')
                ->where('solic_equipamientos.lote_id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','=',NULL)
                ->where('solic_equipamientos.fecha_anticipo','=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();

            if(sizeof($equipamientosAntc) != 0){
                foreach ($equipamientosAntc as $antc){
                    $antc_equip = Solic_equipamiento::findOrFail($antc->id);
                    $antc_equip->control = 1;
                    $antc_equip->save();
                }
            }
            if(sizeof($equipamientosInst) != 0){
                foreach ($equipamientosInst as $inst){
                    $equipo = $inst->equipamiento.', '.$equipo;
                    $cancel_equip = Solic_equipamiento::findOrFail($inst->id);
                    $cancel_equip->control = 3;
                    $cancel_equip->save();
                }
            }
            // Se almacena el costo del equipamiento ya instalado.
            if(sizeof($equipamientosCost)){
                if($equipamientosCost[0]->sumCosto != 0)
                    $ajuste = $equipamientosCost[0]->sumCosto;
                else
                    $ajuste = 0;
            }
           
            $contrato = Contrato::findOrFail($request->id);
            $contrato->status = $request->status;
            $contrato->motivo_cancel = $request->motivo_cancel;
            if ($request->fecha_status == ''){
                $fecha = Carbon::now();
                $contrato->fecha_status = $fecha;
            }
            else{
                $contrato->fecha_status = $request->fecha_status;
            }
            $contrato->save();

            $lote = Lote::findOrFail($id_lote);

            if ($request->status == 1) {
                //Se indica al lote que ya se encuentra vendido.
                $lote->contrato = 1;
                $lote->save();

            } else {
                // Ventas canceladas o No firmadas
                if ($request->status == 0 || $request->status == 2) {
                    //El lote se habilita para venta.
                    $lote->contrato = 0;
                    $lote->apartado = 0;
                    // En caso de tener equipamiento instalado se ajusta el precio 
                    $lote->ajuste += $ajuste;
                    // Se indica que el lote cuenta con equipamiento
                    if($ajuste != 0)
                        $lote->comentarios ='Lote con equipamiento: '.$equipo.'. '.$lote->comentarios;

                    //Se cancelan los equipamientos solicitados.
                    if(sizeof($equipamientosCancel) != 0){
                        foreach ($equipamientosCancel as $canc){
                            $cancel_equip = Solic_equipamiento::findOrFail($canc->id);
                            $cancel_equip->control = 2;
                            $cancel_equip->status = 5;
                            $cancel_equip->save();
                        }
                    }
                    
                    //Se elimina el apartado asignado al lote.
                    $apartado = Apartado::select('id')->where('lote_id','=',$id_lote)->get();
                    if(sizeof($apartado))
                        foreach($apartado as $ap){
                            $borrarApartado = Apartado::findOrFail($ap->id);
                            $borrarApartado->delete();
                        }
                        // Se obtiene el nombre y superficie de terreno del modelo asignado al lote.
                    $modelo = Modelo::select('terreno','nombre')->where('id','=',$lote->modelo_id)->get();

                    // Ventas que no sean terrenos
                    if($modelo[0]->nombre != 'Terreno'){
                        //Se obtiene el precio por excedente en la etapa actual.
                        $precio_etapa = Precio_etapa::select('id','precio_excedente')
                        ->where('fraccionamiento_id','=',$lote->fraccionamiento_id)
                        ->where('etapa_id','=',$lote->etapa_id)->get();

                        // Se obtiene el precio del modelo actual.
                        $precio_modelo = Precio_modelo::select('precio_modelo')->where('precio_etapa_id','=',$precio_etapa[0]->id)
                                        ->where('modelo_id','=',$lote->modelo_id)->get();

                        // Se obtienen los sobreprecios del lote.
                        $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                        ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                        ->where('sobreprecios_modelos.lote_id','=',$id_lote)->get();

                        //Se calcula el terreno excedente
                        $terrenoExcedente = round(($lote->terreno - $modelo[0]->terreno),2);
                        if((double)$terrenoExcedente > 0)
                            //Se calcula el monto por terreno excedente.
                            $lote->excedente_terreno = round(($terrenoExcedente * $precio_etapa[0]->precio_excedente), 2);
                        else {
                            $lote->excedente_terreno = 0;
                        }
                        //Se asigna el precio base
                        $lote->precio_base = round(($precio_modelo[0]->precio_modelo), 2);
                        //Se calcula el precio de venta del lote.
                        $precio_venta = round(($sobreprecios[0]->sobreprecios + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra),2);
                        
                    }
                    else{
                        $cotizadorLote = Cotizacion_lotes::select('id')->where('lotes_id','=',$id_lote)
                            ->where('num_contrato','=',$request->id)->get();

                        if(sizeOf($cotizadorLote)){
                            $cotizadorLote = Cotizacion_lotes::findOrFail($cotizadorLote[0]->id);
                            $cotizadorLote->estatus = 2;
                            $cotizadorLote->save();
                        }
                    }
                    $lote->save();

                    $credito = Credito::select('prospecto_id')
                        ->where('id', '=', $request->id)
                        ->get();
                    $cliente = Cliente::findOrFail($credito[0]->prospecto_id);
                    $cliente->clasificacion = 6;
                    $cliente->save();
                }
                // Venta firmada
                if ($request->status == 3) {
                    //Se obtienen la información del paquete asignado.
                    $credito = Credito::select('prospecto_id', 'descripcion_paquete', 'num_lote', 'fraccionamiento', 'etapa')
                        ->where('id', '=', $request->id)
                        ->get();
                    //Se asigna el paquete al registro del lote
                    $lote->paquete = $credito[0]->descripcion_paquete;
                    //El lote se indica como vendido.
                    $lote->contrato = 1;
                    $lote->save();
                    // Se accede al registro del Cliente.
                    $cliente = Cliente::findOrFail($credito[0]->prospecto_id);
                    // Cliente clasificado como ventas
                    $cliente->clasificacion = 5;
                    $vendedorid = $cliente->vendedor_id;
                    $cliente->save();

                    // Actualización de datos fiscales
                    $credit_fisc = Credito::findOrFail($request->id);
                    $p_cliente = Personal::findOrFail($credit_fisc->prospecto_id);
                    
                        if($datosFiscales['rfc_fisc'] != '' && $credit_fisc->notif_fisc == 0 || $datosFiscales['rfc_fisc'] != '' && $credit_fisc->notif_fisc == 1){
                            $credit_fisc->notif_fisc = 2;
                            $credit_fisc->fecha_rfc = Carbon::now();
                            //Se manda notificación sobre la venta.
                            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', $vendedorid)->get();
                            $fecha = Carbon::now();
                            $msj = "Se ha cerrado la venta del lote " . $credito[0]->num_lote . " del proyecto " . $credito[0]->fraccionamiento . " etapa " . $credito[0]->etapa. 
                            " a nombre del cliente ".$p_cliente->nombre.' '.$p_cliente->apellidos.
                            " con RFC";
                            $arregloAceptado = [
                                'notificacion' => [
                                    'usuario' => $imagenUsuario[0]->usuario,
                                    'foto' => $imagenUsuario[0]->foto_user,
                                    'fecha' => $fecha,
                                    'msj' => $msj,
                                    'titulo' => 'Venta con RFC'
                                ]
                            ];

                            $personal = Personal::join('users', 'personal.id', '=', 'users.id')->select('personal.email', 'personal.id')->whereIn('users.usuario', ['enrique.mag','antonio.nv','shady'])->get();

                            if(sizeof($personal))
                            foreach ($personal as $personas) {
                                $correo = $personas->email;
                                Mail::to($correo)->send(new NotificationReceived($msj));
                                User::findOrFail($personas->id)->notify(new NotifyAdmin($arregloAceptado));
                            }
                        }
                        if($datosFiscales['rfc_fisc'] == '' && $credit_fisc->notif_fisc == 0){
                            $credit_fisc->notif_fisc = 1;
                            //Se manda notificación sobre la venta.
                            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', $vendedorid)->get();
                            $fecha = Carbon::now();
                            $msj = "Se ha cerrado la venta del lote " . $credito[0]->num_lote . " del proyecto " . $credito[0]->fraccionamiento . " etapa " . $credito[0]->etapa. 
                            " a nombre del cliente ".$p_cliente->nombre.' '.$p_cliente->apellidos.
                            " sin RFC";
                            $arregloAceptado = [
                                'notificacion' => [
                                    'usuario' => $imagenUsuario[0]->usuario,
                                    'foto' => $imagenUsuario[0]->foto_user,
                                    'fecha' => $fecha,
                                    'msj' => $msj,
                                    'titulo' => 'Venta sin RFC'
                                ]
                            ];

                            $personal = Personal::join('users', 'personal.id', '=', 'users.id')->select('personal.email', 'personal.id')->whereIn('users.usuario', ['enrique.mag','antonio.nv','shady'])->get();

                            if(sizeof($personal))
                            foreach ($personal as $personas) {
                                $correo = $personas->email;
                                Mail::to($correo)->send(new NotificationReceived($msj));
                                User::findOrFail($personas->id)->notify(new NotifyAdmin($arregloAceptado));
                            }
                        }
                    
                    // Se capturan los datos fiscales del cliente.
                    $credit_fisc->email_fisc = $datosFiscales['email_fisc'];
                    $credit_fisc->tel_fisc = $datosFiscales['tel_fisc'];
                    $credit_fisc->nombre_fisc = $datosFiscales['nombre_fisc'];
                    $credit_fisc->direccion_fisc = $datosFiscales['direccion_fisc'];
                    $credit_fisc->col_fisc = $datosFiscales['col_fisc'];
                    $credit_fisc->cp_fisc = $datosFiscales['cp_fisc'];
                    $credit_fisc->rfc_fisc = $datosFiscales['rfc_fisc'];

                    $credit_fisc->cfi_fisc = $datosFiscales['cfi_fisc'];
                    $credit_fisc->regimen_fisc = $datosFiscales['regimen_fisc'];
                    $credit_fisc->banco_fisc = $datosFiscales['banco_fisc'];
                    $credit_fisc->num_cuenta_fisc = $datosFiscales['num_cuenta_fisc'];
                    $credit_fisc->clabe_fisc = $datosFiscales['clabe_fisc'];

                    $credit_fisc->save();

                    //Se manda notificación sobre la venta.
                    $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', $vendedorid)->get();
                    $fecha = Carbon::now();
                    $msj = "Se ha vendido el lote " . $credito[0]->num_lote . " del proyecto " . $credito[0]->fraccionamiento . " etapa " . $credito[0]->etapa;
                    $arregloAceptado = [
                        'notificacion' => [
                            'usuario' => $imagenUsuario[0]->usuario,
                            'foto' => $imagenUsuario[0]->foto_user,
                            'fecha' => $fecha,
                            'msj' => $msj,
                            'titulo' => 'Venta :)'
                        ]
                    ];

                    $personal = Personal::join('users', 'personal.id', '=', 'users.id')->select('personal.email', 'personal.id')->where('users.id', '=', $vendedorid)->get();
                    // Se envia notifivacion por correo y en el sistema para informar sobre la venta.
                    if(sizeof($personal))
                    foreach ($personal as $personas) {
                        $correo = $personas->email;
                        Mail::to($correo)->send(new NotificationReceived($msj));
                        User::findOrFail($personas->id)->notify(new NotifyAdmin($arregloAceptado));
                    }
                    
                }
            }

            $typCredit = inst_seleccionada::where('credito_id', '=', $request->id)->where('elegido', '=', 1)->get();
            $inst = inst_seleccionada::findOrFail($typCredit[0]->id);
            $inst->status = 2;
            $inst->save();

            //Se envia notificacion a Depto de Cobranza sobre la venta (Venta directa)
            if($request->status == 3 && $typCredit[0]->tipo_credito == "Crédito Directo"){
                $c = Personal::findOrFail($credito[0]->prospecto_id);
                $toAlert = [24706, 24705];
                $msj = 'Se ha realizado una nueva firma de credito directo. Contrato #'.$request->id. ' del cliente '.  $c->nombre.' '.$c->apellidos;

                foreach($toAlert as $index => $id){
                    $senderData = DB::table('users')->select('foto_user', 'usuario')->where('id','=',Auth::user()->id)->get();

                    $dataAr = [
                        'notificacion'=>[
                            'usuario' => $senderData[0]->usuario,
                            'foto' => $senderData[0]->foto_user,
                            'fecha' => Carbon::now(),
                            'msj' => $msj,
                            'titulo' => 'Firma de contrato'
                        ]
                    ];
                    User::findOrFail($id)->notify(new NotifyAdmin($dataAr));
                    $persona = Personal::findOrFail($id);
                    Mail::to($persona->email)->send(new NotificationReceived($msj));
                    
                }
            }

        // } catch (Exception $e){
        //     DB::rollBack();
        // }
    }

    // Función para añadir un nuevo pagare.
    public function agregarPago(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = new Pago_contrato();
        $pago->contrato_id = $request->contrato_id;
        $pago->num_pago = $request->num_pago;
        $pago->monto_pago = $request->monto_pago;
        $pago->fecha_pago = $request->fecha_pago;
        $pago->restante = $request->monto_pago;
        $pago->save();

        $pagos = Pago_contrato::select('id')->where('contrato_id','=',$request->contrato_id)
            ->where('tipo_pagare','=',0)->orderBy('fecha_pago','asc')->get();

        if(sizeOf($pagos))
            foreach($pagos as $index => $p){
                $pag = Pago_contrato::findOrFail($p->id);
                $pag->num_pago = $index;
                $pag->save();
            }
    }

    //Función para actualizar el pagare
    public function actualizarPago(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = Pago_contrato::findOrFail($request->id);
        $pago->monto_pago = $request->monto;
        $pago->fecha_pago = $request->fecha;
        $pago->save();
    }

    //Función para eliminar un pagare
    public function eliminarPago(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = Pago_contrato::findOrFail($request->id);
        $pago->delete();
    }

    // Función para actualizar contrato
    public function actualizarContrato(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try {
            DB::beginTransaction();
            //Datos del cliente que se guardan en la tabla personal
            $personal = Personal::findOrFail($request->prospecto_id);
                $personal->apellidos = $request->apellidos;
                $personal->nombre = $request->nombre;
                $personal->f_nacimiento = $request->f_nacimiento;
                $personal->rfc = $request->rfc;
                $personal->homoclave = $request->homoclave;
                $personal->direccion = $request->direccion;
                $personal->cp = $request->cp;
                $personal->colonia = $request->colonia;
                $personal->telefono = $request->telefono;
                $personal->clv_lada = $request->clv_lada;
                $personal->celular = $request->celular;
                $personal->email = $request->email;

            //Datos del cliente que se guardan en la tabla clientes
            $cliente = Cliente::findOrFail($request->prospecto_id);
                $cliente->sexo = $request->sexo;
                $cliente->email_institucional = $request->email_institucional;
                $cliente->edo_civil = $request->edo_civil;
                $cliente->nss = $request->nss;
                $cliente->curp = $request->curp;
                $cliente->empresa = $request->empresa;
                $cliente->coacreditado = $request->coacreditado;
                $cliente->ciudad = $request->ciudad;
                $cliente->estado = $request->estado;
                $cliente->nacionalidad = $request->nacionalidad;
                $cliente->puesto = $request->puesto;
                $cliente->sexo_coa = $request->sexo_coa;
                $cliente->direccion_coa = $request->direccion_coa;
                $cliente->email_institucional_coa = $request->email_institucional_coa;
                $cliente->edo_civil_coa = $request->edo_civil_coa;
                $cliente->nss_coa = $request->nss_coa;
                $cliente->curp_coa = $request->curp_coa;
                $cliente->nombre_coa = $request->nombre_coa;
                $cliente->apellidos_coa = $request->apellidos_coa;
                $cliente->f_nacimiento_coa = $request->f_nacimiento_coa;
                $cliente->colonia_coa = $request->colonia_coa;
                $cliente->cp_coa = $request->cp_coa;
                $cliente->rfc_coa = $request->rfc_coa;
                $cliente->homoclave_coa = $request->homoclave_coa;
                $cliente->ciudad_coa = $request->ciudad_coa;
                $cliente->estado_coa = $request->estado_coa;
                $cliente->empresa_coa = $request->empresa_coa;
                $cliente->nacionalidad_coa = $request->nacionalidad_coa;
                $cliente->telefono_coa = $request->telefono_coa;
                $cliente->celular_coa = $request->celular_coa;
                $cliente->email_coa = $request->email_coa;
                $cliente->parentesco_coa = $request->parentesco_coa;
                $cliente->lugar_nacimiento = $request->lugar_nacimiento;
                $cliente->publicidad_id = $request->publicidad_id;
                $cliente->nombre_recomendado = $request->nombre_recomendado;

            //Datos que se almacenan en la tabla Creditos
            $credito = Credito::findOrFail($request->contrato_id);
                $credito->num_dep_economicos =  $request->num_dep_economicos;
                $credito->nombre_primera_ref = $request->nombre_primera_ref;
                $credito->telefono_primera_ref = $request->telefono_primera_ref;
                $credito->celular_primera_ref = $request->celular_primera_ref;
                $credito->nombre_segunda_ref = $request->nombre_segunda_ref;
                $credito->telefono_segunda_ref = $request->telefono_segunda_ref;
                $credito->celular_segunda_ref = $request->celular_segunda_ref;
                $credito->paquete =  $request->paquete;
                $credito->descripcion_paquete = $request->descripcion_paquete;
                $credito->costo_paquete = $request->costo_paquete;
                $credito->precio_venta = $request->precio_venta;
                $credito->credito_solic = $request->credito_solic;
                $credito->plazo = $request->plazo_credito;
                $credito->contrato = 1;

            // Se obtiene la institución de financiamiento actual del contrato
            $inst_sel = inst_seleccionada::select('id')
                ->where('credito_id','=',$request->contrato_id)
                ->where('elegido','=',1)->get();

            $credito_sol = inst_seleccionada::findOrFail($inst_sel[0]->id);
                $credito_sol->monto_credito = $request->credito_solic;
                //Se valida si hay algun cambio de financiamiento
                if($credito_sol->tipo_credito != $request->tipo_credito && $credito_sol->tipo_credito != 'Apartado'){
                    //En caso de detectarse un cambio, se crea el registro de la reubicación
                    $reubicacion = new ReubicacionController();
                    //Llamada a la función de reubicación desde el controlador de Reubicación.
                    $reubicacion->createReubicacion(
                                $credito->id,
                                $credito->lote_id,
                                $credito->prospecto_id,
                                $credito->vendedor_id,
                                $credito->promocion,
                                $credito_sol->tipo_credito,
                                $credito_sol->institucion,
                                $credito->valor_terreno,
                                'Se cambia de crédito por: '.$request->tipo_credito.' con '.$request->institucion,
                                ''
                            );
                }
                //Se guarda el cambio del nuevo credito.
                $credito_sol->tipo_credito = $request->tipo_credito;
                $credito_sol->institucion = $request->institucion;
                $credito_sol->plazo_credito = $request->plazo_credito;
                $credito_sol->save();

            //Se indica que el lote ha sido vendido.
            $lote = Lote::findOrFail($request->lote_id);
            $lote->contrato = 1;

            // Datos ue se almacenan en la tabla Contratos
            $contrato = Contrato::findOrFail($request->contrato_id);
                $contrato->infonavit = $request->infonavit;
                $contrato->fovisste = $request->fovisste;
                $contrato->comision_apertura = $request->comision_apertura;
                $contrato->investigacion = $request->investigacion;
                $contrato->avaluo = $request->avaluo;
                $contrato->avaluo_cliente = $request->avaluo_cliente;
                $contrato->prima_unica = $request->prima_unica;
                $contrato->escrituras = $request->escrituras;
                $contrato->credito_neto = $request->credito_neto;
                $contrato->monto_total_credito = $request->monto_total_credito;
                $contrato->total_pagar = $request->total_pagar;
                $contrato->enganche_total = $request->enganche_total;
                $contrato->fecha = $request->fecha;
                $contrato->direccion_empresa = $request->direccion_empresa;
                $contrato->cp_empresa = $request->cp_empresa;
                $contrato->colonia_empresa = $request->colonia_empresa;
                $contrato->estado_empresa = $request->estado_empresa;
                $contrato->ciudad_empresa = $request->ciudad_empresa;
                $contrato->telefono_empresa = $request->telefono_empresa;
                $contrato->ext_empresa = $request->ext_empresa;
                $contrato->direccion_empresa_coa = $request->direccion_empresa_coa;
                $contrato->cp_empresa_coa = $request->cp_empresa_coa;
                $contrato->colonia_empresa_coa = $request->colonia_empresa_coa;
                $contrato->estado_empresa_coa = $request->estado_empresa_coa;
                $contrato->ciudad_empresa_coa = $request->ciudad_empresa_coa;
                $contrato->telefono_empresa_coa = $request->telefono_empresa_coa;
                $contrato->ext_empresa_coa = $request->ext_empresa_coa;
                $contrato->observacion = $request->observacion;
                $contrato->publicidad_id = $request->publicidad_id;

            //Se obtienen los intereses generados, en caso de tener
            $sumaIntereses = Expediente::select(DB::raw("SUM(interes_ord) as suma"))->where('id','=',$request->contrato_id)->get();
                if($sumaIntereses[0]->suma == NULL){
                    $sumaIntereses[0]->suma = 0;
                }
            //Se obtienen los descuentos generados, en caso de tener
            $sumaDescuento = Expediente::select(DB::raw("SUM(descuento) as suma"))->where('id','=',$request->contrato_id)->get();
                if($sumaDescuento[0]->suma == NULL){
                    $sumaDescuento[0]->suma = 0;
                }

            //Se obtienen la suma de gastos administrativos, en caso de tener
            $sumaGastos = Gasto_admin::select(DB::raw("SUM(costo) as suma"))->where('contrato_id','=',$request->contrato_id)->get();
                if($sumaGastos[0]->suma == NULL){
                    $sumaGastos[0]->suma = 0;
                }

            //Se obtiene la suma de depositos realizados
            $sumaDeposito = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.cant_depo) as suma"))->where('contratos.id','=',$request->contrato_id)->get();
                if($sumaDeposito[0]->suma == NULL){
                    $sumaDeposito[0]->suma = 0;
                }
            //Se obtiene la suma de credito principal cobrados
            $sumaDepositoCredit = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->select(DB::raw("SUM(dep_creditos.cant_depo) as suma"))->where('contratos.id','=',$request->contrato_id)
                ->where('inst_seleccionadas.elegido','=',1)
                ->get();
                if($sumaDepositoCredit[0]->suma == NULL){
                    $sumaDepositoCredit[0]->suma = 0;
                }

            //Llamada a la función privada para calcular el monto cobrado del terreno para lotes con alianza.
            $this->calculateSaldoTerreno($request->contrato_id);

            //Se obtiene la suma de segundo credito cobrado
            $sumaDepositoCredit2 = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->select(DB::raw("SUM(dep_creditos.cant_depo) as suma"))->where('contratos.id','=',$request->contrato_id)
                ->where('inst_seleccionadas.tipo','=',1)
                ->get();
                if($sumaDepositoCredit[0]->suma == NULL){
                    $sumaDepositoCredit[0]->suma = 0;
                }
            
            $sumaTotal =  $sumaIntereses[0]->suma + $sumaGastos[0]->suma - $sumaDeposito[0]->suma - $sumaDepositoCredit[0]->suma - $sumaDepositoCredit2[0]->suma - $sumaDescuento[0]->suma;
            // Se almacena el saldo.
            $contrato->saldo = $credito->precio_venta + $sumaTotal;

            //Guardar el costo del lote
            $loteId = $credito->lote_id;
            //Guardar el costo del lote
                $etapa = Lote::join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->select('etapas.id', 'lotes.terreno','etapas.terreno_m2 as etapaTerreno','modelos.tipo','lotes.indivisos')
                ->where('lotes.id', '=', $loteId)->get();

                //Se obtiene el precio por m2 del terreno para esa etapa
                $precioT = Precios_terreno::where('etapa_id', '=', $etapa[0]->id)
                    ->where('estatus', '=', 1)
                ->first();

                if($precioT){
                    //Se calcula el valor de terreno para departamentos
                    if($etapa[0]->tipo == 2){
                        $credito->valor_terreno = ($etapa[0]->etapaTerreno*($etapa[0]->indivisos/100))*$precioT->precio_m2;
                    }
                    //Se calcula el valor de terreno para vivienda
                    else{
                        $credito->valor_terreno = ($precioT->precio_m2* $etapa[0]->terreno) + $precioT->total_gastos;
                        
                    //  $credito->valor_terreno = $credito->valor_terreno * 1.10;
                    }
                    $credito->porcentaje_terreno = ((($credito->valor_terreno)*100)/$credito->precio_venta);
                }
            //Guardar el costo del lote
            $lote->save();
            $credito->save();
            $personal->save();
            $cliente->save();
            $contrato->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función privada para retornar un arreglo de cuentas bancarias por empresa.
    private function getCuentas($cuenta){
        $cuentas = Cuenta::select('num_cuenta','banco')->where('empresa','=',$cuenta)->get();
        $arrayCuentas = [];

        foreach($cuentas as $index => $cuenta){
            array_push($arrayCuentas,$cuenta->num_cuenta.'-'.$cuenta->banco);
        }

        return $arrayCuentas;
    }

    // Función privada para calcular el monto cobrado del terreno en lotes alianza.
    private function calculateSaldoTerreno($id){
        $credito = Credito::findOrFail($id);
        // Se obtinene las cuentas bancarias de cumbres.
        $cuentas = $this->getCuentas('Grupo Constructor Cumbres');

            // Se obtiene la suma de traspasos de créditos por alianza.
            $sumaDepositoCreditTerreno = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->select(DB::raw("SUM(dep_creditos.monto_terreno) as suma"))->where('contratos.id','=',$id)
                ->where('inst_seleccionadas.elegido','=',1)
                ->where('dep_creditos.fecha_ingreso_concretania','!=',NULL)
                ->get();
                if($sumaDepositoCreditTerreno[0]->suma == NULL){
                    $sumaDepositoCreditTerreno[0]->suma = 0;
                }

            // Se obtiene la suma de traspasos de depositos por alianza.
            $sumaDepositoTerreno = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.monto_terreno) as suma"))->where('contratos.id','=',$id)
                ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                ->where('depositos.lote_id','=',$credito->lote_id)
                ->get();
                if($sumaDepositoTerreno[0]->suma == NULL){
                    $sumaDepositoTerreno[0]->suma = 0;
                }

            // Se obtinene la suma de depositos ingresados a cuentas Cumbres.
            $sumaCuentaCumbres = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.cant_depo) as suma"))
                ->where('contratos.id','=',$id)
                ->where('depositos.lote_id','=',$credito->lote_id)
                ->whereIn('depositos.banco',$cuentas)
                ->get();
                if($sumaCuentaCumbres[0]->suma == NULL){
                    $sumaCuentaCumbres[0]->suma = 0;
                }

            // Se obtienen la suma de depositos virtuales a Cumbres
            $depositoGCC = Deposito_gcc::select(DB::raw("SUM(depositos_gcc.monto) as suma"))
                ->where('depositos_gcc.contrato_id','=',$id)
                ->where('depositos_gcc.lote_id','=',$credito->lote_id)
                ->get();
                if($depositoGCC[0]->suma == NULL){
                    $depositoGCC[0]->suma = 0;
                }

            // Se obtienen la suma de depositos virtuales a Concretania
            $depositoConc = Deposito_conc::select(DB::raw("SUM(monto) as suma"))
                ->where('contrato_id','=',$id)
                ->where('lote_id','=',$credito->lote_id)
                ->where('devolucion','=',1)
                ->get();
                if($depositoConc[0]->suma == NULL){
                    $depositoConc[0]->suma = 0;
                }
        // Calcula el saldo.
        $credito->saldo_terreno = $sumaDepositoCreditTerreno[0]->suma + $sumaCuentaCumbres[0]->suma + 
        $sumaDepositoTerreno[0]->suma + $depositoGCC[0]->suma -  $depositoConc[0]->suma;
        $credito->save();
    }

    // Función para reubicar clientes con contrato a otros lotes.
    public function reasignarCliente(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            //Variable para almacenar el nuevo lote.
            $loteNuevo_id = $request->sel_lote;
            // Se obtienen los datos del lote anterior al cambio.
            $lote_ant = Lote::findOrFail($request->lote_id);
            $terrenoExcedenteOld = 0;
                    // Precio actual de terreno por m2
                    $precioTerrenoOld = Precio_etapa::select('precio_excedente','id')
                    ->where('etapa_id','=',$lote_ant->etapa_id)
                    ->where('fraccionamiento_id','=',$lote_ant->fraccionamiento_id)->get();
                    // Medida base de terreno para el modelo.
                    $terrenoModelo = Modelo::select('terreno')
                    ->where('id','=',$lote_ant->modelo_id)
                    ->get();
                    // Precio base actual del modelo.
                    $precioBaseOld = Precio_modelo::select('precio_modelo')
                    ->where('modelo_id','=',$lote_ant->modelo_id)
                    ->where('precio_etapa_id', '=', $precioTerrenoOld[0]->id)
                    ->get();
                    // Sobreprecios del lote
                    $sobrepreciosOld = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                    ->where('sobreprecios_modelos.lote_id','=',$lote_ant->id)->get();
                    // Se calcula el excedente de terreno
                    $terrenoExcedenteOld = ($lote_ant->terreno - $terrenoModelo[0]->terreno);
                    if($terrenoExcedenteOld > 0)
                        $lote_ant->excedente_terreno = $terrenoExcedenteOld * $precioTerrenoOld[0]->precio_excedente;
                    //Se asigna el precio de base actual del lote.
                    $lote_ant->precio_base = $precioBaseOld[0]->precio_modelo;
                    //Se asigna el valor de sobreprecio actual.
                    if($sobrepreciosOld[0]->sobreprecios != NULL)
                        $lote_ant->sobreprecio = $sobrepreciosOld[0]->sobreprecios;
                    else
                        $lote_ant->sobreprecio = 0;

            //Variable para almacenar el estado del lote ant para venta.
            $varContrato = $lote_ant->contrato;
            $lote_ant->contrato = 0;
            $lote_ant->paquete = '';
            //$lote_ant->apartado = 0;
            $lote_ant->save();

            //Se elimina el apartado asignado al lote anterior
            // $apartado = Apartado::select('id')->where('lote_id','=',$lote_ant->id)->get();
            // if(sizeof($apartado))
            //     foreach($apartado as $ap){
            //         $borrarApartado = Apartado::findOrFail($ap->id);
            //         $borrarApartado->delete();
            //     }
                
            DB::beginTransaction();

            //Nuevo lote
            $lote_new = Lote::findOrFail($loteNuevo_id);
            $new_avance = Licencia::findOrFail($loteNuevo_id);

            /////////////////////////////////////////////////////////////////
            // Se obtiene el valor de m2 por excedente para la etapa del nuevo lote.
            $precio_etapa = Precio_etapa::select('id','precio_excedente')
                            ->where('fraccionamiento_id','=',$lote_new->fraccionamiento_id)
                            ->where('etapa_id','=',$lote_new->etapa_id)->get();
            // Se obtiene el precio del modelo para el nuevo lote.
            $precio_modelo = Precio_modelo::select('precio_modelo')->where('precio_etapa_id','=',$precio_etapa[0]->id)
                            ->where('modelo_id','=',$lote_new->modelo_id)->get();
            // Se obtienen los sobreprecios del nuevo lote.
            $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
            ->where('sobreprecios_modelos.lote_id','=',$loteNuevo_id)->get();
            // Medida estandar del modelo para el nuevo lote.
            $modelo = Modelo::select('terreno')->where('id','=',$lote_new->modelo_id)->get();
            // Se calcula el terreno excedente.
            $terrenoExcedente = round(($lote_new->terreno - $modelo[0]->terreno),2);
                if((double)$terrenoExcedente > 0)
                    $lote_new->excedente_terreno = round(($terrenoExcedente * $precio_etapa[0]->precio_excedente), 2);
                else {
                    $lote_new->excedente_terreno = 0;
                }
            
            $lote_new->precio_base = $precio_modelo[0]->precio_modelo;
            $lote_new->precio_base = round(($lote_new->precio_base), 2);
            $precio_venta = round(($sobreprecios[0]->sobreprecios + $lote_new->precio_base + $lote_new->ajuste + $lote_new->excedente_terreno + $lote_new->obra_extra),2);
            $terreno_tam_excedente = round(($lote_new->terreno - $modelo[0]->terreno),2);
            $lote_new->contrato = 1;

            ////////////////////////////////////////////////////////////////////////////////////////
            $credito = Credito::findOrFail($request->id);
            $contrato = Contrato::findOrFail($request->id);
            
            //Se obtienen los datos sobre la institución de financiamiento actual.
            $institucion = inst_seleccionada::select('tipo_credito','institucion')
                        ->where('credito_id','=',$credito->id)
                        ->where('elegido','=',1)->first();
            
            // Si se requiere registrar la reubicación
            if($request->reubicar == 1){
                // Se crea el registro de reubicación.
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
                                $request->observacion,
                                ''
                            );
            }
            

            //Se actualizan los datos en el registro de credito y contrato.
            $contrato->avance_lote = $new_avance->avance;
            // Si el contrato ya tiene creada una comision, se indica que requiere una reubicacion de comisión.
            if($contrato->comision == 1)
                $contrato->comision = 3;

            $credito->fraccionamiento = $request->fraccionamiento;
            $credito->etapa = $request->etapa;
            $credito->manzana = $request->manzana;
            $credito->num_lote = $request->num_lote;
            $credito->modelo = $request->modelo;
            $credito->precio_base = $lote_new->precio_base + $lote_new->ajuste;
            $credito->superficie = $request->superficie;
            $credito->terreno_excedente = $terreno_tam_excedente;
            $credito->precio_terreno_excedente = $lote_new->excedente_terreno;
            $credito->precio_obra_extra = $request->precio_obra_extra;
            $credito->promocion = $request->promocion;
            $credito->descripcion_promocion = $request->descripcion_promocion;
            $credito->descuento_promocion = $request->descuento_promocion;
            $credito->paquete = '';
            $credito->descripcion_paquete = '';
            $credito->costo_paquete = 0;
            $credito->precio_venta = round($precio_venta - $request->descuento_promocion,2);
            $credito->lote_id = $loteNuevo_id;
            $credito->save();
            $contrato->save();
            $lote_new->save();
            DB::commit();
        } catch (Exception $e) { 
            DB::rollBack();
        }
    }

    // Función para reubicar clientes sin contrato a otros lotes.
    public function reasignarCliente2(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            //Variable para almacenar el nuevo lote
            $loteNuevo_id = $request->sel_lote;

            //Se accede al registro del lote actual
            $lote_ant = Lote::findOrFail($request->lote_id);
            $terrenoExcedenteOld = 0;
                    // Se obtienen el precio de m2 por terreno excedente del lote actual
                    $precioTerrenoOld = Precio_etapa::select('precio_excedente','id')
                    ->where('etapa_id','=',$lote_ant->etapa_id)
                    ->where('fraccionamiento_id','=',$lote_ant->fraccionamiento_id)->get();
                    // Se obtienen la medida base del modelo del lote actual
                    $terrenoModelo = Modelo::select('terreno')
                    ->where('id','=',$lote_ant->modelo_id)
                    ->get();
                            
                    // Se obtienen el precio de m2 por terreno excedente del lote actual
                    $precioBaseOld = Precio_modelo::select('precio_modelo')
                    ->where('modelo_id','=',$lote_ant->modelo_id)
                    ->where('precio_etapa_id', '=', $precioTerrenoOld[0]->id)
                    ->get();
            
                    // Se obtienen los sobreprecios del lote actual
                    $sobrepreciosOld = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                    ->where('sobreprecios_modelos.lote_id','=',$lote_ant->id)->get();

                    // Se calcula el costo del terreno excedente actual para el lote.
                    $terrenoExcedenteOld = ($lote_ant->terreno - $terrenoModelo[0]->terreno);
                    if($terrenoExcedenteOld > 0)
                        $lote_ant->excedente_terreno = $terrenoExcedenteOld * $precioTerrenoOld[0]->precio_excedente;

                    // Se asigna el precio base actual al lote
                    $lote_ant->precio_base = $precioBaseOld[0]->precio_modelo;

                    if($sobrepreciosOld[0]->sobreprecios != NULL)
                        $lote_ant->sobreprecio = $sobrepreciosOld[0]->sobreprecios;
                    else
                        $lote_ant->sobreprecio = 0;

            $varContrato = $lote_ant->contrato;
            $lote_ant->paquete = '';
            //$lote_ant->apartado = 0;
            $lote_ant->save();

            // //Se elimina el apartado asignado al lote anterior
            // $apartado = Apartado::select('id')->where('lote_id','=',$lote_ant->id)->get();
            // if(sizeof($apartado))
            //     foreach($apartado as $ap){
            //         $borrarApartado = Apartado::findOrFail($ap->id);
            //         $borrarApartado->delete();
            //     }
                
            DB::beginTransaction();

            // Se accede al nuevo lote.
            $lote_new = Lote::findOrFail($loteNuevo_id);
            $new_avance = Licencia::findOrFail($loteNuevo_id);

            /////////////////////////////////////////////////////////////////
            // Se obtiene el precio por m2 de terreno excedente para el nuevo lote
            $precio_etapa = Precio_etapa::select('id','precio_excedente')
                            ->where('fraccionamiento_id','=',$lote_new->fraccionamiento_id)
                            ->where('etapa_id','=',$lote_new->etapa_id)->get();
            // Se obtiene el precio base del nuevo lote
            $precio_modelo = Precio_modelo::select('precio_modelo')->where('precio_etapa_id','=',$precio_etapa[0]->id)
                            ->where('modelo_id','=',$lote_new->modelo_id)->get();
            // Se obtienen los sobreprecios del nuevo lote
            $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
            ->where('sobreprecios_modelos.lote_id','=',$loteNuevo_id)->get();
            // Se obtiene la superficie de terreno estandar para el modelo del nuevo lote.
            $modelo = Modelo::select('terreno')->where('id','=',$lote_new->modelo_id)->get();
            // Se calcula el costo por terreno excedente.
            $terrenoExcedente = round(($lote_new->terreno - $modelo[0]->terreno),2);
                if((double)$terrenoExcedente > 0)
                    $lote_new->excedente_terreno = round(($terrenoExcedente * $precio_etapa[0]->precio_excedente), 2);
                else {
                    $lote_new->excedente_terreno = 0;
                }
            $lote_new->precio_base = $precio_modelo[0]->precio_modelo;
            $lote_new->precio_base = round(($lote_new->precio_base), 2);
            // Se calcula el precio de venta para el nuevo lote.
            $precio_venta = round(($sobreprecios[0]->sobreprecios + $lote_new->precio_base + $lote_new->ajuste + $lote_new->excedente_terreno + $lote_new->obra_extra),2);
            $terreno_tam_excedente = round(($lote_new->terreno - $modelo[0]->terreno),2);
            ////////////////////////////////////////////////////////////////////////////////////////
            // Se actualiza los datos de la simulación de crédito.
            $credito = Credito::findOrFail($request->id);
            $credito->fraccionamiento = $request->fraccionamiento;
            $credito->etapa = $request->etapa;
            $credito->manzana = $request->manzana;
            $credito->num_lote = $request->num_lote;
            $credito->modelo = $request->modelo;
            $credito->precio_base = $lote_new->precio_base + $lote_new->ajuste;
            $credito->superficie = $request->superficie;
            $credito->terreno_excedente = $terreno_tam_excedente;
            $credito->precio_terreno_excedente = $lote_new->excedente_terreno;
            $credito->precio_obra_extra = $request->precio_obra_extra;
            $credito->promocion = $request->promocion;
            $credito->descripcion_promocion = $request->descripcion_promocion;
            $credito->descuento_promocion = $request->descuento_promocion;
            $credito->paquete = '';
            $credito->descripcion_paquete = '';
            $credito->costo_paquete = 0;
            $credito->precio_venta = round($precio_venta - $request->descuento_promocion,2);
            $credito->lote_id = $loteNuevo_id;
            $credito->save();
            $lote_new->save();
            DB::commit();

            } catch (Exception $e) { 
                DB::rollBack();
        }
    }

    // Función para retornar los contratos registrados en excel.
    public function excelContratos (Request $request){
        $buscar = $request->buscar;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $b_status = $request->b_status;
        $f_ini = $request->f_ini;
        $f_fin = $request->f_fin;
        $publicidad = $request->publicidad;

        // Llama a la función privada que retorna la query
        $contratos = $this->getContratos();
        $contratos = $contratos->where('inst_seleccionadas.elegido', '=', '1');
        //Filtro empresa donde trabaja el cliente
        if($request->b_empresa_cliente != '')
            $contratos= $contratos->where('clientes.empresa', 'like', '%'.$request->b_empresa_cliente.'%');
        //Filtro nombre de institucion de financiamiento
        if($request->b_institucion != '')
            $contratos= $contratos->where('inst_seleccionadas.institucion', 'like', '%'.$request->b_institucion.'%');

        // Filtro para empresa constructora
        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }
        // Filtro para medio publicitario
        if($publicidad != '')
            $contratos  = $contratos->where('contratos.publicidad_id', '=',  $publicidad);
        // Filtro para estatus de contrato
        if($b_status != ''){
            $contratos= $contratos->where('contratos.status','=',$b_status);
        }

        if ($buscar != '') {
            switch ($criterio) {
                case 'personal.nombre': {
                        $contratos = $contratos
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                case 'v.nombre': {
                        $contratos = $contratos
                            ->where(DB::raw("CONCAT(v.nombre,' ',v.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                case 'inst_seleccionadas.tipo_credito': {
                        $contratos = $contratos
                            ->where($criterio, 'like', '%' . $buscar . '%');
                        break;
                    }
                case 'creditos.id': {
                        $contratos = $contratos
                            ->where($criterio, 'like', '%' . $buscar . '%');
                        break;
                    }
                case 'creditos.vendedor_id': {
                    $contratos = $contratos->where($criterio, '=',$buscar);

                        if($buscar3 != '')
                            $contratos = $contratos->where('lotes.fraccionamiento_id','=',$buscar3);
                        if($b_etapa != '')
                            $contratos = $contratos->where('lotes.etapa_id','=',$b_etapa);
                        if($f_fin != '' || $f_ini != '')
                            $contratos = $contratos->whereBetween('contratos.fecha', [$f_ini, $f_fin]);
                    break;
                    }
                case 'contratos.fecha': {
                        $contratos = $contratos
                            ->whereBetween($criterio, [$buscar, $buscar3]);
                        break;
                    }

                case 'contratos.fecha_status': {
                        $contratos = $contratos
                            ->whereBetween($criterio, [$buscar,  $buscar3]);
                        break;
                    }
                
                case 'creditos.fraccionamiento': {
                        $contratos = $contratos;
                            if($buscar != '')
                                $contratos = $contratos->where('lotes.fraccionamiento_id', '=',  $buscar);
                            if($b_etapa != '')
                                $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                            if($b_manzana != '')
                                $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                            if($b_lote != '')
                                $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
                            if($f_ini != '' || $f_fin != '')
                                $contratos = $contratos->whereBetween('contratos.fecha', [$f_ini, $f_fin]);
                        break;
                    }
            }
        }

        $contratos = $contratos->orderBy('id', 'desc')->get();
        // Retorno de resultado en excel.
        return Excel::create('contratos', function($excel) use ($contratos){
            $excel->sheet('contratos', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Contrato', 'Cliente', 'Telefono', 'Celular','Email', 'Empresa', 'Vendedor', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Modelo', 'Tipo de crédito', 'Institución','Fecha del contrato', 'Precio de Venta', 'Status', 'Publicidad'
                ]);

                $sheet->cells('A1:R1', function ($cells) {
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
                    'P' => '$#,##0.00',
                ));

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    switch($contrato->status){
                        case 0: {
                            $status = 'Cancelado';
                            break;
                        }
                        case 1:{
                            $status = 'Pendiente';
                            break;
                        }
                        case 2:{
                            $status = 'No firmado';
                            break;
                        }
                        case 3:{
                            $status = 'Firmado';
                            break;
                        }

                    }

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($contrato->fecha);
                    $contrato->fecha = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->contratoId, 
                        $contrato->nombre. ' ' . $contrato->apellidos,
                        $contrato->telefono,
                        $contrato->celular,
                        $contrato->email,
                        $contrato->empresa,
                        $contrato->vendedor_nombre. ' ' .$contrato->vendedor_apellidos,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->fecha,
                        $contrato->precio_venta,
                        $status,
                        $contrato->publicidad,

                    ]);	
                }
                $num='A1:R' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    // Función que retorna si un contrato ya se encuentra en un contrato firmado o pendiente.
    public function validarLoteEnContrato(Request $request){
        if(!$request->ajax())return redirect('/');
        $idLote = $request->lote_id;
        $lote = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->select('creditos.id')
                        ->where('contratos.status','=',1)
                        ->where('creditos.lote_id','=',$idLote)
                        ->orwhere('contratos.status','=',3)
                        ->where('creditos.lote_id','=',$idLote)
                        ->count();

        return ['lote' => $lote];
    }

    // Función para detener o reanudar proceso del contrato.
    public function cambiarProceso(Request $request){
        $contrato = Contrato::findOrFail($request->id);
        $contrato->detenido = $request->detenido;
        $contrato->save();
    }

    // Función para indicar que el expediente del contrato ah sido integrado.
    public function entregarExp(Request $request){
        if(!$request->ajax())return redirect('/');
        $contrato = Contrato::findOrFail($request->id);
        $contrato->exp_bono = 1;
        $contrato->save();
    }

    // Función para imprimir Anexo de contratos para departamento.
    public function printAnexoA($id){
        $contrato =  Credito::join('lotes','creditos.lote_id','=','lotes.id')
                        //->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('lotes.colindancias','fraccionamientos.logo_fracc')
                        ->where('creditos.id','=',$id)
                        ->first();

        // return $contrato;
        $pdf = \PDF::loadview('pdf.contratos.anexoA', ['contrato' => $contrato]);
        return $pdf->stream('anexoA.pdf');
    }

    // Funcion para generar reporte Modelo Caco
    public function reportEli(Request $request){
        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->leftJoin('expedientes','contratos.id','=','expedientes.id')
                        ->leftJoin('personal as g','expedientes.gestor_id','=','g.id')
                        ->join('personal as cli','creditos.prospecto_id','=','cli.id')
                        ->join('personal as v','creditos.vendedor_id','=','v.id')
                        ->join('vendedores','v.id','=','vendedores.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->select(
                            'contratos.id',
                            'contratos.saldo',
                            'creditos.lote_id',
                            'fraccionamientos.nombre as proyecto',
                            'etapas.num_etapa as etapa',
                            'lotes.manzana',
                            'lotes.num_lote',
                            'modelos.nombre as modelo',
                            'lotes.calle', 'lotes.numero','lotes.interior',
                            DB::raw("CONCAT(cli.nombre,' ',cli.apellidos) AS nombre_cliente"),
                            'contratos.fecha',
                            'lotes.fecha_termino_ventas',
                            'contratos.status',
                            'expedientes.fecha_firma_esc',
                            'expedientes.liquidado',
                            'i.tipo_credito',
                            'i.institucion', 
                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor"),
                            'lotes.credito_puente',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_asesor"),
                            'vendedores.tipo',
                            'creditos.precio_venta',
                            'expedientes.valor_escrituras',
                            'contratos.integracion'
                        )
                        ->where('contratos.status','!=',0)
                        ->where('contratos.status','!=',2)
                        ->where('i.elegido', '=', 1)
                        ->orderBy('fraccionamientos.nombre','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->get();

        $lotes = [];

        foreach ($contratos as $key => $contrato) {
            array_push($lotes,$contrato->lote_id);

            $contrato->pagare = Pago_contrato::select('monto_pago','fecha_pago')
                ->where('contrato_id','=',$contrato->id)->where('tipo_pagare','=',0)->orderBy('fecha_pago','desc')->first();

                $contrato->avaluo_status = 'Sin solicitarse';
                $contrato->fecha_avaluo = '';
                $contrato->estado_casa = 'Vendida';
                $contrato->responsable = $contrato->nombre_asesor;
            $avaluo = Avaluo::where('contrato_id','=',$contrato->id)->orderBy('created_at','desc')->first();

            if($avaluo){
                $contrato->avaluo_status = $avaluo->status;

                if($avaluo->fecha_solicitud != NULL)
                    $contrato->fecha_avaluo = 'Fecha de solicitud: '.$avaluo->fecha_solicitud;

                if($avaluo->fecha_recibido != NULL)
                    $contrato->fecha_avaluo = 'Fecha recibido: '.$avaluo->fecha_recibido;

                if($avaluo->fecha_concluido != NULL)
                    $contrato->fecha_avaluo = 'Fecha concluido: '.$avaluo->fecha_concluido;
            }

            if($contrato->interior != NULL)
                $contrato->numero = $contrato->numero.'-'.$contrato->interior;

            if($contrato->tipo == 0)
                $contrato->tipo = 'Interno';
            else
                $contrato->tipo = 'Externo';

            if($contrato->tipo_credito == 'Crédito Directo' && $contrato->liquidado == 1)
                $contrato->estado_casa = 'Individualizada';
            
            if($contrato->tipo_credito != 'Crédito Directo' && $contrato->fecha_firma_esc != NULL)
                $contrato->estado_casa = 'Individualizada';

            if($contrato->integracion == 1 && $contrato->nombre_gestor == NULL || $contrato->integracion == 0 && $contrato->status == 1)
                $contrato->responsable = 'Mary Dominguez';
            if($contrato->nombre_gestor != NULL)
                $contrato->responsable = $contrato->nombre_gestor;
        }

        $lotesDisp = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->select(
                    'fraccionamientos.nombre as proyecto',
                    'etapas.num_etapa as etapa',
                    'lotes.manzana',
                    'lotes.num_lote',
                    'modelos.nombre as modelo',
                    'lotes.calle', 'lotes.numero','lotes.interior',
                    'lotes.fecha_termino_ventas',
                    'lotes.precio_base', 'lotes.obra_extra', 'lotes.excedente_terreno',
                    'lotes.sobreprecio', 'lotes.credito_puente','lotes.habilitado'
                )
                ->whereNotIn('lotes.id',$lotes)
                ->orderBy('fraccionamientos.nombre','asc')
                ->orderBy('etapas.num_etapa','asc')
                ->get();

        foreach ($lotesDisp as $key => $lote) {
            if($lote->habilitado == 1)
                $lote->habilitado = 'Habilitado para venta';
            else
                $lote->habilitado = 'Deshabilitado';

            $lote->precio_venta = $lote->precio_base + $lote->obra_extra + $lote->excedente_terreno + $lote->sobreprecio;
        }

        return Excel::create('Modelo Caco', function($excel) use ($contratos,$lotesDisp){
            
            $excel->sheet('Ventas', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '#Folio', 'Fracc.', 'Etapa', 'Mnza', 'Lote', 'Modelo', 'Calle', 'No. Oficial', 'Cliente',
                    'Fecha de venta', 'Fecha compromiso de termino', 'Estado de la casa', 'Crédito', 'Institución',
                    'Gestor', 'Crédito Puente', 'Vigencia del Crédito', 'Responsable actual', 'Comentarios Eli', 
                    'Status Avaluo', 'Fecha avaluo', 'Asesor', 'Tipo Asesor', 'Ultima fecha de enganche', 'Monto', 
                    'Valor de venta', 'Valor a escriturar',
                ]);

                $sheet->cells('A1:AA1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
  
                $cont=1;

                $sheet->setColumnFormat(array(
                    'Z' => '$#,##0.00',
                    'Y' => '$#,##0.00',
                    'AA' => '$#,##0.00'
                ));

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    $sheet->row($index+2, [
                        $contrato->id, 
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->calle,
                        $contrato->numero,
                        $contrato->nombre_cliente,
                        $contrato->fecha,
                        $contrato->fecha_termino_ventas,
                        $contrato->estado_casa,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->nombre_gestor,
                        $contrato->credito_puente,
                        '',
                        $contrato->responsable,
                        '',
                        $contrato->avaluo_status,
                        $contrato->fecha_avaluo,
                        $contrato->nombre_asesor,
                        $contrato->tipo,
                        $contrato->pagare['fecha_pago'],
                        $contrato->pagare['monto_pago'],
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                    ]);	
                }
                $num='A1:AA' . $cont;
                $sheet->setBorder($num, 'thin');
            });

            $excel->sheet('Lotes Disponibles', function($sheet) use ($lotesDisp){
                
                $sheet->row(1, [
                    '#Folio', 'Fracc.', 'Etapa', 'Mnza', 'Lote', 'Modelo', 'Calle', 'No. Oficial', 'Cliente',
                    'Fecha de venta', 'Fecha compromiso de termino', 'Estado de la casa', 'Crédito', 'Institución',
                    'Gestor', 'Crédito Puente', 'Vigencia del Crédito', 'Responsable actual', 'Comentarios Eli', 
                    'Status Avaluo', 'Fecha avaluo', 'Asesor', 'Tipo Asesor', 'Ultima fecha de enganche', 'Monto', 
                    'Valor de venta', 'Valor a escriturar',          ]);

                $sheet->cells('A1:AA1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
  
                $cont=1;

                $sheet->setColumnFormat(array(
                    'Z' => '$#,##0.00',
                    'Y' => '$#,##0.00',
                    'AA' => '$#,##0.00'
                ));

                foreach($lotesDisp as $index => $lote) {
                    $cont++;

                    $sheet->row($index+2, [
                        '',
                        $lote->proyecto,
                        $lote->etapa,
                        $lote->manzana,
                        $lote->num_lote,
                        $lote->modelo,
                        $lote->calle,
                        $lote->numero,
                        '',
                        '',
                        $lote->fecha_termino_ventas,
                        $lote->habilitado,
                        '',
                        '',
                        '',
                        $lote->credito_puente,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $lote->precio_venta,
                        ''
                    ]);	
                }
                $num='A1:AA' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    // Función para subir archivo fiscal para ventas.
    public function formSubmitFisc(Request $request, $id){
        $fileName = $request->archivo->getClientOriginalName();
        $moved =  $request->archivo->move(public_path('/files/datosFisc/'), $fileName);

        if($moved){
            $contrato = Credito::findOrFail($id);
            $contrato->archivo_fisc = $fileName;
            $contrato->fecha_archivo = Carbon::now();
            $contrato->save(); //Insert
        }
        
    	return response()->json(['success'=>'You have successfully upload file.']);
    }

    // Función que descarga el archivo fiscal de una venta.
    public function downloadFileFisc($fileName)
    {
        $pathtoFile = public_path() . '/files/datosFisc/' . $fileName;
        return response()->download($pathtoFile);
    }
}
