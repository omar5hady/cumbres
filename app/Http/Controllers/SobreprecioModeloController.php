<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sobreprecio_modelo;
use DB;
use App\Lote;
use App\Credito;
use Auth;

class SobreprecioModeloController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3= $request->buscar3;

        $query = Sobreprecio_modelo::join('lotes','sobreprecios_modelos.lote_id','=','lotes.id')
            ->join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->select('lotes.num_lote as lotes',DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioModelo"),
                'sobreprecios_modelos.id',
                'sobreprecios_modelos.sobreprecio_etapa_id', 
                'lotes.id as lote_id', 'lotes.manzana as manzana',
                'etapas.num_etapa as etapa','fraccionamientos.nombre as proyecto');
        
        if($buscar == ''){
        $sobreprecio_modelo = $query
            ->orderBy('fraccionamientos.nombre','ASC')
            ->orderBy('etapas.num_etapa','ASC')
            ->orderBy('lotes.manzana','ASC')
            ->orderBy('lotes.num_lote','ASC')->paginate(20);
        }
        else{
            if($buscar2 == '' && $buscar3 == '') {
                $sobreprecio_modelo = $query
                    ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                    ->orderBy('fraccionamientos.nombre','ASC')
                    ->orderBy('etapas.num_etapa','ASC')
                    ->orderBy('lotes.manzana','ASC')
                    ->orderBy('lotes.num_lote','ASC')->paginate(20);
            }
            else{
                if($buscar2 == ''){
                    $sobreprecio_modelo = $query
                        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $buscar3 . '%')
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(20);
                    }
                
                if($buscar3 == ''){
                    $sobreprecio_modelo = $query
                        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                        ->where('lotes.num_lote', 'like', '%'. $buscar2 . '%')
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(20);
                    }
                else{
                    $sobreprecio_modelo = $query
                        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $buscar3 . '%')
                        ->where('lotes.num_lote', 'like', '%'. $buscar2 . '%')
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(20);

                    }
            }
        }


        return [
            'pagination' => [
                'total'         => $sobreprecio_modelo->total(),
                'current_page'  => $sobreprecio_modelo->currentPage(),
                'per_page'      => $sobreprecio_modelo->perPage(),
                'last_page'     => $sobreprecio_modelo->lastPage(),
                'from'          => $sobreprecio_modelo->firstItem(),
                'to'            => $sobreprecio_modelo->lastItem(),
            ],
            'sobreprecio_modelo' => $sobreprecio_modelo
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $sobreprecio_modelo = new Sobreprecio_modelo();
        $sobreprecio_modelo->lote_id = $request->lote_id;
        $sobreprecio_modelo->sobreprecio_etapa_id = $request->sobreprecio_etapa_id;
        $sobreprecio_modelo->save();

        $lote_id = Lote::select('id')->where('id','=',$request->lote_id)
        ->get();

        foreach($lote_id as $loteid){
            $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
            ->where('sobreprecios_modelos.lote_id','=',$loteid->id)->get();


            foreach($sobreprecios as $sobreprecio){
                $sobreprecioslote = Lote::findOrFail($loteid->id);
                $sobreprecioslote->sobreprecio = $sobreprecio->sobreprecios;
                $sobreprecioslote->save();

                $creditos = Credito::select('id')->where('lote_id','=',$loteid->id)->get();
                if($sobreprecioslote->contrato==0)
                    foreach($creditos as $creditosid){
                        
                        $credito = Credito::findOrFail($creditosid->id);
                        $credito->precio_venta = $sobreprecio->sobreprecios + $credito->precio_base + $credito->precio_terreno_excedente + $credito->precio_obra_extra - $credito->descuento_promocion + $credito->costo_paquete;
                        $credito->save();
                        
                    }
            } 
        }
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $sobreprecio_modelo = Sobreprecio_modelo::findOrFail($request->id);
        $sobreprecio_modelo->lote_id = $request->lote_id;
        $sobreprecio_modelo->sobreprecio_etapa_id =  $request->sobreprecio_etapa_id;
        $sobreprecio_modelo->save();

        $lote_id = Lote::select('id')->where('id','=',$request->lote_id)
        ->get();

        foreach($lote_id as $loteid){
            $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
            ->where('sobreprecios_modelos.lote_id','=',$loteid->id)->get();
            foreach($sobreprecios as $sobreprecio){
                $sobreprecioslote = Lote::findOrFail($loteid->id);
                $sobreprecioslote->sobreprecio = $sobreprecio->sobreprecios;
                $sobreprecioslote->save();
            } 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $sobreprecio_modelo = Sobreprecio_modelo::findOrFail($request->id);
        $lote_id = $sobreprecio_modelo->lote_id;
        $sobreprecio_modelo->delete();

        
        
            $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
            ->where('sobreprecios_modelos.lote_id','=',$lote_id)->get();


            foreach($sobreprecios as $sobreprecio){
                $sobreprecioslote = Lote::findOrFail($lote_id);
                $sobreprecioslote->sobreprecio = $sobreprecio->sobreprecios;
                $sobreprecioslote->save();
                if($sobreprecioslote->contrato==0){
                    
                

                    $creditos = Credito::select('id')->where('lote_id','=',$lote_id)->get();
                    foreach($creditos as $creditosid){
                        
                        $credito = Credito::findOrFail($creditosid->id);
                        $credito->precio_venta = $sobreprecio->sobreprecios + $credito->precio_base + $credito->precio_terreno_excedente + $credito->precio_obra_extra - $credito->descuento_promocion + $credito->costo_paquete;
                        $credito->save();
                        

                    }
                }
            } 
        

        
    }
}
