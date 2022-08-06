<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prestamos_personales;
use App\Pagos_prestamos;
use App\Personal;
use App\Obs_prestamos_pers;
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
                                    'personal.apellidos',);
                                    if($isGerenteCurrent == 'true'){
                                        $query=$query->where('jefe_id','=',$id_user)->orWhere('user_id','=',$id_user)->get();
                                    }elseif($isRHCurrent == 'true' || $isDireccionCurrent == 'true'){
                                        $query=$query->get();
                                    }else{
                                        $query=$query->where('user_id','=',$id_user)->get();
                                    }
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


    }

   
    public function store()
    {
        
    }

   
  
  
    public function edit($id)
    {
        //
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
