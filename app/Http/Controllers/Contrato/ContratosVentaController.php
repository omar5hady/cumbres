<?php

namespace App\Http\Controllers\Contrato;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Credito;
use App\Contrato;
use App\Lote;
use App\Tipo_credito;
use App\Pago_contrato;
use App\Licencia;
use App\Amenitie;
use App\SpecificationLote;
use App\Urban_equipment;
use Carbon\Carbon;
use NumerosEnLetras;
use DB;

use App\Http\Resources\SpecificationLoteResource;
use App\Http\Resources\AmenitieResource;
use App\Http\Resources\UrbanEquipmentResource;

class ContratosVentaController extends Controller
{

    private function getDatosContrato($id){

        $contrato = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas as inst', 'creditos.id', '=', 'inst.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias as l', 'lotes.id', '=', 'l.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->select(
                'creditos.*','contratos.*', 'contratos.fecha as fecha_contrato',
                'lotes.num_lote as lote', 'lotes.manzana', 'lotes.sublote',
                'lotes.terreno as sup_terreno', 'lotes.construccion as sup_construccion',
                'lotes.emp_terreno', 'lotes.emp_constructora',
                'lotes.calle as calle_lote', 'lotes.numero as num_oficial', 'lotes.interior',
                'lotes.clv_catastral', 'lotes.fin_obra', 'lotes.condiciones as estado_inmueble',
                'lotes.indivisos', 'lotes.etapa_id', 'lotes.fraccionamiento_id',
                'l.avance','l.colindancias', 'l.num_escritura', 'l.num_notario',
                'l.distrito_notario','l.folio_registro', 'l.date_escritura', 'l.date_birth',
                'modelos.nombre as modelo', 'modelos.tipo as tipo_modelo',
                'fraccionamientos.nombre as proyecto',
                'fraccionamientos.ciudad as ciudad_proy',
                'fraccionamientos.estado as estado_proy',
                'fraccionamientos.calle as direccionProyecto',
                'personal.nombre as c_nombre', 'personal.apellidos as c_apellidos',
                'personal.num_ine', 'personal.num_pasaporte',
                'personal.f_nacimiento', 'personal.rfc','personal.homoclave',
                'personal.direccion as dir_cliente', 'personal.colonia as col_cliente',
                'personal.cp as cp_cliente', 'personal.telefono as tel_cliente',
                'personal.email as cliente_email',
                'personal.celular as cel_cliente', 'personal.clv_lada',
                'clientes.estado as edo_cliente', 'clientes.ciudad as ciudad_cliente',
                'clientes.coacreditado',
                'clientes.nacionalidad',
                'clientes.lugar_nacimiento',
                'clientes.id as prospecto_id',
                'clientes.edo_civil',
                'clientes.nss',
                'clientes.curp',
                'clientes.empresa',
                'clientes.coacreditado',
                'clientes.nombre_coa',
                'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa',
                'clientes.nacionalidad_coa',
                'clientes.puesto',

                'inst.tipo_credito', 'inst.institucion'

            )
            ->where('inst.elegido', '=', '1')
            ->where('contratos.id', '=', $id)
            ->first();

        $contrato->especificaciones = $this->getEspecificaciones($contrato->lote_id);
        $contrato->amenidades = $this->getAmenidades($contrato->etapa_id);
        $contrato->equip_urbano = $this->getEqUrbano($contrato->fraccionamiento_id);

        return $contrato;

    }

    private function getEspecificaciones($id){
        $especificaciones = SpecificationLote::select('general')->where('lote_id','=',$id)->distinct()->get();
                if(sizeof($especificaciones)){
                    foreach($especificaciones as $generales){
                        $generales->detalle = SpecificationLoteResource::collection(
                            SpecificationLote::where('lote_id','=',$id)->where('general','=',$generales->general)->get());
                    }
                }

        return $especificaciones;
    }

    private function getAmenidades($etapa_id){
        return AmenitieResource::collection(Amenitie::where('etapa_id','=',$etapa_id)->get());
    }

    private function getEqUrbano($fraccionamiento_id){
        $equip = Urban_equipment::select('categoria')->where('fraccionamiento_id','=',$fraccionamiento_id)->distinct()->get();
                if(sizeof($equip)){
                    foreach($equip as $generales){
                        $generales->detalle = UrbanEquipmentResource::collection(
                            Urban_equipment::where('fraccionamiento_id','=',$fraccionamiento_id)->where('categoria','=',$generales->categoria)->get());
                    }
                }

        return $equip;
    }


    private function calcularDiasHabiles($ini, $fin){
        $dt = Carbon::parse($ini);
        $dt2 = Carbon::parse($fin);
        return $daysForExtraCoding = $dt->diffInDaysFiltered(function(Carbon $date) {
            return !$date->isWeekend();
        }, $dt2);

    }



    public function printContratoCredito(Request $request, $id)
    {
        setlocale(LC_TIME, 'es_MX.utf8');

        //Llamada a la función privada que obtiene los datos del contrato.
        $contrato = $this->getDatosContrato($id);

        $credito_sel = Tipo_credito::where('nombre','=',$contrato->tipo_credito)->where('institucion_fin','=',$contrato->institucion)->first();

        $contrato->diasTramites = $credito_sel->dias_nat;

        if($contrato->avance_lote < 95){
            $contrato->diasTramites += $this->calcularDiasHabiles($contrato->fecha_contrato, $contrato->fin_obra);
        }
        $contrato->date_birth = new Carbon($contrato->date_birth);
        $contrato->date_birth = $contrato->date_birth->diff(new Carbon($contrato->fecha_contrato))->format('%y años, %m meses');

        $contrato->valor_const = $contrato->precio_venta - $contrato->valor_terreno;
        $contrato->valor_const = NumerosEnLetras::convertir($contrato->valor_const, 'Pesos', true, 'Centavos');
        $contrato->precio_venta = NumerosEnLetras::convertir($contrato->precio_venta, 'Pesos', true, 'Centavos');
        $contrato->valor_terreno = NumerosEnLetras::convertir($contrato->valor_terreno, 'Pesos', true, 'Centavos');

        $contrato->fecha_contrato = new Carbon($contrato->fecha_contrato);
        $contrato->fecha_contrato = $contrato->fecha_contrato->formatLocalized('%d días del mes de %B del año %Y');


        switch($contrato->edo_civil){
            case 1:{
                $contrato->edo_civil = "Casado - Separación de bienes";
                break;
            }
            case 2:{
                $contrato->edo_civil = "Casado - Sociedad conyugal";
                break;
            }
            case 3:{
                $contrato->edo_civil = "Divorciado";
                break;
            }
            case 4:{
                $contrato->edo_civil = "Soltero";
                break;
            }
            case 5:{
                $contrato->edo_civil = "Unión libre";
                break;
            }
            case 6:{
                $contrato->edo_civil = "Viudo";
                break;
            }
            case 7:{
                $contrato->edo_civil = "Otro";
                break;
            }
        }

        //Formato de fechas
        $contrato->date_escritura = new Carbon($contrato->date_escritura);
        $contrato->date_escritura = $contrato->date_escritura->formatLocalized('%d de %B de %Y');
        $contrato->f_nacimiento = new Carbon($contrato->f_nacimiento);
        $contrato->f_nacimiento = $contrato->f_nacimiento->formatLocalized('%d de %B de %Y');

        if($contrato->tipo_credito == 'Crédito Directo'){
            $pdf = \PDF::loadview('pdf.contratos.norma247.contrato_contado', ['contrato' => $contrato]);
            return $pdf->stream('contrato_venta_contado.pdf');
        }
        else{
            $pdf = \PDF::loadview('pdf.contratos.norma247.contrato_credito', ['contrato' => $contrato]);
            return $pdf->stream('contrato_venta_credito.pdf');
        }

    }

    public function printAvisoPrivacidad(){
        $pathtoFile = public_path().'/pdf/AvisoPrivacidadCarta.pdf';
        return response()->file($pathtoFile);
    }
}
