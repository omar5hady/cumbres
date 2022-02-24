<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NotifyAdmin;
use App\Http\Controllers\ClienteController;
use App\Cliente_observacion;
use App\Medio_publicitario;
use App\Digital_lead;
use App\Obs_lead;
use App\Personal;
use App\User;
use App\Cliente;
use App\Vendedor;
use App\Campania;
use App\Expediente;
use Carbon\Carbon;
use Auth;
use DB;
use Excel;

/*  Controlador para funciones sobre Leads digitales  */
class DigitalLeadController extends Controller
{
    // Función que retorna los leads registrados.
    public function index(Request $request){
        // Llamada a la funcion privada que obtiene la query necesaria.
        $leads = $this->queryLeads($request);
        $leads = $leads->paginate(20);

        if(sizeof($leads))
        foreach($leads as $index => $persona){ // Se recorre el resultado.
            $persona->progress = 0;
            $date = Carbon::parse($persona->fecha_update);
            $now = Carbon::now();
            // Calculo de dias sin seguimiento.
            $persona->diferencia = $date->diffInDays($now);
            // Llama a la funcion privada que calcula el avance de progreso en el registro de información.
            $persona->progress = round($this->getProgress($persona),2);
        }
        return $leads; // Retorno de resultados.
    }

    // Función que retorna los leads registrados en un excel.
    public function excelLeads(Request $request){
        // Llamada a la funcion privada que obtiene la query necesaria.
        $leads = $this->queryLeads($request);
        $leads = $leads->get();       
        // Retorno y creación en excel del resultado.
        return Excel::create('Digital Leads', function($excel) use ($leads){
            $excel->sheet('Digital Leads', function($sheet) use ($leads){
                $sheet->row(1, [ 
                    'Nombre','Apellidos', 'Celular', 'Correo electronico',
                    'Proyecto de interés','Prototipo de interés','Campaña publicitaria', 
                    'Medio de contacto','Vendedor asignado','Fecha de alta','Hora de alta'
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

                foreach($leads as $index => $lead) {
                    $cont++;
                    $campaña = 'Tráfico organico';
                    $proyecto = '';
                    $fecha = new Carbon($lead->created_at);
                    $fecha = $fecha->format('Y-m-d');
                    $hora = new Carbon($lead->created_at);
                    $hora = $hora->format('H:i');
                    if($lead->nombre_campania != NULL)
                        $campaña = $lead->nombre_campania.'-'.$lead->medio_digital;
                    if($lead->proyecto_interes != 0)
                        $proyecto = $lead->proyecto;
                    else
                        $proyecto = $lead->zona_interes;

                    $sheet->row($index+2, [
                        $lead->nombre, 
                        $lead->apellidos,
                        $lead->clv_lada.' '.$lead->celular,
                        $lead->email,
                        $proyecto,
                        $lead->modelo_interes,
                        $campaña,
                        $lead->medio_contacto,
                        $lead->vendedor,
                        $fecha,
                        $hora
                    ]);	
                }
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    // Función privada para calcular el progreso de registro de información en un lead.
    private function getProgress($lead){
        $progress = 0; // Se inicializa variable donde se almacena el progreso calculado
        // por cada campo con informacion se suma 1 al progreso
        if($lead->medio_contacto != '') $progress++;
        if($lead->medio_publicidad != '' && $lead->medio_publicidad != NULL) $progress++;
        if($lead->nombre != '' && $lead->nombre != NULL) $progress++;
        if($lead->apellidos != '' && $lead->apellidos != NULL) $progress++;
        if($lead->email != '' && $lead->email != NULL) $progress++;
        if($lead->celular != '' && $lead->celular != NULL) $progress++;
        if($lead->telefono != '' && $lead->telefono != NULL) $progress++;
        if($lead->proyecto_interes != NULL) $progress++;
        if($lead->modelo_interes != '' && $lead->modelo_interes != NULL) $progress++;
        if($lead->rango1 != '' && $lead->rango1 != NULL) $progress++;
        if($lead->rango2 != '' && $lead->rango2 != NULL) $progress++;
        if($lead->edo_civil != '' && $lead->edo_civil != NULL) $progress++;
        if($lead->perfil_cliente != '' && $lead->perfil_cliente != NULL) $progress++;
        if($lead->ingresos != '' && $lead->ingresos != NULL) $progress++;
        if($lead->coacreditado != '' && $lead->coacreditado != NULL) $progress++;
        if($lead->hijos != '' && $lead->hijos != NULL) $progress++;
        if($lead->mascotas != '' && $lead->mascotas != NULL) $progress++;
        if($lead->tipo_credito != '' && $lead->tipo_credito != NULL) $progress++;
        if($lead->tipo_uso != '' && $lead->tipo_uso != NULL) $progress++;
        if($lead->empresa != '' && $lead->empresa != NULL) $progress++;
        if($lead->rfc != '' && $lead->rfc != NULL) $progress++;
        if($lead->nss != '' && $lead->nss != NULL) $progress++;
        if($lead->sexo != '' && $lead->sexo != NULL) $progress++;
        if($lead->f_nacimiento != '' && $lead->f_nacimiento != NULL) $progress++;
        if($lead->autos != '' && $lead->autos != NULL) $progress++;
        if($lead->pago_mensual != '' && $lead->pago_mensual != NULL) $progress++;
        // Se calcula el porcentaje.
        $progress = ($progress/26) * 100;
        return $progress;
    }

    // Función privada que obtiene la query con leads registrados.
    private function queryLeads($request){
        $buscar = $request->buscar;
        $campania = $request->campania;
        $status = $request->status;
        $asesor = $request->asesor;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $proyecto = $request->proyecto;
        $prioridad = $request->prioridad;
        $modelo = $request->modelo;
        //Query principal
        $leads = Digital_lead::leftJoin('campanias as c','digital_leads.campania_id','=','c.id')
                        ->leftJoin('fraccionamientos as f','digital_leads.proyecto_interes','=','f.id')
                        ->leftJoin('personal as p','digital_leads.vendedor_asign','=','p.id')
                        ->select(
                                DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS vendedor"),
                                'c.nombre_campania','c.medio_digital','f.nombre as proyecto','digital_leads.*')
                                /*
                                    Motivos: 
                                        * 1 = Ventas
                                        * 5 = Recomendados
                                        * 2 = Postventa
                                        * 3 = Rentas
                                        * 4 = Dirección
                                        * 6 = Terrenos
                                        * 7 = Cumbres León
                                */
                                ->where('digital_leads.motivo','=',$request->motivo);
                if(Auth::user()->rol_id == 2){
                    // Se muestran los leads asignados al asesor
                    $leads = $leads->where('vendedor_asign','=',Auth::user()->id);
                        if($status == ''){
                            $leads = $leads->where('digital_leads.status','!=',0);
                        }
                }
                if($fecha1 != '' && $fecha2!='') // Fecha de registro
                    $leads = $leads->whereBetween('digital_leads.created_at',[$fecha1,$fecha2]);
                if($buscar != '') // Nombre de lead
                    $leads = $leads->where(DB::raw("CONCAT(digital_leads.nombre,' ',digital_leads.apellidos)"), 'like', '%'. $buscar . '%');
                if($status != '') /* Estatus de lead: 
                                1 = En Seguimiento
                                0 = Descartado
                                2 = Potencial
                                3 = Enviado a prospectos
                                */
                    $leads = $leads->where('digital_leads.status','=',$status);

                if($modelo != '') // Prototipo de interes
                    $leads = $leads->where('digital_leads.modelo_interes', 'like', '%'. $modelo . '%');
                if($proyecto != '') // Proyecto de interes
                    $leads = $leads->where('digital_leads.proyecto_interes','=',$proyecto);
                if($campania != '') // Campaña de atención.
                    $leads = $leads->where('digital_leads.campania_id','=',$campania);
                if($asesor != '') // Nombre de asesor asignado
                    $leads = $leads->where('digital_leads.vendedor_asign','=',$asesor);
                if($prioridad != '') // Prioridad de atención. Baja, Media o Alta
                            $leads = $leads->where('digital_leads.prioridad','=',$prioridad);
            $leads = $leads->orderBy('nombre','asc')
            ->orderBy('apellidos','asc');

        return $leads;
    }
    
    // Funcion para registrar un nuevo Lead.
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now(); // Fecha actual
        $lead = new Digital_lead(); // Nuevo lead
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->telefono = $request->telefono;
        $lead->clv_lada = $request->clv_lada;
        $lead->celular = $request->celular;
        $lead->campania_id = $request->campania_id;
        $lead->medio_contacto = $request->medio_contacto;
        $lead->proyecto_interes = $request->proyecto_interes;
        $lead->tipo_uso = $request->tipo_uso;
        $lead->modelo_interes = $request->modelo_interes;
        $lead->rango1 = $request->rango1;
        $lead->rango2 = $request->rango2;
        $lead->email = $request->email;
        $lead->zona_interes = $request->zona_interes;

        $lead->nombre_rec = $request->nombre_rec;
        $lead->apellidos_rec = $request->apellidos_rec;
        $lead->email_rec = $request->email_rec;
        $lead->celular_rec = $request->celular_rec;
        $lead->telefono_rec = $request->telefono_rec;

        $lead->motivo = $request->motivo;
        if($lead->motivo == 4) // Dirección
            $lead->prioridad = $request->prioridad;
        $lead->descripcion = $request->descripcion;
        $lead->direccion = $request->direccion;

        if($request->vendedor_asign!= 0){ // Si se ha asignado un vendedor
            if($lead->vendedor_asign != $request->vendedor_asign){
                $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                $fecha = Carbon::now();
                $arreglo = [
                        'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => 'Le ha asignado un lead digital',
                        'titulo' => 'Lead Digital'
                    ]
                ];
                // Se envia notificación al asesor sobre la asignación del Lead.
                User::findOrFail($request->vendedor_asign)->notify(new NotifyAdmin($arreglo));
            }
            
            $lead->vendedor_asign = $request->vendedor_asign;
        }
        $lead->fecha_update = $fecha;

        /////////////// PASO 2 ////////////////
            $lead->rfc = $request->rfc;
            $lead->nss = $request->nss;
            $lead->sexo = $request->sexo;
            $lead->f_nacimiento = $request->f_nacimiento;
            $lead->edo_civil = $request->edo_civil;
            $lead->hijos = $request->hijos;
            $lead->num_hijos = $request->num_hijos;
            $lead->empresa = $request->empresa;
            $lead->ingresos = $request->ingresos;

        ////////////// Paso 3 /////////////////
            $lead->mascotas = $request->mascotas;
            $lead->tam_mascota = $request->tam_mascota;
            $lead->num_mascotas = $request->num_mascotas;
            $lead->tipo_credito = $request->tipo_credito;
            $lead->coacreditado = $request->coacreditado;
            $lead->num_autos = $request->num_autos;
            $lead->autos = $request->autos;
            $lead->amenidad_priv = $request->amenidad_priv;
            $lead->detalle_casa = $request->detalle_casa;
            $lead->perfil_cliente = $request->perfil_cliente;
        $lead->save();
        // Se crea un comentario para el lead indicando el registro.
        $obs = new Obs_lead();
        $obs->lead_id = $lead->id;
        $obs->comentario = 'Lead registrado';
        $obs->usuario = Auth::user()->usuario;
        $obs->visto = $fecha;
        $obs->save();

        if($lead->motivo >= 2){
            $obs = new Obs_lead();
            $obs->lead_id = $lead->id;

            if($lead->motivo == 2) // Postventa
                $obs->comentario = 'Cliente necesita atención postventa, para mayor informacion revisar el modulo de Digital Leads';
            if($lead->motivo == 3) // Rentas
                $obs->comentario = 'Lead busca información sobre renta de casa, para mayor infomración revisar el modulo de Digital Leads';
            if($lead->motivo == 4) // Dirección
                $obs->comentario = 'Cliente de atención especial, para mas información visita el modulo Digital Leads';
            if($lead->motivo == 5) // Recomendado
                $obs->comentario = 'Cliente registrado como Aliado Cumbres';
            if($lead->motivo == 6) // Terrenos
                $obs->comentario = 'Lead ofreciendo terreno para venta, para mayor informacion revisar el modulo de Digital Leads';

            $obs->usuario = Auth::user()->usuario;
            $obs->save();
        }
    }
    
    // Función para eliminar un registro Lead.
    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //Se accede al registro por ID
        $lead = Digital_lead::findOrFail($request->id);
        $lead->delete(); // Elimina el registro.
    }

    // Función para actualizar un registro Lead.
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $lead = Digital_lead::findOrFail($request->id);
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->telefono = $request->telefono;
        $lead->clv_lada = $request->clv_lada;
        $lead->celular = $request->celular;
        $lead->campania_id = $request->campania_id;
        $lead->medio_contacto = $request->medio_contacto;
        $lead->proyecto_interes = $request->proyecto_interes;
        $lead->tipo_uso = $request->tipo_uso;
        $lead->modelo_interes = $request->modelo_interes;
        $lead->rango1 = $request->rango1;
        $lead->rango2 = $request->rango2;
        $lead->email = $request->email;
        $lead->zona_interes = $request->zona_interes;
        $lead->fecha_update = $fecha;

        $lead->nombre_rec = $request->nombre_rec;
        $lead->apellidos_rec = $request->apellidos_rec;
        $lead->email_rec = $request->email_rec;
        $lead->celular_rec = $request->celular_rec;
        $lead->telefono_rec = $request->telefono_rec;

        $lead->motivo = $request->motivo;
        $lead->descripcion = $request->descripcion;
        $lead->direccion = $request->direccion;
        if($request->vendedor_asign!= 0){

            if($lead->vendedor_asign != $request->vendedor_asign){// Si se asigno un asesor diferente al registrado anteriormente
                $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                $fecha = Carbon::now();
                $arreglo = [
                        'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => 'Se le ha asignado un lead digital',
                        'titulo' => 'Lead Digital'
                    ]
                ];
                $obs = new Obs_lead(); // Registra comentario con la asignación del lead.
                $obs->lead_id = $lead->id;
                $obs->comentario = 'Aviso!, se le ha asignado un nuevo lead para seguimiento, 
                                    favor de ingresar al modulo de Digital Leads para mas información. ';
                $obs->usuario = Auth::user()->usuario;
                $obs->save();
                // Se envia notificación al asesor.
                User::findOrFail($request->vendedor_asign)->notify(new NotifyAdmin($arreglo));
            }
            $lead->vendedor_asign = $request->vendedor_asign;
        }

        /////////////// PASO 2 ////////////////
        $lead->rfc = $request->rfc;
        $lead->nss = $request->nss;
        $lead->sexo = $request->sexo;
        $lead->f_nacimiento = $request->f_nacimiento;
        $lead->edo_civil = $request->edo_civil;
        $lead->hijos = $request->hijos;
        $lead->num_hijos = $request->num_hijos;
        $lead->empresa = $request->empresa;
        $lead->ingresos = $request->ingresos;

        ////////////// Paso 3 /////////////////
        $lead->mascotas = $request->mascotas;
        $lead->tam_mascota = $request->tam_mascota;
        $lead->num_mascotas = $request->num_mascotas;
        $lead->tipo_credito = $request->tipo_credito;
        $lead->coacreditado = $request->coacreditado;
        $lead->num_autos = $request->num_autos;
        $lead->autos = $request->autos;
        $lead->amenidad_priv = $request->amenidad_priv;
        $lead->detalle_casa = $request->detalle_casa;
        $lead->perfil_cliente = $request->perfil_cliente;
        $lead->save();

        $obs = new Obs_lead(); // Comentario al lead para indicar la actualización de información
        $obs->lead_id = $lead->id;
        $obs->comentario = 'Se actualiza información del Lead';
        $obs->usuario = Auth::user()->usuario;
        $obs->visto = $fecha;
        $obs->save();

            

    }

    // Función para registrar lead en la Tabla de Prospectos.
    public function sendProspectos(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        // Se busca en la tabla personal si existe un prospecto registrado con el mismo RFC del lead.
        $cliente = Personal::select('id')->where('rfc','=',$request->rfc)->get();
        // Se obtiene el id del medio de publicidad.
        $medio = Medio_publicitario::select('id')->where('nombre','like','%'.$request->medio_publicidad.'%')->get();
        if(sizeof($medio) == 0)
            $publi = 20;
        else{
            $publi = $medio[0]->id;
        }
        try{
            DB::beginTransaction();
            // Si no se encuentra ningun prospecto registrado
            if(sizeof($cliente) == 0){
                $persona = new Personal(); // Crea nuevo registro en la tabla Personal.
                $persona->nombre = $request->nombre;
                $persona->apellidos = $request->apellidos;
                $persona->telefono = $request->telefono;
                $persona->clv_lada = $request->clv_lada;
                $persona->celular = $request->celular;
                $persona->email = $request->email;
                $persona->rfc = $request->rfc;
                $persona->f_nacimiento = $request->f_nacimiento;
                $persona->departamento_id = 8;
                $persona->save();

                $id = $persona->id;

                $cliente = new Cliente(); // Nuevo registro en la tabla Clientes.
                $cliente->id = $id;
                $cliente->publicidad_id = $publi;
                $cliente->proyecto_interes_id = $request->proyecto_interes;
                $cliente->vendedor_id = $request->vendedor_asign;
                $cliente->nss = $request->nss;
                $cliente->sexo = $request->sexo;
                $cliente->edo_civil = $request->edo_civil;
                $cliente->empresa = $request->empresa;
                $cliente->ingreso = $request->ingresos;
                $cliente->coacreditado = $request->coacreditado;
                $cliente->tipo_casa = '';
                $cliente->clasificacion = 4;
                $cliente->save();

            }
            else{ // Si ya esta registrado el prospecto.
                // Se actualiza la informacion en la tabla Personal y Clientes.
                $persona = Personal::findOrFail($cliente[0]->id);
                $persona->nombre = $request->nombre;
                $persona->apellidos = $request->apellidos;
                $persona->telefono = $request->telefono;
                $persona->clv_lada = $request->clv_lada;
                $persona->celular = $request->celular;
                $persona->email = $request->email;
                $persona->rfc = $request->rfc;
                $persona->f_nacimiento = $request->f_nacimiento;
                $persona->save;

                $cliente = Cliente::findOrFail($cliente[0]->id);
                $cliente->publicidad_id = $medio[0]->id;
                $cliente->proyecto_interes_id = $request->proyecto_interes;
                $cliente->vendedor_id = $request->vendedor_asign;
                $cliente->nss = $request->nss;
                $cliente->sexo = $request->sexo;
                $cliente->edo_civil = $request->edo_civil;
                $cliente->empresa = $request->empresa;
                $cliente->ingreso = $request->ingresos;
                $cliente->coacreditado = $request->coacreditado;
                $cliente->clasificacion = 4;
                $cliente->save();
            }
            // Se accede al registro del Lead
            $lead = Digital_lead::findOrFail($request->id);
            $lead->prospecto = 1; // Indica que ha sido registrado en la tabla de personal.
            $lead->status = 3; // Estatus enviado a prospectos.
            $lead->save();

            $obs = new Obs_lead(); // Nuevo comentario al lead.
            $obs->lead_id = $lead->id;
            $obs->comentario = 'El lead se ha enviado a la base de prospectos del vendedor';
            $obs->usuario = Auth::user()->usuario;
            $obs->visto = $fecha;
            $obs->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }   
    }

    // Funcion para registrar un comentario nuevo al Lead.
    public function storeObs(Request $request){
        $fecha = Carbon::now(); // Fecha actual.
        $obs = new Obs_lead(); // Nuevo registro para comentario de leads.
        $obs->lead_id = $request->lead_id;
        $obs->comentario = $request->comentario;
        $obs->usuario = Auth::user()->usuario;
        if($request->fecha_aviso == '')
            $obs->visto = $fecha;
        else{
            $obs->fecha_aviso = $request->fecha_aviso; // Fecha para recordatorio
        }
        $obs->save();
        // Se actualiza el lead para indicar la fecha de actualización.
        $lead = Digital_lead::findOrFail($request->lead_id);
        $lead->fecha_update = $fecha;
        $lead->save();
        // Se registra notificiación
        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
        $fecha = Carbon::now();
        $msj = "Nuevo comentario en el lead: ".$lead->nombre.' '.$lead->apellidos;
        $arregloAceptado = [
            'notificacion' => [
                'usuario' => $imagenUsuario[0]->usuario,
                'foto' => $imagenUsuario[0]->foto_user,
                'fecha' => $fecha,
                'msj' => $msj,
                'titulo' => 'Lead :)'
            ]
        ];

        if($lead->motivo == 1){ // Ventas
            $personal = User::select('id')
            ->where('id', '=', 25511)
            ->orWhere('rol_id','=',8)
            ->where('digital_lead','=',1)
            ->orWhere('rol_id','=',1)
            ->get();
        }

        if($lead->motivo == 2){ // Postventa
            $personal = User::select('id')
            ->where('id', '=', 25511)
            ->orWhere('rol_id','=',8)
            ->where('digital_lead','=',1)

            ->orWhere('rol_id','=',12)
            ->where('digital_lead','=',1)
            ->orWhere('rol_id','=',1)
            ->get();
        }

        if($lead->motivo == 3){ // Rentas
            $personal = User::select('id')
            ->where('id', '=', 25511)
            ->orWhere('rol_id','=',8)
            ->where('digital_lead','=',1)
            ->orWhere('rol_id','=',1)
            ->get();
        }

        

        foreach ($personal as $personas) {
            //$correo = $personas->email;
            //Mail::to($correo)->send(new NotificationReceived($msj));
            if(Auth::user()->id != $personas->id)
                User::findOrFail($personas->id)->notify(new NotifyAdmin($arregloAceptado));
        }
        
    }

    // Función para retornar los comentarios en el lead.
    public function getObs(Request $request){
        $obs = Obs_lead::where('lead_id','=',$request->id)
        ->orderBy('created_at','desc')->paginate(15);
        return $obs;
    }

    // Funcion para asignar un lead de manera aleatoria.
    public function asignarLead(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Se accede al registro del lead.
            $lead = Digital_lead::findOrFail($request->id);
            // Nuevo Cliente controller.
            $cliente = new ClienteController();
            if($lead->proyecto_interes == 0 ) // Lead sin proyecto de interes
                // Llamada a la funcion en cliente controler que retorna el vendedor de manera aleatoria.
                $vendedor_asign = $cliente->asignarClienteAleatorio(0);
            else
                // Llamada a la funcion en cliente controler que retorna el vendedor de manera aleatoria.
                $vendedor_asign = $cliente->asignarClienteAleatorio($lead->proyecto_interes);
            // Se asigna el vendedor obtenido al lead.                
            $lead->vendedor_asign = $vendedor_asign['vendedor_elegido'];
            $lead->status = 2;// Se cambia estus para indicar que es un cliente potencial.
            $lead->save();
            
            $vendedor = Vendedor::findOrFail($vendedor_asign['vendedor_elegido']);
            $vendedor->cont_leads++; // Conteo de #leads asignados al vendedor
            $vendedor->save();
        
            $obs = new Obs_lead(); // Nuevo comentario al lead indicando que se asigno el Lead.
            $obs->lead_id = $lead->id;
            $obs->comentario = 'Aviso!, se le ha asignado un nuevo lead para seguimiento, 
                                favor de ingresar al modulo de Digital Leads para mas información. ';
            $obs->usuario = Auth::user()->usuario;
            $obs->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }   
    }

    // Función para obtener los comentarios programados
    public function reminderCommentarioLead(){
        // Se obtienen los leads con comentarios pendientes por ver.
        $reminders = Digital_lead::join('obs_leads', 'digital_leads.id','=','obs_leads.lead_id')
            ->select('obs_leads.id','nombre','apellidos','celular','email','comentario','motivo')
            ->where('obs_leads.visto','=', NULL);
        // Leads con comentarios programados para recordatorio.
        $reminders_fecha=Digital_lead::join('obs_leads', 'digital_leads.id','=','obs_leads.lead_id')
        ->select('obs_leads.id','nombre','apellidos','celular','email','comentario','motivo','obs_leads.fecha_aviso');
            if(Auth::user()->rol_id == 2 ){ // Asesores.
                $reminders = $reminders
                                ->where('digital_leads.vendedor_asign','=', Auth::user()->id)//Vendedor asignado al lead
                                ->where('obs_leads.fecha_aviso','=',NULL); //Sin comentario programado
                $reminders = $reminders->get();
                $reminders_fecha = $reminders_fecha->where('digital_leads.vendedor_asign','=', Auth::user()->id)//Vendedor asignado
                    ->where('obs_leads.visto','=', NULL) // Comentario sin ver
                    ->where('obs_leads.fecha_aviso','=', Carbon::now()->format('Y-m-d'))->get();// Comentario programado para la fecha actual.
            }
            elseif(Auth::user()->rol_id == 12 ){ // Postventa
                $reminders = $reminders->where('motivo','=', 2);
                $reminders = $reminders->get();
            }
            elseif(Auth::user()->id == 25816 || Auth::user()->id == 30993){
                $reminders = $reminders->where('motivo','=', 3);// Rentas
                $reminders = $reminders->get();
            }
            elseif(Auth::user()->id == 10 || Auth::user()->rol_id == 1){
                $reminders = $reminders->where('motivo','=', 4); // Dirección
                $reminders = $reminders->get();
            }
            elseif(Auth::user()->id == 3){ // Terrenos.
                $reminders = $reminders->where('motivo','=', 6);
                $reminders = $reminders->get();
            }

            return ['reminders'=>$reminders,
                    'reminders_fecha' => $reminders_fecha
                ];
    }

    // Funcion para cambiar el estatus de un Lead a descartado.
    public function changeStatus(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lead = Digital_lead::findOrFail($request->id);
        $lead->status = $request->status;
        
        if($request->status == 0){ // Lead descartado
            // Se envia notificación a los recepcionistas digitales para indicar el descarte del lead.
            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                $fecha = Carbon::now();
                $arreglo = [
                        'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => 'El lead '.$lead->nombre.' '.$lead->apellidos.' ha sido descartado',
                        'titulo' => 'Lead Descartado'
                    ]
                ];

                $usuarios = User::select('id')->where('rol_id','=',8)->where('digital_lead','=',1)
                    ->where('id','!=',Auth::user()->id)
                    ->get();
                foreach ($usuarios as $usuario) 
                    User::findOrFail($usuario->id)->notify(new NotifyAdmin($arreglo));

                $obs = new Obs_lead(); // Comentario al lead indicando el descarte.
                $obs->lead_id = $lead->id;
                $obs->comentario = 'Lead Descartado';
                $obs->usuario = Auth::user()->usuario;
                $obs->save();
        }
        $lead->save();
    }

    // Función para indicar que una notificacion ha sido vista por el usuario.
    public function leadEnterado(Request $request){
        $fecha = Carbon::now();
        $obs = Obs_lead::findOrFail($request->id);
        $obs->visto = $fecha;
        $obs->save();
    }

    // Función para obtener datos de cliente ya registrado en la bd de Personal buscando por RFC.
    public function getCliente(Request $request){
        $cliente = Expediente::join('contratos as cont','expedientes.id','=','cont.id')
                ->join('creditos as cr','cont.id','=','cr.id')
                ->join('clientes as c','cr.prospecto_id','=','c.id')
                ->join('lotes as l','cr.lote_id','=','l.id')
                ->join('personal as p','c.id','=','p.id')
                ->select(
                    'p.nombre','p.apellidos',
                    DB::raw("CONCAT(p.rfc,p.homoclave) AS rfc_hom"),
                    'l.calle','l.numero','l.interior','l.manzana','p.id',
                    'p.celular','p.telefono','p.email'
                )
                ->where(DB::raw("CONCAT(p.rfc,'',p.homoclave)"), '=', $request->rfc)
                ->get();

        return ['cliente'=>$cliente];
    }

    // Función para generar reporte de Digital Leads.
    public function reporteLeads(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $proyecto = $request->proyecto;
        // Se obtienen Campañas digitales.
        $campanias = Campania::select('nombre_campania','medio_digital','fecha_ini','fecha_fin','id')
        ->get();
        $cont_org = 0;
        $asesor_org = 0;
        
        foreach ($campanias as $campania) {
            //TOTAL LEADS POR CAMPAÑA
                $campania->conteo = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                        if($fecha1 != '')// Por fecha de registro.
                            $campania->conteo = $campania->conteo->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '')// Por proyecto de interes.
                            $campania->conteo = $campania->conteo->where('proyecto_interes', '=',$proyecto);
                $campania->conteo = $campania->conteo->count();
            //LEADS ASIGNADOS A ASESOR
                $campania->asesor = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                $campania->asesor = $campania->asesor->where('vendedor_asign','!=',NULL);
                        if($fecha1 != '')// Por fecha de registro
                            $campania->asesor = $campania->asesor->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '') // Por proyecto de interes.
                            $campania->asesor = $campania->asesor->where('proyecto_interes', '=',$proyecto);
                $campania->asesor = $campania->asesor->count();

            // LEADS DESCARTADOS SIN ASIGNAR
                $campania->descartado = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                $campania->descartado = $campania->descartado->where('status','=',0)->where('vendedor_asign','=',NULL);
                        if($fecha1 != '') // Fecha de registro
                            $campania->descartado = $campania->descartado->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '') // Proyecto de interes.
                            $campania->descartado = $campania->descartado->where('proyecto_interes', '=',$proyecto);
                $campania->descartado = $campania->descartado->count();

            // LEADS DESCARTADOS POR ASESOR
                $campania->descAsesor = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                $campania->descAsesor = $campania->descAsesor->where('status','=',0)->where('vendedor_asign','!=',NULL);
                        if($fecha1 != '') // Fecha de registro
                            $campania->descAsesor = $campania->descAsesor->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '') // Proyecto de interes.
                            $campania->descAsesor = $campania->descAsesor->where('proyecto_interes', '=',$proyecto);
                $campania->descAsesor = $campania->descAsesor->count();
        }

        /// CONTEOS PARA TRAFICO ORGANICO
            $cont_org = Digital_lead::select('campania_id')
                            ->where('campania_id','=',NULL) // Sin campaña asignada
                            ->where('motivo','=',1);
                            if($fecha1 != '') // Fecha de registro
                                $cont_org = $cont_org->whereBetween('created_at', [$fecha1, $fecha2]);
                            if($proyecto != '')  // Proyecto de interes
                                $cont_org = $cont_org->where('proyecto_interes', '=',$proyecto);
                    $cont_org = $cont_org->count();

            $cont_desc = Digital_lead::select('campania_id') // Organico descartados
                            ->where('campania_id','=',NULL)
                            ->where('motivo','=',1)
                            ->where('status','=',0)
                            ->where('vendedor_asign','=',NULL);
                            if($fecha1 != '') // Fecha de registro
                                $cont_desc = $cont_desc->whereBetween('created_at', [$fecha1, $fecha2]);
                            if($proyecto != '') // Proyecto de interes
                                $cont_desc = $cont_desc->where('proyecto_interes', '=',$proyecto);
                    $cont_desc = $cont_desc->count();

            $desc_ase = Digital_lead::select('campania_id') // Organico descartado por asesor.
                            ->where('campania_id','=',NULL)
                            ->where('motivo','=',1)
                            ->where('status','=',0)
                            ->where('vendedor_asign','!=',NULL); // Vendedor asignado.
                            if($fecha1 != '') // Fecha de registro
                                $desc_ase = $desc_ase->whereBetween('created_at', [$fecha1, $fecha2]);
                            if($proyecto != '') // Proyecto de interes
                                $desc_ase = $desc_ase->where('proyecto_interes', '=',$proyecto);
                    $desc_ase = $desc_ase->count();

            $asesor_org = Digital_lead::select('campania_id') // Asignados a asesor.
                            ->where('campania_id','=',NULL)
                            ->where('vendedor_asign','!=',NULL);
                            if($fecha1 != '') // Fecha de registro
                                $asesor_org = $asesor_org->whereBetween('created_at', [$fecha1, $fecha2]);
                            if($proyecto != '') // Proyecto de interes
                                $asesor_org = $asesor_org->where('proyecto_interes', '=',$proyecto);
                    $asesor_org = $asesor_org->where('motivo','=',1)->count();

        /// REPORTE POR ASESOR
            // Se obtienen los asesores que tienen por lo menos un lead asignado.
            $asesores = Digital_lead::join('personal','digital_leads.vendedor_asign','=','personal.id')
                                    ->select('personal.id','personal.nombre','personal.apellidos')
                                    ->where('vendedor_asign','!=',NULL)
                                    ->where('motivo','=',1)
                                    ->groupBy('personal.id')
                                    ->get();
            foreach ($asesores as $asesor) { 
                // Conteo de leads por asesor   
               
                $asesor->conteo = Digital_lead::select('vendedor_asign')
                                ->where('vendedor_asign','=',$asesor->id)->where('motivo','=',1);
                                if($fecha1 != '') // Fecha de registro
                                    $asesor->conteo = $asesor->conteo->whereBetween('created_at', [$fecha1, $fecha2]);
                                if($proyecto != '')  // Proyecto de interes
                                    $asesor->conteo = $asesor->conteo->where('proyecto_interes', '=',$proyecto);
                    $asesor->conteo = $asesor->conteo->count();
                // Conteo de leads descartados por asesor
                $asesor->descartados = Digital_lead::select('vendedor_asign')
                                ->where('vendedor_asign','=',$asesor->id)->where('motivo','=',1)
                                ->where('status','=',0);
                                if($fecha1 != '') // Fecha de Registro
                                    $asesor->descartados = $asesor->descartados->whereBetween('created_at', [$fecha1, $fecha2]);
                                if($proyecto != '') // Proyecto de interes
                                    $asesor->descartados = $asesor->descartados->where('proyecto_interes', '=',$proyecto);
                    $asesor->descartados = $asesor->descartados->count();
                $asesor->sinAtender = 0;
            }

            // Se obtienen todos los asesores internos registrados en el sistema 
            $vendedores = User::join('personal','users.id','=','personal.id')
                ->join('vendedores','personal.id','vendedores.id')
                ->join('personal as gerente','vendedores.supervisor_id','=','gerente.id')
                ->select('personal.id',
                        DB::raw("CONCAT(gerente.nombre,' ',gerente.apellidos) AS gerente"),
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"))
                ->where('vendedores.tipo','=',0)
                ->where('users.condicion','=',1)
                ->whereNotIn('users.usuario',['mayra_jaz','yasmin_ventas','e_preciado'])
                ->where('users.usuario','!=','descartado')
                ->where('users.usuario','!=','oficina')
                ->orderBy('vendedores.supervisor_id','asc')
                ->orderBy('vendedores.cont_leads','asc')
                ->orderBy('vendedor','asc')->get();
                $varGerente = '';
                foreach ($vendedores as $index => $vendedor) {

                    if($varGerente != $vendedor->gerente ){
                        $varGerente = $vendedor->gerente;
                    }
                    else{
                        $vendedor->gerente = '';
                    }

                    $vendedor->total = 0; // Total de prospectos por asesor.
                    $vendedor->reg = 0; // Prospectos con atención regular.
                    $vendedor->bd7 = 0; // Prospectos con mas de 7 dias sin atencion y mens de 15 dias.
                    $vendedor->bd15 = 0; // Prospectos con mas de 15 dias de atención.
                    $vendedor->dif = 0;
                    $vendedor->dif7 = 0;
                    $vendedor->dif15 = 0;
                    $vendedor->ger = 0;
                    $vendedor->ger7 = 0;
                    $vendedor->ger15 = 0;
                    $now = Carbon::now();

                    $clientes = Cliente::select('id','seguimiento')->where('vendedor_id','=',$vendedor->id)
                    ->where('clasificacion','!=',5)
                    ->where('clasificacion','!=',6)
                    ->where('clasificacion','!=',7)
                    ->where('clasificacion','!=',1)
                    ->get();
                    $vendedor->total = $clientes->count(); // Total de prospectos tipo A, B y C
                    $vendedor->bd = 0;
        
                    foreach ($clientes as $index => $c) {
                        $seguimiento = '';
                        $fechaSeg = Carbon::parse($c->seguimiento);
                        $seguimiento = $fechaSeg->diffInDays($now);

                        if($seguimiento <= 7)
                            $vendedor->ger++;
                        if($seguimiento > 7 && $seguimiento <= 15)
                            $vendedor->ger7++;
                        if($seguimiento > 15)
                            $vendedor->ger15++;

                        $obs = Cliente_observacion::where('created_at','>=',Carbon::now()->subDays(7)) //Clientes con seguimiento
                        ->where('cliente_id','=',$c->id)
                        ->where('gerente','=',0)
                        ->count();

                        $obs2 = Cliente_observacion::where('created_at','>=',Carbon::now()->subDays(16)) // Clientes con mas de 7 dias sin atención.
                        ->where('cliente_id','=',$c->id)
                        ->where('gerente','=',0)
                        ->count();
        
                        if($obs > 0)
                            $vendedor->reg ++; // Conteo de clientes regulares

                        if($obs2 > 0)
                            $vendedor->bd7 ++; 
                                
                        $vendedor->dif7 =  $vendedor->bd7 - $vendedor->reg; // Calculo de prospectos con mas de 7 dias sin seguimiento
                        $vendedor->dif15 = $vendedor->total - $vendedor->bd7 - $vendedor->reg; // Calculo de prospectos con menos de 15 dias sin seguimiento
                        if($vendedor->dif15 < 0)
                            $vendedor->dif15 = 0;
                    }
                }
        
        if($request->excel == 0) // Retorno de resultados en formato Json
            return [
                'campanias' => $campanias,
                'camp_org' => $cont_org,
                'asesor_org' => $asesor_org,
                'asesores' => $asesores,
                'desc_ase' => $desc_ase,
                'cont_desc' => $cont_desc,

                'vendedores' => $vendedores,
            ];
        else{ // Retorno de resultado en Excel.
            return Excel::create('Reporte Digital Leads', function($excel) use ($campanias, $cont_org, $asesor_org,
                                                                $desc_ase, $cont_desc, $vendedores, $asesores){
                $excel->sheet('Reporte por campaña', function($sheet) use ($campanias,  $cont_org, $asesor_org, $desc_ase, $cont_desc){
                    
                    $sheet->row(1, [
                        'Campaña','Fecha de Campaña', '# Leads', 'Descartado sin canalizar',
                        'Canalizados a asesor', 'Descartado por asesor'
                    ]);
    
                    $sheet->cells('A1:F1', function ($cells) {
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
                    $cont=2;
                    $sheet->row(2, [
                        'Tráfico Organico', 
                        '',
                        $cont_org,
                        $cont_desc,
                        $asesor_org,
                        $desc_ase,
                    ]);
    
                    foreach($campanias as $index => $lead) {
                        $cont++;

                        $fecha = new Carbon($lead->fecha_ini);
                        $lead->fecha_ini = $fecha->formatLocalized('%d de %B de %Y');
                        $fecha2 = new Carbon($lead->fecha_fin);
                        $lead->fecha_fin = $fecha2->formatLocalized('%d de %B de %Y');
    
                        $sheet->row($index+3, [
                            $lead->nombre_campania.' ('.$lead->medio_digital.')', 
                            $lead->fecha_ini.' al '.$lead->fecha_fin,
                            $lead->conteo,
                            $lead->descartado,
                            $lead->asesor,
                            $lead->descAsesor
                        ]);	
                    }
                    $num='A1:F' . $cont;
                    $sheet->setBorder($num, 'thin');
                });

                $excel->sheet('Asesor', function($sheet) use ($vendedores, $asesores){

                    $sheet->mergeCells('A1:C1');
                    
                    $sheet->row(1, [
                        'Reporte por asesor'
                    ]);
                    $sheet->row(2, [
                        'Asesor', '#Leads asignados', 'Descartados'
                    ]);
    
                    $sheet->cells('A1:C2', function ($cells) {
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
                    $cont=2;
    
                    foreach($asesores as $index => $asesor) {
                        $cont++;

                        $sheet->row($index+3, [
                            $asesor->nombre.' '.$asesor->apellidos,
                            $asesor->conteo,
                            $asesor->descartados
                        ]);	
                    }
                    $num='A1:B' . $cont;
                    $sheet->setBorder($num, 'thin');
                    
                    $cont+=2;

                    $sheet->mergeCells('A'.$cont.':D'.$cont);
                    
                    $sheet->row($cont, [
                        'Seguimiento de prospectos'
                    ]);
                    $sheet->row($cont+1, [
                        'Asesor', 'Prospectos en verde', 'Prospectos en amarillo', 'Prospectos en rojo'
                    ]);
                    $row = $cont+1;
    
                    $sheet->cells('A'.$cont.':D'.$row, function ($cells) {
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

                    $cont+=2;
                    $row = $cont;
    
                    foreach($vendedores as $index => $asesor) {
                        $cont++;

                        $sheet->row($index+$row, [
                            $asesor->vendedor,
                            $asesor->reg,
                            $asesor->dif7,
                            $asesor->dif15,

                        ]);	
                    }
                    $num='A1:D' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
            )->download('xls');

        }


    }
}
