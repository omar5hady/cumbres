<?php

namespace App\Http\Controllers\Modelo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Specification;
use App\SpecificationLote;
use App\Modelo;
use App\Lote;
use Auth;
use DB;
use Excel;
use File;

use App\Http\Resources\SpecificationResource;

class EspecificacionController extends Controller
{
    public function store(Request $request){

        $anterior = Specification::select('id')->where('modelo_id','=',$request->modelo_id)->get();
        $lotes = $this->getLotesModelo($request->modelo_id);
        if(sizeof($lotes))
            $this->deleteEspLotes($lotes);

        if(sizeof($anterior)){
            foreach($anterior as $ant)
                $this->destroy($ant->id);
        }

        if($request->hasFile('archivo')){
            $extension = File::extension($request->archivo->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->archivo->getRealPath();
                //Se obtiene la información del archivo.
                $data = Excel::load($path, function($reader) {
                })->get();

                if(!empty($data) && $data->count()){
                    //Se reocrren los registros encontrados
                    foreach ($data as $key => $value) {
                        $especificacion = new Specification();
                        $especificacion->general = $value->general;
                        $especificacion->subconcepto = $value->subconcepto;
                        $especificacion->descripcion = $value->descripcion;
                        $especificacion->modelo_id = $request->modelo_id;
                        $especificacion->save();

                        $this->asignarEspecificacion($lotes, $value);
                    }
                }

                return back();
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }


    }

    public function index(Request $request){
        $especificaciones = Specification::select('general')->where('modelo_id','=',$request->modelo_id)->distinct()->get();
        if(sizeof($especificaciones)){
            foreach($especificaciones as $generales){
                $generales->detalle = SpecificationResource::collection(
                    Specification::where('modelo_id','=',$request->modelo_id)->where('general','=',$generales->general)->get());
            }
        }
        return $especificaciones;
    }

    public function destroy($id){
        $especificacion = Specification::findOrFail($id);
        $especificacion->delete();
    }

    public function getEspecificaciones($modelo_id){
        $especificaciones = SpecificationResource::collection(Specification::where('modelo_id','=',$modelo_id)->get());
        return $especificaciones;
    }

    private function getLotesModelo($modelo_id){
        $lotes = Lote::select('id')->where('modelo_id','=',$modelo_id)
            ->where('habilitado','=',1)
            ->get();


        return $lotes;
    }

    private function deleteEspLotes($lotes){
        $esp = SpecificationLote::select('id')->whereIn('lote_id',$lotes)->get();
        if(sizeof($esp)){
            foreach($esp as $e){
                $lote = SpecificationLote::findOrFail($e->id);
                $lote->delete();
            }
        }
    }

    private function asignarEspecificacion($lotes, $datos){
        if(sizeof($lotes)){
            foreach($lotes as $lote){
                $esp = new SpecificationLote();
                $esp->general = $datos->general;
                $esp->subconcepto = $datos->subconcepto;
                $esp->descripcion = $datos->descripcion;
                $esp->lote_id = $lote->id;
                $esp->save();
            }
        }
    }
}
