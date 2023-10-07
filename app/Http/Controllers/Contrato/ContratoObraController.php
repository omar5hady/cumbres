<?php

namespace App\Http\Controllers\Contrato;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\DropboxFiles;
use Spatie\Dropbox\Client;
use Illuminate\Support\Facades\Storage;

use App\Ini_obra;
use App\SpFile;
use Carbon\Carbon;

use DB;
use Auth;

class ContratoObraController extends Controller
{
    public function __construct()
    {
        // Necesitamos obtener una instancia de la clase Client la cual tiene algunos métodos
        // que serán necesarios.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
    }

    public function store(Request $request){
        $url = $this->storeFile($request);

        $contrato = Ini_obra::findOrFail($request->id);
        if($request->contratista == 1)
            $contrato->acuse_contratista = $url;
        else
            $contrato->acuse_cierre = $url;

        if($contrato->acuse_contratista != '' && $contrato->acuse_contratista != NULL
            && $contrato->acuse_cierre != '' && $contrato->acuse_cierre != NULL
        )
            $contrato->status = 2;
        $contrato->save();
    }
    // private function deleteDropBoxFile($carpeta,$id){
    //     // Eliminamos el registro de nuestra tabla.
    //     $del = DropboxFiles::findOrFail($id);
    //     $this->dropbox->delete('SolicPagos/Adjuntos/'.$del->name);
    //     $del->delete();
    // }

    private function storeFile(Request $request){

        $carpeta = 'Obra/Cierres/';
        $name = uniqid() . '' . $request->pdf->getClientOriginalName();
        // Guardamos el archivo indicando el driver y el método putFileAs el cual recibe
        // el directorio donde será almacenado, el archivo y el nombre.
        // ¡No olvides validar todos estos datos antes de guardar el archivo!
        Storage::disk('dropbox')->putFileAs(
            $carpeta,
            $request->pdf,
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
        $archivo->extension = $request->pdf->getClientOriginalExtension();
        $archivo->size = $response['size'];
        $archivo->public_url = $response['url'];
        $archivo->save();

        return $archivo->public_url;

    }
}
