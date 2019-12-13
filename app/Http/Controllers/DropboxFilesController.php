<?php

namespace App\Http\Controllers;
 
use App\DropboxFiles;
use Spatie\Dropbox\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 
class DropboxFilesController extends Controller
{
    public function __construct()
    {
        // Necesitamos obtener una instancia de la clase Client la cual tiene algunos métodos
        // que serán necesarios.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();   
    }
 
    public function index()
    {
        // Obtenemos todos los registros de la tabla files
        // y retornamos la vista files con los datos.
        $files = DropboxFiles::orderBy('created_at', 'desc')->get();
        
        return view('files', compact('files'));
    }
 
    public function store(Request $request)
    {
        $name =  $request->file->getClientOriginalName();
        // Guardamos el archivo indicando el driver y el método putFileAs el cual recibe
        // el directorio donde será almacenado, el archivo y el nombre.
        // ¡No olvides validar todos estos datos antes de guardar el archivo!
        Storage::disk('dropbox')->putFileAs(
            'oso/', 
            $request->file, 
            $request->file->getClientOriginalName()
        );
 
        // Creamos el enlace publico en dropbox utilizando la propiedad dropbox
        // definida en el constructor de la clase y almacenamos la respuesta.
        $response = $this->dropbox->createSharedLinkWithSettings(
            'oso/'.$name, 
            ["requested_visibility" => "public"]
        );
 
        // Creamos un nuevo registro en la tabla files con los datos de la respuesta.
        DropboxFiles::create([
            'name' => $response['name'],
            'extension' => $request->file->getClientOriginalExtension(),
            'size' => $response['size'],
            'public_url' => $response['url']
        ]);
        
        // Retornamos un redirección hacía atras
        return back();
    }
 
    public function download(File $file)
    {
        // Retornamos una descarga especificando el driver dropbox
        // e indicándole al método download el nombre del archivo.
        return Storage::disk('dropbox')->download($file->name);
    }
 
    public function destroy(File $file)
    {
        // Eliminamos el archivo en dropbox llamando a la clase
        // instanciada en la propiedad dropbox.
        $this->dropbox->delete($file->name);
        // Eliminamos el registro de nuestra tabla.
        $file->delete();
 
        return back();
    }
}
