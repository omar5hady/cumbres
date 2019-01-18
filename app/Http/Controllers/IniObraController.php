<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ini_obra;
use App\Ini_obra_lote;
use Illuminate\Support\Facades\DB;

class IniObraController extends Controller
{
 
    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','ini_obras.etapa_id','=','etapas.id')
            ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'ini_obras.estado','contratistas.nombre as contratista','fraccionamientos.nombre as proyecto',
            'etapas.num_etapa as proyecto')
            ->orderBy('ini_obras.id', 'desc')->paginate(8);
        }
        else{
            $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','ini_obras.etapa_id','=','etapas.id')
            ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'ini_obras.estado','contratistas.nombre as contratista','fraccionamientos.nombre as proyecto',
            'etapas.num_etapa as proyecto')
            ->where('ini_obras.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ini_obras.id', 'desc')->paginate(8);
        }
         
        return [
            'pagination' => [
                'total'        => $ini_obra->total(),
                'current_page' => $ini_obra->currentPage(),
                'per_page'     => $ini_obra->perPage(),
                'last_page'    => $ini_obra->lastPage(),
                'from'         => $ini_obra->firstItem(),
                'to'           => $ini_obra->lastItem(),
            ],
            'ini_obra' => $ini_obra
        ];
    }
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','ini_obras.etapa_id','=','etapas.id')
        ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'ini_obras.estado','contratistas.nombre as contratista','fraccionamientos.nombre as proyecto',
            'etapas.num_etapa as proyecto')
        ->where('ini_obra.id','=',$id)
        ->orderBy('ini_obra.id', 'desc')->take(1)->get();
         
        return ['ini_obra' => $ini_obra];
    }
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $detalles = Ini_obra_lote::select('ini_obra_lotes.costo_directo',
        'ini_obra_lotes.costo_indirecto','ini_obra_lotes.importe','ini_obra_lotes.lote',
        'ini_obra_lotes.manzana','ini_obra_lotes.modelo','ini_obra_lotes.construccion')
        ->where('ini_obra_lotes.ini_obra_id','=',$id)
        ->orderBy('ini_obra_lotes.id', 'desc')->get();
         
        return ['detalles' => $detalles];
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        try{
            DB::beginTransaction(); 
            $ini_obra = new Ini_obra();
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->etapa_id = $request->etapa_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $request->f_ini;
            $ini_obra->f_fin = $request->f_fin;
            $ini_obra->total_importe = $request->total_importe;
            $ini_obra->total_costo_directo = $request->total_costo_directo;
            $ini_obra->total_costo_indirecto =  $request->total_costo_indirecto;
            $ini_obra->anticipo = $request->anticipo;
            $ini_obra->total_anticipo = $request->total_anticipo;
            $ini_obra->save();
 
            $lotes = $request->data;//Array de detalles
            //Recorro todos los elementos
 
            foreach($lotes as $ep=>$det)
            {
                $lotes = new Ini_obra_lote();
                $lotes->ini_obra_id = $ini_obra->id;
                $lotes->lote = $det['lote'];
                $lotes->manzana = $det['manzana'];
                $lotes->modelo = $det['modelo'];
                $lotes->construccion = $det['construccion'];
                $lotes->costo_directo = $det['costo_directo'];
                $lotes->costo_indirecto = $det['costo_indirecto'];
                $lotes->importe = $det['importe'];       
                $lotes->descripcion = $det['descripcion'];
                $lotes->save();
            }          
 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

}
