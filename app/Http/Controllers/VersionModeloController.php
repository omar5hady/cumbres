<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Version_modelo;
use App\Lote;
use App\Contrato;
use App\Modelo;
use App\SpecificationLote;
use Auth;

use App\Http\Resources\SpecificationLoteResource;
use App\Http\Controllers\NotificacionesAvisosController;

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

        $lotes = Lote::join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
            ->join('etapas as e','lotes.etapa_id','=','e.id')
            ->join('modelos as m','lotes.modelo_id','=','m.id')
            ->select('lotes.*','f.nombre as proyecto','e.num_etapa as etapa','m.nombre as modelo')
            ->where('lotes.habilitado','=',1)
            ->where('m.nombre','!=','Por asignar')
            ->where('m.nombre','!=','Terreno');

        if($proyecto != '')
            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$proyecto);

        if($etapa != '')
            $lotes = $lotes->where('lotes.etapa_id','=',$etapa);

        if($manzana != '')
            $lotes = $lotes->where('lotes.manzana','=',$manzana);

        if($lote != '')
            $lotes = $lotes->where('lotes.num_lote','=',$lote);

        if($modelo != '')
            $lotes = $lotes->where('lotes.modelo_id','=',$modelo);

        $lotes = $lotes->orderBy('f.nombre','ASC')
                        ->orderBy('e.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(15);

        // recorre todos los datos optenidos
        // obtiene el nombre del archivo
        foreach($lotes as $index => $lote) {
            $lote->especificaciones = SpecificationLote::select('general')->where('lote_id','=',$lote->id)->distinct()->get();
                if(sizeof($lote->especificaciones)){
                    foreach($lote->especificaciones as $generales){
                        $generales->detalle = SpecificationLoteResource::collection(
                            SpecificationLote::where('lote_id','=',$lote->id)->where('general','=',$generales->general)->get());
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

    public function updateEspecificacion(Request $request){
        $esp = SpecificationLote::findOrFail($request->id);
        $esp->subconcepto = $request->subconcepto;
        $esp->descripcion = $request->descripcion;
        $esp->lote_id = $request->lote_id;
        $esp->save();

        $this->setNotification($request->lote_id);
    }

    public function deleteEspecificacion(Request $request){
        $esp = SpecificationLote::findOrFail($request->id);
        $esp->delete();
    }

    public function storeEspecificacion(Request $request){
        $esp = new SpecificationLote();
        $esp->general = $request->general;
        $esp->subconcepto = $request->subconcepto;
        $esp->descripcion = $request->descripcion;
        $esp->lote_id = $request->lote_id;
        $esp->save();

        return($esp);
    }

    private function setNotification($lote_id) {
        //Buscar si el lote pertenece a un contrato firmado
        $contrato = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->leftJoin('expedientes','contratos.id','=','expedientes.id')
                    ->select('contratos.id','expedientes.liquidado',
                        'creditos.fraccionamiento', 'creditos.manzana',
                        'creditos.etapa','creditos.num_lote'
                    )
                    ->whereIn('contratos.status',[1,3])
                    ->where('creditos.lote_id','=',$lote_id)
                    ->get();

        if(sizeof($contrato)){
            if($contrato[0]->liquidado === NULL || $contrato[0]->liquidado !== 1){
                $msj = 'Se han modificado las especificaciones del lote '.$contrato[0]->num_lote.
                    ' del fraccionamiento '.$contrato[0]->fraccionamiento.' Etapa '.$contrato[0]->etapa.' Manzana '.$contrato[0]->manzana;
                $aviso = new NotificacionesAvisosController();
                $aviso->store(12,$msj);
                $aviso->store(3,$msj);

                $lote = Lote::findOrFail($lote_id);
                $lote->cambio_esp = 1;
                $lote->save();
            }
        }
    }

    public function setEspecifiacionesMasa(Request $request){
        $lotes = $request->lotes;
        $especificaciones = $request->especifiaciones;

        $espLt = SpecificationLote::select('id')->whereIn('lote_id',$lotes)->get();
        if(sizeof($espLt))
            foreach($espLt as $lt){
                $e = SpecificationLote::findOrFail($lt->id);
                $e->delete();
            }

        foreach($lotes as $l){
            foreach($especificaciones as $general){
                foreach($general['detalle'] as $detalle){
                    $especificacion = new SpecificationLote();
                    $especificacion->lote_id = $l;
                    $especificacion->general = $detalle['general'];
                    $especificacion->subconcepto = $detalle['subconcepto'];
                    $especificacion->descripcion = $detalle['descripcion'];
                    $especificacion->save();
                }
            }

            $this->setNotification($l);
        }

    }
}
