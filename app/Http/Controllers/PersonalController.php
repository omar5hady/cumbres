<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Personal;
use DB;
use App\Cliente;
use Auth;
use Carbon\Carbon;
use App\Contrato;
use Excel;

class PersonalController extends Controller
{
    
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
       if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = Personal::join('departamento','personal.departamento_id','=','departamento.id_departamento')
                ->join('empresas','personal.empresa_id','=','empresas.id')
                ->select('personal.nombre','personal.apellidos',
                    'personal.f_nacimiento','personal.rfc','personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                    'personal.telefono','personal.ext','personal.celular','personal.email','personal.activo',
                    'personal.id','personal.departamento_id','departamento.departamento as departamento',
                    'personal.empresa_id','empresas.nombre as empresa',
                    'departamento.id_departamento');
        
        if($buscar==''){
            $Personales = $query
                ->where('personal.nombre','!=','Sin Asignar');
        }
        else{
            if($criterio == 'id_departamento'){
                $Personales = $query
                    ->where($criterio, '=', $buscar )
                    ->where('personal.nombre','!=','Sin Asignar');
            }
            elseif($criterio == 'personal.nombre'){
                $Personales = $query
                    ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                    ->where('personal.nombre','!=','Sin Asignar');
            }
            else{
                $Personales = $query
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('personal.nombre','!=','Sin Asignar');
            }
        }

        $Personales = $Personales->orderBy('id','desc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $Personales->total(),
                'current_page'  => $Personales->currentPage(),
                'per_page'      => $Personales->perPage(),
                'last_page'     => $Personales->lastPage(),
                'from'          => $Personales->firstItem(),
                'to'            => $Personales->lastItem(),
            ],
            'Personales' => $Personales
        ];
    }

   
    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $Personal = new Personal();
        $Personal->departamento_id = $request->departamento_id;
        $Personal->nombre = $request->nombre;
        $Personal->apellidos = $request->apellidos;
        $Personal->f_nacimiento = $request->f_nacimiento;
        $Personal->rfc = $request->rfc;
        $Personal->homoclave = $request->homoclave;
        $Personal->direccion = $request->direccion;
        $Personal->colonia = $request->colonia;
        $Personal->cp = $request->cp;
        $Personal->telefono = $request->telefono;
        $Personal->ext = $request->ext;
        $Personal->celular = $request->celular;
        $Personal->email = $request->email;
        $Personal->activo = $request->activo;
        $Personal->empresa_id = $request->empresa_id;
        $Personal->save();
    }

 
    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->departamento_id = $request->departamento_id;
        $Personal->nombre = $request->nombre;
        $Personal->apellidos = $request->apellidos;
        $Personal->f_nacimiento = $request->f_nacimiento;
        $Personal->rfc = $request->rfc;
        $Personal->homoclave = $request->homoclave;
        $Personal->direccion = $request->direccion;
        $Personal->colonia = $request->colonia;
        $Personal->cp = $request->cp;
        $Personal->telefono = $request->telefono;
        $Personal->ext = $request->ext;
        $Personal->celular = $request->celular;
        $Personal->email = $request->email;
        $Personal->activo = $request->activo;
        $Personal->empresa_id = $request->empresa_id;
        $Personal->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->activo = '0';
        $Personal->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->activo = '1';
        $Personal->save();
    }

    
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $Personal = Personal::findOrFail($request->id);
        $Personal->delete();
    }

    public function selectNombre(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $departamento = $request->departamento_id;

        $Personal = Personal::select(DB::raw("CONCAT(nombre,' ',apellidos) AS name"),'id')
                     ->where('departamento_id', '=', $departamento )
                     ->where('activo','=','1')
                     ->orderBy('name')
                     ->get();
                     return['personal' => $Personal];
    }


    public function select_Pers_sinUser(Request $request){
        if(!$request->ajax())return redirect('/');
        $personas = Personal::leftJoin('users as users','personal.id','=','users.id')
                             ->select('nombre','users.id as UserId','personal.id as personalId')
                             ->where('users.id','=',NULL)
                             ->where('personal.departamento_id','!=','8')
                             ->where('nombre','!=','Sin Asignar')->get();

                             return ['personas' => $personas];
    }

    public function selectRFC(Request $request){
        $rfc = $request->rfc;
         $rfc1 = Personal::select('rfc')
                          ->where('rfc','=',$rfc)->count();
            if($rfc1==1){
                $personaid = Personal::select('rfc','id')
                            ->where('rfc','=',$rfc)->get();
                $vendedor = Cliente::join('vendedores','clientes.vendedor_id','=','vendedores.id')
                ->join('personal','vendedores.id','=','personal.id')
                ->select('personal.nombre','personal.apellidos')
                ->where('clientes.id','=',$personaid[0]->id)->get();
                return ['rfc1'=>$rfc1,'personaid' => $personaid, 'vendedor'=> $vendedor];
                          }else{
                            return ['rfc1'=>$rfc1];
                          }
                          
       
            
    }

    public function select_gestores (){
        $gestores = Personal::join('users','personal.id','=','users.id')
                            ->select('personal.id',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_gestor"))
                            ->where('users.rol_id','=',8)
                            ->where('users.condicion','=',1)
                            ->get();
        return ['gestores' => $gestores];
    }

    public function indexClientes(Request $request){
        $desde = $request->desde;
        $clasificacion = $request->clasificacion;
        $hasta = $request->hasta;
        $proyecto = $request->proyecto;
        $publicidad = $request->publicidad;

        $query = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select(
                'personal.id',
                'personal.f_nacimiento',
                'personal.direccion',
                'personal.telefono',
                'personal.colonia',
                'personal.cp',
                'personal.celular',
                'personal.email',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
                'clientes.email_institucional',
                'clientes.proyecto_interes_id',
                'clientes.publicidad_id',
                'clientes.vendedor_id','clientes.empresa','clientes.clasificacion',
                'clientes.created_at',
                'medios_publicitarios.nombre as publicidad',
                'fraccionamientos.nombre as proyecto');

        if($clasificacion != 5){
            if($desde == '' && $hasta == '' && $proyecto == '' && $publicidad == ''){
                $cliente = $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde == '' && $hasta == '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
        }
        else{
            if($desde == '' && $hasta == '' && $proyecto == '' && $publicidad == ''){
                $cliente = $query
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    
                    ->distinct()
                    // ->orderBy('personal.nombre', 'asc')
                    // ->orderBy('personal.apellidos', 'asc')
                    ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad == ''){
                $cliente = $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                    // ->orderBy('personal.nombre', 'asc')
                    // ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('expedientes.fecha_firma_esc','=',NULL)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde == '' && $hasta == '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                    // ->orderBy('personal.nombre', 'asc')
                    // ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->paginate(15);
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->paginate(15);
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->paginate(15);
            }
       }
           
        return [
            'pagination' => [
                'total'         => $cliente->total(),
                'current_page'  => $cliente->currentPage(),
                'per_page'      => $cliente->perPage(),
                'last_page'     => $cliente->lastPage(),
                'from'          => $cliente->firstItem(),
                'to'            => $cliente->lastItem(),
            ],
            'clientes' => $cliente];
    }

    public function excelClientes(Request $request){
        $desde = $request->desde;
        $clasificacion = $request->clasificacion;
        $hasta = $request->hasta;
        $proyecto = $request->proyecto;
        $publicidad = $request->publicidad;

        $query = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select(
                'personal.id',
                'personal.f_nacimiento',
                'personal.direccion',
                'personal.telefono',
                'personal.colonia',
                'personal.cp',
                'personal.celular',
                'personal.email',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),
                'clientes.email_institucional',
                'clientes.proyecto_interes_id',
                'clientes.publicidad_id',
                'clientes.vendedor_id','clientes.empresa','clientes.clasificacion',
                'clientes.created_at',
                'medios_publicitarios.nombre as publicidad',
                'fraccionamientos.nombre as proyecto');

        if($clasificacion != 5){
            if($desde == '' && $hasta == '' && $proyecto == '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde == '' && $hasta == '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.clasificacion', '=', $clasificacion)
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
        }
        else{
            if($desde == '' && $hasta == '' && $proyecto == '' && $publicidad == ''){
                $cliente = $query
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                    ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad == ''){
                $cliente = $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->get();
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('expedientes.fecha_firma_esc','=',NULL)
                    ->orderBy('personal.nombre', 'asc')
                    ->orderBy('personal.apellidos', 'asc')
                ->get();
            }
            elseif($desde == '' && $hasta == '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad == ''){
                $cliente= $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto != '' && $publicidad != ''){
                    $cliente= $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->get();
            }
            elseif($desde == '' && $hasta == '' && $proyecto != '' && $publicidad != ''){
                $cliente= $query
                    ->where('clientes.proyecto_interes_id', '=', $proyecto)
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->get();
            }
            elseif($desde != '' && $hasta != '' && $proyecto == '' && $publicidad != ''){
                $cliente= $query
                    ->whereBetween('clientes.created_at', [$desde, $hasta])
                    ->where('clientes.publicidad_id', '=', $publicidad)
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->distinct()
                ->get();
            }
       }
           
       return Excel::create('Prospectos', function($excel) use ($cliente){
        $excel->sheet('Prospectos', function($sheet) use ($cliente){
            
            $sheet->row(1, [
                'Nombre', 'Edad', 'Direccion', 'Celular', 'Email', 'Email Institucional',
                'Proyecto de interes','Clasificación', 'Fecha de alta', 'Publicidad'
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

            foreach($cliente as $index => $prospecto) {
                $cont++;

                setlocale(LC_TIME, 'es_MX.utf8');
                $actual = Carbon::now();
                $edad = Carbon::parse($prospecto->f_nacimiento)->age;
                $prospecto->edad = $actual->diffForHumans($prospecto->f_nacimiento, $actual);
                
                switch($prospecto->clasificacion){
                    case 1:{
                        $clasificacion = 'No viable';
                        break;
                    }
                    case 2:{
                        $clasificacion = 'A';
                        break;
                    }
                    case 3:{
                        $clasificacion = 'B';
                        break;
                    }
                    case 4:{
                        $clasificacion = 'C';
                        break;
                    }
                    case 5:{
                        $clasificacion = 'Venta';
                        break;
                    }
                    case 6:{
                        $clasificacion = 'Cancelado';
                        break;
                    }
                    case 7:{
                        $clasificacion = 'Coacreditado';
                        break;
                    }
                }

                $sheet->row($index+2, [
                    $prospecto->n_completo,
                    $edad,
                    $prospecto->direccion.' Col. '.$prospecto->colonia,
                    $prospecto->celular,
                    $prospecto->email,
                    $prospecto->email_institucional,
                    $prospecto->proyecto,
                    $clasificacion,
                    $prospecto->created_at,
                    $prospecto->publicidad

                ]);	
            }
            $num='A1:J' . $cont;
            $sheet->setBorder($num, 'thin');
        });
    }
)->download('xls');
    }

    public function selectClientesVenta(Request $request){
        $datos = Personal::join('clientes as c','personal.id','=','c.id')
                        ->join('creditos as cre','personal.id','=','cre.prospecto_id')
                        ->join('contratos','cre.id','=','contratos.id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->select(
                            'c.id',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente")
                        )
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar. '%')
                        ->distinct()
                        ->get();

        return['clientes'=>$datos];
    }

    public function getDatosCliente(Request $request){
        $datos = Personal::join('clientes as c','personal.id','=','c.id')
                        ->join('creditos as cre','personal.id','=','cre.prospecto_id')
                        ->join('contratos','cre.id','=','contratos.id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->select(
                            'c.id',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'cre.fraccionamiento','cre.etapa','cre.manzana','cre.num_lote'
                        )
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), '=',$request->buscar)
                        ->get();

        return['clientes'=>$datos];
    }

}