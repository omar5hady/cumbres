<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Proveedor;
use App\Personal;
use App\Cliente;
use App\User;
use App\ProvCuenta;
use Auth;

use App\DropboxFiles;
use Spatie\Dropbox\Client;
use Illuminate\Support\Facades\Storage;

class ProveedorController extends Controller
{

    public function __construct()
    {
        // Necesitamos obtener una instancia de la clase Client la cual tiene algunos métodos
        // que serán necesarios.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
    }

     // Función para subir archivo entrega para ventas.
     public function submitProveedorConst(Request $request){
        $id = $request->id;
        $proveedor = Proveedor::findOrFail($id);
        $url = $this->storeFile($request);

            $this->deleteAnt($proveedor->const_fisc);
            $proveedor->const_fisc = $url;

        $proveedor->save();
    }

    private function deleteAnt($urlAnt){
        $file = DropboxFiles::select('id')->where('public_url','=',$urlAnt)->get();
        if(sizeof($file)){
            // Eliminamos el registro de nuestra tabla.
            $del = DropboxFiles::findOrFail($file[0]->id);
            $this->dropbox->delete('Proveedor/Constancia/'.$del->name);
            $del->delete();
        }
    }

    private function storeFile(Request $request){

        $carpeta = 'Proveedor/Constancia/';
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

        return $archivo->public_url;
    }

    // obtiene informacion de la tabla  de proveedores
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;


        $proveedores = Proveedor::select('id','proveedor', 'contacto',
                    'direccion', 'colonia', 'num_cuenta', 'banco', 'clabe', 'const_fisc',
                    'telefono', 'email', 'email2', 'poliza','tipo');

        if($buscar != '') $proveedores = $proveedores->where($criterio, 'like', '%'. $buscar . '%');


        $proveedores = $proveedores->orderBy('proveedor','asc')
                    ->paginate(20);

        foreach($proveedores as $prov){
            $prov->cuentas = ProvCuenta::where('proveedor_id','=',$prov->id)->get();
        }

        return [
            'pagination' => [
                'total'         => $proveedores->total(),
                'current_page'  => $proveedores->currentPage(),
                'per_page'      => $proveedores->perPage(),
                'last_page'     => $proveedores->lastPage(),
                'from'          => $proveedores->firstItem(),
                'to'            => $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores
        ];

    }

    public function getCuentasProv(Request $request){
        return ProvCuenta::where('proveedor_id','=',$request->id)->get();
    }

    public function storeCuenta(Request $request){
        $cuenta = new ProvCuenta();
        $cuenta->num_cuenta = $request->num_cuenta;
        $cuenta->banco = $request->banco;
        $cuenta->clabe = $request->clabe;
        $cuenta->proveedor_id = $request->proveedor_id;
        $cuenta->save();
    }

    public function deleteCuenta(Request $request){
        $cuenta = ProvCuenta::findOrFail($request->id);
        $cuenta->delete();
    }

    // crea un nuevo registro en las tablas de personal y en la tabla de proveedor
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();

            $persona = new Personal();
            $persona->departamento_id = 9;
            $persona->nombre = $request->proveedor;
            $persona->apellidos = '.';
            $persona->f_nacimiento = '2019-01-01';
            $persona->rfc = $request->rfc;
            $persona->direccion = $request->direccion;
            $persona->colonia = $request->colonia;
            $persona->telefono = $request->telefono;
            $persona->celular = $request->telefono;
            $persona->email = $request->email;
            $persona->activo = 1;
            $persona->empresa_id = 1;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->id = $persona->id;
            $proveedor->proveedor =$request->proveedor;
            $proveedor->contacto =$request->contacto;
            $proveedor->direccion =$request->direccion;
            $proveedor->colonia=$request->colonia;
            $proveedor->telefono =$request->telefono;
            $proveedor->email =$request->email;
            $proveedor->email2 =$request->email2;
            $proveedor->poliza=$request->poliza;
            $proveedor->tipo=$request->tipo;
            $proveedor->num_cuenta = $request->num_cuenta;
            $proveedor->clabe = $request->clabe;
            $proveedor->banco = $request->banco;
            $proveedor->save();

            if($request->num_cuenta != '' &&  $request->banco != ''){
                $cuenta = new ProvCuenta();
                $cuenta->num_cuenta = $request->num_cuenta;
                $cuenta->banco = $request->banco;
                $cuenta->clabe = $request->clabe;
                $cuenta->proveedor_id = $proveedor->id;
                $cuenta->save();
            }

            if($request->usuario != ''){
                $user = new User();
                $user->id = $persona->id;
                $user->usuario = $request->usuario;
                $user->password = bcrypt( $request->password);
                $user->condicion = '1';
                $user->rol_id = 10;

                $user->id = $persona->id;
                $user->save();
            }
            DB::commit();

        } catch (Exception $e){  // en caso de que haya algun conflicto en crear los registros , no se crea y se regresa a un estado inicial
            DB::rollBack();
        }
    }

    // atualiza solo los datos de la tabla del proveedor
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->proveedor =$request->proveedor;
        $proveedor->contacto =$request->contacto;
        $proveedor->direccion =$request->direccion;
        $proveedor->colonia=$request->colonia;
        $proveedor->telefono =$request->telefono;
        $proveedor->email =$request->email;
        $proveedor->email2 =$request->email2;
        $proveedor->poliza=$request->poliza;
        $proveedor->tipo=$request->tipo;
        $proveedor->num_cuenta = $request->num_cuenta;
        $proveedor->banco = $request->banco;
        $proveedor->clabe = $request->clabe;
        $proveedor->save();

        $personal = Personal::findOrFail($request->id);
        $personal->nombre = $request->proveedor;
        $personal->apellidos = '';
        $personal->save();
    }

    public function selectProveedor(Request $request){
        $proveedor = Proveedor::select('id','proveedor');
        if($request->constancia){
            $proveedor = $proveedor->where('const_fisc','!=',NULL);
            $proveedor = $proveedor->take(150)->get();

            return ['proveedor' => $proveedor];
        }
        else{
            $usuarios = User::join('personal','users.id','=','personal.id')
                ->select('users.id', DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS proveedor"))
                ->where('users.condicion','=',1)
                ->where('users.rol_id','!=',10)
                ->whereNotIn('users.usuario',['yasmin_ventas', 'ALEJANDROT','descartado', 'oficina', 'e_preciado', 'may_jaz']);
            $clientes = Cliente::join('personal','clientes.id','=','personal.id')
                    ->select('clientes.id', DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS proveedor"))
                    ->where('clientes.clasificacion','!=',7);

            if($request->proveedor != ''){
                $proveedor = $proveedor->where('proveedor','like','%'.$request->proveedor.'%')
                ->limit(10);

                $usuarios = $usuarios->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->proveedor . '%')
                ->limit(10);

                $clientes = $clientes->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"),'like','%'.$request->proveedor.'%')
                ->limit(10);
            }

            $proveedor = $proveedor->take(50)->get();
            $usuarios = $usuarios->get();
            $clientes = $clientes->get();


            return [
                        'proveedor' => $proveedor,
                        'usuarios' => $usuarios,
                        'clientes' => $clientes,
                    ];
        }
    }
}
