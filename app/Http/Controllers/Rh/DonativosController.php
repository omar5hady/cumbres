<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonativoResource;


use App\DonativoItem;
use App\HistDonativo;
use Auth;
use File;


class DonativosController extends Controller
{
    /**
     * Esta función devuelve una colección de elementos de DonativoResource en función de la solicitud
     * de entrada.
     *
     * @param Request request  es una instancia de la clase Request, que representa una
     * solicitud HTTP realizada a la aplicación. Contiene información sobre la solicitud, como el
     * método HTTP, los encabezados y cualquier dato que se haya enviado con la solicitud. En este
     * contexto, se utiliza para recuperar cualquier parámetro de consulta o filtro que
     *
     * @return La función `index` está devolviendo una colección de recursos `DonativoResource`. Los
     * recursos se obtienen llamando a la función `getItems` con el parámetro ``.
     */
    public function index(Request $request){
        return DonativoResource::collection(
                $this->getItems($request)
            );
    }

    /**
     * Esta función recupera una lista de DonativoItems basada en user_id y filtros de estado,
     * ordenados por fecha de creación y paginados.
     *
     * @param Request request  es una instancia de la clase Request que contiene los datos
     * enviados por el cliente en la solicitud HTTP. Puede contener información como datos de
     * formulario, parámetros de consulta y encabezados. En esta función, se utiliza para filtrar los
     * resultados de la consulta según los parámetros user_id y status enviados en
     *
     * @return una lista paginada de objetos DonativoItem filtrados por user_id y estado, y ordenados
     * por fecha de creación en orden descendente.
     */
    private function getItems(Request $request){

        $donativos = DonativoItem::select('*');

        if($request->user_id != '')
            $donativos = $donativos->where('user_id','=',Auth::user()->id);
        if($request->status != '')
            $donativos = $donativos->where('status','=',$request->status);
        if($request->item != '')
            $donativos = $donativos->where('titulo','like','%'.$request->item.'%');

        $donativos = $donativos->orderBy('created_at','desc')->paginate(10);

        return $donativos;

    }

    /**
     * Esta función almacena un objeto DonativoItem nuevo o actualizado con datos de entrada del
     * usuario y un archivo de imagen opcional.
     *
     * @param Request request  es una instancia de la clase Illuminate\Http\Request que
     * representa una solicitud HTTP. Contiene información sobre la solicitud, como el método HTTP, los
     * encabezados y los datos de entrada. En este caso, se utiliza para recuperar datos del envío de
     * un formulario.
     */
    public function store(Request $request){
        $file = $request->file;
        if($request->id == '')
            $donativo = new DonativoItem();
        else
            $donativo = DonativoItem::findOrFail($request->id);
        $donativo->user_id = Auth::user()->id;
        $donativo->titulo = $request->titulo;
        $donativo->descripcion = $request->descripcion;
        /* Este bloque de código verifica si el usuario ha seleccionado un archivo para cargar
        verificando si el valor de `->nom_archivo` no es igual a la cadena "Seleccione
        Archivo". Si se ha seleccionado un archivo, llama a la función `setImage` con el archivo
        cargado y el valor actual de `->imagen` (que puede estar vacío si es un
        `DonativoItem` nuevo). La función `setImage` guarda el archivo cargado en el servidor y
        devuelve el nombre del archivo, que luego se asigna a `->imagen`. Esto actualiza el
        campo `imagen` del `DonativoItem` con el nuevo archivo, o lo establece en el mismo valor si
        no se cargó ningún archivo nuevo. */
        if($request->nom_archivo != 'Seleccione Archivo')
            $donativo->picture = $this->setImage($request->file, $donativo->picture);
        $donativo->save();
    }

    /**
     * Esta función guarda un archivo de imagen en un directorio específico y devuelve el nombre del
     * archivo.
     *
     * @param file El archivo que debe guardarse en el servidor. Se pasa como argumento a la función.
     * @param ant El parámetro "ant" es una cadena que representa el nombre de un archivo previamente
     * almacenado. Se utiliza para verificar si ya hay un archivo almacenado para el registro actual y
     * eliminarlo antes de guardar el nuevo archivo. Si no hay un archivo almacenado anteriormente,
     * este parámetro debe ser una cadena vacía o NULL
     *
     * @return el nombre del archivo que se cargó y guardó en el servidor.
     */
    private function setImage($file, $ant = ''){
        // Se busca si el registro ya cuenta con una plantilla almacenada.

        // Si ya se encuentra registrado un archivo, se elimina el anterior.
        if($ant != '' && $ant != NULL){
            $pathAnterior = public_path().'/files/rh/items/'.$ant;
            File::delete($pathAnterior);
        }
        // Se guarda el archivo en el servidor
        $fileName = uniqid().'.'.$file->getClientOriginalExtension();
        $moved =  $file->move(public_path('/files/rh/items/'), $fileName);

        // Se verifica que se guardo correctamente el servidor.
        if($moved){
            //Retorna el nombre del archivo
            return $fileName;
        }
    }

    /**
     * La función elimina un objeto DonativoItem y su archivo de imagen asociado, si existe.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que contiene
     * la información de la solicitud HTTP enviada al servidor. Se puede utilizar para recuperar datos
     * de entrada, encabezados, cookies y otra información relacionada con la solicitud. En este caso,
     * se utiliza para recuperar el ID del DonativoItem
     */
    public function destroy(Request $request){
        $donativo = DonativoItem::findOrFail($request->id);

        /* Este bloque de código está comprobando si el atributo `imagen` del objeto `DonativoItem` no
        es nulo. Si no es nulo, significa que hay un archivo asociado con el objeto que debe
        eliminarse. Luego, el código construye la ruta al archivo usando la función `public_path()`
        y el directorio `'/files/rh/items/'`, y elimina el archivo usando la función
        `File::delete()`. Esto garantiza que todos los archivos asociados se eliminen cuando se
        elimine el objeto `DonativoItem`. */
        if($donativo->picture != NULL){
            $pathAnterior = public_path().'/files/rh/items/'.$donativo->picture;
            File::delete($pathAnterior);
        }

        $donativo->delete();
    }
}
