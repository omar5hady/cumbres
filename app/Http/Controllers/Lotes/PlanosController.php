<?php

namespace App\Http\Controllers\Lotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanoResource;

use App\DropboxFiles;
use Spatie\Dropbox\Client;
use Illuminate\Support\Facades\Storage;

use App\Lote;
use App\PlanoProyecto;

use DB;
use Auth;

class PlanosController extends Controller
{

    public function __construct()
    {
        // Necesitamos obtener una instancia de la clase Client la cual tiene algunos métodos
        // que serán necesarios.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
    }

    public function destroy(Request $request){
        $doc = PlanoProyecto::findOrFail($request->id);

        $docs = PlanoProyecto::select('id')->where('file_id','=',$doc->file_id)->get();
        if(sizeof($docs) == 1){
            $this->deleteDropBoxFile($doc->file_id);
        }
        $doc->delete();
    }

    private function deleteDropBoxFile($id){
        // Eliminamos el registro de nuestra tabla.
        $del = DropboxFiles::findOrFail($id);
        $this->dropbox->delete('Proyectos/'.$del->name);
        $del->delete();
    }

    private function getLotes(Request $request){
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->select('lotes.*','fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa',
                    'modelos.nombre as modelo'
                );

        if($request->b_proyecto != '')
            $lotes = $lotes->where('lotes.fraccionamiento_id','=', $request->b_proyecto);

        if($request->b_etapa != '')
            $lotes = $lotes->where('lotes.etapa_id','=', $request->b_etapa);

        if($request->b_manzana != '')
            $lotes = $lotes->where('lotes.manzana','like', '%'.$request->b_manzana.'%');

        if($request->b_modelo != '')
            $lotes = $lotes->where('lotes.modelo_id','=', $request->b_modelo);

        if($request->b_inicio != '')
            $lotes = $lotes->where('lotes.num_inicio','=', $request->b_inicio);

        $lotes = $lotes->orderBy('fraccionamientos.nombre','ASC')
                ->orderBy('etapas.num_etapa','ASC')
                ->orderBy('lotes.manzana','ASC')
                ->orderBy('lotes.num_lote','ASC')->paginate(10);

        return $lotes;
    }

    public function index(Request $request){
        $lotes = $this->getLotes($request);

        if(sizeof($lotes))
            foreach($lotes as $lote)
                $lote->planos = PlanoResource::collection(PlanoProyecto::where('lote_id','=',$lote->id)->get());

        return $lotes;
    }

    public function store(Request $request){
        $fileID = $this->storeFile($request);
        if($request->proyecto != ''){
            $lotes = Lote::select('id')->where('fraccionamiento_id','=',$request->proyecto);
                if($request->etapa != '')
                    $lotes = $lotes->where('etapa_id','=',$request->etapa);
            $lotes = $lotes->get();
            foreach($lotes as $lote){
                $plano = new PlanoProyecto();
                $plano->lote_id = $lote->id;
                $plano->file_id = $fileID;
                $plano->tipo = $request->tipo;
                $plano->description = $request->description;
                $plano->save();
            }
        }
        else{
            $ids = explode(",", $request->ids);
            foreach($ids as $id){
                $plano = new PlanoProyecto();
                $plano->lote_id = $id;
                $plano->file_id = $fileID;
                $plano->tipo = $request->tipo;
                $plano->description = $request->description;
                $plano->save();
            }
        }
    }

    private function storeFile(Request $request){

        $carpeta = 'Proyectos/';
        $name = uniqid() . '' . $request->file->getClientOriginalName();
        // Guardamos el archivo indicando el driver y el método putFileAs el cual recibe
        // el directorio donde será almacenado, el archivo y el nombre.
        // ¡No olvides validar todos estos datos antes de guardar el archivo!
        Storage::disk('dropbox')->putFileAs(
            $carpeta,
            $request->file,
            $name
        );

        // Creamos el enlace publico en dropbox utilizando la propiedad dropbox
        // definida en el constructor de la clase y almacenamos la respuesta.
        $response = $this->dropbox->createSharedLinkWithSettings(
            $carpeta.$name,
            ["requested_visibility" => "public"]
        );

        // Creamos un nuevo registro en la tabla files con los datos de la respuesta.
        $archivo = new DropboxFiles();
        $archivo->name = $response['name'];
        $archivo->extension = $request->file->getClientOriginalExtension();
        $archivo->size = $response['size'];
        $archivo->public_url = $response['url'];
        $archivo->save();

        return $archivo->id;

    }
}
