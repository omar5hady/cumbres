<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Personal;
use App\Credito;
use Auth;
use App\Cliente_observacion;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $personas = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.homoclave',
            'personal.f_nacimiento','personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','personal.empresa_id', 
             DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            'clientes.sexo','clientes.tipo_casa','clientes.email_institucional','clientes.lugar_contacto',
            'clientes.proyecto_interes_id','clientes.publicidad_id','clientes.edo_civil','clientes.nss',
            'clientes.curp','clientes.vendedor_id','clientes.empresa','clientes.coacreditado','clientes.clasificacion',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
             DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto')
            ->where('vendedor_id','=',Auth::user()->id)
            ->orderBy('personal.apellidos', 'desc')
            ->orderBy('personal.nombre', 'desc')
            ->paginate(6);
        }
        else{

            if($criterio == 'personal.nombre'){
                 $personas = Cliente::join('personal','clientes.id','=','personal.id')
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
                 DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
                'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
                'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
                'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
                'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
                 DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
                'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto')   
                ->where('vendedor_id','=',Auth::user()->id)     
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                ->orderBy('personal.apellidos', 'desc')
                ->orderBy('personal.nombre', 'desc')
               ->paginate(6);
            }

            else{
            $personas = Cliente::join('personal','clientes.id','=','personal.id')
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
             DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
             DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto')   
            ->where('vendedor_id','=',Auth::user()->id)     
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personal.apellidos', 'desc')
            ->orderBy('personal.nombre', 'desc')
           ->paginate(3);
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
            'personas' => $personas
        ];
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
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
            $cliente->proyecto_interes_id = $request->proyecto_interes_id;
            $cliente->publicidad_id = $request->publicidad_id;
            $cliente->edo_civil = $request->edo_civil;
            $cliente->nss = $request->nss;
            $cliente->curp = $request->curp;
            if($request->vendedor_id == NULL)
                $cliente->vendedor_id = Auth::user()->id;
            else{
                $cliente->vendedor_id = $request->vendedor_id;
            }
            $cliente->empresa = $request->empresa;
            $cliente->coacreditado = $request->coacreditado;
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
            $cliente->save();

            $observacion = new Cliente_observacion();
            $observacion->cliente_id = $persona->id;
            $observacion->comentario = $request->observacion;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }         
         
    }

    public function storeCoacreditado(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
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
            $cliente->vendedor_id = Auth::user()->id;
            $cliente->empresa = $request->empresa;
            $cliente->coacreditado = $request->coacreditado;
            $cliente->clasificacion = $request->clasificacion;
            
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
        if (!$request->ajax()) return redirect('/');
         
        try{
            DB::beginTransaction();
 
            
            $cliente = Cliente::findOrFail($request->id);
 
            $Persona = Personal::findOrFail($request->id);
 
            $Persona->departamento_id = 8;
            $Persona->nombre = $request->nombre;
            $Persona->apellidos = $request->apellidos;
            $Persona->f_nacimiento = $request->f_nacimiento;
            $Persona->rfc = $request->rfc;
            $Persona->direccion = $request->direccion;
            $Persona->colonia = $request->colonia;
            $Persona->cp = $request->cp;
            $Persona->telefono = $request->telefono;
            $Persona->ext = $request->ext;
            $Persona->celular = $request->celular;
            $Persona->email = $request->email;
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
            if($request->vendedor_id == NULL)
            $cliente->vendedor_id = Auth::user()->id;
            else{
            $cliente->vendedor_id = $request->vendedor_id;
        }
            $cliente->empresa = $request->empresa;

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
            $cliente->direccion_coa = $request->direccion_coa;
            $cliente->colonia_coa = $request->colonia_coa;
            $cliente->cp_coa = $request->cp_coa;
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
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }
 
    public function selectCoacreditadoVue(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;

        $coacreditados = Cliente::join('personal','clientes.id','=','personal.id')
        ->select('personal.nombre','personal.apellidos','personal.id','personal.rfc','personal.homoclave','personal.f_nacimiento',
            'personal.telefono','personal.celular','personal.email','clientes.sexo',
            'clientes.email_institucional','clientes.edo_civil','clientes.nss','clientes.curp','clientes.tipo_casa',
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
        ->where('vendedor_id','=',Auth::user()->id)
        ->where('nombre','like','%'.$filtro.'%')
        ->orWhere('apellidos','like','%'.$filtro.'%')
        ->get();
        return['coacreditados' => $coacreditados];
    }

    public function listarObservacion(Request $request){

        $buscar = $request->buscar;
        $observacion = Cliente_observacion::select('comentario','usuario','created_at')
                    ->where('cliente_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

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
        if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $cliente = Cliente::findOrFail($request->id);

        $Personal->activo = '0';
        $Personal->save();

        $cliente->clasificacion = 6;
        $cliente->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax())return redirect('/');
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
            ->where('clientes.proyecto_interes_id','=',$request->fraccionamiento_id)
            ->get();

        return['clientes' => $personas];
    }

    public function clientesSimulacion(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $personas = Cliente::join('personal','clientes.id','=','personal.id')
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
            'clientes.nacionalidad','clientes.puesto', 'clientes.estado','clientes.ciudad',
            
            'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
            'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
            'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
            'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
            'clientes.nacionalidad_coa','clientes.estado_coa','clientes.ciudad_coa',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
            'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto') 
            ->where('vendedor_id','=',Auth::user()->id)
            ->where('clientes.clasificacion','=','2')
            ->orderBy('personal.apellidos', 'desc')
            ->orderBy('personal.nombre', 'desc')
            ->paginate(6);
        }
        else{

            if($criterio == 'personal.nombre'){
                $personas = Cliente::join('personal','clientes.id','=','personal.id')
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
                'clientes.nacionalidad','clientes.puesto', 'clientes.estado','clientes.ciudad',
                
                'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
                'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
                'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
                'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
                'clientes.nacionalidad_coa','clientes.estado_coa','clientes.ciudad_coa',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
                'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto') 
                ->where('vendedor_id','=',Auth::user()->id)     
                ->where('clientes.clasificacion','=','2')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                ->orderBy('personal.apellidos', 'desc')
                ->orderBy('personal.nombre', 'desc')
               ->paginate(6);
            }

            else{
                $personas = Cliente::join('personal','clientes.id','=','personal.id')
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
                'clientes.nacionalidad','clientes.puesto', 'clientes.estado','clientes.ciudad',
                
                'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
                'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
                'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
                'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
                'clientes.nacionalidad_coa','clientes.estado_coa','clientes.ciudad_coa',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
                'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto') 
            ->where('vendedor_id','=',Auth::user()->id)     
            ->where('clientes.clasificacion','=','2')
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personal.apellidos', 'desc')
            ->orderBy('personal.nombre', 'desc')
            ->paginate(3);
            }

            foreach($personas as $index => $persona) {                
                $institucion=[];
                $institucion=Credito::select('id')
                    ->where('prospecto_id','=',$persona->id)->get();
                if(sizeof($prospecto) > 0){
                    $persona->simulacion=$institucion[0]->id;
                }
                else{
                    $persona->simulacion=0;
                }
                
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
            'personas' => $personas
        ];
    }

    public function obtenerDatos(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $personas = Cliente::join('personal','clientes.id','=','personal.id')
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
        
        'clientes.sexo_coa', 'clientes.tipo_casa_coa','clientes.email_institucional_coa','clientes.empresa_coa',
        'clientes.edo_civil_coa','clientes.nss_coa','clientes.curp_coa','clientes.nombre_coa','clientes.apellidos_coa',
        'clientes.f_nacimiento_coa', 'clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa','clientes.colonia_coa',
        'clientes.cp_coa','clientes.telefono_coa','clientes.ext_coa','clientes.celular_coa','clientes.email_coa','clientes.parentesco_coa',
        'clientes.nombre_coa','clientes.apellidos_coa',
        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS n_completo_coa"),
        'medios_publicitarios.nombre as publicidad','fraccionamientos.nombre as proyecto')
        ->where('personal.id','=',$id)
        ->orderBy('personal.apellidos', 'desc')
        ->orderBy('personal.nombre', 'desc')->take(1)->get();

        return ['personas' => $personas];
    }
    
}
