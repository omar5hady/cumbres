<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Version_modelo;
use App\Lote;
use App\Modelo;
use Auth;

class VersionModeloController extends Controller
{
    //Funcion para subir imagen de los modelos de casas
    public function formSubmit(Request $request,$id,$version)
    { 
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $fileName = time().'.'.$request->archivo->getClientOriginalExtension(); // genera el nombre del archivo con su linea te tiempo
        $moved =  $request->archivo->move(public_path('/files/modelos'), $fileName); // guarda el archivo

        if($moved){ // crea nuevos registros con la informacion del archivo
            if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
            $archivo = new Version_modelo();
            $archivo->modelo_id = $request->id;
            $archivo->archivo = $fileName;
            $archivo->version = $request->version;
            $archivo->save(); //Insert
    
            }
        
    	return response()->json(['success'=>'You have successfully upload file.']);
    }

    // elimina archivo de modelo 
    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $archivo = Version_modelo::findOrFail($request->id);
        $image_path =  public_path().'/files/modelos/'.$archivo->archivo;
        unlink($image_path);
        $archivo->delete();

    }

    // obtiene las veriones del archivo seleccionado 
    public function getVersiones(Request $request){
        if(!$request->ajax())return redirect('/');

        $versiones = Version_modelo::select('archivo','version','id')->where('modelo_id','=',$request->modelo)->get();

        return['versiones'=>$versiones];
    }

    // obtiene toda la informacion relacionada con lotes y los modelos
    public function indexLotes(Request $request){
        //if(!$request->ajax())return redirect('/');

        $proyecto = $request->proyecto;
        $etapa = $request->etapa;
        $modelo = $request->modelo;
        $manzana = $request->manzana;
        $lote = $request->lote;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                    'lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo', 'modelos.archivo', 'lotes.nombre_archivo',
                    'lotes.habilitado','lotes.lote_comercial','lotes.id',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id',
                    'lotes.credito_puente','lotes.fecha_termino_ventas');

        // busqueda por criterio
        if($manzana == '' && $lote == ''){
            $lotes = $query
            ->where('lotes.habilitado','=',1)
            // ->where('lotes.contrato','=',0)
            ->where('lotes.fraccionamiento_id','=',$proyecto)
            ->where('lotes.etapa_id','=',$etapa)
            ->where('lotes.modelo_id','=',$modelo);
        }
        elseif($manzana != '' && $lote == ''){
            $lotes = $query
            ->where('lotes.habilitado','=',1)
            // ->where('lotes.contrato','=',0)
            ->where('lotes.fraccionamiento_id','=',$proyecto)
            ->where('lotes.etapa_id','=',$etapa)
            ->where('lotes.modelo_id','=',$modelo)
            ->where('lotes.manzana', 'like', '%'. $manzana . '%');
        }
        elseif($manzana != '' && $lote != ''){
            $lotes = $query
            ->where('lotes.habilitado','=',1)
            // ->where('lotes.contrato','=',0)
            ->where('lotes.fraccionamiento_id','=',$proyecto)
            ->where('lotes.etapa_id','=',$etapa)
            ->where('lotes.modelo_id','=',$modelo)
            ->where('lotes.manzana', 'like', '%'. $manzana . '%')
            ->where('lotes.num_lote', '=', $lote);
        }
        elseif($manzana == '' && $lote != ''){
            $lotes = $query
            ->where('lotes.habilitado','=',1)
            // ->where('lotes.contrato','=',0)
            ->where('lotes.fraccionamiento_id','=',$proyecto)
            ->where('lotes.etapa_id','=',$etapa)
            ->where('lotes.modelo_id','=',$modelo)
            ->where('lotes.num_lote', '=', $lote);
        }

        $lotes = $lotes->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(15);

        // recorre todos los datos optenidos 
        // obtiene el nombre del archivo 
        foreach($lotes as $index => $lote) {
            $archivo = Version_modelo::select('archivo')->where('modelo_id','=',$lote->modelo_id)->where('version','=',$lote->nombre_archivo)->get();
            if(sizeof($archivo)) 
                $lote->archivo = $archivo[0]->archivo;
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

    // 
    public function updateVersionLote(Request $request){

        $lote = Lote::findOrFail($request->id);
        if($request->nombre_archivo == ''){
            $lote->nombre_archivo = NULL;
        }
        else{
            $lote->nombre_archivo = $request->nombre_archivo;
        }
        
        $lote->save();
    }
}
