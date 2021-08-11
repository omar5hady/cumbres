<?php

namespace App\Http\Controllers;
 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DropboxFiles;
use Spatie\Dropbox\Client;
use App\Solic_detalle;
use App\Doc_puente;
use App\Credito_puente;
use Auth;

use App\Http\Controllers\CreditoPuenteController;

 
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
 
    public function store(Request $request,$id,$sub)
    {
        if(Auth::user()->rol_id == 11)return redirect('/');
        $carpeta = $sub . '/';
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
        DropboxFiles::create([
            'name' => $response['name'],
            'extension' => $request->file->getClientOriginalExtension(),
            'size' => $response['size'],
            'public_url' => $response['url']
        ]);

        switch($sub){
            case 'solicitudDetalle':{
                $solicitud = Solic_detalle::findOrFail($id);
                $solicitud->nom_contrato = $name;
                $solicitud->save();
                break;
            }
            case 'planosPuente':{
                $puente = new Doc_puente();
                $puente->puente_id = $id;
                $puente->descripcion = $request->descripcion;
                $puente->archivo = $name;
                $puente->clasificacion = $request->clasificacion;
                $puente->save();
                break;
            }
            case 'siembraPuente':{
                $puente = Credito_puente::findOrFail($request->id);
                $puente->archivo_siembra = $name;
                $puente->save();

                $obs = new CreditoPuenteController();
                $obs->nuevaObservacion($request->id, 'Se ha cargado el archivo de siembra');
                break;
            }
        }
        // Retornamos un redirección hacía atras
        //return back();
    }
 
    public function download($carpeta,$file)
    {
        // Retornamos una descarga especificando el driver dropbox
        // e indicándole al método download el nombre del archivo.
        return Storage::disk('dropbox')->download($carpeta.'/'.$file);
    }
 
    public function destroy(Request $request)
    {
        if(Auth::user()->rol_id == 11)return redirect('/');
        // Eliminamos el archivo en dropbox llamando a la clase
        // instanciada en la propiedad dropbox.
        $this->dropbox->delete($request->sub.'/'.$request->file);
        // Eliminamos el registro de nuestra tabla.
        $archivo = DropboxFiles::select('id')->where('name','=',$request->file)->get();
        $del = DropboxFiles::findOrFail($archivo[0]->id);
        $del->delete();

        switch($request->sub){
            case 'solicitudDetalle':{
                $solicitud = Solic_detalle::findOrFail($request->id);
                $solicitud->nom_contrato = NULL;
                $solicitud->save();
                break;
            }
            case 'planosPuente':{
                $doc_puente = Doc_puente::findOrFail($request->id);
                $doc_puente->delete();
                $break;
            }
            case 'siembraPuente':{
                $puente = Credito_puente::findOrFail($request->id);
                $puente->archivo_siembra = NULL;
                $puente->save();
                break;
            }
        }  
 
    }
}
