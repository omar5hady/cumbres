<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Digital_lead;
use App\Obs_lead;
use App\Personal;
use App\User;
use App\Cliente;
use App\Http\Controllers\ClienteController;
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
        $obs->visto = $fecha;
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
            
            if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1){
                $reminders = $reminders->where('digital_leads.vendedor_asign','=', Auth::user()->id);
                $reminders = $reminders->get();

                return $reminders;
            }

            elseif(Auth::user()->rol_id == 12 ){
                $reminders = $reminders->where('motivo','=', 2);
                $reminders = $reminders->get();

                return $reminders;
            }

            elseif(Auth::user()->id == 25816 ){
                $reminders = $reminders->where('motivo','=', 3);
                $reminders = $reminders->get();

                return $reminders;
            }
            elseif(Auth::user()->id == 10 || Auth::user()->rol_id == 1){
                $reminders = $reminders->where('motivo','=', 4);
                $reminders = $reminders->get();

                return $reminders;
            }
       
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
}
