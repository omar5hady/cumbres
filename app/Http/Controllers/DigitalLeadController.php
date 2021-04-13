<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Digital_lead;
use App\Obs_lead;
use App\Personal;
use App\User;
use App\Cliente;
use App\Vendedor;
use App\Campania;
use App\Http\Controllers\ClienteController;
use App\Cliente_observacion;
use App\Notifications\NotifyAdmin;
use App\Medio_publicitario;
use Auth;
use DB;
use Excel;
use App\Expediente;
use Carbon\Carbon;

class DigitalLeadController extends Controller
{
    public function index(Request $request){
        $leads = $this->queryLeads($request);

        $leads = $leads->paginate(20);

        if(sizeof($leads))
        foreach($leads as $index => $persona){
            $persona->progress = 0;
            $date = Carbon::parse($persona->fecha_update);
            $now = Carbon::now();
            $persona->diferencia = $date->diffInDays($now);

            $persona->progress = round($this->getProgress($persona),2);
        }
                        
        return $leads;
    }

    public function excelLeads(Request $request){

        $leads = $this->queryLeads($request);

        $leads = $leads->get();       

        return Excel::create('Digital Leads', function($excel) use ($leads){
            $excel->sheet('Digital Leads', function($sheet) use ($leads){
                
                $sheet->row(1, [
                    'Nombre','Apellidos', 'Celular', 'Correo electronico',
                    'Proyecto de interés','Prototipo de interés','Campaña publicitaria', 
                    'Medio de contacto','Vendedor asignado','Fecha de alta'
                ]);

                $sheet->cells('A1:J1', function ($cells) {
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
                    if($lead->nombre_campania != NULL){
                        $campaña = $lead->nombre_campania.'-'.$lead->medio_digital;
                    }
                    if($lead->proyecto_interes != 0){
                        $proyecto = $lead->proyecto;
                    }
                    else{
                        $proyecto = $lead->zona_interes;
                    }

                    $sheet->row($index+2, [
                        $lead->nombre, 
                        $lead->apellidos,
                        $lead->celular,
                        $lead->email,
                        $proyecto,
                        $lead->modelo_interes,
                        $campaña,
                        $lead->medio_contacto,
                        $lead->vendedor,
                        $lead->created_at
                    ]);	
                }
                $num='A1:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');

    }

    private function getProgress($lead){
        $progress = 0;

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

        $progress = ($progress/26) * 100;
        return $progress;

    }

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
        $leads = Digital_lead::leftJoin('campanias as c','digital_leads.campania_id','=','c.id')
                        ->leftJoin('fraccionamientos as f','digital_leads.proyecto_interes','=','f.id')
                        ->leftJoin('personal as p','digital_leads.vendedor_asign','=','p.id')
                        ->select(
                                DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS vendedor"),
                                'c.nombre_campania','c.medio_digital','f.nombre as proyecto','digital_leads.*')
                        ->where('digital_leads.motivo','=',$request->motivo);
                        if(Auth::user()->rol_id == 2){
                            $leads = $leads->where('vendedor_asign','=',Auth::user()->id);
                                if($status == ''){
                                    $leads = $leads->where('digital_leads.status','!=',0);
                                }
                            
                        }

                        if($fecha1 != '' && $fecha2!=''){
                            $leads = $leads->whereBetween('digital_leads.created_at',[$fecha1,$fecha2]);
                        }

                        if($buscar != ''){
                            $leads = $leads->where(DB::raw("CONCAT(digital_leads.nombre,' ',digital_leads.apellidos)"), 'like', '%'. $buscar . '%');
                        }

                        if($status != ''){
                            $leads = $leads->where('digital_leads.status','=',$status);
                        }

                        if($modelo != ''){
                            $leads = $leads->where('digital_leads.modelo_interes', 'like', '%'. $modelo . '%');
                        }

                        if($proyecto != ''){
                            $leads = $leads->where('digital_leads.proyecto_interes','=',$proyecto);
                        }

                        if($campania != ''){
                            $leads = $leads->where('digital_leads.campania_id','=',$campania);
                        }

                        if($asesor != ''){
                            $leads = $leads->where('digital_leads.vendedor_asign','=',$asesor);
                        }

                        if($prioridad != '')
                            $leads = $leads->where('digital_leads.prioridad','=',$prioridad);

                        $leads = $leads->orderBy('nombre','asc')
                        ->orderBy('apellidos','asc');

            return $leads;
    }
    
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $lead = new Digital_lead();
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->telefono = $request->telefono;
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
        if($lead->motivo == 4)
            $lead->prioridad = $request->prioridad;
        $lead->descripcion = $request->descripcion;
        $lead->direccion = $request->direccion;

        if($request->vendedor_asign!= 0){
            
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
        
        $obs = new Obs_lead();
        $obs->lead_id = $lead->id;
        $obs->comentario = 'Lead registrado';
        $obs->usuario = Auth::user()->usuario;
        $obs->visto = $fecha;
        $obs->save();

        if($lead->motivo == 2){
            $obs = new Obs_lead();
            $obs->lead_id = $lead->id;
            $obs->comentario = 'Cliente necesita atención postventa, para mayor informacion revisar el modulo de Digital Leads';
            $obs->usuario = Auth::user()->usuario;
            $obs->save();

        }
        if($lead->motivo == 3){
            $obs = new Obs_lead();
            $obs->lead_id = $lead->id;
            $obs->comentario = 'Lead busca información sobre renta de casa, para mayor infomración revisar el modulo de Digital Leads';
            $obs->usuario = Auth::user()->usuario;
            $obs->save();
        }

        if($lead->motivo == 4){
            $obs = new Obs_lead();
            $obs->lead_id = $lead->id;
            $obs->comentario = 'Cliente de atención especial, para mas información visita el modulo Digital Leads';
            $obs->usuario = Auth::user()->usuario;
            $obs->save();
        }

        if($lead->motivo == 5){
            $obs = new Obs_lead();
            $obs->lead_id = $lead->id;
            $obs->comentario = 'Cliente registrado como Aliado Cumbres';
            $obs->usuario = Auth::user()->usuario;
            $obs->save();
        }

        if($lead->motivo == 6){
            $obs = new Obs_lead();
            $obs->lead_id = $lead->id;
            $obs->comentario = 'Lead ofreciendo terreno para venta, para mayor informacion revisar el modulo de Digital Leads';
            $obs->usuario = Auth::user()->usuario;
            $obs->save();
        }
    }
    
    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lead = Digital_lead::findOrFail($request->id);
        $lead->delete();
    }

    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $lead = Digital_lead::findOrFail($request->id);
        $lead->nombre = $request->nombre;
        $lead->apellidos = $request->apellidos;
        $lead->telefono = $request->telefono;
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

            if($lead->vendedor_asign != $request->vendedor_asign){
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

                $obs = new Obs_lead();
                $obs->lead_id = $lead->id;
                $obs->comentario = 'Aviso!, se le ha asignado un nuevo lead para seguimiento, 
                                    favor de ingresar al modulo de Digital Leads para mas información. ';
                $obs->usuario = Auth::user()->usuario;
                $obs->save();
        
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

        $obs = new Obs_lead();
        $obs->lead_id = $lead->id;
        $obs->comentario = 'Se actualiza información del Lead';
        $obs->usuario = Auth::user()->usuario;
        $obs->visto = $fecha;
        $obs->save();

            

    }

    public function sendProspectos(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $cliente = Personal::select('id')->where('rfc','=',$request->rfc)->get();
        
        $medio = Medio_publicitario::select('id')->where('nombre','like','%'.$request->medio_publicidad.'%')->get();
        if(sizeof($medio) == 0)
            $publi = 20;
        else{
            $publi = $medio[0]->id;
        }
        try{
            DB::beginTransaction();

            if(sizeof($cliente) == 0){
                $persona = new Personal();
                $persona->nombre = $request->nombre;
                $persona->apellidos = $request->apellidos;
                $persona->telefono = $request->telefono;
                $persona->celular = $request->celular;
                $persona->email = $request->email;
                $persona->rfc = $request->rfc;
                $persona->f_nacimiento = $request->f_nacimiento;
                $persona->departamento_id = 8;
                $persona->save();

                $id = $persona->id;

                $cliente = new Cliente();
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
            else{
                $persona = Personal::findOrFail($cliente[0]->id);
                $persona->nombre = $request->nombre;
                $persona->apellidos = $request->apellidos;
                $persona->telefono = $request->telefono;
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
            $lead = Digital_lead::findOrFail($request->id);
            $lead->prospecto = 1;
            $lead->status = 3;
            $lead->save();

            $obs = new Obs_lead();
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

    public function storeObs(Request $request){
        $fecha = Carbon::now();
        $obs = new Obs_lead();
        $obs->lead_id = $request->lead_id;
        $obs->comentario = $request->comentario;
        $obs->usuario = Auth::user()->usuario;
        if($request->fecha_aviso == '')
            $obs->visto = $fecha;
        else{
            $obs->fecha_aviso = $request->fecha_aviso;
        }
        $obs->save();

        $lead = Digital_lead::findOrFail($request->lead_id);
        $lead->fecha_update = $fecha;
        $lead->save();

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

        if($lead->motivo == 1){
            $personal = User::select('id')
            ->where('id', '=', 25511)
            ->orWhere('rol_id','=',8)
            ->where('digital_lead','=',1)
            ->orWhere('rol_id','=',1)
            ->get();
        }

        if($lead->motivo == 2){
            $personal = User::select('id')
            ->where('id', '=', 25511)
            ->orWhere('rol_id','=',8)
            ->where('digital_lead','=',1)

            ->orWhere('rol_id','=',12)
            ->where('digital_lead','=',1)
            ->orWhere('rol_id','=',1)
            ->get();
        }

        if($lead->motivo == 3){
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

    public function getObs(Request $request){
        $obs = Obs_lead::where('lead_id','=',$request->id)
        ->orderBy('created_at','desc')->paginate(15);
        return $obs;
    }

    public function asignarLead(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $lead = Digital_lead::findOrFail($request->id);

            $cliente = new ClienteController();
            
            if($lead->proyecto_interes == 0 )
                $vendedor_asign = $cliente->asignarClienteAleatorio(0);
            else
                $vendedor_asign = $cliente->asignarClienteAleatorio($lead->proyecto_interes);
                
            $lead->vendedor_asign = $vendedor_asign['vendedor_elegido'];
            
            $lead->status = 2;
            $lead->save();

            
            $vendedor = Vendedor::findOrFail($vendedor_asign['vendedor_elegido']);
            $vendedor->cont_leads++;
            $vendedor->save();
        

            $obs = new Obs_lead();
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

    public function reminderCommentarioLead(){

        $reminders = Digital_lead::join('obs_leads', 'digital_leads.id','=','obs_leads.lead_id')
            ->select('obs_leads.id','nombre','apellidos','celular','email','comentario','motivo')
            ->where('obs_leads.visto','=', NULL);
        
        $reminders_fecha;
            
            if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1){
                $reminders = $reminders->where('digital_leads.vendedor_asign','=', Auth::user()->id)
                                ->where('obs_leads.fecha_aviso','=',NULL);
                $reminders = $reminders->get();

                $reminders_fecha = Digital_lead::join('obs_leads', 'digital_leads.id','=','obs_leads.lead_id')
                    ->select('obs_leads.id','nombre','apellidos','celular','email','comentario','motivo','obs_leads.fecha_aviso')
                    ->where('obs_leads.visto','=', NULL)
                    ->where('obs_leads.fecha_aviso','=', Carbon::now()->format('Y-m-d'))->get();
    
            }

            elseif(Auth::user()->rol_id == 12 ){
                $reminders = $reminders->where('motivo','=', 2);
                $reminders = $reminders->get();
            }

            elseif(Auth::user()->id == 25816 ){
                $reminders = $reminders->where('motivo','=', 3);
                $reminders = $reminders->get();
            }
            elseif(Auth::user()->id == 10 || Auth::user()->rol_id == 1){
                $reminders = $reminders->where('motivo','=', 4);
                $reminders = $reminders->get();
            }

            elseif(Auth::user()->id == 3){
                $reminders = $reminders->where('motivo','=', 6);
                $reminders = $reminders->get();
            }

            return ['reminders'=>$reminders,
                    'reminders_fecha' => $reminders_fecha
                ];
       
    }

    public function changeStatus(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lead = Digital_lead::findOrFail($request->id);
        $lead->status = $request->status;
        $lead->save();

        if($request->status == 0){

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

                $usuarios = User::select('id')->where('rol_id','=',8)->where('digital_lead','=',1)->get();
                foreach ($usuarios as $usuario) 
                    User::findOrFail($usuario->id)->notify(new NotifyAdmin($arreglo));

        }
    }

    public function leadEnterado(Request $request){
        $fecha = Carbon::now();
        $obs = Obs_lead::findOrFail($request->id);
        $obs->visto = $fecha;
        $obs->save();

    }

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

    public function reporteLeads(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $proyecto = $request->proyecto;

        $campanias = Campania::select('nombre_campania','medio_digital','fecha_ini','fecha_fin','id')
        ->get();

        $cont_org = 0;
        $asesor_org = 0;
        
        foreach ($campanias as $campania) {

            //TOTAL LEADS POR CAMPAÑA
                $campania->conteo = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                        if($fecha1 != '')
                            $campania->conteo = $campania->conteo->whereBetween('created_at', [$fecha1, $fecha2]);

                        if($proyecto != '')
                            $campania->conteo = $campania->conteo->where('proyecto_interes', '=',$proyecto);

                $campania->conteo = $campania->conteo->count();

            //LEADS ASIGNADOS A ASESOR
                $campania->asesor = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                        
                $campania->asesor = $campania->asesor->where('vendedor_asign','!=',NULL);
                        if($fecha1 != '')
                            $campania->asesor = $campania->asesor->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '')
                            $campania->asesor = $campania->asesor->where('proyecto_interes', '=',$proyecto);
                $campania->asesor = $campania->asesor->count();

            // LEADS DESCARTADOS SIN ASIGNAR
                $campania->descartado = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                $campania->descartado = $campania->descartado->where('status','=',0)->where('vendedor_asign','=',NULL);
                        if($fecha1 != '')
                            $campania->descartado = $campania->descartado->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '')
                            $campania->descartado = $campania->descartado->where('proyecto_interes', '=',$proyecto);
                $campania->descartado = $campania->descartado->count();

            // LEADS DESCARTADOS POR ASESOR
                $campania->descAsesor = Digital_lead::select('campania_id')
                        ->where('campania_id','=',$campania->id)->where('motivo','=',1);
                $campania->descAsesor = $campania->descAsesor->where('status','=',0)->where('vendedor_asign','!=',NULL);
                        if($fecha1 != '')
                            $campania->descAsesor = $campania->descAsesor->whereBetween('created_at', [$fecha1, $fecha2]);
                        if($proyecto != '')
                            $campania->descAsesor = $campania->descAsesor->where('proyecto_interes', '=',$proyecto);
                $campania->descAsesor = $campania->descAsesor->count();
        }

        /// CONTEOS PARA TRAFICO ORGANICO
            $cont_org = Digital_lead::select('campania_id')
                            ->where('campania_id','=',NULL)
                            ->where('motivo','=',1);
                            if($fecha1 != '')
                                $cont_org = $cont_org->whereBetween('created_at', [$fecha1, $fecha2]);

                            if($proyecto != '') 
                                $cont_org = $cont_org->where('proyecto_interes', '=',$proyecto);
                    $cont_org = $cont_org->count();

            $cont_desc = Digital_lead::select('campania_id')
                            ->where('campania_id','=',NULL)
                            ->where('motivo','=',1)
                            ->where('status','=',0)
                            ->where('vendedor_asign','=',NULL);
                            if($fecha1 != '')
                                $cont_desc = $cont_desc->whereBetween('created_at', [$fecha1, $fecha2]);

                            if($proyecto != '') 
                                $cont_desc = $cont_desc->where('proyecto_interes', '=',$proyecto);
                    $cont_desc = $cont_desc->count();

            $desc_ase = Digital_lead::select('campania_id')
                            ->where('campania_id','=',NULL)
                            ->where('motivo','=',1)
                            ->where('status','=',0)
                            ->where('vendedor_asign','!=',NULL);
                            if($fecha1 != '')
                                $desc_ase = $desc_ase->whereBetween('created_at', [$fecha1, $fecha2]);

                            if($proyecto != '') 
                                $desc_ase = $desc_ase->where('proyecto_interes', '=',$proyecto);
                    $desc_ase = $desc_ase->count();

            $asesor_org = Digital_lead::select('campania_id')
                            ->where('campania_id','=',NULL)
                            ->where('vendedor_asign','!=',NULL);
                            if($fecha1 != '')
                                $asesor_org = $asesor_org->whereBetween('created_at', [$fecha1, $fecha2]);

                            if($proyecto != '') 
                                $asesor_org = $asesor_org->where('proyecto_interes', '=',$proyecto);
                    $asesor_org = $asesor_org->where('motivo','=',1)->count();

        /// REPORTE POR ASESOR
            $asesores = Digital_lead::join('personal','digital_leads.vendedor_asign','=','personal.id')
                                    ->select('personal.id','personal.nombre','personal.apellidos')
                                    ->where('vendedor_asign','!=',NULL)
                                    ->where('motivo','=',1)
                                    ->groupBy('personal.id')
                                    ->get();

            foreach ($asesores as $asesor) {    
                $asesor->conteo = Digital_lead::select('vendedor_asign')
                                ->where('vendedor_asign','=',$asesor->id)->where('motivo','=',1);
                                if($fecha1 != '')
                                    $asesor->conteo = $asesor->conteo->whereBetween('created_at', [$fecha1, $fecha2]);

                                if($proyecto != '')     
                                    $asesor->conteo = $asesor->conteo->where('proyecto_interes', '=',$proyecto);
                    $asesor->conteo = $asesor->conteo->count();

                $asesor->descartados = Digital_lead::select('vendedor_asign')
                                ->where('vendedor_asign','=',$asesor->id)->where('motivo','=',1)
                                ->where('status','=',0);
                                if($fecha1 != '')
                                    $asesor->descartados = $asesor->descartados->whereBetween('created_at', [$fecha1, $fecha2]);

                                if($proyecto != '')     
                                    $asesor->descartados = $asesor->descartados->where('proyecto_interes', '=',$proyecto);
                    $asesor->descartados = $asesor->descartados->count();

                $asesor->sinAtender = 0;
            }

            $vendedores = User::join('personal','users.id','=','personal.id')
                ->join('vendedores','personal.id','vendedores.id')
                ->select('personal.id',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"))
                ->where('vendedores.tipo','=',0)
                ->where('users.condicion','=',1)
                ->where('users.usuario','!=','descartado')
                ->where('users.usuario','!=','oficina')
                ->orderBy('vendedores.cont_leads','asc')
                ->orderBy('vendedor','asc')->get();

                foreach ($vendedores as $index => $vendedor) {

                    $vendedor->total = 0;
                    $vendedor->reg = 0;
                    $vendedor->bd7 = 0;
                    $vendedor->bd15 = 0;
                    $vendedor->dif = 0;
                    $vendedor->dif7 = 0;
                    $vendedor->dif15 = 0;
        
                    $clientes = Cliente::select('id')->where('vendedor_id','=',$vendedor->id)
                    ->where('clasificacion','!=',5)
                    ->where('clasificacion','!=',6)
                    ->where('clasificacion','!=',7)
                    ->where('clasificacion','!=',1)
                    ->get();
                    $vendedor->total = $clientes->count();
                    $vendedor->bd = 0;
        
                    foreach ($clientes as $index => $c) {
                        $obs = Cliente_observacion::where('created_at','>=',Carbon::now()->subDays(7)) //Clientes con seguimiento
                                                    //->where('created_at','<',Carbon::now()->subDays(16))
                        ->where('cliente_id','=',$c->id)
                        ->count();

                        $obs2 = Cliente_observacion::where('created_at','>=',Carbon::now()->subDays(15))
                        ->where('cliente_id','=',$c->id)
                        ->count();
        
                        if($obs > 0){
                            $vendedor->reg ++;
                        }

                        if($obs2 > 0){
                            $vendedor->bd7 ++;
                        }

                                
                        $vendedor->bd;
                        $vendedor->dif7 =  $vendedor->bd7 - $vendedor->reg;
                        $vendedor->dif15 = $vendedor->total - $vendedor->bd7 - $vendedor->bd;;
                    }
                }



        
        if($request->excel == 0)
            return [
                'campanias' => $campanias,
                'camp_org' => $cont_org,
                'asesor_org' => $asesor_org,
                'asesores' => $asesores,
                'desc_ase' => $desc_ase,
                'cont_desc' => $cont_desc,

                'vendedores' => $vendedores,
            ];
        else{

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
                            $asesor->dif7
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

    public function excelReporte(Request $request){

    }
}
