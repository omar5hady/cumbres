<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ini_obra;
use App\Ini_obra_lote;
use DB;

class IniObraController extends Controller
{
 
    
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $ini_obras = Ini_obra::orderBy('fraccionamiento_id','desc')->paginate(5);
        }
        else{
            $ini_obras = Ini_obra::where($criterio, 'like', '%'. $buscar . '%')->orderBy('fraccionamiento_id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $ini_obras->total(),
                'current_page'  => $ini_obras->currentPage(),
                'per_page'      => $ini_obras->perPage(),
                'last_page'     => $ini_obras->lastPage(),
                'from'          => $ini_obras->firstItem(),
                'to'            => $ini_obras->lastItem(),
            ],
            'ini_obras' => $ini_obras
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
        $ini_obras = new Ini_obra();
        $ini_obras->fraccionamiento_id = $request->fraccionamiento_id;
        $ini_obras->etapa_id = $request->etapa_id;
        $ini_obras->contratista_id = $request->contratista_id;
        $ini_obras->f_ini = $request->f_ini; 
        $ini_obras->f_fin = $request->f_fin; 
        $ini_obras->clave = $request->clave;        
        $ini_obras->total_costo_directo = $request->total_costo_directo;
        $ini_obras->total_costo_indirecto = $request->total_costo_indirecto; 
        $ini_obras->total_importe = $request->total_importe; 
        $ini_obras->anticipo = $request->anticipo; 
        $ini_obras->total_anticipo = $request->total_anticipo; 
        $ini_obras->iniciado = 1; 
        $ini_obras->save();

        $datos_en_array = array();

        foreach ($request->costo_directo as $key => $costo_directo)
        {
            
            $datos_en_array[$key]['costo_directo'] = $request->costo_directo;
   
        }
        
        Ini_obra_lote::insert($datos_en_array);
        DB::table('ini_obra_lotes')->insert($datos_en_array);

        $ini_obras_lotes = new Ini_obra_lote();
        $ini_obras_lotes->ini_obra_id = $ini_obras->id;
        $ini_obras_lotes->lote_id = $request->lote_id;
        $ini_obras_lotes->manzana = $request->manzana;
        $ini_obras_lotes->superficie = 200;
        $ini_obras_lotes->costo_directo = $request->costo_directo;
        $ini_obras_lotes->costo_indirecto = $request->costo_indirecto;
        $ini_obras_lotes->importe = $request->importe;
        $ini_obras_lotes->descripcion = 'oso pardo que no es pardo';
        $ini_obras_lotes->iniciado = 0;
        $ini_obras_lotes->save();
    
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
        $ini_obras = Ini_obra::findOrFail($request->id);
        $ini_obras->id = $request->id;
        $ini_obras->fraccionamiento_id = $request->fraccionamiento_id;
        $ini_obras->etapa_id = $request->etapa_id;
        $ini_obras->contratista_id = $request->contratista_id;
        $ini_obras->f_ini = $request->f_ini; 
        $ini_obras->f_fin = $request->f_fin; 
        $ini_obras->clave = $request->clave;        
        $ini_obras->total_costo_directo = $request->total_costo_directo;
        $ini_obras->total_costo_indirecto = $request->total_costo_indirecto; 
        $ini_obras->total_importe = $request->total_importe; 
        $ini_obras->anticipo = $request->anticipo; 
        $ini_obras->total_anticipo = $request->total_anticipo; 
        $ini_obras->iniciado = $request->iniciado; 
        $ini_obras->save();
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
        $ini_obras = Ini_obra::findOrFail($request->id);
        $ini_obras->delete();
    }

}
