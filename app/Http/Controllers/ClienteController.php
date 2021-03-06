<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Personal;
use App\Credito;
use App\Vendedor;
use App\Fraccionamiento;
use Auth;
use App\Cliente_observacion;
use Illuminate\Support\Facades\DB;
use Excel;
use Carbon\Carbon;
use App\Mail\NotificationReceived;
use App\Notifications\NotifyAdmin;
use App\User;
use App\Calendar;
Use App\Asign_proyecto;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $buscarC = $request->b_clasificacion;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $publicidad = $request->b_publicidad;
        $b_aux = $request->b_aux;

        $queryVendedor = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
                'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
                'personal.colonia','personal.ext','personal.cp', 
                'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
                'personal.email','personal.empresa_id', 
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
                'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto', 
                'clientes.estado','clientes.ciudad','clientes.proyecto_interes_id',
                'clientes.publicidad_id','clientes.edo_civil','clientes.nss', 'clientes.nombre_recomendado',
                'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado',
                'clientes.clasificacion','clientes.created_at','clientes.precio_rango','clientes.ingreso',
                'clientes.created_at', 'clientes.lugar_nacimiento','clientes.vendedor_aux','clientes.reasignar',
                
                'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa',
                'clientes.empresa_coa','clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa',
                'clientes.nombre_coa','clientes.apellidos_coa','clientes.f_nacimiento_coa', 
                'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
                'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa',
                'clientes.email_coa','clientes.parentesco_coa',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
                'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto'
            );

        $queryGen = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id' )
            ->leftJoin('personal as vAux', 'clientes.vendedor_aux', '=', 'vAux.id' )
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
            'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','personal.empresa_id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS v_completo"),
            DB::raw("CONCAT(vAux.nombre,' ',vAux.apellidos) AS vAux_completo"),
            'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto', 'clientes.estado','clientes.ciudad',
            'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss', 'clientes.nombre_recomendado',
            'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion', 'clientes.reasignar',
            'clientes.created_at','clientes.precio_rango','clientes.ingreso','clientes.created_at','clientes.lugar_nacimiento',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa','clientes.vendedor_aux',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');
        
        if( Auth::user()->rol_id == 2){
            
                if ($buscar==''){
                    $personas = $queryVendedor;
                    if($b_aux == '')
                        $personas = $personas->where('vendedor_id','=',Auth::user()->id);
                    else
                        $personas = $personas->where('clientes.vendedor_aux','=',Auth::user()->id);
                    if($buscarC != '')
                        $personas = $personas->where('clientes.clasificacion', '=', $buscarC);
                }
                else{
                    if($criterio == 'personal.nombre'){
                        $personas = $queryVendedor->where($criterio, 'like', '%'. $buscar . '%');
                        if($b_aux == '')
                            $personas = $personas->where('vendedor_id','=',Auth::user()->id);
                        else
                            $personas = $personas->where('clientes.vendedor_aux','=',Auth::user()->id);

                        if($buscarC != '')
                            $personas = $personas->where('clientes.clasificacion', '=', $buscarC);
                        
                        $personas = $personas->orWhere('personal.apellidos', 'like', '%'. $buscar . '%');

                        if($b_aux == '')
                            $personas = $personas->where('vendedor_id','=',Auth::user()->id);
                        else
                            $personas = $personas->where('clientes.vendedor_aux','=',Auth::user()->id);

                        if($buscarC != '')
                            $personas = $personas->where('clientes.clasificacion', '=', $buscarC);

                        $personas = $personas->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        if($buscarC != '')
                            $personas = $personas->where('clientes.clasificacion', '=', $buscarC);

                        if($b_aux == '')
                            $personas = $personas->where('vendedor_id','=',Auth::user()->id);
                        else
                            $personas = $personas->where('clientes.vendedor_aux','=',Auth::user()->id);
                        
                    }
                    else{
                        $personas = $queryVendedor
                        ->where($criterio, 'like', '%'. $buscar . '%');
                        if($buscarC != '')
                            $personas = $personas->where('clientes.clasificacion', '=', $buscarC);
                        if($b_aux == '')
                            $personas = $personas->where('vendedor_id','=',Auth::user()->id);
                        else
                            $personas = $personas->where('clientes.vendedor_aux','=',Auth::user()->id);   
                        
                    }
                }
            
            
        }
        else{
            if($buscarC!=''){
                if ($buscar==''){
                    $personas = $queryGen
                    //->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '=', $buscarC);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryGen->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                    elseif($criterio == 'clientes.vendedor_id'){
                        if($buscar2 == ""){
                            $personas = $queryGen  
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, '=',$buscar)
                            ->where('clientes.clasificacion', '=', $buscarC);
                            
                        }else{
                            $personas = $queryGen
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, '=',$buscar)
                            ->where('clientes.clasificacion', '=', $buscarC)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar2 . '%');
                        }
                        
                    }
                    elseif($criterio == 'clientes.created_at'){
                        if($buscar3 != ''){
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('fraccionamientos.id','=',$buscar3);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('clientes.publicidad_id', '=', $publicidad)
                                    ->where('fraccionamientos.id','=',$buscar3);

                            }
                            
                        }
                        else{
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('clientes.publicidad_id', '=', $publicidad);

                            }
                        }
                        
                    }
    
                    else{
                        $personas = $queryGen
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, 'like', '%'. $buscar . '%')
                            ->where('clientes.clasificacion', '=', $buscarC);
                    }
                }

            }
            else{
                if ($buscar==''){
                    $personas = $queryGen
                    //->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '!=', 7);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryGen->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                    elseif($criterio == 'clientes.vendedor_id'){
                        if($buscar2 == ""){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, '=',$buscar)
                        ->where('clientes.clasificacion', '!=', 7);
                        }else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, '=',$buscar)
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar2 . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                        }
                    }
                    elseif($criterio == 'clientes.created_at'){
                        if($buscar3 != ''){
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('fraccionamientos.id','=',$buscar3)
                                    ->where('clientes.clasificacion', '!=', 7);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('fraccionamientos.id','=',$buscar3)
                                    ->where('clientes.clasificacion', '!=', 7)
                                    ->where('clientes.publicidad_id', '=', $publicidad);
                            }
                        }
                        else{
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '!=', 7);
                            }
                            else{
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '!=', 7)
                                    ->where('clientes.publicidad_id', '=', $publicidad);
                            }
                        }  
                    }
    
                    else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                }
            }
        }

        if($b_aux != '')
            $personas = $personas->where('clientes.vendedor_aux','!=',NULL);

        $personas = $personas->orderBy('personal.nombre', 'asc')
                        ->orderBy('personal.apellidos', 'asc')
                        ->paginate(20);

        foreach($personas as $index => $persona){
            $ultimoCom = Cliente_observacion::select('created_at')->where('cliente_id','=',$persona->id)->orderBy('id','desc')->get();
            if(sizeof($ultimoCom)){
                $date = Carbon::parse($ultimoCom[0]->created_at);
                $now = Carbon::now();
                $persona->diferencia = $date->diffInDays($now);
            }
            else{
                $persona->diferencia = 16;
            }
            
        }
         
 
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas, 'contadorClientes' => $personas->total(), 'user'=> Auth::user()->id
        ];
    }
 
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
         
        try{
            DB::beginTransaction();
            $persona = new Personal();
            $persona->departamento_id = $request->departamento_id;
            $persona->nombre = $request->nombre;
            $persona->apellidos = $request->apellidos;
            $persona->f_nacimiento = $request->f_nacimiento;
            $persona->rfc = $request->rfc;
            $persona->homoclave = $request->homoclave;
            $persona->telefono = $request->telefono;
            $persona->ext = $request->ext;
            $persona->celular = $request->celular;
            $persona->email = $request->email;
            $persona->departamento_id = 8;
            $persona->activo = 1;
            $persona->save();
 
            $cliente = new Cliente();
            $cliente->id = $persona->id;
            $cliente->sexo = $request->sexo;
            $cliente->tipo_casa = $request->tipo_casa;
            $cliente->email_institucional = $request->email_institucional;
            $cliente->lugar_contacto = $request->lugar_contacto;
            $cliente->lugar_nacimiento = $request->lugar_nacimiento;
            $cliente->nombre_recomendado = $request->nombre_recomendado;
            $cliente->proyecto_interes_id = $request->proyecto_interes_id;
            $cliente->publicidad_id = $request->publicidad_id;
            $cliente->edo_civil = $request->edo_civil;
            $cliente->nss = $request->nss;
            $cliente->curp = $request->curp;
            if($request->vendedor_id == NULL){
                $rolUsuario = Auth::user()->rol_id;
                if($rolUsuario == 1 || $rolUsuario == 4 || $rolUsuario == 6 || $rolUsuario == 8)
                    $cliente->vendedor_id = 19;
                else{
                    $cliente->vendedor_id = Auth::user()->id;
                }
            }
            else{
                $cliente->vendedor_id = $request->vendedor_id;
            }
            $cliente->empresa = $request->empresa;
            $cliente->precio_rango = $request->precio_rango;
            $cliente->ingreso = $request->ingreso;
            $cliente->coacreditado = $request->coacreditado;
            if($request->coacreditado == 1){
                $obsCoa = Personal::select('id')->where('rfc','=',$request->rfc_coa)->get();
                $observacion1 = new Cliente_observacion();
                $observacion1->cliente_id = $obsCoa[0]->id;
                $observacion1->comentario = 'Dado de alta como coacreditado de '.$request->nombre.' '.$request->apellidos;
                $observacion1->usuario = Auth::user()->usuario;
                $observacion1->save();

            }
            $cliente->clasificacion = $request->clasificacion;
            
            
            $cliente->sexo_coa = $request->sexo_coa;
            $cliente->tipo_casa_coa = $request->tipo_casa_coa;
            $cliente->email_institucional_coa = $request->email_institucional_coa;
            $cliente->empresa_coa = $request->empresa_coa;
            $cliente->edo_civil_coa = $request->edo_civil_coa;
            $cliente->nss_coa = $request->nss_coa;
            $cliente->curp_coa = $request->curp_coa;
            $cliente->nombre_coa = $request->nombre_coa;
            $cliente->apellidos_coa = $request->apellidos_coa;
            $cliente->f_nacimiento_coa = $request->f_nacimiento_coa;
            $cliente->rfc_coa = $request->rfc_coa;
            $cliente->homoclave_coa = $request->homoclave_coa;
            $cliente->telefono_coa = $request->telefono_coa;
            $cliente->ext_coa = $request->ext_coa;
            $cliente->celular_coa = $request->celular_coa;
            $cliente->email_coa = $request->email_coa;
            $cliente->parentesco_coa = $request->parentesco_coa;
            $cliente->lugar_nacimiento_coa = $request->lugar_nacimiento_coa;
            $cliente->save();

            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $persona->id;
            $observacion->comentario = $request->observacion;
            $observacion->prox_cita = $request->makeRemember;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }         
         
    }

    public function storeCoacreditado(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
         
        try{
            DB::beginTransaction();
            $persona = new Personal();
            $persona->departamento_id = $request->departamento_id;
            $persona->nombre = $request->nombre;
            $persona->apellidos = $request->apellidos;
            $persona->f_nacimiento = $request->f_nacimiento;
            $persona->rfc = $request->rfc;
            $persona->homoclave = $request->homoclave;
            $persona->telefono = $request->telefono;
            $persona->celular = $request->celular;
            $persona->email = $request->email;
            $persona->departamento_id = 8;
            $persona->activo = 1;
            $persona->save();
 
            $cliente = new Cliente();
            $cliente->id = $persona->id;
            $cliente->sexo = $request->sexo;
            $cliente->tipo_casa = $request->tipo_casa;
            $cliente->email_institucional = $request->email_institucional;
            $cliente->lugar_contacto = $request->lugar_contacto;
            $cliente->proyecto_interes_id = $request->proyecto_interes_id;
            $cliente->publicidad_id = 1;
            $cliente->edo_civil = $request->edo_civil;
            $cliente->nss = $request->nss;
            $cliente->curp = $request->curp;
            $rolUsuario = Auth::user()->rol_id;
            if($rolUsuario == 1 || $rolUsuario == 4 || $rolUsuario == 6 || $rolUsuario == 8)
                $cliente->vendedor_id = 19;
            else{
                $cliente->vendedor_id = Auth::user()->id;
            }
            $cliente->empresa = $request->empresa;
            $cliente->coacreditado = $request->coacreditado;
            $cliente->clasificacion = 7;
            $cliente->lugar_nacimiento = $request->lugar_nacimiento;
            $cliente->nombre_recomendado = $request->nombre_recomendado;
            
            $cliente->save();

            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $persona->id;
            $observacion->comentario = "Dado de alta como coacreditado";
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }         
         
    }
 
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
         
        try{
            DB::beginTransaction();
 
            
            $cliente = Cliente::findOrFail($request->id);
 
            $Persona = Personal::findOrFail($request->id);
 
            $Persona->departamento_id = 8;
            $Persona->nombre = $request->nombre;
            $Persona->apellidos = $request->apellidos;
            $Persona->f_nacimiento = $request->f_nacimiento;
            $Persona->rfc = $request->rfc;
            // $Persona->direccion = $request->direccion;
            // $Persona->colonia = $request->colonia;
            // $Persona->cp = $request->cp;
            $Persona->telefono = $request->telefono;
            $Persona->ext = $request->ext;
            $Persona->celular = $request->celular;
            $Persona->email = $request->email;
            $Persona->homoclave = $request->homoclave;
            $Persona->activo = 1;
           
            $Persona->save();
             
            $cliente->sexo = $request->sexo;
            $cliente->tipo_casa = $request->tipo_casa;
            $cliente->email_institucional = $request->email_institucional;
            $cliente->lugar_contacto = $request->lugar_contacto;
            $cliente->proyecto_interes_id = $request->proyecto_interes_id;
            $cliente->publicidad_id = $request->publicidad_id;
            $cliente->edo_civil = $request->edo_civil;
            $cliente->nss = $request->nss;
            $cliente->curp = $request->curp;
            $cliente->nombre_recomendado = $request->nombre_recomendado;
            $cliente->lugar_nacimiento = $request->lugar_nacimiento;
            
            $cliente->empresa = $request->empresa;
            $cliente->precio_rango = $request->precio_rango;
            $cliente->ingreso = $request->ingreso;

            $cliente->coacreditado = $request->coacreditado;
            $cliente->clasificacion = $request->clasificacion;
            
            if($cliente->nombre_coa != $request->nombre_coa){
            $cliente->sexo_coa = $request->sexo_coa;
            $cliente->tipo_casa_coa = $request->tipo_casa_coa;
            $cliente->email_institucional_coa = $request->email_institucional_coa;
            $cliente->empresa_coa = $request->empresa_coa;
            $cliente->edo_civil_coa = $request->edo_civil_coa;
            $cliente->nss_coa = $request->nss_coa;
            $cliente->curp_coa = $request->curp_coa;
            $cliente->nombre_coa = $request->nombre_coa;
            $cliente->apellidos_coa = $request->apellidos_coa;
            $cliente->f_nacimiento_coa = $request->f_nacimiento_coa;
            $cliente->rfc_coa = $request->rfc_coa;
            $cliente->homoclave_coa = $request->homoclave_coa;
            // $cliente->direccion_coa = $request->direccion_coa;
            // $cliente->colonia_coa = $request->colonia_coa;
            // $cliente->cp_coa = $request->cp_coa;
            $cliente->lugar_nacimiento_coa = $request->lugar_nacimiento_coa;
            $cliente->telefono_coa = $request->telefono_coa;
            $cliente->ext_coa = $request->ext_coa;
            $cliente->celular_coa = $request->celular_coa;
            $cliente->email_coa = $request->email_coa;
            $cliente->parentesco_coa = $request->parentesco_coa;
            $cliente->ciudad_coa = "";
            $cliente->estado_coa = "";
            $cliente->nacionalidad_coa = 0;
            }
            $cliente->save();

            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $cliente->id;
            $observacion->comentario = $request->observacion;
            $observacion->prox_cita = $request->makeRemember;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }

    public function updateProspecto(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
         
        try{
            DB::beginTransaction();
 
            
            $cliente = Cliente::findOrFail($request->id);
 
            $Persona = Personal::findOrFail($request->id);
 
            $Persona->nombre = $request->nombre;
            $Persona->apellidos = $request->apellidos;
            $Persona->f_nacimiento = $request->f_nacimiento;
            $Persona->rfc = $request->rfc;
            $Persona->homoclave = $request->homoclave;
            $Persona->direccion = $request->direccion;
            $Persona->colonia = $request->colonia;
            $Persona->cp = $request->cp;
            $Persona->telefono = $request->telefono;
            $Persona->celular = $request->celular;
            $Persona->email = $request->email;
           
            $Persona->save();
             
            $cliente->sexo = $request->sexo;
            $cliente->tipo_casa = $request->tipo_casa;
            $cliente->email_institucional = $request->email_institucional;
            $cliente->proyecto_interes_id = $request->proyecto_interes_id;
            $cliente->publicidad_id = $request->publicidad_id;
            $cliente->edo_civil = $request->edo_civil;
            $cliente->nss = $request->nss;
            $cliente->curp = $request->curp;
            $cliente->clasificacion = $request->clasificacion;
            $cliente->save();
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }
 
    public function selectCoacreditadoVue(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;
        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 6 || Auth::user()->rol_id == 8 || Auth::user()->rol_id == 1){
            $coacreditados = Cliente::join('personal','clientes.id','=','personal.id')
            ->select('personal.nombre','personal.apellidos','personal.id','personal.rfc','personal.homoclave','personal.f_nacimiento',
                'personal.telefono','personal.celular','personal.email','clientes.sexo',
                'clientes.email_institucional','clientes.edo_civil','clientes.nss','clientes.curp','clientes.tipo_casa','clientes.lugar_nacimiento',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
                ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $filtro. '%')
            ->get();
            }else{
                $coacreditados = Cliente::join('personal','clientes.id','=','personal.id')
                ->select('personal.nombre','personal.apellidos','personal.id','personal.rfc','personal.homoclave','personal.f_nacimiento',
                    'personal.telefono','personal.celular','personal.email','clientes.sexo',
                    'clientes.email_institucional','clientes.edo_civil','clientes.nss','clientes.curp','clientes.tipo_casa','clientes.lugar_nacimiento',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
                ->where('vendedor_id','=',Auth::user()->id)
                ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $filtro. '%')
                ->get();
            }
        
        return['coacreditados' => $coacreditados];
    }

    public function listarObservacion(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Cliente_observacion::select('comentario','usuario','created_at', 'prox_cita')
                    ->where('cliente_id','=', $buscar)->orderBy('created_at','desc')->paginate(40);

        return [
            'pagination' => [
                'total'         => $observacion->total(),
                'current_page'  => $observacion->currentPage(),
                'per_page'      => $observacion->perPage(),
                'last_page'     => $observacion->lastPage(),
                'from'          => $observacion->firstItem(),
                'to'            => $observacion->lastItem(),
            ],
            'observacion' => $observacion
        ];
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $cliente = Cliente::findOrFail($request->id);

        $Personal->activo = '0';
        $Personal->save();

        $cliente->clasificacion = 1;
        $cliente->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $cliente = Cliente::findOrFail($request->id);

        $Personal->activo = '1';
        $Personal->save();

        $cliente->clasificacion = 5;
        $cliente->save();
        
    }

    public function selectClientes(Request $request){
        if (!$request->ajax()) return redirect('/');
        $personas = Cliente::join('personal','clientes.id','=','personal.id')
            ->select('personal.id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
            ->where('clientes.vendedor_id','=',$request->vendedor_id)
            ->orderBy('personal.nombre','asc')
            ->orderBy('personal.apellidos','asc')
            ->get();

        return['clientes' => $personas];
    }

    public function clientesSimulacion(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $rol = Auth::user()->rol_id;

        $query = Cliente::join('personal','clientes.id','=','personal.id')
                ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
                ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
                'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
                'personal.colonia','personal.ext','personal.cp',
                'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
                'personal.email',

                'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
                'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss',
                'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
                'clientes.nacionalidad','clientes.puesto', 'clientes.estado','clientes.ciudad', 'clientes.nombre_recomendado',
                
                'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
                'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
                'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
                'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
                'clientes.nacionalidad_coa','clientes.estado_coa','clientes.ciudad_coa',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
                'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');
   
        if($rol == 2){
            if ($buscar==''){
                $personas = $query
                ->where('vendedor_id','=',Auth::user()->id)
                ->where('clientes.clasificacion','=','2');
            }
            else{

                if($criterio == 'personal.nombre'){
                    $personas = $query 
                    ->where('vendedor_id','=',Auth::user()->id)     
                    ->where('clientes.clasificacion','=','2')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                    ->where('clientes.clasificacion','=','2');
                }

                else{
                    $personas = $query
                    ->where('vendedor_id','=',Auth::user()->id)     
                    ->where('clientes.clasificacion','=','2')
                    ->where($criterio, 'like', '%'. $buscar . '%');
                }
            }
        }
        else{
            if($rol == 1 || $rol==4 || $rol==6 || $rol==8 || $rol == 11){
                if ($buscar==''){
                    $personas = $query
                    ->where('clientes.clasificacion','=','2');
                }
                else{

                    if($criterio == 'personal.nombre'){
                        $personas = $query
                        ->where('clientes.clasificacion','=','2')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion','=','2');
                    }

                    else{
                        $personas = $query
                        ->where('clientes.clasificacion','=','2')
                        ->where($criterio, 'like', '%'. $buscar . '%');

                    }
                }
            }
        }

        $personas = $personas->orderBy('personal.apellidos', 'desc')
                    ->orderBy('personal.nombre', 'desc')
                    ->paginate(20);
         
        
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas, 'contadorSimulacion' => $personas->total()
        ];
    }

    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

            $usuarioId = Auth::user()->id;

            $vendedor = Cliente::join('personal','clientes.id','=','personal.id')
                        ->select('clientes.vendedor_id','personal.nombre', 'personal.apellidos')
                        ->where('clientes.id','=',$request->cliente_id)->get();
         
            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $request->cliente_id;
            $observacion->comentario = $request->observacion;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save(); 
            
            
            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=',$usuarioId)->get();
                $fecha = Carbon::now();
                $msj = $vendedor[0]->nombre . " " . $vendedor[0]->apellidos . " tiene una nueva observación, favor de revisarla";
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Nueva Observación'
                    ]
                ];
                    if($vendedor[0]->vendedor_id != $usuarioId)
                        User::findOrFail($vendedor[0]->vendedor_id)->notify(new NotifyAdmin($arregloAceptado));

         
    }

    public function indexProspectos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $vendedor = $request->vendedor;
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;
        $b_clasificacion = $request->b_clasificacion;
        $coacreditados = $request->coacreditados;

        $query = Cliente::join('personal','clientes.id','=','personal.id')
                ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
                ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
                'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
                'personal.colonia','personal.ext','personal.cp',
                'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
                'personal.email','personal.empresa_id', 
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
                'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
                'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss','clientes.created_at',
                'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
                
                'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
                'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
                'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
                'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
                'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');

        if($coacreditados == 0  &&$b_clasificacion!=''){
            if ($buscar==''){
                $personas = $query
                ->where('vendedor_id','=',$vendedor)
                ->where('clientes.clasificacion', '=', $b_clasificacion)
                ->where('clientes.clasificacion', '!=', 7);
            }
            else{
    
                if($criterio == 'personal.nombre'){
                     $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('clientes.clasificacion', '=', $b_clasificacion)
                    ->where('clientes.clasificacion', '!=', 7)
                    ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                    ->where('vendedor_id','=',$vendedor)     
                    ->where('clientes.clasificacion', '=', $b_clasificacion)
                    ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                    ->where('vendedor_id','=',$vendedor)     
                    ->where('clientes.clasificacion', '=', $b_clasificacion)
                    ->where('clientes.clasificacion', '!=', 7);
                }

                elseif($criterio == 'clientes.proyecto_interes_id'){
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, '=',$buscar)
                    ->where('clientes.clasificacion', '=', $b_clasificacion)
                    ->where('clientes.clasificacion', '!=', 7);
                }

                elseif($criterio == 'clientes.created_at'){
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->whereBetween($criterio, [$buscar, $buscar2])
                    ->where('clientes.clasificacion', '=', $b_clasificacion)
                    ->where('clientes.clasificacion', '!=', 7);
                }
    
                else{
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('clientes.clasificacion', '=', $b_clasificacion)
                    ->where('clientes.clasificacion', '!=', 7);
                }
            }

        }
        elseif($coacreditados == 1 && $b_clasificacion == ''){
            if ($buscar==''){
                $personas = $query
                ->where('vendedor_id','=',$vendedor)
                ->where('clientes.clasificacion', '=', 7);
            }
            else{
    
                if($criterio == 'personal.nombre'){
                     $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('clientes.clasificacion', '=', 7)
                    ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                    ->where('vendedor_id','=',$vendedor)     
                    ->where('clientes.clasificacion', '=', 7)
                    ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                    ->where('vendedor_id','=',$vendedor)     
                    ->where('clientes.clasificacion', '=', 7);
                }

                elseif($criterio == 'clientes.proyecto_interes_id'){
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, '=',$buscar)
                    ->where('clientes.clasificacion', '=', 7);
                }

                elseif($criterio == 'clientes.created_at'){
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->whereBetween($criterio, [$buscar, $buscar2])
                    ->where('clientes.clasificacion', '=', 7);
                }
    
                else{
                    $personas = $query   
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('clientes.clasificacion', '=', 7);
                }
            }
        }
        else{
            if ($buscar==''){
                $personas = $query
                ->where('vendedor_id','=',$vendedor)
                ->where('clientes.clasificacion', '!=', 7);
            }
            else{
    
                if($criterio == 'personal.nombre'){
                     $personas = $query
                    ->where('clientes.clasificacion', '!=', 7)
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    
                    ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                    ->where('vendedor_id','=',$vendedor)   
                    
                    ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                    ->where('vendedor_id','=',$vendedor)     
                    ->where('clientes.clasificacion', '!=', 7);
                }

                elseif($criterio == 'clientes.proyecto_interes_id'){
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, '=',$buscar);
                }

                elseif($criterio == 'clientes.created_at'){
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->whereBetween($criterio, [$buscar, $buscar2]);
                }
    
                else{
                    $personas = $query
                    ->where('vendedor_id','=',$vendedor)     
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('clientes.clasificacion', '!=', 7);
                }
            }
        } 

        $personas = $personas->orderBy('personal.apellidos', 'desc')
                    ->orderBy('personal.nombre', 'desc')
                    ->paginate(20);
 
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas, 'contador' => $personas->total()
        ];
    }

    public function asignarCliente(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        try{
            
            DB::beginTransaction();
            $persona = Personal::findOrFail($request->id);
            $cliente = Cliente::findOrFail($request->id);
            $vendedorAnt = Personal::select('id','nombre','apellidos')->where('id','=',$cliente->vendedor_id)->get();
            $vendedorNew = Personal::select('id','nombre','apellidos')->where('id','=',$request->asesor_id)->get();
            $cliente->vendedor_id = $request->asesor_id;
            $cliente->save();

            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $request->id;
            $observacion->comentario = 'Cliente reasignado del asesor '.$vendedorAnt[0]->nombre.' '.$vendedorAnt[0]->apellidos.' al asesor '.$vendedorNew[0]->nombre.' '.$vendedorNew[0]->apellidos;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $msj = 'Se ha asignado el cliente '.$persona->nombre.' '.$persona->apellidos.' al asesor '.$vendedorNew[0]->nombre.' '.$vendedorNew[0]->apellidos;
            $arregloAceptado = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => $msj,
                    'titulo' => 'Prospecto reasignado'
                ]
            ];

            User::findOrFail($vendedorNew[0]->id)->notify(new NotifyAdmin($arregloAceptado));
            User::findOrFail(10)->notify(new NotifyAdmin($arregloAceptado));

            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function asignarCliente2(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        
         
        try{
            DB::beginTransaction();
            //FindOrFail se utiliza para buscar lo que recibe de argumento
            $cliente = Cliente::findOrFail($request->id);
            $cliente->vendedor_id = $request->asesor_id;
            if($cliente->clasificacion == 1 && Auth::user()->rol_id == 28271 || $cliente->clasificacion == 1 && Auth::user()->rol_id == 28270 || $cliente->clasificacion == 1 && Auth::user()->rol_id == 28128)
                return redirect('/');
            $cliente->clasificacion = $request->clasificacion;
            
            $cliente->save();
            
            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $request->id;
            $observacion->comentario = $request->observacion;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function exportExcelClientesAsesor(Request $request)
    {
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $buscarC = $request->b_clasificacion;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $publicidad = $request->b_publicidad;

        $queryVendedor = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
            'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','personal.empresa_id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
            'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss', 'clientes.nombre_recomendado',
            'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
            'clientes.created_at','clientes.precio_rango','clientes.ingreso','clientes.created_at',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');

        $queryGen = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id' )
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
            'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','personal.empresa_id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS v_completo"),
            'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
            'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss', 'clientes.nombre_recomendado',
            'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
            'clientes.created_at','clientes.precio_rango','clientes.ingreso','clientes.created_at',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');
        
        if( Auth::user()->rol_id == 2){
            if($buscarC != ''){
                if ($buscar==''){
                    $personas = $queryVendedor
                    ->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '=', $buscarC);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryVendedor  
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
    
                    else{
                        $personas = $queryVendedor
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                }
            }
            ////
            else{
                if ($buscar==''){
                    $personas = $queryVendedor
                    ->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '!=', 7);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryVendedor
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '!=', 7)
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '!=', 7);
                    }
    
                    else{
                        $personas = $queryVendedor
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                }

            }
            
        }
        else{
            if($buscarC!=''){
                if ($buscar==''){
                    $personas = $queryGen
                    //->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '=', $buscarC);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                    elseif($criterio == 'clientes.vendedor_id'){
                        if($buscar2 == ""){
                            $personas = $queryGen  
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, '=',$buscar)
                            ->where('clientes.clasificacion', '=', $buscarC);
                        }else{
                            $personas = $queryGen
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, '=',$buscar)
                            ->where('clientes.clasificacion', '=', $buscarC)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar2 . '%');
                        }
                        
                    }
                    elseif($criterio == 'clientes.created_at'){
                        if($buscar3 != ''){
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('fraccionamientos.id','=',$buscar3);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('clientes.publicidad_id', '=', $publicidad)
                                    ->where('fraccionamientos.id','=',$buscar3);

                            }
                            
                        }
                        else{
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('clientes.publicidad_id', '=', $publicidad);

                            }
                        }
                        
                    }
    
                    else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                }

            }
            else{
                if ($buscar==''){
                    $personas = $queryGen
                    //->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '!=', 7);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                    elseif($criterio == 'clientes.vendedor_id'){
                        if($buscar2 == ""){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, '=',$buscar)
                        ->where('clientes.clasificacion', '!=', 7);
                        }else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, '=',$buscar)
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar2 . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                        }
                    }
                    elseif($criterio == 'clientes.created_at'){
                        if($buscar3 != ''){
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('fraccionamientos.id','=',$buscar3)
                                    ->where('clientes.clasificacion', '!=', 7);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('fraccionamientos.id','=',$buscar3)
                                    ->where('clientes.clasificacion', '!=', 7)
                                    ->where('clientes.publicidad_id', '=', $publicidad);
                            }
                        }
                        else{
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '!=', 7);
                            }
                            else{
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '!=', 7)
                                    ->where('clientes.publicidad_id', '=', $publicidad);
                            }
                        }  
                    }
    
                    else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                }
            }
        }
         
        $personas = $personas->orderBy('personal.nombre', 'asc')
                        ->orderBy('personal.apellidos', 'asc')
                        ->get();
         
 
        return Excel::create('resumen_cliente', function($excel) use ($personas){
            $excel->sheet('clientes', function($sheet) use ($personas){
                
                $sheet->row(1, [
                    'Nombre', 'Celular', 'Email', 'RFC', 'IMSS', 'CURP',
                    'Proyecto de interes','Clasificación'
                ]);


                $sheet->cells('A1:H1', function ($cells) {
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

                foreach($personas as $index => $persona) {
                    $cont++;
                    switch($persona->clasificacion){
                        case 1:{
                            $clasificacion = 'No viable';
                                break;
                        }
                        

                        case 2:{
                            $clasificacion = 'Tipo A';
                            break;
                        }
                        

                        case 3:
                        {
                            $clasificacion = 'Tipo B';
                                break;
                        }

                        case 4:
                        {
                            $clasificacion ='Tipo C';
                            break;
                        }

                        case 5:
                        {
                            $clasificacion = 'Ventas';
                            break;
                        }

                        case 6:
                        {
                            $clasificacion ='Cancelado';
                            break;
                        }

                        case 7:
                        {
                            $clasificacion ='Coacreditado';
                            break;
                        }

                    }

                    $sheet->row($index+2, [
                        $persona->n_completo, 
                        $persona->celular, 
                        $persona->email, 
                        mb_strtoupper($persona->rfc), 
                        $persona->nss,
                        mb_strtoupper($persona->curp),
                        $persona->proyecto,
                        $clasificacion
                    ]);	
                }
                $num='A1:H' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function exportExcelClientesGerente(Request $request)
    {
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $buscarC = $request->b_clasificacion;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $publicidad = $request->b_publicidad;

        $queryVendedor = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
            'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','personal.empresa_id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
            'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss', 'clientes.nombre_recomendado',
            'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
            'clientes.created_at','clientes.precio_rango','clientes.ingreso','clientes.created_at',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');

        $queryGen = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id' )
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
            'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','personal.empresa_id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS v_completo"),
            'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
            'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss', 'clientes.nombre_recomendado',
            'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
            'clientes.created_at','clientes.precio_rango','clientes.ingreso','clientes.created_at',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto');
        
        if( Auth::user()->rol_id == 2){
            if($buscarC != ''){
                if ($buscar==''){
                    $personas = $queryVendedor
                    ->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '=', $buscarC);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryVendedor  
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
    
                    else{
                        $personas = $queryVendedor
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                }
            }
            ////
            else{
                if ($buscar==''){
                    $personas = $queryVendedor
                    ->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '!=', 7);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryVendedor
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '!=', 7)
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('vendedor_id','=',Auth::user()->id)
                        ->where('clientes.clasificacion', '!=', 7);
                    }
    
                    else{
                        $personas = $queryVendedor
                        ->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                }

            }
            
        }
        else{
            if($buscarC!=''){
                if ($buscar==''){
                    $personas = $queryGen
                    //->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '=', $buscarC);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC)
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                    elseif($criterio == 'clientes.vendedor_id'){
                        if($buscar2 == ""){
                            $personas = $queryGen  
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, '=',$buscar)
                            ->where('clientes.clasificacion', '=', $buscarC);
                        }else{
                            $personas = $queryGen
                            //->where('vendedor_id','=',Auth::user()->id)     
                            ->where($criterio, '=',$buscar)
                            ->where('clientes.clasificacion', '=', $buscarC)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar2 . '%');
                        }
                        
                    }
                    elseif($criterio == 'clientes.created_at'){
                        if($buscar3 != ''){
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('fraccionamientos.id','=',$buscar3);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('clientes.publicidad_id', '=', $publicidad)
                                    ->where('fraccionamientos.id','=',$buscar3);

                            }
                            
                        }
                        else{
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '=', $buscarC)
                                    ->where('clientes.publicidad_id', '=', $publicidad);

                            }
                        }
                        
                    }
    
                    else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '=', $buscarC);
                    }
                }

            }
            else{
                if ($buscar==''){
                    $personas = $queryGen
                    //->where('vendedor_id','=',Auth::user()->id)
                    ->where('clientes.clasificacion', '!=', 7);
                }
                else{
    
                    if($criterio == 'personal.nombre'){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7)
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->orWhere(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                    elseif($criterio == 'clientes.vendedor_id'){
                        if($buscar2 == ""){
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, '=',$buscar)
                        ->where('clientes.clasificacion', '!=', 7);
                        }else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, '=',$buscar)
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar2 . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                        }
                    }
                    elseif($criterio == 'clientes.created_at'){
                        if($buscar3 != ''){
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('fraccionamientos.id','=',$buscar3)
                                    ->where('clientes.clasificacion', '!=', 7);
                            }
                            else{
                                $personas = $queryGen  
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('fraccionamientos.id','=',$buscar3)
                                    ->where('clientes.clasificacion', '!=', 7)
                                    ->where('clientes.publicidad_id', '=', $publicidad);
                            }
                        }
                        else{
                            if($publicidad == ''){
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '!=', 7);
                            }
                            else{
                                $personas = $queryGen
                                    //->where('vendedor_id','=',Auth::user()->id)     
                                    ->whereBetween($criterio, [$buscar, $buscar2])
                                    ->where('clientes.clasificacion', '!=', 7)
                                    ->where('clientes.publicidad_id', '=', $publicidad);
                            }
                        }  
                    }
    
                    else{
                        $personas = $queryGen
                        //->where('vendedor_id','=',Auth::user()->id)     
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('clientes.clasificacion', '!=', 7);
                    }
                }
            }
        }

        $personas = $personas->orderBy('personal.nombre', 'asc')
                        ->orderBy('personal.apellidos', 'asc')
                        ->get();

        if(sizeof($personas)){
            foreach($personas as $index => $persona ){
                $observacion = Cliente_observacion::where('cliente_id','=',$persona->id)->orderBy('created_at','desc')->get();

                if(sizeof($observacion))
                    $persona->observacion = $observacion[0]->usuario.': '.$observacion[0]->comentario;
                else{
                    $persona->observacion = '';
                }
            }
        }
         
 
        return Excel::create('resumen_cliente', function($excel) use ($personas){
            $excel->sheet('clientes', function($sheet) use ($personas){
                
                $sheet->row(1, [
                    'Nombre', 'Celular','Telefono' ,'Email', 'RFC', 'IMSS', 'CURP',
                    'Proyecto de interes','Clasificación','Vendedor','Medio Publicitario','Ultimo Comentario',
                    'Fecha de alta'
                ]);


                $sheet->cells('A1:M1', function ($cells) {
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

                foreach($personas as $index => $persona) {
                    $cont++;
                    switch($persona->clasificacion){
                        case 1:{
                            $clasificacion = 'No viable';
                                break;
                        }
                        

                        case 2:{
                            $clasificacion = 'Tipo A';
                            break;
                        }
                        

                        case 3:
                        {
                            $clasificacion = 'Tipo B';
                                break;
                        }

                        case 4:
                        {
                            $clasificacion ='Tipo C';
                            break;
                        }

                        case 5:
                        {
                            $clasificacion = 'Ventas';
                            break;
                        }

                        case 6:
                        {
                            $clasificacion ='Cancelado';
                            break;
                        }

                        case 7:
                        {
                            $clasificacion ='Coacreditado';
                            break;
                        }

                    }

                    $sheet->row($index+2, [
                        $persona->n_completo, 
                        $persona->celular, 
                        $persona->telefono, 
                        $persona->email, 
                        mb_strtoupper($persona->rfc), 
                        $persona->nss,
                        mb_strtoupper($persona->curp),
                        $persona->proyecto,
                        $clasificacion,
                        $persona->v_completo,
                        $persona->publicidad,
                        $persona->observacion,
                        $persona->created_at
                    ]);	
                }
                $num='A1:M' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xlsx');
    }

    public function getBirthdayPeople(Request $request){

        $now = Carbon::now();
        if( Auth::user()->rol_id == 8){
            $people = $this->getBirthdayPeopleGestores();
        }
        else{
            $people = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id' )
            ->select('personal.nombre', 'personal.id', 'personal.apellidos', 'personal.celular','personal.email',
                        'personal.f_nacimiento','clientes.clasificacion','v.nombre as vendedor','v.apellidos as vendedor_ap')
            ->whereMonth('personal.f_nacimiento',$now->month)
            ->whereDay('personal.f_nacimiento',$now->day);
            if(Auth::user()->rol_id != 4 && Auth::user()->rol_id != 6)
                $people = $people->where('clientes.vendedor_id','=',Auth::user()->id);
            $people = $people->orderBy('personal.nombre','asc')
            ->get();
        }
        

        return ['people'=>$people];
    }

    public function getBirthdayPeopleGestores(){

        $now = Carbon::now();
        $people = Cliente::join('personal','clientes.id','=','personal.id')
                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                    ->join('personal as v', 'vendedores.id', '=', 'v.id' )
                    ->join('creditos', 'clientes.id', '=', 'creditos.prospecto_id')
                    ->join('contratos', 'creditos.id', '=', 'contratos.id')
                    ->join('expedientes', 'contratos.id', '=', 'expedientes.id')
                    ->select('personal.nombre', 'personal.id', 'personal.apellidos', 'personal.celular','personal.email',
                                'personal.f_nacimiento','clientes.clasificacion')
                    ->whereMonth('personal.f_nacimiento',$now->month)
                    ->whereDay('personal.f_nacimiento',$now->day);
                    $people = $people->where('expedientes.gestor_id','=',Auth::user()->id);
                    $people = $people->orderBy('personal.nombre','asc')
                    ->distinct('personal.id')
                    ->get();

        return $people;
    }

    public function asignarClienteAleatorio($tipo){
        $band = 0;
        $current = Carbon::now();

        $calendario = Calendar::select('user_id')
                ->where('event_name','!=','Guardia')
                ->whereDate('end_date','>=',$current)
                ->whereDate('start_date','<=',$current);
                if($tipo != 0){
                    $calendario = $calendario
                    ->orWhere('event_name','=','Guardia')
                    ->where('proyecto_id','!=',$tipo)
                    ->whereDate('end_date','>=',$current)
                    ->whereDate('start_date','<=',$current);
                }
                $calendario = $calendario->get();

        $cal = [];
        $as = [];
        
        if(sizeof($calendario))
            foreach($calendario as $index => $calendar){
                array_push($cal,$calendar->user_id);
            }

        if($tipo == 0){
            $vendedores = User::join('personal','users.id','=','personal.id')
            ->join('vendedores','personal.id','vendedores.id')
            ->select('personal.id',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"))
            ->where('vendedores.tipo','=',0)
            ->where('users.condicion','=',1)
            ->whereNotIn('users.id',$cal)
            ->whereNotIn('users.id',[28020,28230,55,29051])
            ->where('users.usuario','!=','descartado')
            ->where('users.usuario','!=','oficina')
            ->orderBy('vendedores.cont_leads','asc')
            ->orderBy('vendedor','asc')->get();
        }
            
        else{
            $asesProy = Asign_proyecto::select('asesor_id')->where('proyecto_id','=',$tipo)->get();
            
            if(sizeof($asesProy)){

                foreach($asesProy as $index => $a){
                    array_push($as,$a->asesor_id);
                }


                $vendedores = User::join('personal','users.id','=','personal.id')
                    ->join('vendedores','personal.id','vendedores.id')
                    ->select('personal.id',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"))
                    ->where('vendedores.tipo','=',0)
                    ->where('users.condicion','=',1)
                    ->whereNotIn('users.id',$cal)
                    ->whereIn('users.id',$as)
                    ->where('users.usuario','!=','descartado')
                    ->where('users.usuario','!=','oficina')
                    ->orderBy('vendedores.cont_leads','asc')
                    ->orderBy('vendedor','asc')->get();

            }
            else{

                $vendedores = User::join('personal','users.id','=','personal.id')
                    ->join('vendedores','personal.id','vendedores.id')
                    ->select('personal.id',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"))
                    ->where('vendedores.tipo','=',0)
                    ->where('users.condicion','=',1)
                    ->whereNotIn('users.id',$cal)
                    ->whereNotIn('users.id',[28020,28230,29051,55])
                    ->where('users.usuario','!=','descartado')
                    ->where('users.usuario','!=','oficina')
                    ->orderBy('vendedores.cont_leads','asc')
                    ->orderBy('vendedor','asc')->get();

            }
                

            
        }
        $ids = [];

        foreach ($vendedores as $index => $vendedor) {

            $vendedor->total = 0;
            $vendedor->bd = 0;

            $clientes = Cliente::select('id')->where('vendedor_id','=',$vendedor->id)
            ->where('clasificacion','!=',5)
            ->where('clasificacion','!=',6)
            ->where('clasificacion','!=',7)
            ->where('clasificacion','!=',1)
            ->get();
            $vendedor->total = $clientes->count();
            $vendedor->bd = 0;

            foreach ($clientes as $index => $c) {
                $obs = Cliente_observacion::where('created_at','>=',Carbon::now()->subDays(8))
                ->where('cliente_id','=',$c->id)
                ->count();

                if($obs > 0){
                    $vendedor->bd ++;
                }

                $vendedor->dif = $vendedor->total - $vendedor->bd;
            }

            if($vendedor->dif < 10){
                array_push($ids,$vendedor->id);
                
            }


        }
        $cont = 0;

        if(sizeof($ids))
        
            //$value = random_int ( 0 , (sizeOf($ids)-1) );
            $value = 0;


        return [
            'vendedores' =>  $vendedores,
            'random' => $value,
            'vendedor_elegido' => $ids[$value]
        ];
       

    }

    public function reasignarProspecto(Request $request){
        $cliente = Cliente::findOrFail($request->id);
        $cliente->reasignar = 1;
        $cliente->save();

        $gerente = Fraccionamiento::select('gerente_id','nombre')->where('id','=',$cliente->proyecto_interes_id)->first();

        $observacion = new Cliente_observacion();
        $observacion->cliente_id = $cliente->id;
        $observacion->comentario = 'Se solicita la reasignacion del prospecto al fraccionamiento '.$gerente->nombre;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();

        $persona = Personal::select('nombre','apellidos')->where('id','=',$request->id)->first();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=',Auth::user()->id)->get();
        $fecha = Carbon::now();
        $msj = "Se solicita la reasignacion del prospecto ".$persona->nombre.' '.$persona->apellidos;
        $notificacion = [
            'notificacion' => [
                'usuario' => $imagenUsuario[0]->usuario,
                'foto' => $imagenUsuario[0]->foto_user,
                'fecha' => $fecha,
                'msj' => $msj,
                'titulo' => 'Reasignar Prospecto'
            ]
        ];
    
        User::findOrFail($gerente->gerente_id)->notify(new NotifyAdmin($notificacion));


    }

    public function clientesPorReasignar(Request $request){

        $nombre = $request->nombre;
        $desde = $request->desde;
        $hasta = $request->hasta;
        $proyecto = $request->proyecto;
        $clasificacion = $request->clasificacion;


        $clientes = Cliente::join('personal','clientes.id','=','personal.id')
                        ->join('personal as asesor','clientes.vendedor_id','=','asesor.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->select(
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS cliente"),
                            DB::raw("CONCAT(asesor.nombre,' ',asesor.apellidos) AS vendedor"),
                            'personal.id','personal.rfc','personal.celular','personal.email',
                            'fraccionamientos.nombre as proyecto','clientes.clasificacion','clientes.created_at',
                            'clientes.proyecto_interes_id'
                        )
                        ->where('clientes.reasignar','=',1);
                        if(Auth::user()->rol_id == 4 )
                            $clientes = $clientes->where('fraccionamientos.gerente_id','=',Auth::user()->id);

                        if($nombre != '')
                            $clientes = $clientes->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $nombre . '%');
                        
                        if($proyecto != '')
                            $clientes = $clientes->where( 'clientes.proyecto_interes_id','=',$proyecto);
                        
                        if($clasificacion != '')
                            $clientes = $clientes->where( 'clientes.clasificacion','=',$clasificacion);


                        $clientes = $clientes->orderBy('personal.nombre','asc')
                        ->orderBy('personal.apellidos','asc')
                        ->paginate();

        return [
            'pagination' => [
                'total'         => $clientes->total(),
                'current_page'  => $clientes->currentPage(),
                'per_page'      => $clientes->perPage(),
                'last_page'     => $clientes->lastPage(),
                'from'          => $clientes->firstItem(),
                'to'            => $clientes->lastItem(),
            ],
            'clientes' => $clientes];
    }

    public function getAsesores(Request $request){
        $asesores = User::join('personal','users.id','=','personal.id')
            ->join('vendedores','personal.id','=','vendedores.id')
            ->join('asign_proyectos','vendedores.id','=','asign_proyectos.asesor_id')
            ->select(
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS asesor"),
                'personal.id'
            )
            ->where('users.condicion','=',1)
            ->where('vendedores.tipo','=',0)
            ->where('asign_proyectos.proyecto_id','=',$request->proyecto);
            // if(Auth::user()->rol_id != 1)
            //     $asesores = $asesores->where('vendedores.supervisor_id','=',Auth::user()->id);
            $asesores = $asesores->orderBy('personal.nombre', 'asc')
                ->orderBy('personal.apellidos', 'asc')
                ->get();


            foreach ($asesores as $index => $vendedor) {

                $vendedor->total = 0;
                $vendedor->bd = 0;
                $vendedor->dif = 0;
    
                $clientes = Cliente::select('id')->where('vendedor_id','=',$vendedor->id)
                ->where('clasificacion','!=',5)
                ->where('clasificacion','!=',6)
                ->where('clasificacion','!=',7)
                ->where('clasificacion','!=',1)
                ->get();
                $vendedor->total = $clientes->count();
                $vendedor->bd = 0;
    
                foreach ($clientes as $index => $c) {
                    $obs = Cliente_observacion::where('created_at','>=',Carbon::now()->subDays(16))
                    ->where('cliente_id','=',$c->id)
                    ->count();
    
                    if($obs > 0){
                        $vendedor->bd ++;
                    }
    
                    $vendedor->dif = $vendedor->total - $vendedor->bd;
                }
    
    
            }   

        return ['asesores' => $asesores];
    }

    public function setVendedorAux(Request $request){
        if( $request->vendedor != ''){

            $cliente = Cliente::findOrFail($request->id);
            $cliente->vendedor_aux = $cliente->vendedor_id;
            $cliente->vendedor_id = $request->vendedor;
            $cliente->reasignar = 0;
            $cliente->save();

            $persona = Personal::select('nombre','apellidos')->where('id','=',$request->id)->first();
            $vendedor = Personal::select('nombre','apellidos')->where('id','=',$request->vendedor)->first();

            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $cliente->id;
            $observacion->comentario = 'Se aprueba la reasignacion del prospecto al vendedor: '.$vendedor->nombre.' '.$vendedor->apellidos;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=',Auth::user()->id)->get();
                    $fecha = Carbon::now();
                    $msj = "Se le ha asignado a ".$persona->nombre.' '.$persona->apellidos.' a su BD de Prospectos';
                    $notificacion = [
                        'notificacion' => [
                            'usuario' => $imagenUsuario[0]->usuario,
                            'foto' => $imagenUsuario[0]->foto_user,
                            'fecha' => $fecha,
                            'msj' => $msj,
                            'titulo' => 'Nuevo Prospecto'
                        ]
                    ];
                
                User::findOrFail($request->vendedor)->notify(new NotifyAdmin($notificacion));

        }
        
    }

    
    
}
