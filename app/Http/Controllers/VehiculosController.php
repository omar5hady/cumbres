<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificacionesAvisosController;
use Carbon\Carbon;
use App\Mant_vehiculo;
use App\Mant_retencion;
use App\Obs_mant_vehiculo;
use App\Vehiculo;
use App\Personal;
use App\User;
use Auth;
use Excel;


// Controlador para el modelo Vehiculos.
class VehiculosController extends Controller
{
    //Función que retorna las marcas de autos mas usadas.
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
            'Polaris',
            'Renault',
            'Seat',
            'Suzuki',
            'Toyota',
            'Volkswagen'
        ];

        return ['marcas'=>$marcas];
    }

    //Función que retornan los vehiculos registrados
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        //Query
        $vehiculos = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                ->select('vehiculos.*','personal.nombre','personal.apellidos');
        if($request->b_vehiculo != '')//Buscador por nombre de vehiculo
            $vehiculos = $vehiculos->where('vehiculos.vehiculo','like','%'.$request->b_vehiculo.'%');
        if($request->b_empresa != '')//Buscador por empresa
            $vehiculos = $vehiculos->where('vehiculos.empresa','=',$request->b_empresa);
        if($request->b_marca != '')//Buscador por marca de vehiculo
            $vehiculos = $vehiculos->where('vehiculos.marca','=',$request->b_marca);
        if($request->b_comodato != '')//Buscador para vehiculos registrados en comodato
            $vehiculos = $vehiculos->where('vehiculos.comodato','=',$request->b_comodato);
        //En caso de no ser administrador
        if(Auth::user()->usuario != 'marce.gaytan' && Auth::user()->usuario != 'karen.viramontes'
            && Auth::user()->usuario != 'uriel.al' && Auth::user()->usuario != 'shady'
        ) $vehiculos = $vehiculos->where('vehiculos.responsable_id','=',Auth::user()->id);//Mostrara solo los vehiculos registraods a quien hace la busqueda

        $vehiculos = $vehiculos->orderBy('vehiculos.empresa','asc')
                ->orderBy('vehiculos.marca','asc')
                ->orderBy('vehiculos.vehiculo','asc')
                ->orderBy('vehiculos.modelo','asc')
                ->paginate(10);

        return $vehiculos;
    }
    // Función para registrar un nuevo vehiculo
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $vehiculo = new Vehiculo();
        $vehiculo->vehiculo =  $request->vehiculo;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca =  $request->marca;
        $vehiculo->clave =  $request->clave;
        $vehiculo->placas =  $request->placas;
        $vehiculo->numero_serie =  $request->numero_serie;
        $vehiculo->numero_motor =  $request->numero_motor;
        $vehiculo->responsable_id = $request->responsable_id;
        $vehiculo->comodato = $request->comodato;
        $vehiculo->empresa = $request->empresa;
        $vehiculo->save();
    }
    //Función para actualizar un registro de vehiculo
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $vehiculo = Vehiculo::findOrFail($request->id);
        $vehiculo->vehiculo =  $request->vehiculo;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca =  $request->marca;
        $vehiculo->clave =  $request->clave;
        $vehiculo->placas =  $request->placas;
        $vehiculo->numero_serie =  $request->numero_serie;
        $vehiculo->numero_motor =  $request->numero_motor;
        $vehiculo->responsable_id = $request->responsable_id;
        $vehiculo->comodato = $request->comodato;
        $vehiculo->empresa = $request->empresa;
        $vehiculo->save();
    }
    //Funcion para obtener lso vehiculos en comodato
    public function getComoDato(Request $request){
        if(!$request->ajax())return redirect('/');
        //Query principal
        $vehiculos = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                ->select('vehiculos.id','vehiculos.vehiculo','vehiculos.marca', 'vehiculos.modelo','personal.nombre','personal.apellidos')
                ->where('comodato','=',1);//Filtro para mostrar en comodato
        if(Auth::user()->rol_id != 1 && Auth::user()->usuario != 'zaira.valt')
            $vehiculos = $vehiculos->where('responsable_id','=',Auth::user()->id);//Se muestran solo los vehiculos de quien realiza la busqueda
        $vehiculos = $vehiculos->get();
        return $vehiculos;
    }

    //Función para registrar una solicitud de mantenimiento para vehiculos en comodato
    public function storeSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        //Se obtienen los datos de la persona responsable del vehiculo.
        $solicitante = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                        ->select('personal.nombre','personal.apellidos')
                        ->where('vehiculos.id','=',$request->vehiculo)
                        ->first();
        try{
            DB::beginTransaction();
            //Se crea el registro para mantenimiento.
            $solicitud = new Mant_vehiculo();
            $solicitud->solicitante = $solicitante->nombre.' '.$solicitante->apellidos;
            $solicitud->vehiculo = $request->vehiculo;
            $solicitud->reparacion = $request->reparacion;
            $solicitud->descripcion = $request->descripcion;
            $solicitud->taller = $request->taller;
            $solicitud->forma_pago = $request->forma_pago;
            $solicitud->importe_total = $request->importe_total;
            $solicitud->monto_comp = $request->monto_comp;
            $solicitud->monto_gcc = $request->monto_gcc;
            $solicitud->save();
            //Arreglo para las retenciones de nomina
            $retenciones = $request->retenciones;
            //Se recorre el arreglo, y se crea un registro por cada uno
            foreach ($retenciones as $key => $ret) {
                if($ret['importe'] > 0){
                    $retencion = new Mant_retencion();
                    $retencion->mantenimiento_id = $solicitud->id;
                    $retencion->fecha_retencion = $ret['fecha_retencion'];
                    $retencion->importe = $ret['importe'];
                    $retencion->save();
                }
            }
            //Se crea notificación
            $msj = 'El colaborador '.$solicitud->solicitante.' ha generado una nueva solicitud para mantenimiento de su vehiculo';
            $aviso = new NotificacionesAvisosController();
            $usuarios = User::select('id') //La notificación se mandara a todos los administradores del modulo.
                                ->where('id','!=',Auth::user()->id)
                                ->where('admin_mant_vehiculos','=',1)
                                ->get();
            foreach ($usuarios as $index => $user) {
                $aviso->store($user->id,$msj);
            }
        DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    //Función para actualizar la solicitud.
    public function updateSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        //Se obtienen los datos de la persona responsable del vehiculo.
        $solicitante = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                        ->select('personal.nombre','personal.apellidos')
                        ->where('vehiculos.id','=',$request->vehiculo)
                        ->first();
        try{
            DB::beginTransaction();
            //Se accede al registro
            $solicitud = Mant_vehiculo::findOrFail($request->id);
            $solicitud->solicitante = $solicitante->nombre.' '.$solicitante->apellidos;
            $solicitud->vehiculo = $request->vehiculo;
            $solicitud->reparacion = $request->reparacion;
            $solicitud->descripcion = $request->descripcion;
            $solicitud->taller = $request->taller;
            $solicitud->forma_pago = $request->forma_pago;
            $solicitud->importe_total = $request->importe_total;
            $solicitud->monto_comp = $request->monto_comp;
            $solicitud->monto_gcc = $request->monto_gcc;
            $solicitud->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    //Función que retorna todas las solicitudes de mantenimiento generadas
    public function getSolicitudes(Request $request){
        if(!$request->ajax())return redirect('/');
        //Llamada a la función privada que retorna la query principal
        $solicitudes = $this->getQuerySolic($request);
        $solicitudes = $solicitudes->orderBy('mant_vehiculos.id','desc')->paginate(10);

        if(sizeOf($solicitudes)){
            //Se recorren los resultados obtenidos
            foreach ($solicitudes as $key => $solicitud) {
                //Variable para almacenar el monto total que ha sido retenido.
                $solicitud->totalRetenido = 0;
                //Se obtienen todas las retenciones generadas
                $solicitud->retenciones = Mant_retencion::where('mantenimiento_id','=',$solicitud->id)->get();
                if(sizeof($solicitud->retenciones)){
                    //Se calcula el total retenido.
                    foreach ($solicitud->retenciones as $key => $retencion) {
                        if($retencion->status == 1)
                            $solicitud->totalRetenido += $retencion->importe;
                    }
                }
            }
        }
        return $solicitudes;
    }
    //Función que retorna todas las solicitudes de mantenimiento generadas en excel
    public function getSolicitudesExcel(Request $request){
        //Llamada a la función privada que retorna la query principal
        $solicitudes = $this->getQuerySolic($request);
        $solicitudes = $solicitudes->orderBy('mant_vehiculos.id','desc')->paginate(10);

        if(sizeOf($solicitudes)){
            //Se recorren los resultados obtenidos
            foreach ($solicitudes as $key => $solicitud) {
                //Variable para almacenar el monto total que ha sido retenido.
                $solicitud->totalRetenido = 0;
                //Se obtienen todas las retenciones generadas
                $solicitud->retenciones = Mant_retencion::where('mantenimiento_id','=',$solicitud->id)->get();
                if(sizeof($solicitud->retenciones)){
                    //Se calcula el total retenido.
                    foreach ($solicitud->retenciones as $key => $retencion) {
                        if($retencion->status == 1)
                            $solicitud->totalRetenido += $retencion->importe;
                    }
                }
            }
        }
        //Creación y retorno de los resultados en excel.
        return Excel::create('Solicitudes de Mant. Comodato',
                    function($excel) use ($solicitudes){
                        $excel->sheet('Solicitudes', function($sheet) use ($solicitudes){

                            $sheet->row(1, [
                                'Vehiculo', 'Solicitante' ,'Servicio', 'Importe total', 'Aportación compañero', 'Monto retenido',
                                'Fecha de solic.', 'Status', 'RH'
                            ]);


                            $sheet->cells('A1:I1', function ($cells) {
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
                                'D' => '$#,##0.00',
                                'E' => '$#,##0.00',
                                'F' => '$#,##0.00'
                            ));



                            foreach($solicitudes as $index => $solicitud) {
                                if($solicitud->recep_rh == NULL){
                                    $solicitud->recep_rh = 'Sin firma';
                                }

                                switch($solicitud->status){
                                    case 0:{
                                        $solicitud->status = 'Rechazado';
                                        break;
                                    }
                                    case 1:{
                                        $solicitud->status = 'Pendiente';
                                        break;
                                    }
                                    case 2:{
                                        $solicitud->status = 'Aprobado';
                                        break;
                                    }
                                    case 3:{
                                        $solicitud->status = 'Liquidado';
                                        break;
                                    }
                                }

                                $sheet->row($index+2, [
                                    $solicitud->marca.' '.$solicitud->auto.' '.$solicitud->modelo,
                                    $solicitud->solicitante,
                                    $solicitud->reparacion,
                                    $solicitud->importe_total,
                                    $solicitud->monto_comp,
                                    $solicitud->totalRetenido,
                                    $solicitud->created_at,
                                    $solicitud->status,
                                    $solicitud->recep_rh
                                ]);
                            }


                            $num='A1:I' . $cont;
                            $sheet->setBorder($num, 'thin');
                            $sheet->cells('S1:S'.$cont, function($cells) {


                                $cells->setFontColor('#ff4040');

                            });
                        });
                    }
                )->download('xls');
    }
    //Fucnion que retorna todas las retenciones
    public function getRetenciones(Request $request){
        $retenciones = Mant_retencion::where('mantenimiento_id','=',$request->id)->get();
        return $retenciones;
    }
    //Función para registrar una retención.
    public function storeRetencion(Request $request){
        //Se accede al nombre de quien realiza la solicitud.
        $usuario = Personal::select('nombre','apellidos')->where('id','=',Auth::user()->id)->first();
        //Creación del registro para la rentención.
        $pago = new Mant_retencion();
        $pago->status = 1;
        $pago->fecha_retencion = $request->fecha_retencion;
        $pago->mantenimiento_id = $request->mantenimiento_id;
        $pago->importe = $request->importe;
        $pago->fecha_real = Carbon::now();
        $pago->autorizacion = $usuario->nombre.' '.$usuario->apellidos;
        $pago->save();
        $monto = number_format((float)$pago->importe, 2, '.', ',');
        //Se manda llamar la funcion que registra una observación para la solicitud de mantenimiento
        //La observación generada sera para indicar el monto retenido.
        $this->guardarObs($pago->mantenimiento_id, 'Ha sido retenido el importe de $'.$monto);
        //Se obtiene el total de monto retenido.
        $pagos = Mant_retencion::select(DB::raw("SUM(mant_retenciones.importe) as total"))
            ->where('mantenimiento_id','=',$pago->mantenimiento_id)
            ->where('status','=',1)->first();
        //Se accede al registro de la solicitud.
        $solicitud = Mant_vehiculo::findOrFail($pago->mantenimiento_id);
        if($pagos->total == $solicitud->monto_comp){//Se verifica si aun hay saldo pendiente por retener
            //En caso de no tener saldo pendiente se cambia el estatus de la solicitud a liquidado.
            $solicitud->status = 3;
            //Se crea observación indicando la liquidación.
            $this->guardarObs($pago->mantenimiento_id, 'La solicitud ha sido liquidada');
            $solicitud->save();
        }
    }
    //Función privada que retorna la query principal para obtener las solicitudes de mantenimiento
    private function getQuerySolic(Request $request){
        $fecha1 = $request->b_fecha1;
        $fecha2 = $request->b_fecha2;
        $status = $request->b_status;
        $buscar = $request->buscar;
        $b_solicitante = $request->b_solicitante;
        //Query
        $solicitudes = Mant_vehiculo::join('vehiculos','mant_vehiculos.vehiculo','=','vehiculos.id')
                                ->select('mant_vehiculos.*','vehiculos.vehiculo as auto',
                                            'vehiculos.marca','vehiculos.modelo', 'vehiculos.responsable_id');

                if($fecha1 != '' && $fecha2)//Busqueda por fecha de solicitud
                    $solicitudes = $solicitudes->whereBetween('mant_vehiculos.created_at',[$fecha1, $fecha2.' 23:59:59']);
                if($status != '')//Busqueda por estatus de la solicitud
                                // 0: Cancelado, 1: Pendiente, 2: Aprobado y 3: Liquidado.
                    $solicitudes = $solicitudes->where('mant_vehiculos.status','=',$status);
                if($buscar != '')//Busqueda general por nombre, marca y modelo del vehiculo.
                    $solicitudes = $solicitudes->where(DB::raw("CONCAT(vehiculos.marca,' ',vehiculos.vehiculo,' ',vehiculos.modelo)"), 'like', '%'. $buscar . '%');
                if($b_solicitante != '')//Busqueda por nombre del solicitante
                    $solicitudes = $solicitudes->where('mant_vehiculos.solicitante', 'like', '%'. $b_solicitante . '%');
                if(Auth::user()->admin_mant_vehiculos != 1)//Busqueda por responsable del vehiculo (Busqueda para usuarios que no son administradores del modulo.)
                    $solicitudes = $solicitudes->where('vehiculos.responsable_id','=',Auth::user()->id);
        return $solicitudes;
    }
    //Función para indicar la firma de enterado del jefe directo
    public function setRecepJefe(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_jefe = Carbon::now();
        $solicitud->save();
        //Se genera observación indicando la autorización del jefe inmediato
        $this->guardarObs($request->id, 'Autorizado por jefe inmediato.');
    }
    //Función para indicar la firma de enterado del departamento de RH
    public function setRecepRH(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_rh = Carbon::now();
        $solicitud->save();
        //Se genera observación indicando la autorización del departamento.
        $this->guardarObs($request->id, 'Autorizado por departamento de RH.');
    }
    //Función para indicar la firma de enterado de departamento de control de pagos
    public function setRecepControl(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_control = Carbon::now();
        $solicitud->save();
        //Se genera observación indicando la autorización del departamento de control.
        $this->guardarObs($request->id, 'Autorizado por Control.');
    }
    //Función para indicar la firma de enterado de dirección
    public function setRecepDireccion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_direccion = Carbon::now();
        $solicitud->save();
        //Se genera observación indicando la autorización de dirección.
        $this->guardarObs($request->id, 'Autorizado por Dirección.');
    }
    //Función para aprobar o rechazar una solicitud.
    public function changeStatus(Request $request){
        //Función para acceder al registro de la solicitud de mantenimiento.
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->status = $request->status;;
        $solicitud->save();
        if($request->status == 2)//Solicitud aprobada
            $obs = 'Se ha aprobado la solicitud';
        if($request->status == 0)//Solicitud rechazada
            $obs = 'La solicitud ha sido rechazada.';
        //Se crea comentario indicando el estatus obtenido
        $this->guardarObs($request->id, $obs);
        //En caso de ser aprobado, se genera notificación.
        if($request->status == 2){
            $msj = $obs.' para el vehiculo '.$solicitud->vehiculo.' del colaborador '.$solicitud->solicitante;
            $aviso = new NotificacionesAvisosController();
            $usuarios = User::select('id')
                                ->whereIn('usuario',['marce.gaytan','uriel.al','karen.viramontes','shady'])
                                ->get();
            foreach ($usuarios as $index => $user) {
                $aviso->store($user->id,$msj);
            }
        }
    }

    //Funcion para generar observaciones a la solicitud.
    //La funcion recibe como argumentos el ID de la solicitud y el comentario.
    public function guardarObs($mantenimiento_id, $observacion){
        $obs = new Obs_mant_vehiculo();
        $obs->mantenimiento_id = $mantenimiento_id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }
    //Funcion para guardar observacion
    public function storeObs(Request $request){
        $this->guardarObs($request->id, $request->obs);
    }
    //Función para obtener las observaciones de una solicitud.
    public function getObservaciones(Request $request){
        $obs = Obs_mant_vehiculo::where('mantenimiento_id','=',$request->id)->get();
        return $obs;
    }
    //Función para indicar que un pago ha sido retenido.
    public function retenerPago(Request $request){
        //Se obtiene el nombre de quien realiza la acción
        $usuario = Personal::select('nombre','apellidos')->where('id','=',Auth::user()->id)->first();
        //Se accede al registro de la retención y se actualiza el estatus a 1 e indica la fecha en que se retiene el pago.
        $pago = Mant_retencion::findOrFail($request->id);
        $pago->status = 1;
        $pago->fecha_real = Carbon::now();
        $pago->autorizacion = $usuario->nombre.' '.$usuario->apellidos;
        $pago->save();
        $monto = number_format((float)$pago->importe, 2, '.', ',');
        //Se registra observación
        $this->guardarObs($pago->mantenimiento_id, 'Ha sido retenido el importe de $'.$monto);
        //Se obtienen todos los pagos retenidos.
        $pagos = Mant_retencion::select(DB::raw("SUM(mant_retenciones.importe) as total"))
            ->where('mantenimiento_id','=',$pago->mantenimiento_id)
            ->where('status','=',1)->first();
        //Se accede al registro de la solicitud
        $solicitud = Mant_vehiculo::findOrFail($pago->mantenimiento_id);
        if($pagos->total == $solicitud->monto_comp){//Se verifica que no exista saldo pendiente
            //En casi de no tener saldo pendiente se cambia el estatus a liquidado.
            $solicitud->status = 3;
            $this->guardarObs($pago->mantenimiento_id, 'La solicitud ha sido liquidada');
            $solicitud->save();
        }
    }
    //Función para eliminar una retención.
    public function eliminarRetencion(Request $request){
        $pago = Mant_retencion::findOrFail($request->id);
        $pago->delete();
    }

}
