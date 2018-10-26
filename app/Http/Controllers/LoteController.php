<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use App\Modelo;
use App\Manzana;
use Session;
use Excel;
use File;
use DB;

class LoteController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
       // if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                      'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios')
                ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(5);
        }
        else{
            if($buscar2=='')
            {
                $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(5);
            }
            else{
                if($buscar3=='')
                {
                    $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%')
                        ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(5);
                }
                else{
                    $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%')
                        ->where('lotes.manzana', 'like', '%'. $buscar3 . '%')
                        ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(5);
                }
            }
        }

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
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
        $lote = new Lote();
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->modelo_id = $request->modelo_id;
        $lote->empresa_id = $request->empresa_id;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->casa_muestra = $request->casa_muestra;
        $lote->lote_comercial = $request->lote_comercial;
        $lote->comentarios = $request->comentarios;

        $lote->save();
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
        $lote = Lote::findOrFail($request->id);
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->modelo_id = $request->modelo_id;
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->casa_muestra = $request->casa_muestra;
        $lote->lote_comercial = $request->lote_comercial;
        $lote->comentarios = $request->comentarios;

        $lote->save();
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
        $lote = Lote::findOrFail($request->id);
        $lote->delete();
    }


    public function import(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
 
                    foreach ($data as $key => $value) {
                        $terreno = Modelo::select('terreno','construccion')
                            ->where('id', '=', $request->modelo_id )->get();

                        $insert[] = [
                        'fraccionamiento_id' => $request->fraccionamiento_id,
                        'etapa_id' => $request->etapa_id,
                        'manzana' => $value->manzana,
                        'num_lote' => $value->num_lote,
                        'sublote' => $value->sublote,
                        'modelo_id' => $request->modelo_id,
                        'empresa_id' => 1,
                        'calle' => $value->calle,
                        'numero' => $value->numero,
                        'interior' => $value->interior,
                        'terreno' => $value->terreno,
                        'construccion' => $terreno[0]->construccion,
                        'casa_muestra' => $value->casa_muestra,
                        'lote_comercial' => $value->lote_comercial,
                        'comentarios' => $value->comentarios,
                        
                        ];
                    }
 
                    if(!empty($insert)){
 
                        $insertData = DB::table('lotes')->insert($insert);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {                        
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }
 
                return back();
 
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }

    // public function selectManzana_proyecto(Request $request){
    //     //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
    //     if(!$request->ajax())return redirect('/');

    //     $buscar = $request->buscar;
    //     $manzanas = Manzana::select('manzana','id')
    //     ->where('fraccionamiento_id', '=', $buscar )->get();
    //     return['manzanas' => $manzanas];
    // }

    public function storeManzana(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $manzana = new Manzana();
        $manzana->fraccionamiento_id = $request->fraccionamiento_id;
        $manzana->manzana = $request->manzana;
        $manzana->save();
    }

    public function select_modelos_etapa(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('modelos.nombre', 'lotes.modelo_id', 'lotes.fraccionamiento_id',  'lotes.etapa_id')
        ->distinct()
        ->where('lotes.fraccionamiento_id', '=', $buscar1)
        ->where('lotes.etapa_id', '=', $buscar2)->get();
        return['lotes' => $lotes];
    }

    public function select_manzanas_etapa (Request $request){
        if(!$request->ajax())return redirect('/');
        
        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $manzana = Lote::select('lotes.manzana')->distinct()
                        ->where('lotes.fraccionamiento_id','=', $buscar)
                        ->where('lotes.etapa_id','=', $buscar1)->get();

        return ['manzana' => $manzana];

    }

    public function select_lote_manzana (Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $lote_manzana = Lote::select ('lotes.num_lote','lotes.id')
                             ->where('lotes.fraccionamiento_id','=', $buscar)
                             ->where('lotes.etapa_id','=', $buscar1)
                             ->where('lotes.manzana','=', $buscar2)->get();

        return ['lote_manzana' => $lote_manzana];
    }


}
