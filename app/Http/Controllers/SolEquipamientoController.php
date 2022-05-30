<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipamiento;
use App\Proveedor;
use App\Contrato;
use DB;
use Carbon\Carbon;
use Auth;
use App\Solic_equipamiento;
use App\Obs_solic_equipamiento;
use App\User;
use App\Notifications\NotifyAdmin;
use App\Credito;
use File;

class SolEquipamientoController extends Controller
{   

    //Funcion para optener informacion general sobre las solicitudes de equipamiento
    public function indexHistorial(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $userID = Auth::user()->id;
        $rolID = Auth::user()->rol_id;
        $status = $request->status;

        // consulta principal 
        $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->leftjoin('recep_equipamientos', 'solic_equipamientos.id', '=', 'recep_equipamientos.id')

            ->select('solic_equipamientos.fecha_solicitud',
                    'solic_equipamientos.id',
                    'solic_equipamientos.lote_id',
                    'solic_equipamientos.costo',
                    'solic_equipamientos.fecha_solicitud',
                    'solic_equipamientos.fecha_colocacion',
                    'solic_equipamientos.anticipo',
                    'solic_equipamientos.fecha_anticipo',
                    'solic_equipamientos.liquidacion',
                    'solic_equipamientos.fecha_liquidacion',
                    'solic_equipamientos.fin_instalacion',
                    'solic_equipamientos.status',
                    'solic_equipamientos.recepcion',
                    'solic_equipamientos.num_factura',
                    'solic_equipamientos.control',
                    'solic_equipamientos.anticipo_cand',
                    'solic_equipamientos.liquidacion_cand',
                    'proveedores.proveedor',
                    'proveedores.tipo',
                    'equipamientos.equipamiento',
                    'equipamientos.tipoRecepcion',
                    'contratos.id as folio',
                    'creditos.modelo',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'fraccionamientos.nombre as proyecto',
                    'etapas.num_etapa as etapa',
                    'lotes.manzana', 'lotes.sublote',
                    'lotes.num_lote','licencias.avance',
                    DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                    DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'),
                    DB::raw('DATEDIFF(recep_equipamientos.fecha_revision, solic_equipamientos.fin_instalacion) as dias_rev'),

                    'solic_equipamientos.comp_pago_1',
                    'solic_equipamientos.comp_pago_2'
        );
        if($status!=''){
            $equipamientos = $equipamientos->where('solic_equipamientos.status','=',$status);
        }

        if($request->liquidado != ''){
            if($request->liquidado == 1)
                $equipamientos = $equipamientos->where('solic_equipamientos.fecha_liquidacion','=',NULL);
            if($request->liquidado == 2)
                $equipamientos = $equipamientos->where('solic_equipamientos.fecha_liquidacion','!=',NULL);
        }

        // Criterios de busqueda
        if($buscar != ''){
            switch($criterio){
                case 'c.nombre':{
                    $equipamientos = $equipamientos
                            ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'contratos.id':{
                    $equipamientos = $equipamientos
                            ->where($criterio, '=', $buscar);
                    break;
                }
                case 'proveedores.proveedor':{
                    $equipamientos = $equipamientos
                            ->where($criterio, 'like', '%'. $buscar . '%');
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                        $equipamientos = $equipamientos->where($criterio, '=', $buscar);

                    if($b_etapa != '')
                        $equipamientos = $equipamientos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $equipamientos = $equipamientos->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    if($b_lote != '')
                        $equipamientos = $equipamientos->where('lotes.num_lote', '=', $b_lote);
                    break;
                }
            }
        }
            
        if($rolID == 10)
                $equipamientos = $equipamientos->where('proveedores.id','=',$userID);

        if($request->b_empresa != '')
            $equipamientos= $equipamientos->where('lotes.emp_constructora','=',$request->b_empresa);

        $equipamientos = $equipamientos->orderBy('contratos.id','desc')
                            ->orderBy('proveedores.proveedor','asc')
                            ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                            ->distinct()
                        ->paginate(8);
  

        
        return [
            'pagination' => [
                'total'        => $equipamientos->total(),
                'current_page' => $equipamientos->currentPage(),
                'per_page'     => $equipamientos->perPage(),
                'last_page'    => $equipamientos->lastPage(),
                'from'         => $equipamientos->firstItem(),
                'to'           => $equipamientos->lastItem(),
            ],
            'equipamientos' => $equipamientos,
        ];
    }

    // actualiza el costo de la solicitud 
    public function actCosto(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->costo = $request->costo;
        $solicitud->save();
    }

    // Actualiza en campo de anticipo y la fecha de la solicitud
    public function actAnticipo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_anticipo = $request->fecha_anticipo;
        $solicitud->anticipo = $request->anticipo;
        $solicitud->save();
    }

    // Atualiza el campo de liquidacion y la fecha de la solicitud
    public function actLiquidacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_liquidacion = $request->fecha_liquidacion;
        $solicitud->liquidacion = $request->liquidacion;
        $solicitud->save();
    }

    //Actualiza la fecha de colocacion de equipamiento y regresa a su estado inicial la fecha de terminacion , 
    // regresa su status a 2
    // crea un nuevo registro en las observaciones de equipamiento
    public function actColocacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_colocacion = $request->fecha_colocacion;
        $equipamiento = Equipamiento::findOrFail($solicitud->equipamiento_id);
        $proveedor = Proveedor::findOrFail($equipamiento->proveedor_id);
        if($proveedor->tipo == 1){
            $solicitud->fin_instalacion = $request->fecha_colocacion;
            $solicitud->status = 4;
        }
        else{
            $solicitud->fin_instalacion = NULL;
            $solicitud->status = 2;
        }
        $solicitud->save();

        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->id;
        $observacion->comentario ='Fecha programada de instalación: '.$request->comentario; // observacion a guardar
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    // en el campo de "aticipo_cand" se setea en 1 que es una condicion para que sea bloqueado
    public function bloquearAnticipo(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $user = Solic_equipamiento::findOrFail($request->id);
        $user->anticipo_cand = '1';
        $user->save();
    }

    //En el campo de "liquidacion_cand" se setea en 1 que es una condicion para quese bloqueado
    public function bloquearLiquidacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $user = Solic_equipamiento::findOrFail($request->id);
        $user->liquidacion_cand = '1';
        $user->save();
    }

    //Funcion para finalizar las solicitudes 
    public function setInstalacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $tiempo = new Carbon();
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fin_instalacion = $tiempo; // se setea con la fecha en curso 
        $solicitud->status = 3; // status de finalizado
        $solicitud->save();

        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->id;
        $observacion->comentario ='Fecha de instalación: '.$request->comentario;
        $observacion->usuario = Auth::user()->usuario;

        // variables utilizadas para las notificaciones del personal
        $equipamiento = Equipamiento::findOrFail($solicitud->equipamiento_id);
        $credito = Credito::findOrFail( $solicitud->contrato_id);

        $observacion->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = $equipamiento->equipamiento. "para el lote ".$credito->num_lote. " del proyecto ".$credito->fraccionamiento." etapa ".$credito->etapa. " instalado";
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Equipamiento listo para revisión'
                    ]
                ];

                $users = User::select('id')->where('rol_id','!=','10')
                    ->where('equipamientos','=','1')->get();

                foreach($users as $notificar){
                    User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloAceptado));
                }

        
    }

    // otiene la informacion de los contratos firmados con status entregado en cero
    public function indexRea(Request $request){
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;
        $manzana = $request->manzana;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->select(
                'creditos.id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.fraccionamiento as proyecto',
                'creditos.promocion',
                'creditos.descripcion_promocion',
                'creditos.descuento_promocion',
                'creditos.paquete',
                'creditos.descripcion_paquete',
                'creditos.lote_id'
            );

        if($proyecto==''){
            $contratos = $query
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->orderBy('id', 'desc')->get();
        }
        elseif($etapa =='' && $manzana == ''){
            $contratos = $query
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->where('lotes.fraccionamiento_id', '=', $proyecto)
                    ->orderBy('id', 'desc')->get();
        }
        elseif($etapa !='' && $manzana == ''){
            $contratos = $query
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->where('lotes.fraccionamiento_id', '=', $proyecto)
                    ->where('lotes.etapa_id', '=', $etapa)
                    ->orderBy('id', 'desc')->get();
        }
        elseif($etapa !='' && $manzana != ''){
            $contratos = $query
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->where('lotes.fraccionamiento_id', '=', $proyecto)
                    ->where('lotes.etapa_id', '=', $etapa)
                    ->where('lotes.manzan', 'like', '%'. $manzana . '%')
                    ->orderBy('id', 'desc')->get();
        }

        return [
            'contratos' => $contratos,
        ];


    }

    // funcion para modificar los campos de una solicitud
    public function reubicar(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
        $solicitud = Solic_equipamiento::findOrFail($request->id); // busca la solicitud requerida

        $datosAnt = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id') // guarda los datos anteriores para la observacion
                    ->select('creditos.id','creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.modelo',
                                'creditos.fraccionamiento as proyecto')
                    ->where('creditos.id','=',$solicitud->contrato_id)->get();

        // guarda nuevos datos
        $solicitud->lote_id = $request->lote_id;
        $solicitud->contrato_id = $request->contrato_id;
        $solicitud->control = 0;
        $solicitud->save();

        // se crea una nueva observacion
        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->id;
        $observacion->comentario ='Equipamiento reubicado del lote: '.$datosAnt[0]->num_lote.' manzana: '.$datosAnt[0]->manzana.' proyecto: '.$datosAnt[0]->proyecto;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }


    //funcion para subir comprobantes de pago al sistema 
    public function upComprPago1(Request $request){

        if(!$request->ajax())return redirect('/');
        
        $sol_equip = Solic_equipamiento::findOrFail($request->id);

        if($sol_equip->comp_pago_1 != ""){// check if iexist an oter file added before
            $delete = $this->deleteFile($sol_equip->comp_pago_1, '/files/sol_esquip/sol_1/');
        }
        
        $name = $this->saveFiles($request->file,'/files/sol_esquip/sol_1/');// if it was deleted we save the new file

        if($name != "ERROR"){
            $sol_equip->comp_pago_1 = $name;
            $sol_equip->save();
        }
    }

    // funcion para descargar el archivo 
    public function downloadPago1($fileName){
        return response()->file(public_path().'/files/sol_esquip/sol_1/'.$fileName);
    }

    // funcion para subir el archivo de comprobante de pago
    public function upComprPago2(Request $request){
        if(!$request->ajax())return redirect('/');
        
        $sol_equip = Solic_equipamiento::findOrFail($request->id);

        if($sol_equip->comp_pago_2 != ""){// check if iexist an oter file added before
            $delete = $this->deleteFile($sol_equip->comp_pago_2, '/files/sol_esquip/sol_2/');
        }
        
        $name = $this->saveFiles($request->file,'/files/sol_esquip/sol_2/');// if it was deleted we save the new file

        if($name != "ERROR"){
            $sol_equip->comp_pago_2 = $name;
            $sol_equip->save();
        }
    }

    // descarga el archivo 
    public function downloadPago2($fileName){
        return response()->file(public_path().'/files/sol_esquip/sol_2/'.$fileName);
    }

    // Esta funcion es reutilizada para guardar los diferentes archivos subidos por el ususario
    private function saveFiles($file, $path){
        
        $name = uniqId().'.'.$file->getClientOriginalExtension(); // genera un nombre unico para el archivo
        $moved = $file->move(public_path($path), $name);

        if($moved) return $name; 
        else return "ERROR";
    }

    //Esta funcion es reutilizada para eliminar el archivo del sistema 
    // se le pasan los parametros de el registro en la tabla y la ubicacion del archivo
    private function deleteFile($file, $path){
        if(File::delete(public_path().$path.$file)) return true;
        else return false;
    }
}
