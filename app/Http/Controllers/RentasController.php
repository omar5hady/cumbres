<?php

namespace App\Http\Controllers;

use App\Http\Controllers\NotificacionesAvisosController;
use Illuminate\Http\Request;
use App\User;
use App\Lote;
use App\Licencia;
use App\Arrendador;
use App\Renta;
use App\Testigo_renta;
use App\Pago_renta;
use Carbon\Carbon;
use NumerosEnLetras;
use DB;
use File;

class RentasController extends Controller
{
    //Función para obtener los arrendadores dados de alta
    public function getArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        return Arrendador::orderBy('tipo','asc')->get();
    }
    public function cambiarStatusLote(Request $request){
        $lote = Lote::findOrFail($request->id);
        $lote->apartado = $request->status;
        $lote->save();
    }
    //Función para obtener los testigos dados de alta
    public function getTestigos(Request $request){
        if(!$request->ajax())return redirect('/');
        return Testigo_renta::orderBy('nombre','asc')->get();
    }
    //Función para actualizar la informacion de renta para un lote.
    public function updateDatosRenta(Request $request){
        if(!$request->ajax())return redirect('/');
        $lote = Lote::findOrFail($request->id);
        $lote->precio_renta = $request->precio_renta;
        $lote->comentarios = $request->comentarios;
        $lote->save();

        $licencia = Licencia::findOrFail($request->id);
        $licencia->duenio_id = $request->duenio_id;
        $licencia->save();
    }
    //Función para registrar un nuevo arrendador
    public function storeArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        $arrendador = new Arrendador();
        $arrendador->nombre = $request->nombre;
        $arrendador->tipo = $request->tipo;
        $arrendador->rfc = $request->rfc;
        $arrendador->direccion = $request->direccion;
        $arrendador->cp = $request->cp;
        $arrendador->colonia = $request->colonia;
        $arrendador->municipio = $request->municipio;
        $arrendador->estado = $request->estado;
        $arrendador->clabe = $request->clabe;
        $arrendador->cuenta = $request->cuenta;
        $arrendador->banco = $request->banco;
        $arrendador->save();
    }
    //Función para actualizar los datos de un arrendador.
    public function updateArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        $arrendador = Arrendador::findOrFail($request->id);
        $arrendador->nombre = $request->nombre;
        $arrendador->tipo = $request->tipo;
        $arrendador->rfc = $request->rfc;
        $arrendador->direccion = $request->direccion;
        $arrendador->cp = $request->cp;
        $arrendador->colonia = $request->colonia;
        $arrendador->municipio = $request->municipio;
        $arrendador->estado = $request->estado;
        $arrendador->clabe = $request->clabe;
        $arrendador->cuenta = $request->cuenta;
        $arrendador->banco = $request->banco;
        $arrendador->save();
    }
    //Función para registrar un nuevo arrendador
    public function storeTestigo(Request $request){
        if(!$request->ajax())return redirect('/');
        $testigo = new Testigo_renta();
        $testigo->nombre = $request->nombre;
        $testigo->save();
    }
    //Función para actualizar los datos de un arrendador.
    public function updateTestigo(Request $request){
        if(!$request->ajax())return redirect('/');
        $testigo = Testigo_renta::findOrFail($request->id);
        $testigo->nombre = $request->nombre;
        $testigo->save();
    }
    //Funcion para retornar los lotes con disponibilidad para renta
    public function getRentasDisponibles(Request $request){
        return $lote = Lote::select('id','lotes.calle','lotes.numero','lotes.interior')
                ->where('etapa_id','=',$request->etapa_id)
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->where('habilitado','=', 1)
                ->where('casa_renta','=', 1)
                ->where('contrato','=',0)
                ->orderBy('id','asc')
                ->get();
    }
    //Función que retorna la informacion de un lote en renta
    public function getDatosLoteRenta(Request $request){
        return $lote = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                        ->select('modelos.nombre as modelo','lotes.precio_renta')
                        ->where('lotes.id','=',$request->id)
                        ->first();
    }
    //Funcion para registrar una nueva renta
    public function storeRenta(Request $request){
        $datosRenta = $request->datosRenta;
        $pagos = $datosRenta['pagares'];

        try {
            DB::beginTransaction();
            //Registro para renta
            $renta = new Renta();
            $renta->lote_id = $datosRenta['lote_id'];
            //Arrendatario
            $renta->tipo_arrendatario = $datosRenta['tipo_arrendatario'];
            $renta->nombre_arrendatario = $datosRenta['nombre_arrendatario'];
            $renta->tel_arrendatario = $datosRenta['tel_arrendatario'];
            $renta->clv_lada_arr = $datosRenta['clv_lada_arr'];
            //Moral arrendatario
            $renta->representante_arrendatario = $datosRenta['representante_arrendatario'];
            $renta->dir_arrendatario = $datosRenta['dir_arrendatario'];
            $renta->cp_arrendatario = $datosRenta['cp_arrendatario'];
            $renta->col_arrendatario = $datosRenta['col_arrendatario'];
            $renta->estado_arrendatario = $datosRenta['estado_arrendatario'];
            $renta->municipio_arrendatario = $datosRenta['municipio_arrendatario'];
            $renta->rfc_arrendatario = $datosRenta['rfc_arrendatario'];
            //Aval (Fiador)
            $renta->tipo_aval = $datosRenta['tipo_aval'];
            $renta->nombre_aval = $datosRenta['nombre_aval'];
            $renta->tel_aval = $datosRenta['tel_aval'];
            $renta->clv_lada_aval = $datosRenta['clv_lada_aval'];
                //Moral aval
            $renta->representante_aval = $datosRenta['representante_aval'];
            $renta->dir_aval = $datosRenta['dir_aval'];
            $renta->cp_aval = $datosRenta['cp_aval'];
            $renta->col_aval = $datosRenta['col_aval'];
            $renta->estado_aval = $datosRenta['estado_aval'];
            $renta->municipio_aval = $datosRenta['municipio_aval'];
            //Testigo
            $renta->nombre = $datosRenta['nombre'];
            //Datos contrato
            $renta->monto_renta = $datosRenta['monto_renta'];
            $renta->fecha_ini = $datosRenta['fecha_ini'];
            $renta->fecha_fin = $datosRenta['fecha_fin'];
            $renta->num_meses = $datosRenta['num_meses'];
            //$renta->fecha_firma = $datosRenta['fecha_firma'];
            $renta->dep_garantia = $datosRenta['dep_garantia'];

            $renta->muebles = $datosRenta['muebles'];
            $renta->adendum = $datosRenta['adendum'];
            $renta->servicios = $datosRenta['servicios'];

            $renta->luz = $datosRenta['luz'];
            $renta->agua = $datosRenta['agua'];
            $renta->gas = $datosRenta['gas'];
            $renta->television = $datosRenta['television'];
            $renta->telefonia = $datosRenta['telefonia'];
            $renta->save();
            //Se accede al registro del lote para indicar que ha sido rentado
            $lote = Lote::findOrFail($datosRenta['lote_id']);
            $lote->contrato = 1;
            $lote->save();
            //Se crean los pagares de la renta
            foreach($pagos as $pago){
                //Llamada a la función privada que genera el pagare.
                $this->storePagare($renta->id,
                    $pago['num_pago'],
                    $pago['fecha'],
                    $pago['importe']
                );
            }
        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    //Función privada que registra un pagare para una renta
    private function storePagare($renta_id, $num_pago, $fecha, $importe){
        $pago = new Pago_renta();
        $pago->renta_id = $renta_id;
        $pago->num_pago = $num_pago;
        $pago->fecha = $fecha;
        $pago->importe = $importe;
        $pago->save();
    }
    //Funcion que retorna el listado de contratos de renta registrados
    public function indexRentas(Request $request){
        $rentas = Renta::join('lotes','rentas.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->select('rentas.id','rentas.num_meses','rentas.fecha_fin',
                        'rentas.status', 'rentas.monto_renta', 'rentas.nombre_arrendatario',
                        'lotes.calle', 'lotes.numero', 'lotes.interior', 'etapas.num_etapa as etapa',
                        'fraccionamientos.nombre as proyecto', 'modelos.nombre as modelo'
                    );
                //Filtros de busqueda
                if($request->b_proyecto != '')//Busqueda por proyecto
                    $rentas = $rentas->where('lotes.fraccionamiento_id','=',$request->b_proyecto);
                if($request->b_etapa != '')//Busqueda por etapa
                    $rentas = $rentas->where('lotes.etapa_id','=',$request->b_etapa);
                if($request->b_direccion != '')//Busqueda por dirección oficial
                    $rentas = $rentas->where(DB::raw("CONCAT(lotes.calle,' Num',lotes.numero)"), 'like', '%'. $request->b_direccion . '%');
                if($request->b_cliente != '')//Busqueda por arrendatarios
                    $rentas = $rentas->where('rentas.nombre_arrendatario','like','%'.$request->b_cliente.'%');   
                    
            $rentas = $rentas->orderBy('rentas.id','desc')->paginate(6);
        return $rentas;       
    }
    //Funcion que retorna los datos de un contrato
    public function getDatosRenta(Request $request){
        $renta = Renta::join('lotes','rentas.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->join('arrendadores','licencias.duenio_id','arrendadores.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->select('rentas.*',
                'lotes.fraccionamiento_id',
                'lotes.etapa_id',
                'lotes.calle', 'lotes.numero', 'lotes.interior',
                'fraccionamientos.nombre as proyecto',
                'fraccionamientos.cp as cp_proyecto',
                'fraccionamientos.delegacion',
                'fraccionamientos.ciudad as ciudad_proyecto',
                'fraccionamientos.estado as estado_proyecto',
                'etapas.num_etapa as etapa',
                'arrendadores.tipo as tipo_arrendador',
                'arrendadores.nombre as nombre_arrendador',
                'arrendadores.direccion as direccion_arrendador',
                'arrendadores.cp as cp_arrendador',
                'arrendadores.colonia as colonia_arrendador',
                'arrendadores.municipio as municipio_arrendador',
                'arrendadores.estado as estado_arrendador',
                'arrendadores.banco as banco_arrendador',
                'arrendadores.cuenta as cuenta_arrendador',
                'arrendadores.clabe as clabe_arrendador',
                'modelos.nombre as modelo')
                ->where('rentas.id','=',$request->id)
                ->first();
            //Obtención de los pagares ligados al contrato.
            $renta->pagares = Pago_renta::where('renta_id','=',$renta->id)->get();

        return $renta;
    }
    //Función para mostrar el contrato en PDF
    public function printContrato(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        //Llamada a la función que retorna los datos del contrato
        $contrato = $this->getDatosRenta($request);
        //Se obtiene el formato de numero en letra
        $contrato->cantMeses = NumerosEnLetras::convertir($contrato->num_meses, '', false, '');
        $contrato->monto_renta = NumerosEnLetras::convertir($contrato->monto_renta, 'Pesos', true, 'Centavos');
        //Formato de fecha segun necesidades en el contrato
        $fechaIni = new Carbon($contrato->fecha_ini);
        $contrato->fecha_ini = $fechaIni->formatLocalized('%d de %B de %Y');
        $fechaFin = new Carbon($contrato->fecha_fin);
        $contrato->fecha_fin = $fechaFin->formatLocalized('%d de %B de %Y');
        $fechaContrato = new Carbon($contrato->fecha_firma);
        $contrato->fecha_firma = $fechaContrato->formatLocalized('%d días de %B de %Y');

        $servicios = [];
        $sinServicios = [];

        if($contrato->servicios == 1){
            if($contrato->luz > 0){
                $contrato->luz = number_format((float)$contrato->luz, 2, '.', ',');
                array_push($servicios,'luz $'.$contrato->luz);
            }
            if($contrato->agua > 0){
                $contrato->agua = number_format((float)$contrato->agua, 2, '.', ',');
                array_push($servicios,'agua $'.$contrato->agua);
            }
            if($contrato->gas > 0){
                $contrato->gas = number_format((float)$contrato->gas, 2, '.', ',');
                array_push($servicios,'gas $'.$contrato->gas);
            }
            if($contrato->television > 0){
                $contrato->television = number_format((float)$contrato->television, 2, '.', ',');
                array_push($servicios,'televisión $'.$contrato->television);
            }
            if($contrato->telefonia > 0){
                $contrato->telefonia = number_format((float)$contrato->telefonia, 2, '.', ',');
                array_push($servicios,'telefonía $'.$contrato->telefonia);
            }

            if($contrato->luz == 0){
                array_push($sinServicios,'luz');
            }
            if($contrato->agua == 0){
                array_push($sinServicios,'agua');
            }
            if($contrato->gas == 0){
                array_push($sinServicios,'gas');
            }
            if($contrato->television == 0){
                array_push($sinServicios,'televisión');
            }
            if($contrato->telefonia == 0){
                array_push($sinServicios,'telefonía');
            }
        }
        //Creación del PDF
        $pdf = \PDF::loadview('pdf.rentas.contratoRenta', [
            'contrato' => $contrato,
            'representante' => $request->representante,
            'testigo' => $request->testigo,
            'servicios' => $servicios,
            'sinServicios' => $sinServicios
        ]);
        //Retorno de la vista
        return $pdf->stream('Contrato.pdf');
    }
    //Función para mostrar los pagareas del contrato en PDF
    public function printPagares(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        //Llamda a la función que retorna los datos del contrato
        $contrato = $this->getDatosRenta($request);
        //Se obtiene el formato de numero en letra
        $contrato->cantMeses = NumerosEnLetras::convertir($contrato->num_meses, '', false, '');
        //Se recorre el arreglo que contiene los pagares
        foreach($contrato->pagares as $pago){
            //Formato en letra
            $pago->monto = NumerosEnLetras::convertir($pago->importe, 'Pesos', true, 'Centavos');
            //Formato de moneda
            $pago->importe = number_format((float)$pago->importe, 2, '.', ',');
            //Formato para fecha de pago
            $pago->fecha_pago = new Carbon($pago->fecha);
            $pago->fecha_pago = $pago->fecha_pago->formatLocalized('%d de %B de %Y');
        }
        //Formato para la fecha de contrato
        $fechaContrato = new Carbon($contrato->fecha_firma);
        $contrato->fecha_firma = $fechaContrato->formatLocalized('%d de %B de %Y');
        //Creación de PDF
        $pdf = \PDF::loadview('pdf.rentas.pagosRentas', [
            'contrato' => $contrato
        ]);
        //Retorno de la vista
        return $pdf->stream('Pagares.pdf');
    }
    //Función para imprimir el recibo de garantia en PDF
    public function printDepositoGarantia(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        //Llamda a la función que retorna los datos del contrato
        $contrato = $this->getDatosRenta($request);
        //Formato de fecha segun necesidades
        $fechaContrato = new Carbon($contrato->fecha_firma);
        $contrato->fecha_firma = $fechaContrato->formatLocalized('%d de %B del %Y');

        $contrato->meses_garantia = $contrato->dep_garantia/$contrato->monto_renta;
        $contrato->meses_garantia = NumerosEnLetras::convertir($contrato->meses_garantia, '', false, '');

        //Se obtiene el formato de numero en letra
        $contrato->dep_garantia = NumerosEnLetras::convertir($contrato->dep_garantia, 'Pesos', true, 'Centavos');
        

        $pdf = \PDF::loadview('pdf.rentas.depGarantia', [
            'contrato' => $contrato,
            'representante' => $request->representante
        ]);
        //Retorno de la vista
        return $pdf->stream('ReciboDeposito.pdf');
    }
    //Función para imprimir el ADENDUM del contrato para rentas con mobiliario
    public function printAdendum(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        //Llamda a la función que retorna los datos del contrato
        $contrato = $this->getDatosRenta($request);
        //Formato de fecha segun necesidades
        $fechaContrato = new Carbon($contrato->fecha_firma);
        $contrato->fecha_firma = $fechaContrato->formatLocalized('%d días de %B de %Y');

        //Se obtiene el formato de numero en letra
        $contrato->dep_garantia = NumerosEnLetras::convertir($contrato->dep_garantia, 'Pesos', true, 'Centavos');
        

        $pdf = \PDF::loadview('pdf.rentas.adendum', [
            'contrato' => $contrato,
            'representante' => $request->representante
        ]);
        //Retorno de la vista
        return $pdf->stream('Adendum.pdf');
    }
    // Función para subir archivo fiscal para ventas.
    public function formSubmitArchivo(Request $request, $id){
        $lote = Licencia::findOrFail($id);

        if($lote->archivo_esp != NULL){
            $pathAnterior = public_path() . '/files/lotes/archivoRentas/' . $lote->archivo_esp;
            File::delete($pathAnterior);
        }

        $fileName = $request->archivo->getClientOriginalName();
        $moved =  $request->archivo->move(public_path('/files/lotes/archivoRentas/'), $fileName);

        if($moved){
            $lote = Licencia::findOrFail($id);
            $lote->archivo_esp = $fileName;
            $lote->save(); //Insert
        }
        
    	return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function getPagosPorVencer(Request $request){
        //Se establece la fecha 5 dias posteriores a la actual.
        $fecha = Carbon::today()->addDays(41)->format('Y-m-d');
        return $pagos = $this->getPagaresPendientes($fecha,1);

        if(sizeof($pagos))
        {
            $users = User::select('id')->whereIn('usuario',
                        ['uriel.al','enrique.mag','shady'])->get();

            foreach($pagos as $ind => $pago){
                $monto = number_format((float)$pago->importe, 2, '.', ',');
                $msj = 'El Pago #'.$pago->num_pago.' por la cantidad de $'.$monto.' a nombre de '.$pago->nombre_arrendatario.' esta próximo a vencer';
                foreach ($users as $index => $user) {
                    $aviso = new NotificacionesAvisosController();
                    $aviso->store($user->id,$msj);
                }
            }
        }

    }

    public function cambiarPagoAVencido(Request $request){
        $fecha = Carbon::today()->format('Y-m-d');
        return $pagos = $this->getPagaresPendientes($fecha,1);

        if(sizeof($pagos))
            foreach($pagos as $index => $pago){
                $p = Pago_renta::findOrFail($pago->id);
                $p->status = 0;
                $p->save();
            }
    }

    private function getPagaresPendientes($fecha,$status){
        return $pagos = Pago_renta::join('rentas','pagos_rentas.renta_id','=','rentas.id')
                    ->join('lotes','rentas.lote_id','=','lotes.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('pagos_rentas.*','rentas.nombre_arrendatario',
                        'lotes.calle','lotes.numero','lotes.interior', 'lotes.manzana',
                        'etapas.num_etapa as etapa', 'fraccionamientos.nombre as proyecto'
                    )
                    ->where('pagos_rentas.fecha','=',$fecha)
                    ->where('pagos_rentas.status','=',$status)
                    ->orderBy('id','desc')
                    ->get();
    }
}
