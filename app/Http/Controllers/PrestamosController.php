<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prestamos_personales;
use App\Pagos_prestamos;
use App\Personal;
use App\Obs_prestamos_pers;
use Carbon\Carbon;
use Auth;

class PrestamosController extends Controller
{
    public function dataColaborador(Request $request){
        $data=Personal::select('nombre','apellidos')->where('id','=',$request->id)->first();
        return $data;
    }
    
 
    public function getDataPrestamos(Request $request)
    {
        $id_user=$request->idUser;
        $isRHCurrent=$request->isRHCurrent;
        $isDireccionCurrent=$request->isDireccionCurrent;
        $isGerenteCurrent=$request->isGerenteCurrent;
        $b_colaborador=$request->b_colaborador;
        $b_fecha1=$request->b_fecha1;
        $b_fecha2=$request->b_fecha2;
        $b_status=$request->b_status;


        $query=Prestamos_personales::join('personal','prestamos_personales.user_id','=','personal.id')
                                    ->select( 
                                    'prestamos_personales.id',
                                    'user_id',
                                    'jefe_id',
                                    'monto_solicitado',
                                    'fecha_ini',
                                    'motivo',
                                    'rh_band',
                                    'jefe_band',
                                    'dir_band',
                                    'status',
                                    'saldo',
                                    'desc_quin',
                                    'personal.nombre',
                                    'personal.apellidos',
                                    'status_rh',
                                    'fecha_status_rh',
                                    
                                    );

                                    if($isGerenteCurrent == 'true'){
                                        $query=$query->where('jefe_id','=',$id_user)->orWhere('user_id','=',$id_user);
                                        
                                    }elseif($isRHCurrent == 'true' || $isDireccionCurrent == 'true'){
                                        $query=$query;
                                    }else{
                                        $query=$query->where('user_id','=',$id_user);
                                    }

                                    if($b_colaborador !='' ){
                                        $query=$query->where('user_id','=',$b_colaborador);
                                    }
                                    
                                    if($b_fecha1 !='' && $b_fecha2 !='' ){
                                        $query = $query->whereBetween('fecha_ini', [$b_fecha1, $b_fecha2]);
                                    }
                                    
                                else{
                                    if ($b_fecha2 !='') {
                                        $query = $query->where('fecha_ini','=', $b_fecha2);
                                    }
                                    if ($b_fecha1 !='') {
                                        $query = $query->where('fecha_ini','=', $b_fecha1);
                                    }

                                }
                                if($b_status !=''){
                                    $query = $query->where('status','=',$b_status);
                                }
                                $query = $query->orderBy('personal.nombre','asc')->orderBy('prestamos_personales.id','desc')->paginate(10);
                                    
        return $query;
    }
  
    public function registrarPrestamo(Request $request)
    {

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $user_id=$request->id; 
        $monto=$request->monto; 
        $motivo=$request->motivo; 
        $desc_quin=$request->desc_quin; 
        $fecha_solic=$request->fecha_solic; 
        $idJefe=$request->idJefe;


        $prestamo=new Prestamos_personales;
        $prestamo->user_id=$user_id;
        $prestamo->monto_solicitado=$monto;
        $prestamo->motivo=$motivo;
        $prestamo->desc_quin =$desc_quin;
        $prestamo->saldo =$monto;
        $prestamo->fecha_ini =$fecha_solic;
        $prestamo->jefe_id =$idJefe;
        $prestamo->save();

        return $prestamo->id;


    }

   
    public function observaciones_prestamos(Request $request)
    {
        $id=$request->id;
        $obspres=$request->obs;

        $obs=new Obs_prestamos_pers;
        $obs->prestamo_id = $id;
        $obs->observacion = $obspres;
        $obs->usuario_id = Auth::user()->id;
        $obs->save();
        
    }
    public function  capturar_pago(Request $request){


        $pago_id=$request->pago_id;
        $solicitud_id=$request->solic_id;
        
             $p=Pagos_prestamos::findOrFail($pago_id);
             $p->fecha_pago=Carbon::now();
             $p->status=1;
             $p->save();

             $saldo=Pagos_prestamos::select('saldo')->where('id','=', $pago_id)->first();
                 
            
                 $prestamo=Prestamos_personales::findOrFail($solicitud_id);
                 $prestamo->saldo=$saldo['saldo'];
                 $prestamo->save();

            

    }

    public function getObservaciones(Request $request){
        $obs = Obs_prestamos_pers::where('prestamo_id','=',$request->id)->get();
        return $obs;
    }
   
  
  
    public function aprobar_rh(Request $request)
    {
        $id=$request->id;
        $band=$request->band; // aprobado o rechazado 
        $fecha=$request->fecha_aprob;


      
                            $prestamo=Prestamos_personales::findOrFail($id);
                                if($band == 0){
                                    $prestamo->status=0;
                                    $prestamo->status_rh=0;
                                }
                                else{
                                    $prestamo->status_rh=2;
                                }
                                $prestamo->fecha_status_rh= $fecha;
                                $prestamo->save();
                        
                
        
    }
    public function editarPrestamo(Request $request)
    {
        
        $solicitud_id=$request->solicitud_id;
        $user_id=$request->id; 
        $monto=$request->monto; 
        $motivo=$request->motivo; 
        $desc_quin=$request->desc_quin; 
        $fecha_solic=$request->fecha_solic; 
        $idJefe=$request->idJefe;
        $tablaPagos=$request->arrPagos;
        
        $index=key($tablaPagos);
        
        $prestamo=Prestamos_personales::findOrFail($solicitud_id);
        $prestamo->user_id=$user_id;
        $prestamo->monto_solicitado=$monto;
        $prestamo->motivo=$motivo;
        $prestamo->desc_quin =$desc_quin;

        if($index > 0){ // pendinnteeee

        }
        $prestamo->saldo =$monto;
        $prestamo->fecha_ini =$fecha_solic;
        $prestamo->jefe_id =$idJefe;
        $prestamo->save();



         $arrayPagos=[];
         $index=key($tablaPagos);

        foreach ($tablaPagos as $key => $value) {
            
            $arrayPagos[$key]['solic_id']=$solicitud_id;
            $arrayPagos[$key]['monto_pago']=$value['pago'];
            //$arrayPagos[$key]['fecha_pago']=$value['solicitud_id'];
           // $arrayPagos[$key]['concepto']=$value['solicitud_id'];  ///PENDIENTE DE REVISAR
            $arrayPagos[$key]['status']=0;
            $arrayPagos[$key]['monto_pago_extra']=$value['pagoExtra'];
            $arrayPagos[$key]['saldo']=$value['saldo'];
            
        }

    
       $pagos=Pagos_prestamos::where('solic_id','=', $solicitud_id)->select('id')->get();

        for($index ;$index < sizeof($pagos) ; $index++ ){
            $p=Pagos_prestamos::findOrFail($pagos[$index]->id);
            $p->monto_pago=$arrayPagos[$index]['monto_pago'];
            $p->status =$arrayPagos[$index]['status'];
            $p->monto_pago_extra =$arrayPagos[$index]['monto_pago_extra'];
            $p->saldo =$arrayPagos[$index]['saldo'];
            $p->save();
        }

                
        
    }

    public function generaTablaPagos(Request $request)
    {
        
       
            $solicitud_id=$request->id;
    
            $pagos=$request->arrPagos;
    
            foreach ($pagos as $key => $monto) {
    
                $pago=new Pagos_prestamos;
                    $pago->solic_id=$solicitud_id;
                $pago->monto_pago=$monto['pago'];
                $pago->status =0;
                $pago->monto_pago_extra =$monto['pagoExtra'];
                $pago->saldo =$monto['saldo'];
                $pago->save();
                # code...
            }
        
       
        
    }
    public function getTablaPagos(Request $request)
    {
        
        $solicitud_id=$request->id;

        $pagos = Pagos_prestamos::where('solic_id','=',$solicitud_id)->get();
        //$pagos_cap = Pagos_prestamos::where('solic_id','=',$solicitud_id)->where('status','=','1')->get();
        if(sizeof($pagos)>0 ){
            foreach ($pagos as $key => $pago) {
                if($pago->status == 0){
                    $tabla[$key]['id']=$key+1;
                    $tabla[$key]['id_pago']=$pago->id;
                    $tabla[$key]['status']=$pago->status;
                    $tabla[$key]['fecha_pago']=$pago->fecha_pago;
                    $tabla[$key]['pago']=$pago->monto_pago;
                    $tabla[$key]['pagoExtra']=$pago->monto_pago_extra;
                    $tabla[$key]['saldo']=$pago->saldo;
                }else{
                    $tabla_p[$key]['id']=$key+1;
                    $tabla_p[$key]['id_pago']=$pago->id;
                    $tabla_p[$key]['status']=$pago->status;
                    $tabla_p[$key]['fecha_pago']=$pago->fecha_pago;
                    $tabla_p[$key]['pago']=$pago->monto_pago;
                    $tabla_p[$key]['pagoExtra']=$pago->monto_pago_extra;
                    $tabla_p[$key]['saldo']=$pago->saldo;
                }
            }
            if(sizeof($tabla_p)<= 0 ){
                return [$tabla,$tabla_p=[]];
            }else return [$tabla,$tabla_p];

        }else return [$tabla=[],$tabla_p=[]];

           
        

      
         
       
        
    }

    
    public function firmarPrestamo(Request $request)
    {
        $id=$request->id;
        $firma_de=$request->firma_de;
        
        $prestamo=Prestamos_personales::findOrFail($id);
        switch ($firma_de)  {
            
            case 'jefe':
                $firmas=Prestamos_personales::select('rh_band','jefe_band','dir_band')->where('id','=',$id)->first();
                $prestamo->jefe_band = 1;
                $prestamo->save();
                        if( $firmas->rh_band == 1 && $firmas->dir_band == 1){
                            $status=Prestamos_personales::findOrFail($id);
                            $status->status=2;
                            $status->save();
                        }
                    
                break;
            case 'rh':
                $firmas=Prestamos_personales::select('rh_band','jefe_band','dir_band')->where('id','=',$id)->first();
                $prestamo->rh_band = 1;
                $prestamo->save();
                        if( $firmas->jefe_band == 1 && $firmas->dir_band == 1){
                            $status=Prestamos_personales::findOrFail($id);
                            $status->status=2;
                            $status->save();
                        }
                break;
            case 'dir':
                $firmas=Prestamos_personales::select('rh_band','jefe_band','dir_band')->where('id','=',$id)->first();
                $prestamo->dir_band = 1;
                $prestamo->save();
                        if( $firmas->jefe_band == 1 && $firmas->rh_band == 1){
                            $status=Prestamos_personales::findOrFail($id);
                            $status->status=2;
                            $status->save();
                        }
                break;
            default:
                break;
        }


    }


    public function destroy($id)
    {
        //
    }
}
