<?php

namespace App\Http\Controllers\solicPagos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\DropboxFiles;
use Spatie\Dropbox\Client;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DocSolicPagosResource;

use App\SpSolicitud;
use App\SpFile;
use Carbon\Carbon;

use DB;
use Auth;

class ArchivosController extends Controller
{
    public function __construct()
    {
        // Necesitamos obtener una instancia de la clase Client la cual tiene algunos métodos
        // que serán necesarios.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
    }

    public function store(Request $request){
        $fileID = $this->storeFile($request);

        $solicitud = new SpFile();
        $solicitud->solic_id = $request->id;
        $solicitud->file_id = $fileID;
        $solicitud->tipo = $request->tipo;
        if($solicitud->tipo == 'COMPROBANTE DE PAGO'){
            $solic = SpSolicitud::findOrFail($request->id);
            //$solic->status = 4;
            //$solic->entrega_pago = Carbon::now();
            $file = DropboxFiles::findOrFail($fileID);
            $solic->comprobante_pago = $file->public_url;
            $solic->save();
        }
        $solicitud->carpeta = 'Adjuntos';
        $solicitud->save();

        return DocSolicPagosResource::collection(
            SpFile::where('solic_id','=',$solicitud->solic_id)->get()
        );
    }

    public function destroy(Request $request){
        $doc = SpFile::findOrFail($request->id);
        $this->deleteDropBoxFile($doc->carpeta,$doc->file_id);
        $doc->delete();
    }

    private function deleteDropBoxFile($carpeta,$id){
        // Eliminamos el registro de nuestra tabla.
        $del = DropboxFiles::findOrFail($id);
        $this->dropbox->delete('SolicPagos/Adjuntos/'.$del->name);
        $del->delete();
    }

    private function storeFile(Request $request){

        $carpeta = 'SolicPagos/Adjuntos/';
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
