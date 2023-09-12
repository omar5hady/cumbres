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
    // funcion para la consulta general de los datos del personal
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
       if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = Personal::join('departamento','personal.departamento_id','=','departamento.id_departamento')
                ->join('empresas','personal.empresa_id','=','empresas.id')
                ->select('personal.nombre','personal.apellidos',
                    'personal.f_nacimiento','personal.rfc','personal.homoclave',
                    'personal.direccion','personal.colonia','personal.cp',
                    'personal.telefono','personal.ext','personal.celular','personal.email','personal.activo',
                    'personal.id','personal.departamento_id','departamento.departamento as departamento',
                    'personal.empresa_id','empresas.nombre as empresa',
                    'departamento.id_departamento');

            $Personales = $query
                ->where('personal.nombre','!=','Sin Asignar');

            if($criterio == 'id_departamento'){
                $Personales = $Personales
                    ->where($criterio, '=', $buscar );
            }
            elseif($criterio == 'personal.nombre'){
                $Personales = $Personales
                    ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
            }
            else{
                $Personales = $Personales
                    ->where($criterio, 'like', '%'. $buscar . '%');
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

    //funcion para insertar un nuevo registro en la tabla Personal
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

    // Busca el id del registro a desactivar y setea la celda "activo" a cero
    public function desactivar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->activo = '0';
        $Personal->save();
    }
    // Busca el id del registro a desactivar y setea la celda "activo" a uno
    public function activar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->activo = '1';
        $Personal->save();
    }

    // elimina el registro con el id seleccionado
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $Personal = Personal::findOrFail($request->id);
        $Personal->delete();
    }

    // selecciona  el nombre del personal requerido
    public function selectNombre(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $departamento = $request->departamento_id;

        $Personal = Personal::select(DB::raw("CONCAT(nombre,' ',apellidos) AS name"),'id')
                     ->where('departamento_id', '=', $departamento )
                     ->where('activo','=','1') // filtra por los usuarios activos
                     ->orderBy('name')
                     ->get();
                     return['personal' => $Personal];
    }

    // consulta de personal sin registro en la tabla "users"
    public function select_Pers_sinUser(Request $request){
        if(!$request->ajax())return redirect('/');
        $personas = Personal::leftJoin('users as users','personal.id','=','users.id')
                             ->select('nombre','users.id as UserId','personal.id as personalId')
                             ->where('users.id','=',NULL) // filtra por personal sin registro en la tabla users
                             ->where('personal.departamento_id','!=','8') // filtro de personal que sea diferente de clientes
                             ->where('nombre','!=','Sin Asignar')->get();

                             return ['personas' => $personas];
    }


    public function selectRFC(Request $request){
        $rfc = $request->rfc;
        $rfc1 = Personal::select('rfc')->where('rfc','=',$rfc)->count(); // verifica si hay mas de una concidencia de RFC y los cuenta
            if($rfc1==1){ // en caso de que exista solo una coincidencia  se selecciona el cliente y el vendedor relacionado a ese cliente
                $personaid = Personal::join('clientes','clientes.id','=','personal.id')
                ->select('personal.rfc','personal.id', 'personal.nombre',
                    'personal.apellidos','personal.celular', 'personal.telefono','personal.f_nacimiento', 'personal.email',
                    'clientes.sexo','clientes.publicidad_id', 'clientes.edo_civil', 'clientes.vendedor_id'
                )->where('personal.rfc','=',$rfc)->get();

                $vendedor = Cliente::join('vendedores','clientes.vendedor_id','=','vendedores.id')
                    ->join('personal','vendedores.id','=','personal.id')
                    ->select('personal.nombre','personal.apellidos','personal.id', 'clientes.clasificacion')
                    ->where('clientes.id','=',$personaid[0]->id)->get();

                return ['rfc1'=>$rfc1,'personaid' => $personaid, 'vendedor'=> $vendedor];
            }else{
                return ['rfc1'=>$rfc1];
            }
    }

    // funcion para la consulta de los gestores activos
    public function select_gestores (){
        $gestores = Personal::join('users','personal.id','=','users.id')
                            ->select('personal.id',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_gestor"))
                            ->where('users.rol_id','=',8) // se filtra por el rol de " Gestor de ventas"
                            ->where('users.condicion','=',1) // que esten activos
                            ->get();
        return ['gestores' => $gestores];
    }

    // funcion para seleccionar clientes
    public function indexClientes(Request $request){
        $desde = $request->desde;
        $clasificacion = $request->clasificacion;
        $hasta = $request->hasta;
        $proyecto = $request->proyecto;
        $publicidad = $request->publicidad;

        $query = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
            ->join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
            ->select(  // datos requeridos
                'personal.id',
                'personal.f_nacimiento',
                'personal.direccion',
                'personal.telefono',
                'personal.clv_lada',
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
                'fraccionamientos.nombre as proyecto'
            );


             // filtrar busqueda depende de criterio
            $cliente = $query->where('clientes.clasificacion', '=', $clasificacion);

                if($desde != '' && $hasta != '')
                    $cliente= $cliente->whereBetween('clientes.created_at', [$desde, $hasta]);
                if($proyecto != '')
                    $cliente= $cliente->where('clientes.proyecto_interes_id', '=', $proyecto);
                if($publicidad != '')
                    $cliente= $cliente->where('clientes.publicidad_id', '=', $publicidad);

            $cliente = $cliente->orderBy('personal.nombre', 'asc')
                ->orderBy('personal.apellidos', 'asc')
                ->paginate(15);


        return [
            'clientes' => $cliente];
    }

    // Funcion para crear el archivo excel de la consulta de clientes
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
                'fraccionamientos.nombre as proyecto'
            );

        if($clasificacion != 5){  // filtra clientes que no  sean de ventas

            $cliente= $query->where('clientes.clasificacion', '=', $clasificacion);

                if($desde != '' && $hasta != '')
                    $cliente= $cliente->whereBetween('clientes.created_at', [$desde, $hasta]);
                if($proyecto != '')
                    $cliente= $cliente->where('clientes.proyecto_interes_id', '=', $proyecto);
                if($publicidad != '')
                    $cliente= $cliente->where('clientes.publicidad_id', '=', $publicidad);

            $cliente = $cliente->orderBy('personal.nombre', 'asc')
                ->orderBy('personal.apellidos', 'asc')
                ->get();
        }
        else{

            $cliente = $query->where('expedientes.fecha_firma_esc','!=',NULL);  // selecciona solo clientes que tengan firma de escrituras

                if($desde != '' && $hasta != '')
                    $cliente= $cliente->whereBetween('clientes.created_at', [$desde, $hasta]);
                if($proyecto != '')
                    $cliente= $cliente->where('clientes.proyecto_interes_id', '=', $proyecto);
                if($publicidad != '')
                    $cliente= $cliente->where('clientes.publicidad_id', '=', $publicidad);

            $cliente = $cliente->distinct()->get();
        }

        // se crea la hoja de excel con los datos de la consulta
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

    // Funcion para
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

    //Funcion para optener datos de cliente en base a criterio del nombre
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

    // lsita de claves lada mas comunes , se crea una relacion en un arreglo para ser utilizado en el modulo de registro de cliente
    public function getClavesLadas(Request $request){
        //if(!$request->ajax())return redirect('/');
        $claves = array(
                    array('clave' => 54, 'pais' => 'Argentina' ),
                    array('clave' => 49, 'pais' => 'Alemania' ),
                    array('clave' => 591, 'pais' => 'Bolivia' ),
                    array('clave' => 55, 'pais' => 'Brasil' ),
                    array('clave' => 57, 'pais' => 'Colombia' ),
                    array('clave' => 593, 'pais' => 'Ecuador' ),
                    array('clave' => 595, 'pais' => 'Paraguay' ),
                    array('clave' => 51, 'pais' => 'Perú' ),
                    array('clave' => 598, 'pais' => 'Uruguay' ),
                    array('clave' => 58, 'pais' => 'Venezuela' ),
                    array('clave' => 1, 'pais' => 'Canadá' ),
                    array('clave' => 34, 'pais' => 'España' ),
                    array('clave' => 1, 'pais' => 'Estados Unidos' ),
                    array('clave' => 52, 'pais' => 'México' ),
                    array('clave' => 506, 'pais' => 'Costa Rica' ),
                    array('clave' => 53, 'pais' => 'Cuba' ),
                    array('clave' => 502, 'pais' => 'Guatemala' ),
                    array('clave' => 504, 'pais' => 'Honduras' ),
                    array('clave' => 876, 'pais' => 'Jamaica' ),
                    array('clave' => 505, 'pais' => 'Nicaragua' ),
                    array('clave' => 507, 'pais' => 'Panamá' ),
                    array('clave' => 1787, 'pais' => 'Puerto Rico' ),
                    array('clave' => 809, 'pais' => 'República Dominicana' )
                );

        return ['claves'=>$claves];
    }


}
