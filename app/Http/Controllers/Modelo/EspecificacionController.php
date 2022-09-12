<?php

namespace App\Http\Controllers\Modelo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Specification;
use App\Modelo;
use App\Lote;
use Auth;
use DB;
use Excel;
use File;

class EspecificacionController extends Controller
{
    public function store(Request $request){

        $anterior = Specification::select('id')->where('modelo_id','=',$request->modelo_id)->get();

        if(sizeOf($anterior)){
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
                    }
                }

                return back();
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }


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
            ->where('firmado','=',0)
            ->get();
        return $lotes;
    }
}
