<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use Carbon\Carbon;
use App\Pago_contrato;
use App\inst_seleccionada;
use App\Expediente;
use App\Deposito;
use App\Int_cobro;
use App\Pago_cobro;
use App\Credito;
use App\Personal;
use Excel;
use Auth;
use DB;

// Controlador para integracion de cobros de escrituración
class CobrosController extends Controller
{
    // Funcion que retorna los contratos que sin integración de cobros
    public function getContratos(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $buscar = $request->buscar;

        $contratos = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('lotes as l', 'creditos.lote_id', '=', 'l.id')
                ->join('etapas as e','l.etapa_id','=','e.id')
                ->join('fraccionamientos as f','l.fraccionamiento_id','=','f.id')
                ->select('contratos.id', 'creditos.precio_venta', 'c.nombre', 'c.apellidos',
                        'creditos.porcentaje_terreno', 'l.emp_constructora', 'l.emp_terreno',
                        'creditos.email_fisc','creditos.tel_fisc','creditos.nombre_fisc',
                        'creditos.direccion_fisc','creditos.cp_fisc','creditos.rfc_fisc',
                        'l.manzana', 'l.num_lote', 'l.calle', 'l.numero', 'l.interior', 'f.nombre as proyecto',
                        'contratos.fecha',
                        'e.num_etapa as etapa',
                        'i.tipo_credito', 'i.institucion', 'i.monto_credito', 'i.segundo_credito',
                        'expedientes.valor_escrituras',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) as nombre_completo"),
                        DB::raw("CONCAT(f.ciudad,', ',f.estado) as ciudad_proy")
                    )
                ->where('contratos.status','=',3) // Contrato firmado
                ->where('creditos.integracion_cobro','=',0) // Contrato sin integración de cobros
                ->where('i.elegido','=',1) // Financiamiento elegido
                ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%') // Busqueda por nombre del cliente
                ->take(7)->get();// Retorno de los primeros 7 resultados.

        if(sizeof($contratos))
            // Se recorren los contratos encontrados
            foreach ($contratos as $key => $contrato) {
                // Se obtienen los depositos asignados al contrato.
                $contrato->depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                    ->select('depositos.cant_depo', 'depositos.banco','depositos.fecha_pago','depositos.id')
                    ->where('pagos_contratos.contrato_id','=',$contrato->id)
                    ->get();
                
                if(sizeof($contrato->depositos)){
                    // Por cada deposito se separa la institucion de financiamiento y el banco en campos diferentes para mostrar en pantalla.
                    foreach ($contrato->depositos as $key => $deposito){
                        $pos = strpos($deposito->banco, '-');
                        $deposito->institucion = substr($deposito->banco, $pos+1);
                        $deposito->cuenta = substr($deposito->banco, 0,$pos);
                    }
                }
            }

        return $contratos;
    }

    // Función para crear la integración de cobros.
    public function generarIntegración(Request $request){
        // Veririca que la peticion sea ajax.
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();

            // Se crea el registro para la integración del cobro.
            $integracion = new Int_cobro();
            $integracion->contrato_id = $request->contrato_id;
            $integracion->valor_terreno = $request->valor_terreno;
            $integracion->valor_const = $request->valor_const;
            $integracion->porcentaje_terreno = $request->porcentaje_terreno;
            $integracion->porcentaje_construccion = $request->porcentaje_construccion;
            $integracion->valor_escrituras = $request->valor_escrituras;
            $integracion->save();

            $pagos = $request->pagos;
            // Se almacenen los pagos relacionados al contrato.
            if(sizeof($pagos)){
                foreach ($pagos as $key => $pago) {
                    $cobro = new Pago_cobro();
                    $cobro->integracion_id = $integracion->id;
                    $cobro->deposito_id = $pago['id'];
                    $cobro->fecha_pago = $pago['fecha_pago'];
                    $cobro->banco = $pago['banco'];
                    $cobro->cant_depo = $pago['cant_depo'];
                    $cobro->save();
                }
            }

            // Se accede al registro del Credito y se indica como integrado.
            $credito = Credito::findOrFail($request->contrato_id);
            $credito->integracion_cobro = 1;
            $credito->save();

            $ruta = 'https://siicumbres.com//integracionCobros/exportFormat?id='.$cobro->id;
            $msj = 'Se ha creado la integracion de cobros para el folio #: '.$integracion->contrato_id;

            $personal = Personal::join('users', 'personal.id', '=', 'users.id')
            ->select('personal.email', 'personal.id')->whereIn('users.usuario', [
                    'enrique.mag', 'antonio.nv'
            ])->get();

            // Se envia notificación via correo electrónico.
            if(sizeof($personal))
                foreach ($personal as $personas) {
                    $correo = $personas->email;
                    Mail::to($correo)->send(new NotificationReceived($msj,$ruta));
                }

        DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }         
        
    }

    // Funcion para obtener los cobros de una integración.
    public function getCobros(Request $request){
        $cobros = Pago_cobro::where('integracion_id','=',$request->id)
        ->get();
    
        if(sizeof($cobros)){
            foreach ($cobros as $key => $deposito){
                $pos = strpos($deposito->banco, '-');
                $deposito->institucion = substr($deposito->banco, $pos+1);
                $deposito->cuenta = substr($deposito->banco, 0,$pos);
            }
        }

        return $cobros;
    }

    // Funcion para guardar un pago nuevo en la integración.
    public function storeCobro(Request $request){
        $cobro = new Pago_cobro();
        $cobro->integracion_id = $request->integracion_id;
        $cobro->fecha_pago = $request->fecha_pago;
        $cobro->banco = $request->banco;
        $cobro->num_cheque = $request->num_cheque;
        $cobro->forma_pago = $request->forma_pago;
        $cobro->clave = $request->clave;
        $cobro->tarjeta = $request->tarjeta;
        $cobro->cant_depo = $request->cant_depo;
        $cobro->save();
    }

    // Función para actualizar un pago.
    public function updateCobro(Request $request){
        $cobro = Pago_cobro::findOrFail($request->id);
        $cobro->integracion_id = $request->integracion_id;
        $cobro->fecha_pago = $request->fecha_pago;
        $cobro->banco = $request->banco;
        $cobro->num_cheque = $request->num_cheque;
        $cobro->forma_pago = $request->forma_pago;
        $cobro->clave = $request->clave;
        $cobro->tarjeta = $request->tarjeta;
        $cobro->cant_depo = $request->cant_depo;
        $cobro->save();
    }

    // Función privada que retorna la integracion de cobros.
    private function queryIntegracion(Request $request){
        $integraciones = Int_cobro::join('creditos','int_cobros.contrato_id','=','creditos.id')
        ->join('contratos','creditos.id','contratos.id')  
        ->leftJoin('expedientes','contratos.id','=','expedientes.id')      
        ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('lotes as l', 'creditos.lote_id', '=', 'l.id')
        ->join('etapas as e','l.etapa_id','=','e.id')
        ->join('fraccionamientos as f','l.fraccionamiento_id','=','f.id')
        ->select('int_cobros.*', 
            'l.emp_constructora', 'l.emp_terreno',
            'creditos.email_fisc','creditos.tel_fisc','creditos.nombre_fisc',
            'creditos.direccion_fisc','creditos.cp_fisc','creditos.rfc_fisc',
            'l.manzana', 'l.num_lote', 'l.calle', 'l.numero', 'l.interior', 'f.nombre as proyecto',
            'contratos.fecha',
            'expedientes.fecha_firma_esc',
            'e.num_etapa as etapa',
            'i.tipo_credito', 'i.institucion', 'i.monto_credito', 'i.segundo_credito',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) as nombre_completo"),
            DB::raw("CONCAT(f.ciudad,', ',f.estado) as ciudad_proy")
        )
        ->where('i.elegido','=',1);

        if($request->buscar != ''){
            $integraciones = $integraciones->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $request->buscar . '%');
        }


        return $integraciones;
    }

    // Funcion que retorna las integraciones de cobros registradas.
    public function getIntegraciones(Request $request){
        $integraciones = $this->queryIntegracion($request);
        $integraciones = $integraciones->paginate(10);

            // Se recorren los contratos encontrados
            if(sizeof($integraciones)){
                foreach ($integraciones as $key => $integracion) {
                    // Se obtienen los depositos asignados al contrato.
                    $integracion->depositos = Pago_cobro::where('integracion_id','=',$integracion->id)
                        ->get();
                    
                    if(sizeof($integracion->depositos)){
                        // Por cada deposito se separa la institucion de financiamiento y el banco en campos diferentes para mostrar en pantalla.
                        foreach ($integracion->depositos as $key => $deposito){
                            $pos = strpos($deposito->banco, '-');
                            $deposito->institucion = substr($deposito->banco, $pos+1);
                            $deposito->cuenta = substr($deposito->banco, 0,$pos);
                        }
                    }
                }
            }

        return $integraciones;
    }

    // Función para terminar el proceso de integración.
    public function finalizarIntegracion(Request $request){
        $integracion = Int_cobro::findOrFail($request->id);
        $integracion->status = 1;
        $integracion->save();

        $ruta = 'https://siicumbres.com/integracionCobros/exportFormat?id='.$request->id;
        $msj = 'Se ha finalizado la integracion de cobros para el folio#: '.$integracion->contrato_id;

        $personal = Personal::join('users', 'personal.id', '=', 'users.id')
            ->select('personal.email', 'personal.id')->whereIn('users.usuario', [
                    'enrique.mag', 'jovanni.t', 'j.gaitan', 'ale.teran', 'sandra.rdz'
            ])->get();
            // Se envia notificacion via correo electrónico.
            if(sizeof($personal))
                foreach ($personal as $personas) {
                    $correo = $personas->email;
                    Mail::to($correo)->send(new NotificationReceived($msj,$ruta));
                }
    }

    // Funcion para retornar la integracion en formato Excel.
    public function exportFormat(Request $request){
        $integracion = $this->queryIntegracion($request);
        $integracion = $integracion->where('int_cobros.id','=',$request->id)
                        ->first();


            $integracion->depositos = Pago_cobro::where('integracion_id','=',$integracion->id)
                ->get();

            $integracion->totalCredito = $integracion->monto_credito + $integracion->segundo_credito;
            $integracion->totalCobrado = 0;
            
            
            if(sizeof($integracion->depositos)){
                foreach ($integracion->depositos as $key => $deposito){
                    $pos = strpos($deposito->banco, '-');
                    $deposito->institucion = substr($deposito->banco, $pos+1);
                    $deposito->cuenta = substr($deposito->banco, 0,$pos);

                    $integracion->totalCobrado += $deposito->cant_depo;
                }
            }
            $integracion->diferencia = $integracion->valor_escrituras - ($integracion->totalCredito + $integracion->totalCobrado);

        
        // Creación y retorno del excel
        return Excel::create('Integracion de cobros', function($excel) use ($integracion){
            $excel->sheet(''.$integracion->contrato_id, function($sheet) use ($integracion){
                
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                $sheet->mergeCells('A3:H3');

                $sheet->cells('A1:A3', function($cell) {
                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cells('A4:A9', function($cell) {
                    // manipulate the cell
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('F5:F5', function($cell) {
                    // manipulate the cell
                    $cell->setFontWeight('bold');
                });
                $sheet->cells('F7:F9', function($cell) {
                    // manipulate the cell
                    $cell->setFontWeight('bold');
                });

                if($integracion->emp_constructora == $integracion->emp_terreno)
                    $sheet->row(2, [$integracion->emp_constructora]);
                else
                    $sheet->row(2, ['CONCRETANIA / GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.']);

                $sheet->row(3, ['INTEGRACIÓN DE COBROS PARA LA ESCRITURACIÓN']);

                $sheet->mergeCells('B4:C4');
                $sheet->row(4, [
                    'Nombre del cliente', 
                    $integracion->nombre_completo, 
                    '',
                    'MZ-'.$integracion->manzana,
                    'Lote',
                    $integracion->num_lote
                ]);

                if($integracion->emp_constructora != $integracion->emp_terreno){
                    $sheet->mergeCells('A5:C5');
                    $sheet->cells('A5:C5', function($cell) {
                        $cell->setAlignment('center');
                    });
                    $sheet->setColumnFormat(array(
                        'D5:D5' => '$#,##0.00',
                        'G5:G5' => '$#,##0.00',
                    ));

                    $sheet->row(5, [
                        'VALOR DEL TERRENO', '', '',
                        $integracion->valor_terreno,
                        $integracion->porcentaje_terreno.'%',
                        'VALOR DE CONSTRUCCIÓN',
                        $integracion->valor_const,
                        $integracion->porcentaje_construccion.'%'
                    ]);
                }

                $sheet->mergeCells('B6:C6');
                $sheet->mergeCells('D6:E6');
                $sheet->mergeCells('F6:H6');
                $sheet->row(6, [
                    'Calle: ',
                    $integracion->calle.' '.$integracion->numero.'-'.$integracion->interior,
                    '',
                    $integracion->proyecto,
                    '',
                    $integracion->ciudad_proy
                ]);

                $sheet->setColumnFormat(array(
                    'B7:C7' => 'dd-mm-yyyy',
                    'G7:H7' => '$#,##0.00',
                ));
                $sheet->mergeCells('B7:C7');
                $sheet->mergeCells('D7:E7');
                $sheet->mergeCells('G7:H7');
                $sheet->row(7, [
                    'Fecha de operación: ',
                    $integracion->fecha,
                    '',
                    'Tipo de Bien: INMUEBLE',
                    '',
                    'Valor del inmueble',
                    $integracion->valor_escrituras
                ]);

                $sheet->setColumnFormat(array(
                    'G8:H8' => '$#,##0.00',
                ));
                $sheet->mergeCells('A8:C8');
                $sheet->mergeCells('D8:E8');
                $sheet->mergeCells('G8:H8');
                $sheet->row(8, [
                    'Datos de la operación o acto, forma de pago:',
                    '',
                    '',
                    $integracion->tipo_credito.' '.$integracion->institucion,
                    '',
                    'MONTO CRÉDITO NETO 1',
                    $integracion->monto_credito
                ]);

                $sheet->setColumnFormat(array(
                    'G9:H9' => '$#,##0.00',
                ));
                $sheet->mergeCells('A9:B9');
                $sheet->mergeCells('C9:D9');
                $sheet->row(9, [
                    'Forma de Pago',
                    '',
                    'Instrumento monetario',
                    '',
                    '',
                    'MONTO CRÉDITO NETO 2',
                    $integracion->segundo_credito
                ]);

                $sheet->cells('A10:H10', function($cell) {
                    // manipulate the cell
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->row(10, [
                    'Fecha',
                    'Número de cheque',
                    'Forma de pago',
                    'Clave o folio',
                    'Institución de crédito',
                    'No. de Cuenta',
                    'Digitos de la tarjeta',
                    'Importe'
                ]);

                $cont=11;
                
                //////////////////// VIVIENDAS ///////////////////////
                if(sizeof($integracion->depositos))
                    foreach($integracion->depositos as $index => $deposito) {

                        $sheet->row($cont, [
                            $deposito->fecha_pago, 
                            $deposito->num_cheque, 
                            $deposito->forma_pago,
                            $deposito->clave,
                            $deposito->institucion,
                            $deposito->cuenta,
                            $deposito->tarjeta,
                            $deposito->cant_depo,
                        ]);
                        $cont++;
                        
                    }
                    $cont++;
                    $sheet->mergeCells('A'.$cont.':G'.$cont);
                    
                    $sheet->row($cont, [
                        'Total', 
                        '', 
                        '',
                        '',
                        '',
                        '',
                        '',
                        $integracion->totalCobrado,
                    ]);	 
                    
                    $cont++;
                    $sheet->mergeCells('A'.$cont.':G'.$cont);

                    $sheet->row($cont, [
                        'Diferencia', 
                        '', 
                        '',
                        '',
                        '',
                        '',
                        '',
                        $integracion->diferencia,
                    ]);	


                    $sheet->setBorder('A11:H'.$cont, 'thin');

                    $sheet->setColumnFormat(array(
                        'H11:H'.$cont => '$#,##0.00',
                        'A11:A'.$cont => 'dd-mm-yyyy'
                    ));

                    $cont = $cont+3;
                    $contFin = $cont + 7;

                    $sheet->cells('A'.$cont.':A'.$contFin, function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                    });

                    $sheet->row($cont, [
                        'Correo Eléctronico: ', 
                        $integracion->email_fisc,
                    ]);
                    $sheet->row($cont+1, [
                        'Teléfono: ', 
                        $integracion->tel_fisc,
                    ]);
                    $sheet->row($cont+2, [
                        'Nombre: ', 
                        $integracion->nombre_fisc,
                    ]);
                    $sheet->row($cont+3, [
                        'Dirección: ', 
                        $integracion->direccion_fisc,
                    ]);
                    $sheet->row($cont+4, [
                        'CP: ', 
                        $integracion->cp_fisc,
                    ]);
                    $sheet->row($cont+4, [
                        'RFC: ', 
                        strtoupper($integracion->rfc_fisc),
                    ]);
                    $sheet->row($cont+5, [
                        'Fecha de firma: ', 
                        strtoupper($integracion->fecha_firma_esc),
                    ]);
            });
        })->download('xls');


    }
}
