<?php

namespace App\Http\Controllers\Modelo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Modelo;
use App\CatEquipamiento;
use App\CotEquipamiento;
use App\Lote_promocion;
use App\Cliente;
use App\Personal;
use Auth;
use Excel;
use Carbon\Carbon;

class EquipamientoController extends Controller
{
    public function store(Request $request){
        $search = CatEquipamiento::where('modelo_id','=',$request->modelo_id)
            ->where('status','=',1)->get();

        if(sizeof($search)>0){
            foreach($search as $ant){
                $e_ant = CatEquipamiento::findOrFail($ant->id);
                $e_ant->status = 0;
                $e_ant->save();
            }
        }

        $e = new CatEquipamiento();
        $e->modelo_id = $request->modelo_id;
        $e->cocina_tradicional = $request->cocina_tradicional;
        $e->vestidor = $request->vestidor;
        $e->closets = $request->closets;
        $e->canceles = $request->canceles;
        $e->persianas = $request->persianas;
        $e->calentador_paso = $request->calentador_paso;
        $e->calentador_solar = $request->calentador_solar;
        $e->espejos = $request->espejos;
        $e->tanque_estacionario = $request->tanque_estacionario;
        $e->cocina = $request->cocina;
        $e->usuario = Auth::user()->usuario;
        $e->save();
    }

    public function update(Request $request){
        $search = CatEquipamiento::where('modelo_id','=',$request->modelo_id)
            ->where('status','=',1)->get();

        if(sizeof($search)>0){
            foreach($search as $ant){
                $e_ant = CatEquipamiento::findOrFail($ant->id);
                $e_ant->status = 0;
                $e_ant->save();
            }
        }

        $e = new CatEquipamiento();
        $e->modelo_id = $request->modelo_id;
        $e->cocina_tradicional = $request->cocina_tradicional;
        $e->vestidor = $request->vestidor;
        $e->closets = $request->closets;
        $e->canceles = $request->canceles;
        $e->persianas = $request->persianas;
        $e->calentador_paso = $request->calentador_paso;
        $e->calentador_solar = $request->calentador_solar;
        $e->espejos = $request->espejos;
        $e->tanque_estacionario = $request->tanque_estacionario;
        $e->cocina = $request->cocina;
        $e->usuario = Auth::user()->usuario;
        $e->save();
    }

    private function getCatalogo(Request $request){
        $catalogo = CatEquipamiento::join('modelos as m','cat_equipamientos.modelo_id','=','m.id')
            ->join('fraccionamientos as f','m.fraccionamiento_id','=','f.id')
            ->select('f.nombre as proyecto', 'f.id as proyecto_id', 'm.nombre as modelo','cat_equipamientos.*')
            ->where('cat_equipamientos.status','=',$request->b_status);
            if($request->b_modelo != '')
                $catalogo = $catalogo->where('cat_equipamientos.modelo_id','=',$request->b_modelo);
            if($request->b_proyecto != '')
                $catalogo = $catalogo->where('f.id','=',$request->b_proyecto);
            if($request->b_fecha1 != '')
                $catalogo = $catalogo->whereBetween('cat_equipamientos.created_at', [$request->b_fecha1, $request->b_fecha2]);

        return $catalogo;
    }

    public function index(Request $request){
        $catalogo = $this->getCatalogo($request);

        $catalogo = $catalogo->paginate(8);
        return $catalogo;
    }

    public function excelCatalogo(Request $request){

        $catalogo = $this->getCatalogo($request);
        $catalogo = $catalogo->get();

        // Creación y retorno de excel.
        return Excel::create('Equipamientos', function($excel) use ($catalogo){
                $excel->sheet('Equipamientos', function($sheet) use ($catalogo){

                    $sheet->row(1, [
                        'Proyecto', 'Modelo','Cocina Tradicional','Vestidor','Closets',
                        'Cocina C.M.','Canceles', 'Persianas', 'Calentador Solar',
                        'Espejos', 'Tanque Estacionario',
                        'Fecha de alta'
                    ]);

                    $sheet->cells('A1:L1', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(13);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
                    $cont=1;

                    $sheet->setColumnFormat(array(
                        'C' => '$#,##0.00',
                        'D' => '$#,##0.00',
                        'E' => '$#,##0.00',
                        'F' => '$#,##0.00',
                        'G' => '$#,##0.00',
                        'H' => '$#,##0.00',
                        'I' => '$#,##0.00',
                        'J' => '$#,##0.00',
                        'K' => '$#,##0.00',
                    ));

                    foreach($catalogo as $index => $e) {
                        $cont++;

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha_firma_esc = new Carbon($e->created_at);

                        $sheet->row($index+2, [
                            $e->proyecto,
                            $e->modelo,
                            $e->cocina_tradicional,
                            $e->vestidor,
                            $e->closets,
                            $e->cocina,
                            $e->canceles,
                            $e->persianas,
                            $e->calentador_solar,
                            $e->espejos,
                            $e->tanque_estacionario,
                            $e->created_at,
                        ]);
                    }
                    $num='A1:L'.$cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
        )->download('xls');



    }

    public function createCotizacion(Request $request){
        $cliente = $request->cliente;
        $equipamiento = $request->equipamiento;

        // return $equipamiento;
        try{
            DB::beginTransaction();

            $cliente_id = $this->createCliente($cliente);

            $cotizacion = new CotEquipamiento();
            $cotizacion->lote_id = $equipamiento['lote_id'];
            $cotizacion->precio_venta = $equipamiento['precio_venta'];
            $cotizacion->cliente_id = $cliente_id;
            $cotizacion->cocina_tradicional = $equipamiento['cocina_tradicional'];
            $cotizacion->vestidor = $equipamiento['vestidor'];
            $cotizacion->closets = $equipamiento['closets'];
            $cotizacion->canceles = $equipamiento['canceles'];
            $cotizacion->persianas = $equipamiento['persianas'];
            $cotizacion->calentador_paso = $equipamiento['calentador_paso'];
            $cotizacion->calentador_solar = $equipamiento['calentador_solar'];
            $cotizacion->espejos = $equipamiento['espejos'];
            $cotizacion->tanque_estacionario = $equipamiento['tanque_estacionario'];
            $cotizacion->cocina = $equipamiento['cocina'];
            $cotizacion->usuario = Auth::user()->id;
            $cotizacion->save();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

        return $cotizacion->id;

    }

    private function createCliente($cliente){

        $vendedor_id = $cliente['vendedor_id'];
        if($vendedor_id == ''){
            if(Auth::user()->rol_id == 2)
                $vendedor_id = Auth::user()->id;
            else $vendedor_id = 19;
        }

        if($cliente['id'] == ''){
            $p = new Personal();
            $c = new Cliente();
        }
        else{
            $p = Personal::findOrFail($cliente['id']);
            $c = Cliente::findOrFail($cliente['id']);
        }

        $p->nombre = $cliente['nombre'];
        $p->apellidos = $cliente['apellidos'];
        $p->rfc = $cliente['rfc'];
        $p->celular = $cliente['celular'];
        $p->f_nacimiento = $cliente['f_nacimiento'];
        $p->email = $cliente['email'];
        $p->save();

        $c->id = $p->id;
        $c->sexo = $cliente['sexo'];
        $c->proyecto_interes_id = $cliente['proyecto_interes_id'];
        $c->publicidad_id = $cliente['publicidad_id'];
        $c->edo_civil = $cliente['edo_civil'];
        $c->vendedor_id = $vendedor_id;
        $c->save();


        return $cliente['id'];
    }

    public function indexCotizaciones(Request $request){

        $cotizaciones = $this->getCotizacion();
        $user_id = Auth::user()->id;

        if($request->b_cliente != '')
            $cotizaciones = $cotizaciones
                ->where(DB::raw("CONCAT(cliente.nombre,' ',cliente.apellidos)"), 'like', '%'. $request->b_cliente . '%');

        if($request->b_fecha1 != '')
            $cotizaciones = $cotizaciones
                ->whereBetween('cot_equipamientos.created_at',[$request->b_fecha1, $request->b_fecha2]);

        if(Auth::user()->rol_id == 2){
            $cotizaciones = $cotizaciones->where(function($query) use ($user_id){
                $query->where('cl.vendedor_id','=',$user_id);
                $query->orWhere('cot_equipamientos.usuario','=',$user_id);
            });
        }

        $cotizaciones = $cotizaciones->paginate(10);

        foreach($cotizaciones as $cotizacion){
            $cotizacion->total = $cotizacion->precio_venta + $cotizacion->cocina_tradicional
            +$cotizacion->cocina
            +$cotizacion->vestidor
            +$cotizacion->closets
            +$cotizacion->canceles
            +$cotizacion->persianas
            +$cotizacion->calentador_solar
            +$cotizacion->espejos
            +$cotizacion->tanque_estacionario;
        }

        return $cotizaciones;

    }

    private function getCotizacion(){

        $cotizacion = CotEquipamiento::join('lotes as l','l.id','=','cot_equipamientos.lote_id')
            ->join('modelos as m','m.id','=', 'l.modelo_id')
            ->join('etapas as e','e.id','=', 'l.etapa_id')
            ->join('fraccionamientos as f','f.id','=','l.fraccionamiento_id')
            ->join('personal as cliente','cliente.id','=', 'cot_equipamientos.cliente_id')
            ->join('clientes as cl','cl.id','=','cliente.id')
            ->join('personal as asesor','asesor.id','=','cl.vendedor_id')
            ->join('users','users.id','=','asesor.id')
            ->join('personal as creator','creator.id','=','cot_equipamientos.usuario')
            ->select('cot_equipamientos.*',
                DB::raw("CONCAT(cliente.nombre,' ',cliente.apellidos) AS cliente"),
                DB::raw("CONCAT(asesor.nombre,' ',asesor.apellidos) AS asesor"),
                DB::raw("CONCAT(creator.nombre,' ',creator.apellidos) AS creator"),
                'l.num_lote', 'l.sublote', 'l.manzana', 'l.terreno', 'l.construccion',
                'l.emp_constructora','l.emp_terreno', 'users.usuario',
                'e.tipo_proyecto as tipo',
                'm.nombre as modelo', 'f.nombre as proyecto', 'e.num_etapa as etapa'
            );


            return $cotizacion;

    }

    public function printCotizacion(Request $request){
        $cotizacion = $this->getCotizacion();

        $cotizacion = $cotizacion->where('cot_equipamientos.id','=',$request->id)->first();

        setlocale(LC_TIME, 'es_MX.utf8');
        $fecha = new Carbon($cotizacion->created_at);
        $cotizacion->f_created = $fecha->formatLocalized('%d de %B del %Y %H:%m');
        $cotizacion->modelo = strtoupper($cotizacion->modelo);
        $cotizacion->proyecto = strtoupper($cotizacion->proyecto);
        $cotizacion->etapa = strtoupper($cotizacion->etapa);
        $array = [
                array('titulo' => 'Cocina Tradicional', 'monto' => $cotizacion->cocina_tradicional),
                array('titulo' => 'Cocina (Casa Muestra)', 'monto' => $cotizacion->cocina),
                array('titulo' => 'Vestidor', 'monto' => $cotizacion->vestidor),
                array('titulo' => 'Closets', 'monto' => $cotizacion->closets),
                array('titulo' => 'Canceles', 'monto' => $cotizacion->canceles),
                array('titulo' => 'Persianas', 'monto' => $cotizacion->persianas),
                array('titulo' => 'Calentador Solar', 'monto' => $cotizacion->calentador_solar),
                array('titulo' => 'Espejos', 'monto' => $cotizacion->espejos),
                array('titulo' => 'Tanque Estacionario', 'monto' => $cotizacion->tanque_estacionario),
        ];

        $cotizacion->array = usort($array, function ($a, $b) {
            return $b['monto'] - $a['monto'];
        });

        $cotizacion->columnas = array_chunk($array, 4);

        $cotizacion->total = $cotizacion->precio_venta + $cotizacion->cocina_tradicional
        +$cotizacion->cocina
        +$cotizacion->vestidor
        +$cotizacion->closets
        +$cotizacion->canceles
        +$cotizacion->persianas
        +$cotizacion->calentador_solar
        +$cotizacion->espejos
        +$cotizacion->tanque_estacionario;

        $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
        ->select('promociones.nombre')
        ->where('lotes_promocion.lote_id','=',$cotizacion->lote_id)
        ->where('promociones.v_ini','<=',$cotizacion->created_at)
        ->where('promociones.v_fin','>=',$cotizacion->created_at)->get();

        if(sizeof($promocion))
            $cotizacion->promocion = $promocion[0]->nombre;
        else
            $cotizacion->promocion = '';

        // return $cotizacion;

        $pdf = \PDF::loadview('pdf.cotizador.cotizacionEquipamiento',['cotizacion' => $cotizacion]);
        return $pdf->stream('Cotizacion.pdf');
    }
}
