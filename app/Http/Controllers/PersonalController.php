<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Personal;
use DB;
use App\Cliente;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
       if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $Personales = Personal::join('departamento','personal.departamento_id','=','departamento.id_departamento')
            ->join('empresas','personal.empresa_id','=','empresas.id')
            ->select('personal.nombre','personal.apellidos',
                'personal.f_nacimiento','personal.rfc','personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                'personal.telefono','personal.ext','personal.celular','personal.email','personal.activo',
                'personal.id','personal.departamento_id','departamento.departamento as departamento',
                'personal.empresa_id','empresas.nombre as empresa',
                'departamento.id_departamento')
                ->where('personal.nombre','!=','Sin Asignar')
                ->orderBy('id','desc')->paginate(8);
        }
        else{
            if($criterio == 'id_departamento'){
                $Personales = Personal::join('departamento','personal.departamento_id','=','departamento.id_departamento')
                ->join('empresas','personal.empresa_id','=','empresas.id')
                ->select('personal.nombre','personal.apellidos',
                    'personal.f_nacimiento','personal.rfc','personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                    'personal.telefono','personal.ext','personal.celular','personal.email','personal.activo',
                    'personal.id','personal.departamento_id','departamento.departamento as departamento',
                    'personal.empresa_id','empresas.nombre as empresa',
                    'departamento.id_departamento')
                    ->where($criterio, '=', $buscar )
                    ->where('personal.nombre','!=','Sin Asignar')->orderBy('id','desc')->paginate(8);
            }
            else{
                $Personales = Personal::join('departamento','personal.departamento_id','=','departamento.id_departamento')
                ->join('empresas','personal.empresa_id','=','empresas.id')
                ->select('personal.nombre','personal.apellidos',
                    'personal.f_nacimiento','personal.rfc','personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                    'personal.telefono','personal.ext','personal.celular','personal.email','personal.activo',
                    'personal.id','personal.departamento_id','departamento.departamento as departamento',
                    'personal.empresa_id','empresas.nombre as empresa',
                    'departamento.id_departamento')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('personal.nombre','!=','Sin Asignar')->orderBy('id','desc')->paginate(8);
            }
        }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
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
        if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->activo = '0';
        $Personal->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Personal = Personal::findOrFail($request->id);
        $Personal->activo = '1';
        $Personal->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
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

}