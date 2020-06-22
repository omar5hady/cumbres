<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Personal;
use App\Vendedor;
use Auth;
use Illuminate\Support\Facades\DB;
use File;
use Excel;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = User::join('personal','users.id','=','personal.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.f_nacimiento',
            'personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.homoclave','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol');
         
        if ($buscar==''){
            $personas = $query;
        }
        else{
            if($criterio == 'personal.nombre'){
                $personas = $query       
                ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
            }
            else{
                $personas = $query         
                ->where($criterio, 'like', '%'. $buscar . '%');
            }
        }

        $personas = $personas->orderBy('users.condicion', 'desc')
                            ->orderBy('personal.id', 'desc')
                            ->paginate(8);

        if(sizeOf($personas)){
            foreach($personas as $index => $persona){
                $vendedores = Vendedor::select('tipo','inmobiliaria','esquema','isr','retencion')->where('id','=',$persona->id)->get();
                if(sizeof($vendedores)){
                    $persona->tipo = $vendedores[0]->tipo;
                    $persona->inmobiliaria = $vendedores[0]->inmobiliaria;
                    $persona->esquema = $vendedores[0]->esquema;
                    $persona->isr = $vendedores[0]->isr;
                    $persona->retencion = $vendedores[0]->retencion;
                }
                else{
                    $persona->tipo = '';
                    $persona->inmobiliaria = '';
                    $persona->esquema = '';
                    $persona->isr = '';
                    $persona->retencion = '';
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

    public function indexAsesores(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = User::join('personal','users.id','=','personal.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->join('vendedores','personal.id','=','vendedores.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.f_nacimiento',
            'personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.homoclave','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol','vendedores.inmobiliaria','vendedores.tipo',
            'vendedores.esquema','vendedores.doc_ine','vendedores.doc_comprobante','vendedores.curriculum');
         
            if ($buscar==''){
                $personas = $query;
            }
            else{
                $personas = $query  
                ->where($criterio, '=',  $buscar );

            }

        $personas = $personas->orderBy('users.condicion', 'desc')
                            ->orderBy('personal.apellidos', 'asc')
                            ->orderBy('personal.nombre', 'asc')
                        ->paginate(8);
         
 
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $rol = $request->rol_id;
        $inicioSueldo = Carbon::now()->addMonth(3);
         
        try{
            DB::beginTransaction();
            $persona = new Personal();
            $persona->departamento_id = $request->departamento_id;
            $persona->nombre = $request->nombre;
            $persona->apellidos = $request->apellidos;
            $persona->f_nacimiento = $request->f_nacimiento;
            $persona->rfc = $request->rfc;
            $persona->direccion = $request->direccion;
            $persona->colonia = $request->colonia;
            $persona->cp = $request->cp;
            $persona->telefono = $request->telefono;
            $persona->ext = $request->ext;
            $persona->celular = $request->celular;
            $persona->email = $request->email;
            $persona->activo = $request->activo;
            $persona->empresa_id = $request->empresa_id;
            $persona->save();
 
            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->rol_id = $request->rol_id;          
 
            $user->id = $persona->id;

            switch($rol){
                case 1: // Administrador
                {
                    $user->administracion=1;
                    $user->desarrollo=1;
                    $user->precios=1;
                    $user->obra=1;
                    $user->ventas=1;
                    $user->acceso=1;
                    $user->reportes=1;
                    //Administracion
                    $user->departamentos=1;
                    $user->personas=1;
                    $user->empresas=1;
                    $user->medios_public=1;
                    $user->lugares_contacto=1;
                    $user->servicios=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    $user->asig_servicios=1;
                    $user->mis_asesores=0;
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->etapas=1;
                    $user->modelos=1;
                    $user->lotes=1;
                    $user->asign_modelos=1;
                    $user->licencias=1;
                    $user->acta_terminacion=1;
                    $user->p_etapa=0;
                   
                    //Precios
                    $user->agregar_sobreprecios=1;
                    $user->precios_etapas=1;
                    $user->sobreprecios=1;
                    $user->paquetes=1;
                    $user->promociones=1;
                    //Obra
                    $user->contratistas=1;
                    $user->ini_obra=1;
                    $user->aviso_obra=1;
                    $user->partidas=1;
                    $user->avance=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->mis_prospectos=0;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->hist_creditos=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Acceso
                    $user->usuarios=1;
                    $user->roles=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 2: //Asesor de ventas
                {
                    $user->ventas=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->mis_prospectos=1;
                    $user->simulacion_credito=1;
                    break;
                }
                case 3: //Gerente de proyectos
                {
                    $user->desarrollo=1;
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->modelos=1;
                    $user->lotes=1;
                    $user->licencias=1;
                    $user->acta_terminacion=1;
                    break;
                }
                case 4: //Gerente de ventas
                {
                    $user->administracion=1;
                    $user->ventas=1;
                    $user->reportes=1;
                    //Administracion
                    $user->empresas=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    $user->mis_asesores=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 5: // Gerente de obra
                {
                    $user->obra=1;
                    
                    //Obra
                    $user->contratistas=1;
                    $user->aviso_obra=1;
                    $user->partidas=1;
                    $user->avance=1;
                    break;
                }
                case 6: // Admin ventas
                {
                    $user->administracion=1;
                    $user->desarrollo=1;
                    $user->precios=1;
                    $user->obra=1;
                    $user->ventas=1;
                    $user->acceso=1;
                    $user->reportes=1;
                    //Administracion
                    $user->empresas=1;
                    $user->personas=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->etapas=1;
                    $user->modelos=1;
                    $user->asign_modelos=1;
                    //Precios
                    $user->agregar_sobreprecios=1;
                    $user->precios_etapas=1;
                    $user->sobreprecios=1;
                    $user->paquetes=1;
                    $user->promociones=1;
                    //Obra
                    $user->ini_obra=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->hist_creditos=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Acceso
                    $user->usuarios=1;
                    $user->roles=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 7: // Publicidad
                {
                    $user->administracion=1;
                    $user->desarrollo=1;
                    $user->reportes=1;
                    //Administracion
                    $user->medios_public=1;
                    $user->lugares_contacto=1;
                    $user->servicios=1;
                    $user->asig_servicios=1;
                    //Desarrollo
                    $user->modelos=1;
                    $user->p_etapa=1;
                   
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 8: // Gestor ventas
                {
                    $user->administracion=1;
                    $user->ventas=1;
                    //Administracion
                    $user->empresas=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->hist_creditos=1;
                    $user->contratos=1;
                    $user->docs=1;
                    break;
                }
                case 9: // Contabilidad
                {
                    $user->administracion=1;
                    $user->saldo=1;
                    //Administracion
                    $user->cuenta=1;
                    //Saldos
                    $user->edo_cuenta=1;
                    $user->depositos=1;
                    $user->gastos_admn=1;
                    break;
                }
                case 11: // Direccin
                {
                    $user->desarrollo=1;
                    $user->precios=1;
                    $user->obra=1;
                    $user->ventas=1;
                    $user->acceso=1;
                    $user->reportes=1;
                    
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->etapas=1;
                    $user->modelos=1;
                    $user->lotes=1;
                    $user->licencias=1;
                    $user->acta_terminacion=1;
                   
                    //Precios
                    $user->precios_etapas=1;
                    $user->sobreprecios=1;
                    $user->paquetes=1;
                    $user->promociones=1;
                    //Obra
                    $user->contratistas=1;
                    $user->aviso_obra=1;
                    $user->partidas=1;
                    $user->avance=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->mis_prospectos=0;
                    $user->simulacion_credito=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 12: // Postventa
                {
                    $user->postventa=1;
                    $user->entregas=1;
                    break;
                }
                
            }
 
            $user->save();
            
            if($user->rol_id == 2){
                $vendedor = new Vendedor();
                $vendedor->id = $persona->id;
                $vendedor->tipo = $request->tipo_vendedor;
                $vendedor->inmobiliaria = $request->inmobiliaria;
                if($vendedor->tipo == 1){
                    $vendedor->retencion = $request->retencion;
                    $vendedor->isr = $request->isr;
                }
                else{
                    $vendedor->retencion = 0;
                    $vendedor->isr = 0;
                }
                $vendedor->supervisor_id = Auth::user()->id;
                $vendedor->esquema = $request->esquema;
                $vendedor->fecha_sueldo = $inicioSueldo;
                $vendedor->save();
            }

            DB::commit();

            
 
        } catch (Exception $e){
            DB::rollBack();
        }         
         
    }
 
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $rol= $request->rol_id;
         
        try{
            DB::beginTransaction();
 
            //Buscar primero el proveedor a modificar
            $user = User::findOrFail($request->id);
 
            $Persona = Personal::findOrFail($request->id);
 
            $Persona->departamento_id = $request->departamento_id;
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
            $Persona->activo = $request->activo;
            $Persona->empresa_id = $request->empresa_id;
            $Persona->save();
            
            if($user->rol_id == 2){
                $vendedor = Vendedor::findOrFail($request->id);
                if($request->rol_id != 2){
                    $vendedor->delete();
                }
                else{
                    $vendedor->tipo = $request->tipo_vendedor;
                    if($vendedor->tipo == 1){
                        $vendedor->retencion = $request->retencion;
                        $vendedor->isr = $request->isr;
                    }
                    else{
                        $vendedor->retencion = 0;
                        $vendedor->isr = 0;
                    }
                    $vendedor->inmobiliaria = $request->inmobiliaria;
                    $vendedor->esquema = $request->esquema;
                    $vendedor->save();
                }
            }

            if($user->rol_id != 2){
                if($request->rol_id == 2){
                    $vendedor = new Vendedor();
                    $vendedor->id = $Persona->id;
                    $vendedor->tipo = $request->tipo_vendedor;
                    $vendedor->inmobiliaria = $request->inmobiliaria;
                    $vendedor->save();
                }
            }
             
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->rol_id = $request->rol_id;

            switch($rol){
                case 1: // Administrador
                {
                    $user->administracion=1;
                    $user->desarrollo=1;
                    $user->precios=1;
                    $user->obra=1;
                    $user->ventas=1;
                    $user->acceso=1;
                    $user->reportes=1;
                    //Administracion
                    $user->departamentos=1;
                    $user->personas=1;
                    $user->empresas=1;
                    $user->medios_public=1;
                    $user->lugares_contacto=1;
                    $user->servicios=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    $user->asig_servicios=1;
                    $user->mis_asesores=0;
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->etapas=1;
                    $user->modelos=1;
                    $user->lotes=1;
                    $user->asign_modelos=1;
                    $user->licencias=1;
                    $user->acta_terminacion=1;
                    $user->p_etapa=0;
                   
                    //Precios
                    $user->agregar_sobreprecios=1;
                    $user->precios_etapas=1;
                    $user->sobreprecios=1;
                    $user->paquetes=1;
                    $user->promociones=1;
                    //Obra
                    $user->contratistas=1;
                    $user->ini_obra=1;
                    $user->aviso_obra=1;
                    $user->partidas=1;
                    $user->avance=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->mis_prospectos=0;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->hist_creditos=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Acceso
                    $user->usuarios=1;
                    $user->roles=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 2: //Asesor de ventas
                {
                    $user->ventas=1;
                    
                    //Ventas
                    $user->lotes_disp=1;
                    $user->mis_prospectos=1;
                    $user->simulacion_credito=1;
                    break;
                }
                case 3: //Gerente de proyectos
                {
                    $user->desarrollo=1;
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->modelos=1;
                    $user->lotes=1;
                    $user->licencias=1;
                    $user->acta_terminacion=1;
                    break;
                }
                case 4: //Gerente de ventas
                {
                    $user->administracion=1;
                    $user->ventas=1;
                    $user->reportes=1;
                    //Administracion
                    $user->empresas=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    $user->mis_asesores=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 5: // Gerente de obra
                {
                    $user->obra=1;
                    
                    //Obra
                    $user->contratistas=1;
                    $user->aviso_obra=1;
                    $user->partidas=1;
                    $user->avance=1;
                    break;
                }
                case 6: // Admin ventas
                {
                    $user->administracion=1;
                    $user->desarrollo=1;
                    $user->precios=1;
                    $user->obra=1;
                    $user->ventas=1;
                    $user->acceso=1;
                    $user->reportes=1;
                    //Administracion
                    $user->empresas=1;
                    $user->personas=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->etapas=1;
                    $user->modelos=1;
                    $user->asign_modelos=1;
                    //Precios
                    $user->agregar_sobreprecios=1;
                    $user->precios_etapas=1;
                    $user->sobreprecios=1;
                    $user->paquetes=1;
                    $user->promociones=1;
                    //Obra
                    $user->ini_obra=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->hist_creditos=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Acceso
                    $user->usuarios=1;
                    $user->roles=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 7: // Publicidad
                {
                    $user->administracion=1;
                    $user->desarrollo=1;
                    $user->reportes=1;
                    //Administracion
                    $user->medios_public=1;
                    $user->lugares_contacto=1;
                    $user->servicios=1;
                    $user->asig_servicios=1;
                    //Desarrollo
                    $user->modelos=1;
                    $user->p_etapa=1;
                  
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 8: // Gestor ventas
                {
                    $user->administracion=1;
                    $user->ventas=1;
                    //Administracion
                    $user->empresas=1;
                    $user->inst_financiamiento=1;
                    $user->tipos_credito=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->simulacion_credito=1;
                    $user->hist_simulaciones=1;
                    $user->hist_creditos=1;
                    $user->contratos=1;
                    $user->docs=1;
                    break;
                }
                case 9: // Contabilidad
                {
                    $user->administracion=1;
                    $user->saldo=1;
                    //Administracion
                    $user->cuenta=1;
                    //Saldos
                    $user->edo_cuenta=1;
                    $user->depositos=1;
                    $user->gastos_admn=1;
                    break;
                }
                case 11: // Direccin
                {
                    $user->desarrollo=1;
                    $user->precios=1;
                    $user->obra=1;
                    $user->ventas=1;
                    $user->acceso=1;
                    $user->reportes=1;
                    
                    //Desarrollo
                    $user->fraccionamiento=1;
                    $user->etapas=1;
                    $user->modelos=1;
                    $user->lotes=1;
                    $user->licencias=1;
                    $user->acta_terminacion=1;
                   
                    //Precios
                    $user->precios_etapas=1;
                    $user->sobreprecios=1;
                    $user->paquetes=1;
                    $user->promociones=1;
                    //Obra
                    $user->contratistas=1;
                    $user->aviso_obra=1;
                    $user->partidas=1;
                    $user->avance=1;
                    //Ventas
                    $user->lotes_disp=1;
                    $user->mis_prospectos=0;
                    $user->simulacion_credito=1;
                    $user->contratos=1;
                    $user->docs=1;
                    //Reportes
                    $user->mejora=1;
                    break;
                }
                case 12: // Postventa
                {
                    $user->postventa=1;
                    $user->entregas=1;
                    break;
                }
                
            }
            $user->save();

 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }

    public function asignar(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $rol = $request->rol_id;
        $user = new User();
        $user->id = $request->id_persona;
        $user->usuario = $request->usuario;
        $user->password = bcrypt( $request->password);
        $user->condicion = '1';
        $user->rol_id = $request->rol_id; 
        
        if($user->rol_id == 2){
            $vendedor = new Vendedor();
            $vendedor->id = $request->id_persona;
            $vendedor->save();
        }     

        switch($rol){
            case 1: // Administrador
            {
                $user->administracion=1;
                $user->desarrollo=1;
                $user->precios=1;
                $user->obra=1;
                $user->ventas=1;
                $user->acceso=1;
                $user->reportes=1;
                //Administracion
                $user->departamentos=1;
                $user->personas=1;
                $user->empresas=1;
                $user->medios_public=1;
                $user->lugares_contacto=1;
                $user->servicios=1;
                $user->inst_financiamiento=1;
                $user->tipos_credito=1;
                $user->asig_servicios=1;
                $user->mis_asesores=0;
                //Desarrollo
                $user->fraccionamiento=1;
                $user->etapas=1;
                $user->modelos=1;
                $user->lotes=1;
                $user->asign_modelos=1;
                $user->licencias=1;
                $user->acta_terminacion=1;
                $user->p_etapa=0;
                
                //Precios
                $user->agregar_sobreprecios=1;
                $user->precios_etapas=1;
                $user->sobreprecios=1;
                $user->paquetes=1;
                $user->promociones=1;
                //Obra
                $user->contratistas=1;
                $user->ini_obra=1;
                $user->aviso_obra=1;
                $user->partidas=1;
                $user->avance=1;
                //Ventas
                $user->lotes_disp=1;
                $user->mis_prospectos=0;
                $user->simulacion_credito=1;
                $user->hist_simulaciones=1;
                $user->hist_creditos=1;
                $user->contratos=1;
                $user->docs=1;
                //Acceso
                $user->usuarios=1;
                $user->roles=1;
                //Reportes
                $user->mejora=1;
                break;
            }
            case 2: //Asesor de ventas
            {
                $user->administracion=1;
                $user->ventas=1;
                //Administracion
                $user->empresas=1;
                //Ventas
                $user->lotes_disp=1;
                $user->mis_prospectos=0;
                $user->simulacion_credito=1;
                break;
            }
            case 3: //Gerente de proyectos
            {
                $user->desarrollo=1;
                //Desarrollo
                $user->fraccionamiento=1;
                $user->modelos=1;
                $user->lotes=1;
                $user->licencias=1;
                $user->acta_terminacion=1;
                break;
            }
            case 4: //Gerente de ventas
            {
                $user->administracion=1;
                $user->ventas=1;
                $user->reportes=1;
                //Administracion
                $user->empresas=1;
                $user->inst_financiamiento=1;
                $user->tipos_credito=1;
                $user->mis_asesores=0;
                //Ventas
                $user->lotes_disp=1;
                $user->simulacion_credito=1;
                $user->hist_simulaciones=1;
                $user->contratos=1;
                $user->docs=1;
                //Reportes
                $user->mejora=1;
                break;
            }
            case 5: // Gerente de obra
            {
                $user->obra=1;
                
                //Obra
                $user->contratistas=1;
                $user->aviso_obra=1;
                $user->partidas=1;
                $user->avance=1;
                break;
            }
            case 6: // Admin ventas
            {
                $user->administracion=1;
                $user->desarrollo=1;
                $user->precios=1;
                $user->obra=1;
                $user->ventas=1;
                $user->acceso=1;
                $user->reportes=1;
                //Administracion
                $user->empresas=1;
                $user->personas=1;
                $user->inst_financiamiento=1;
                $user->tipos_credito=1;
                //Desarrollo
                $user->fraccionamiento=1;
                $user->etapas=1;
                $user->modelos=1;
                $user->asign_modelos=1;
                //Precios
                $user->agregar_sobreprecios=1;
                $user->precios_etapas=1;
                $user->sobreprecios=1;
                $user->paquetes=1;
                $user->promociones=1;
                //Obra
                $user->ini_obra=1;
                //Ventas
                $user->lotes_disp=1;
                $user->simulacion_credito=1;
                $user->hist_simulaciones=1;
                $user->hist_creditos=1;
                $user->contratos=1;
                $user->docs=1;
                //Acceso
                $user->usuarios=1;
                $user->roles=1;
                //Reportes
                $user->mejora=1;
                break;
            }
            case 7: // Publicidad
            {
                $user->administracion=1;
                $user->desarrollo=1;
                $user->reportes=1;
                //Administracion
                $user->medios_public=1;
                $user->lugares_contacto=1;
                $user->servicios=1;
                $user->asig_servicios=1;
                //Desarrollo
                $user->modelos=1;
                $user->p_etapa=0;
               
                //Reportes
                $user->mejora=1;
                break;
            }
            case 8: // Gestor ventas
            {
                $user->administracion=1;
                $user->ventas=1;
                //Administracion
                $user->empresas=1;
                $user->inst_financiamiento=1;
                $user->tipos_credito=1;
                //Ventas
                $user->lotes_disp=1;
                $user->simulacion_credito=1;
                $user->hist_simulaciones=1;
                $user->hist_creditos=1;
                $user->contratos=1;
                $user->docs=1;
                break;
            }
            case 9: // Contabilidad
            {
                $user->administracion=1;
                $user->saldo=1;
                //Administracion
                $user->cuenta=1;
                //Saldos
                $user->edo_cuenta=1;
                $user->depositos=1;
                $user->gastos_admn=1;
                break;
            }
            
        }

        $user->save();
    }
 
    public function desactivar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }
 
    public function activar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

    public function selectVendedores(Request $request){
        if (!$request->ajax()) return redirect('/');
        $personas = User::join('personal','users.id','=','personal.id')
            ->select('personal.id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
            ->where('users.rol_id','=','2')
            ->orderBy('personal.nombre', 'asc')
            ->orderBy('personal.apellidos', 'asc')
            ->get();

        return['vendedores' => $personas];
    }

    public function obtenerDatos(Request $request){
        if (!$request->ajax()) return redirect('/');
        $usuario = User::join('personal','users.id','=','personal.id')
            ->select('users.usuario','users.foto_user','users.id',
            'personal.celular','personal.email','personal.colonia','personal.direccion',
            'personal.cp','personal.nombre','personal.apellidos','personal.f_nacimiento','users.password as pass',
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
            ->where('users.id','=',$request->id)->get();

        return['usuario' => $usuario];
    }

    public function selectAsesores(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
            $personas = User::join('personal','users.id','=','personal.id')
            ->join('vendedores','personal.id','=','vendedores.id')
            ->select('personal.id','personal.nombre','personal.apellidos')
            //->where('vendedores.supervisor_id','=',Auth::user()->id)
            //->where('users.condicion','=',1)
            //->orWhere('vendedores.tipo','=',1)
            ->orderBy('personal.nombre', 'asc')
            ->orderBy('personal.apellidos', 'asc')
            ->get();
    
        return ['personas' => $personas];
    }

    public function selectAsesores2(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
            $personas = User::join('personal','users.id','=','personal.id')
            ->join('vendedores','personal.id','=','vendedores.id')
            ->select('personal.id','personal.nombre','personal.apellidos')
            ->where('vendedores.tipo','=',0)
            //->where('vendedores.supervisor_id','=',Auth::user()->id)
            //->where('users.condicion','=',1)
            //->orWhere('vendedores.tipo','=',1)
            ->orderBy('personal.nombre', 'asc')
            ->orderBy('personal.apellidos', 'asc')
            ->get();
    
        return ['personas' => $personas];
    }

    public function getPrivilegios(Request $request){
        //if (!$request->ajax()) return redirect('/');
        $privilegios=User::join('roles','users.rol_id','=','roles.id')
                        ->select( 'users.administracion','users.desarrollo','users.precios','users.obra','users.ventas',
                                'users.acceso','users.reportes','users.rol_id','users.saldo','users.gestoria',
                                'users.postventa','users.comisiones',
                                //Administracion
                                'users.departamentos','users.personas','users.empresas','users.medios_public','users.lugares_contacto','users.servicios',
                                'users.inst_financiamiento','users.tipos_credito','users.asig_servicios','users.mis_asesores','users.cuenta','users.notaria',
                                'users.proveedores',
                                //Desarrollo
                                'users.fraccionamiento','users.etapas','users.modelos','users.lotes','users.asign_modelos','users.licencias',
                                'users.acta_terminacion','users.p_etapa','users.descarga_actas', 'users.ruv', 'users.seg_ruv',
                                //Pago Interno
                                'users.seg_pago',
                                //Precios
                                'users.precios_etapas','users.precios_viviendas','users.sobreprecios','users.paquetes','users.promociones',
                                'users.agregar_sobreprecios',
                                //Obra
                                'users.contratistas','users.ini_obra','users.aviso_obra','users.partidas','users.avance',
                                //Ventas
                                'users.lotes_disp','users.mis_prospectos','users.simulacion_credito','users.hist_simulaciones',
                                'users.hist_creditos','users.contratos','users.docs','users.equipamientos',
                                //Saldo
                                'users.edo_cuenta','users.depositos','users.gastos_admn','users.cobro_credito',
                                'users.dev_exc','users.dev_cancel','users.facturas',
                                //Gestoria
                                'users.expediente','users.asig_gestor','users.seg_tramite','users.avaluos','users.bonos_rec',
                                //Postventa
                                'users.entregas', 'users.solic_detalles',
                                //Comisiones
                                'users.exp_comision','users.gen_comision','users.bono_com',
                                //Acceso
                                'users.usuarios','users.roles',
                                //Reportes
                                'users.mejora','users.rep_proy','users.rep_publi','users.inventario','rep_venta_canc',
                                'rep_asesores',
                                'rep_ini_term_ventas',
                                'rep_recursos_propios'
                        )->where('users.id','=',$request->id)->get();
            
            return['privilegios' => $privilegios];
                    
    }

    public function updatePrivilegios(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $user = User::findOrFail($request->id);
        $user->administracion = $request->administracion;
        $user->desarrollo = $request->desarrollo;
        $user->precios = $request->precios;
        $user->obra = $request->obra;
        $user->ventas = $request->ventas;
        $user->acceso = $request->acceso;
        $user->reportes = $request->reportes;
        $user->saldo = $request->saldo;
        $user->gestoria = $request->gestoria;
        $user->postventa = $request->postventa;
        $user->comisiones = $request->comisiones;
        //Administracion
        $user->departamentos = $request->departamentos;
        $user->personas = $request->personas;
        $user->empresas = $request->empresas;
        $user->medios_public = $request->medios_public;
        $user->lugares_contacto = $request->lugares_contacto;
        $user->servicios = $request->servicios;
        $user->inst_financiamiento = $request->inst_financiamiento;
        $user->tipos_credito = $request->tipos_credito;
        $user->asig_servicios = $request->asig_servicios;
        $user->mis_asesores = $request->mis_asesores;
        $user->cuenta = $request->cuenta;
        $user->proveedores = $request->proveedores;
        //Desarrollo
        $user->fraccionamiento = $request->fraccionamiento;
        $user->etapas = $request->etapas;
        $user->modelos = $request->modelos;
        $user->lotes = $request->lotes;
        $user->asign_modelos = $request->asign_modelos;
        $user->licencias = $request->licencias;
        $user->acta_terminacion = $request->acta_terminacion;
        $user->p_etapa = $request->p_etapa;
        $user->descarga_actas = $request->descarga_actas;
        $user->ruv = $request->ruv;
        $user->seg_ruv = $request->seg_ruv;

        //Pago interno
        $user->seg_pago = $request->seg_pago;
      
        //Precios
        $user->agregar_sobreprecios=$request->agregar_sobreprecios;
        $user->precios_etapas = $request->precios_etapas;
        $user->precios_viviendas = $request->precios_viviendas;
        $user->sobreprecios = $request->sobreprecios;
        $user->paquetes = $request->paquetes;
        $user->promociones = $request->promociones;
        //Obra
        $user->contratistas = $request->contratistas;
        $user->ini_obra = $request->ini_obra;
        $user->aviso_obra = $request->aviso_obra;
        $user->partidas = $request->partidas;
        $user->avance = $request->avance;
        //Ventas
        $user->lotes_disp = $request->lotes_disp;
        $user->mis_prospectos = $request->mis_prospectos;
        $user->simulacion_credito = $request->simulacion_credito;
        $user->hist_simulaciones = $request->hist_simulaciones;
        $user->hist_creditos = $request->hist_creditos;
        $user->contratos = $request->contratos;
        $user->docs = $request->docs;
        $user->equipamientos = $request->equipamientos;
        //Saldos
        $user->edo_cuenta = $request->edo_cuenta;
        $user->depositos = $request->depositos;
        $user->gastos_admn = $request->gastos_admn;
        $user->cobro_credito = $request->cobro_credito;
        $user->dev_exc = $request->dev_exc;
        $user->dev_cancel = $request->dev_cancel;
        $user->facturas = $request->facturas;
        //Gestoria
        $user->expediente = $request->expediente;
        $user->asig_gestor = $request->asig_gestor;
        $user->seg_tramite = $request->seg_tramite;
        $user->avaluos = $request->avaluos;
        $user->bonos_rec = $request->bonos_rec;
        //Postventa
        $user->entregas = $request->entregas;
        $user->solic_detalles = $request->solic_detalles;
        //Comisiones
        $user->exp_comision = $request->exp_comision;
        $user->gen_comision = $request->gen_comision;
        $user->bono_com = $request->bono_com;
        //Acceso
        $user->usuarios = $request->usuarios;
        $user->roles = $request->roles;
        //Reportes
        $user->mejora = $request->mejora;
        $user->rep_publi = $request->rep_publi;
        $user->rep_proy = $request->rep_proy;
        $user->inventario = $request->inventario;
        $user->rep_venta_canc = $request->rep_venta_canc;
        $user->rep_asesores = $request->rep_asesores;
        $user->rep_ini_term_ventas = $request->rep_ini_term_ventas;
        $user->rep_recursos_propios = $request->rep_recursos_propios;
        $user->save();

    }

    public function updateProfile(Request $request, $id){
        if(!$request->ajax())return redirect('/');

        $imgAnterior = User::select('foto_user','id')
                            ->where('foto_user','!=','default-image.gif')
                            ->where('id','=',$id)->get();


                if($imgAnterior->isEmpty()==1){

                            $fileName = uniqid().'.'.$request->foto_user->getClientOriginalExtension();
                            $moved =  $request->foto_user->move(public_path('/img/avatars'), $fileName);

                                                                
                            if($moved){
                                if(!$request->ajax())return redirect('/');
                                $user = User::findOrFail($request->id);
                                $user->foto_user = $fileName;
                                $user->id = $id;
                                $user->save(); //Insert
                            }
                            return back();
                        
                            
                            }else{
                                if ($request->foto_user != $imgAnterior[0]->foto_user){
                            
                                $pathAnterior = public_path().'/img/avatars/'.$imgAnterior[0]->foto_user;
                                File::delete($pathAnterior);  }   
                
                                $fileName = uniqid().'.'.$request->foto_user->getClientOriginalExtension();
                                $moved =  $request->foto_user->move(public_path('/img/avatars'), $fileName);
                
                                if($moved){
                                    if(!$request->ajax())return redirect('/');
                                    $user = User::findOrFail($request->id);
                                    $user->foto_user = $fileName;
                                    $user->id = $id;
                                    
                                    $user->save(); //Insert
                                }
                                return back();                  
                            
                        }

                                    
                    
                            
    }

    public function updatePassword(Request $request){

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
        if($request->password != ''){
            $user = User::findOrFail($request->id);
            $user->password = bcrypt($request->password);
            $user->save();
        }
        $persona = Personal::findOrFail($request->id);
        $persona->email = $request->email;
        $persona->celular = $request->celular;
        $persona->colonia = $request->colonia;
        $persona->direccion = $request->direccion;
        $persona->apellidos = $request->apellidos;
        $persona->nombre = $request->nombre;
        $persona->cp = $request->cp;
        $persona->f_nacimiento = $request->f_nacimiento;
        
        $persona->save();
    }
           
    public function select_users_gerentes (){
        $gerentes = User::join('personal','users.id','=','personal.id')
                        ->select('users.usuario','personal.id','personal.nombre','personal.apellidos')
                        ->where('users.rol_id','=','4')
                        ->get();

                return['gerentes' => $gerentes];
    }

    public function asignarGerentes (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $asignar = Vendedor::findOrFail($request->id);
        $asignar->supervisor_id = $request->supervisor_id;
        $asignar->save();
    }

    public function excelAsesores (Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = User::join('personal','users.id','=','personal.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->join('vendedores','personal.id','=','vendedores.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.f_nacimiento',
            'personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.homoclave','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol','vendedores.inmobiliaria','vendedores.tipo');
         
            if ($buscar==''){
                $personas = $query;
            }
            else{
                $personas = $query 
                ->where($criterio, '=',  $buscar );
            }

            $personas = $personas->orderBy('users.condicion', 'desc')
                        ->orderBy('personal.apellidos', 'asc')
                        ->orderBy('personal.nombre', 'asc')
                    ->get(); 

            return Excel::create('Asesores', function($excel) use ($personas){
                $excel->sheet('asesores', function($sheet) use ($personas){
                    
                    $sheet->row(1, [
                        'Nombre', 'Usuario','Rol' ,'Tipo', 'Inmobiliaria', 'Status'
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
    
                    
                    $cont=1;
    
                    foreach($personas as $index => $persona) {
                        $cont++;
                        
                        if($persona->tipo==0){
                            $tipo = 'Interno';
                        }else{
                            $tipo = 'Externo';
                        }

                        if($persona->condicion == 1){
                            $clasificacion = 'Activo';
                        }else{
                            $clasificacion = 'Inactivo';
                        }
    
                        $sheet->row($index+2, [
                            $persona->nombre.' '.$persona->apellidos, 
                            $persona->usuario, 
                            $persona->rol, 
                            $tipo,
                            $persona->inmobiliaria,
                            $clasificacion,
                        ]);	
                    }
                    $num='A1:F' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
            
            )->download('xlsx');

    }
}