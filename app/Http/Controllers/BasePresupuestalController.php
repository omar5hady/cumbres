<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Base_presupuestal;
use App\Modelo;
use File;
use Excel;

class BasePresupuestalController extends Controller
{
    public function storeBases(Request $request){

        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
    
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                    $reader->ignoreEmpty();
                })->get();

                //return $request->fraccionamiento;
    
                $this->createBase($data,$request->fraccionamiento,$request->credito);
    
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }

    }

    private function createBase($data,$fraccionamiento,$credito){

        foreach($data as $index => $pagina){

            $namePagina = $pagina->getTitle();
            echo($namePagina);

            $modelo = Modelo::select('id','fraccionamiento_id','nombre')->where('fraccionamiento_id','=',$fraccionamiento)->where('nombre','like','%'.$namePagina.'%')->get();
            echo($modelo);
            if(sizeof($modelo)){
                $modelo_id = $modelo[0]->id;
                $baseAnt = Base_presupuestal::select('id')->where('modelo_id','=',$modelo_id)->get();
                /// SE ELIMINA REGISTRO ANTERIOR
                if(sizeOf($baseAnt)){
                    $bAnt = Base_presupuestal::findOrFail($baseAnt[0]->id);
                    $bAnt->activo = 0;
                    $bAnt->save();
                }

                $newBase = new Base_presupuestal();
                    $newBase->modelo_id = $modelo_id;
                    if($credito != '')
                        $newBase->credito_id = $credito;
                    $newBase->activo = 1;
                    $newBase->valor_venta = $pagina[12]['d'];
                        //// COMISIONES BANCARIAS :
                            $newBase->insc_conjunto = $pagina[35]['d'];
                            $newBase->int_nafin = $pagina[36]['d'];
                            $newBase->gastos_esc = $pagina[37]['d'];
                            $newBase->comision_int = $pagina[38]['d'];
                            $newBase->int_cpuente = $pagina[39]['d'];
                            $newBase->int_pago_terreno = $pagina[40]['d'];
                        ///// BASES PRESUPUESTALES
                            $newBase->valor_terreno = $pagina[44]['d'];
                            $newBase->permisos = $pagina[45]['d'];
                            $newBase->presupuesto_urb = $pagina[46]['d'];
                            $newBase->presupuesto_edif = $pagina[47]['d'];
                            $newBase->equipamiento = $pagina[48]['d'];
                            $newBase->escritura_gcc = $pagina[49]['d'];
                            $newBase->laboratorio = $pagina[50]['d'];
                            $newBase->gastos_ind_op = $pagina[51]['d'];
                            $newBase->gastos_comerc = $pagina[52]['d'];
                            $newBase->comicion_venta = $pagina[53]['d'];
                            $newBase->fianzas = $pagina[54]['d'];
                            $newBase->partida_inflacionaria = $pagina[55]['d'];
                            $newBase->adicional_terreno = $pagina[56]['d'];
                        ///// INDICIES PRESUPUESTALES
                            $newBase->ct_urbanizado = $pagina[60]['d'];
                            $newBase->ct_brena = $pagina[61]['d'];
                            $newBase->c_pavimentacion = $pagina[62]['d'];
                            $newBase->c_edificacion1 = $pagina[63]['d'];
                            $newBase->c_edificacion2 = $pagina[64]['d'];
                            $newBase->precio_venta_const = $pagina[65]['d'];
                            $newBase->c_adquisicion_brena = $pagina[66]['d'];

                    $newBase->save();

            }
        }

    }

    public function getBaseActiva(Request $request){

        $bases = Base_presupuestal::join('modelos','bases_presupuestales.modelo_id','=','modelos.id')
        ->leftJoin('creditos_puente','bases_presupuestales.credito_id','=','creditos_puente.id')
                ->select('modelos.nombre','bases_presupuestales.*','creditos_puente.folio')
                ->where('modelos.fraccionamiento_id','=',$request->fraccionamiento);

                if($request->fecha == '')
                    $bases = $bases->where('bases_presupuestales.activo','=',1);
                else{
                    $bases = $bases->whereBetween('bases_presupuestales.created_at', [$request->fecha.' 00:00:00', $request->fecha.' 23:59:59']);
                }
                $bases = $bases->orderBy('modelos.nombre','asc')
                ->get();

        return ['bases'=>$bases];

    }
}


// /**
//  * SELECT p.nombre, p.apellidos, p.email FROM cliente` 
//     inner join personal as p on p.id = clientes.id 
//     inner JOIN creditos as c on c.prospecto_id = p.id
//     inner JOIN contratos as cont on cont.id = c.id
//     inner JOIN expedientes as ex on ex.id = cont.id
//     where ex.fecha_firma_esc is not null and cont.status = 3  
//     ORDER BY `p`.`nombre` ASC
//  */
