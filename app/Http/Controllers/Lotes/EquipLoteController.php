<?php

namespace App\Http\Controllers\Lotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EquipLote;
use App\ObsEquipLote;
use App\RevEquipLote;
use App\Personal;
use Carbon\Carbon;

use App\DropboxFiles;
use Spatie\Dropbox\Client;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;

class EquipLoteController extends Controller
{
    public function __construct()
    {
        // Necesitamos obtener una instancia de la clase Client la cual tiene algunos métodos
        // que serán necesarios.
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
    }

    public function index(Request $request){
        $solicitudes = EquipLote::join('lotes as l','equip_lotes.lote_id','=','l.id')
                ->join('fraccionamientos as p','l.fraccionamiento_id','=','p.id')
                ->join('etapas as e','l.etapa_id','=','e.id')
                ->join('modelos as m','l.modelo_id','=','m.id')
                ->join('equipamientos as eq','equip_lotes.equipamiento_id','=','eq.id')
                ->join('proveedores as pro','eq.proveedor_id','=','pro.id')
                ->select('equip_lotes.*',
                    'p.nombre as proyecto', 'e.num_etapa as etapa', 'm.nombre as modelo',
                    'l.num_lote', 'l.sublote','l.manzana', 'eq.equipamiento','eq.proveedor_id',
                    'eq.tipoRecepcion', 'pro.proveedor',
                    DB::raw('DATEDIFF(current_date,equip_lotes.fecha_anticipo) as diferenciaIni'),
                    DB::raw('DATEDIFF(equip_lotes.fin_instalacion,equip_lotes.fecha_anticipo) as diferenciaFin')
                );
                if($request->b_proyecto != '')
                    $solicitudes = $solicitudes->where('l.fraccionamiento_id','=',$request->b_proyecto);
                if($request->b_etapa != '')
                    $solicitudes = $solicitudes->where('l.etapa_id','=',$request->b_etapa);
                if($request->b_modelo != '')
                    $solicitudes = $solicitudes->where('l.modelo_id','=',$request->b_modelo);
                if($request->b_manzana != '')
                    $solicitudes = $solicitudes->where('l.manzana','=',$request->b_manzana);
                if($request->b_lote != '')
                    $solicitudes = $solicitudes->where('l.num_lote','=',$request->b_lote);
                if($request->b_status != '')
                    $solicitudes = $solicitudes->where('equip_lotes.status','=',$request->b_status);
                if($request->b_empresa != '')
                    $solicitudes = $solicitudes->where('l.emp_constructora','=',$request->b_empresa);

                if(Auth::user()->rol_id == 10)
                    $solicitudes = $solicitudes->where('eq.proveedor_id','=',Auth::user()->id);

        $solicitudes = $solicitudes->paginate(15);

        foreach($solicitudes as $s)
            $s->obs = ObsEquipLote::where('solicitud_id','=',$s->id)->orderBy('created_at','desc')->get();

        return $solicitudes;
    }

    public function store(Request $request){
        $datos = $request->datosSolicitud;
        $equipamiento = new EquipLote();
        $equipamiento->lote_id = $datos['lote_id'];
        $equipamiento->equipamiento_id = $datos['equipamiento_id'];
        $equipamiento->fecha_solicitud = new Carbon();
        $equipamiento->save();

        $this->guardarObs( $equipamiento->id, 'Solicitud creada' );
    }

    public function storeObservacion(Request $request){
        $this->guardarObs( $request->solicitud_id, $request->observacion );
    }

    private function guardarObs($solicitud_id, $observacion){
        $obs = new ObsEquipLote();
        $obs->solicitud_id = $solicitud_id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function update(Request $request){
        $solicitud = $request->solicitud;
        switch($request->accion){
            case 'updateCosto':{
                $this->guardarObs( $solicitud['id'], 'Se ha registrado el costo del equipamiento');
                break;
            }
            case 'updateAnticipo':{
                $this->guardarObs( $solicitud['id'], 'Se ha registrado el anticipo por' .$solicitud['anticipo'] );
                break;
            }
            case 'updateColocacion':{
                $solicitud['status'] = 2;
                break;
            }
            case 'updateFinInst':{
                $solicitud['status'] = 3;
                $this->guardarObs( $solicitud['id'], 'Se ha terminado la instalación del equipamiento');
                break;
            }
            case 'updateRevision':{
                $supervisor = Personal::select('nombre','apellidos')->where('id','=',Auth::user()->id)->first();
                $revision = $request->revision;
                $solicitud['status'] = 4;
                $solicitud['obs_recep'] = $revision['obs'];
                $solicitud['fecha_revision'] = new Carbon();
                $solicitud['supervisor'] = $supervisor->nombre.' '.$supervisor->apellidos;

                $this->storeRevision($solicitud['id'], $solicitud['tipoRecepcion'], $revision);
                //$this->guardarObs( $solicitud['id'], 'Recepción de equipamiento completada');
                break;
            }
            case 'updateLiquidacion':{
                $solicitud['status'] = 5;
                $this->guardarObs( $solicitud['id'], 'La solicitud ha sido liquidada');
                break;
            }
        }

        $this->updateInformacion($solicitud);
        if($request->observacion != '')
            $this->guardarObs( $solicitud['id'], $request->observacion );

    }

    private function storeRevision($solicitud_id, $tipo, $revision){
        switch($tipo){
            case '1':{
                $this->storeRevCocina($solicitud_id,$revision);
                break;
            }
            case '2':{
                $this->storeRevClosets($solicitud_id,$revision);
                break;
            }
        }
    }

    private function storeRevCocina($solicitud_id,$revision){
        foreach($revision['acabados'] as $acabado){
            foreach($acabado['info'] as $rev){
               $this->storeRev($solicitud_id, $rev);
            }
        }
        foreach($revision['revisiones'] as $acabado){
            foreach($acabado['info'] as $rev){
               $this->storeRev($solicitud_id, $rev);
            }
        }
        foreach($revision['otros'] as $acabado){
            foreach($acabado['info'] as $rev){
               $this->storeRev($solicitud_id, $rev);
            }
        }
    }

    private function storeRevClosets($solicitud_id,$revision){
        foreach($revision['acabados'] as $acabado){
            foreach($acabado['info'] as $rev){
               $this->storeRev($solicitud_id, $rev);
            }
        }
        foreach($revision['interiores'] as $acabado){
            foreach($acabado['info'] as $rev){
               $this->storeRev($solicitud_id, $rev);
            }
        }
        foreach($revision['otros'] as $acabado){
            foreach($acabado['info'] as $rev){
               $this->storeRev($solicitud_id, $rev);
            }
        }
    }

    private function storeRev($solicitud_id, $revision){
        $rev = new RevEquipLote();
        $rev->solicitud_id = $solicitud_id;
        $rev->categoria = $revision['categoria'];
        $rev->subcategoria = $revision['subcategoria'];
        $rev->concepto = $revision['concepto'];
        $rev->check1 = $revision['check1'];
        $rev->check2 = $revision['check2'];
        $rev->check3 = $revision['check3'];
        $rev->save();
    }

    private function updateInformacion($solicitud){

        $equipamiento = EquipLote::findOrFail($solicitud['id']);
        $equipamiento->costo                = $solicitud['costo'];
        $equipamiento->fecha_colocacion     = $solicitud['fecha_colocacion'];
        $equipamiento->status               = $solicitud['status'];
        $equipamiento->anticipo             = $solicitud['anticipo'];
        $equipamiento->fecha_anticipo       = $solicitud['fecha_anticipo'];
        $equipamiento->liquidacion          = $solicitud['liquidacion'];
        $equipamiento->fecha_liquidacion    = $solicitud['fecha_liquidacion'];
        $equipamiento->fin_instalacion      = $solicitud['fin_instalacion'];
        $equipamiento->num_factura          = $solicitud['num_factura'];
        $equipamiento->control              = $solicitud['control'];
        $equipamiento->recepcion            = $solicitud['recepcion'];
        $equipamiento->anticipo_cand        = $solicitud['anticipo_cand'];
        $equipamiento->liquidacion_cand     = $solicitud['liquidacion_cand'];
        $equipamiento->comp_pago_1          = $solicitud['comp_pago_1'];
        $equipamiento->comp_pago_2          = $solicitud['comp_pago_2'];

        $equipamiento->obs_recep            = $solicitud['obs_recep'];
        $equipamiento->fecha_revision       = $solicitud['fecha_revision'];
        $equipamiento->supervisor           = $solicitud['supervisor'];
        $equipamiento->save();
    }

    public function printRecepcion(Request $request){
        $lote = EquipLote::join('lotes as l','equip_lotes.lote_id','=','l.id')
                    ->join('fraccionamientos as p','l.fraccionamiento_id','=','p.id')
                    ->join('etapas as e','l.etapa_id','=','e.id')
                    ->join('modelos as m','l.modelo_id','=','m.id')
                    ->join('equipamientos as eq','equip_lotes.equipamiento_id','=','eq.id')
                    ->join('proveedores as pro','eq.proveedor_id','=','pro.id')
                    ->select('l.num_lote','l.manzana','l.sublote','m.nombre as modelo',
                        'l.emp_constructora', 'p.nombre as proyecto', 'e.num_etapa as etapa',
                        'pro.proveedor', 'l.casa_muestra', 'equip_lotes.fecha_revision','equip_lotes.obs_recep',
                        'equip_lotes.supervisor','equip_lotes.id', 'eq.tipoRecepcion', 'eq.equipamiento'
                    )
                    ->where('equip_lotes.id','=',$request->id)->first();

        if($lote->tipoRecepcion != 0){
            $lote->revision = $this->getCategorias($lote->id);
                if(sizeof($lote->revision))
                    foreach($lote->revision as $categoria){
                        $categoria->subcategoria = $this->getSubCategoria($lote->id,$categoria->categoria);
                        foreach($categoria->subcategoria as $sub){
                            $sub->concepto = $this->getDetalle($lote->id,$categoria->categoria, $sub->subcategoria);
                        }
                    }
        }

        //return $lote;
        $pdf = \PDF::loadview('pdf.Docs.Equipamiento.RecepcionEquip', ['revision' => $lote]);
        //$pdf->setPaper('A4','landscape');
        return $pdf->stream('RecepcionEquipamiento.pdf');

    }

    private function getCategorias($solicitud_id){
        return RevEquipLote::select('categoria')->where('solicitud_id','=',$solicitud_id)->distinct()->get();
    }

    private function getSubCategoria($solicitud_id, $categoria){
        return RevEquipLote::select('subcategoria')->where('solicitud_id','=',$solicitud_id)
                ->where('categoria','=',$categoria)
                ->distinct()->get();
    }

    private function getDetalle($solicitud_id, $categoria, $subcategoria){
        return RevEquipLote::select('concepto','check1','check2','check3')
                ->where('solicitud_id','=',$solicitud_id)
                ->where('categoria','=',$categoria)
                ->where('subcategoria','=',$subcategoria)
                ->get();
    }

    public function fileSubmit(Request $request){
        $id = $request->id;
        $equipamiento = EquipLote::findOrFail($id);
        $url = $this->storeFile($request);

        if($request->tipo == 1){
            $this->deleteAnt($equipamiento->comp_pago_1);
            $equipamiento->comp_pago_1 = $url;
        }
        if($request->tipo == 2){
            $this->deleteAnt($equipamiento->comp_pago_2);
            $equipamiento->comp_pago_2 = $url;
        }
        $equipamiento->save();
    }

    private function deleteAnt($urlAnt){
        $file = DropboxFiles::select('id')->where('public_url','=',$urlAnt)->get();
        if(sizeof($file)){
            // Eliminamos el registro de nuestra tabla.
            $del = DropboxFiles::findOrFail($file[0]->id);
            $this->dropbox->delete('Equipamiento/'.$del->name);
            $del->delete();
        }
    }

    private function storeFile(Request $request){

        $carpeta = 'Equipamiento/';
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
}
