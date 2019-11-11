<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Auth;
use App\Solic_equipamiento;
use App\Obs_solic_equipamiento;
use App\Recep_equipamiento;
use App\Cocina_acabado;
use App\Cocina_otra;
use App\Cocina_puerta;
use App\User;
use App\Notifications\NotifyAdmin;

use App\Equipamiento;
use App\Credito;

use App\Closet_acabado;
use App\Closet_interior;
use App\Closet_otro;

class RecepEquipamientoController extends Controller
{
    public function storeRecepcion (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $tipoRecepcion = $request->tipoRecepcion;
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();
        $usuario = DB::table('personal')->select('nombre','apellidos')->where('id','=',Auth::user()->id)->get();

        try{
            DB::beginTransaction();
            $recepcion = new Recep_equipamiento();
                    $recepcion->id = $request->id;
                    $recepcion->fecha_revision = $hoy;
                    $recepcion->supervisor = $usuario[0]->nombre.' '.$usuario[0]->apellidos;
                    $recepcion->resultado = $request->resultado;
                    $recepcion->observacion = $request->comentario;

                    $observacion = new Obs_solic_equipamiento();
                    $observacion->solic_id = $request->id;
                    $observacion->comentario ='Recepción de equipamiento: '.$request->comentario;
                    $observacion->usuario = Auth::user()->usuario;
                    $observacion->save();

                    $solicitud = Solic_equipamiento::findOrFail($request->id);

                    $equipamiento = Equipamiento::findOrFail($solicitud->equipamiento_id);
                    $proveedor = $equipamiento->proveedor_id;
                    $credito = Credito::findOrFail( $solicitud->contrato_id);
                    if($recepcion->resultado == 2){
                        $solicitud->status = 4;
                        $msj= "Equipamiento aprobado: ".$equipamiento->equipamiento. "para el lote ".$credito->num_lote. " del proyecto ".$credito->fraccionamiento." etapa ".$credito->etapa;
                    }
                    else{
                        $solicitud->status = 0;
                        $msj= "Equipamiento rechazado: ".$equipamiento->equipamiento. "para el lote ".$credito->num_lote. " del proyecto ".$credito->fraccionamiento." etapa ".$credito->etapa;
                    }
                    $solicitud->recepcion = 1;
                    $solicitud->save();
                    $recepcion->save();
                    
                    //////////////NOTIFICACION PARA EL PROVEEDOR
                    $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                    $fecha = Carbon::now();
                    
                    $arregloAceptado = [
                        'notificacion' => [
                            'usuario' => $imagenUsuario[0]->usuario,
                            'foto' => $imagenUsuario[0]->foto_user,
                            'fecha' => $fecha,
                            'msj' => $msj,
                            'titulo' => 'Recepcion de equipamiento'
                        ]
                    ];
                    //////////////NOTIFICACION PARA EL PROVEEDOR

                    User::findOrFail($proveedor)->notify(new NotifyAdmin($arregloAceptado));
                
            switch($tipoRecepcion){
                case 1:{
                        $cocina_acabado = new Cocina_acabado();
                            $cocina_acabado->id = $request->id;
                            $cocina_acabado->cubierta_acab_uniones  = $request->cubierta_acab_uniones;
                            $cocina_acabado->cubierta_acab_silicon  = $request->cubierta_acab_silicon;
                            $cocina_acabado->cubierta_acab_cortes  = $request->cubierta_acab_cortes;
                            $cocina_acabado->puerta_acab_alineados  = $request->puerta_acab_alineados;
                            $cocina_acabado->puerta_acab_cantos  = $request->puerta_acab_cantos;
                            $cocina_acabado->save();

                        $cocina_otra = new Cocina_otra();
                            $cocina_otra->id = $request->id;
                            $cocina_otra->estufa_instalacion = $request->estufa_instalacion;
                            $cocina_otra->estufa_pzas_extra = $request->estufa_pzas_extra;
                            $cocina_otra->estufa_manuales = $request->estufa_manuales;
                            $cocina_otra->estufa_danos = $request->estufa_danos;
                            $cocina_otra->tarja_danos = $request->tarja_danos;
                            $cocina_otra->tarja_pzas_extra = $request->tarja_pzas_extra;
                            $cocina_otra->save();

                        $cocina_puerta = new Cocina_puerta();
                            $cocina_puerta->id = $request->id;
                            $cocina_puerta->puerta_danos = $request->puerta_danos;
                            $cocina_puerta->puerta_tornillos = $request->puerta_tornillos;
                            $cocina_puerta->puerta_abatimiento = $request->puerta_abatimiento;
                            $cocina_puerta->puerta_limpieza = $request->puerta_limpieza;
                            $cocina_puerta->puerta_jaladera = $request->puerta_jaladera;
                            $cocina_puerta->puerta_gomas = $request->puerta_gomas;
                            $cocina_puerta->cajones_uniones = $request->cajones_uniones;
                            $cocina_puerta->cajones_silicon = $request->cajones_silicon;
                            $cocina_puerta->cajones_limpieza = $request->cajones_limpieza;
                            $cocina_puerta->cajones_jaladeras = $request->cajones_jaladeras;
                            $cocina_puerta->cajones_cantos = $request->cajones_cantos;
                            $cocina_puerta->cajones_rieles = $request->cajones_rieles;
                            $cocina_puerta->cajones_estantes = $request->cajones_estantes;
                            $cocina_puerta->cajones_pzas_comp = $request->cajones_pzas_comp;
                            $cocina_puerta->alacena_entrepano = $request->alacena_entrepano;
                            $cocina_puerta->alacena_pistones = $request->alacena_pistones;
                            $cocina_puerta->alacena_jaladeras = $request->alacena_jaladeras;
                            $cocina_puerta->alacena_micro = $request->alacena_micro;
                            $cocina_puerta->alacena_cantos = $request->alacena_cantos;
                            $cocina_puerta->alacena_limpieza = $request->alacena_limpieza;
                            $cocina_puerta->alacena_parches = $request->alacena_parches;
                            $cocina_puerta->save();

                    
                    break;
                }
                case 2:{
                        $closet_acabado = new Closet_acabado();
                            $closet_acabado->id = $request->id;
                            //Puertas alineados
                            $closet_acabado->p_ali_der = $request->p_ali_der;
                            $closet_acabado->p_ali_izq = $request->p_ali_izq;
                            $closet_acabado->p_ali_princ = $request->p_ali_princ;
                            $closet_acabado->p_ali_baja = $request->p_ali_baja;
                            //Puertas limpieza
                            $closet_acabado->p_limp_der = $request->p_limp_der;
                            $closet_acabado->p_limp_izq = $request->p_limp_izq;
                            $closet_acabado->p_limp_princ = $request->p_limp_princ;
                            $closet_acabado->p_limp_baja = $request->p_limp_baja;
                            //Puertas silicon
                            $closet_acabado->p_sil_der = $request->p_sil_der;
                            $closet_acabado->p_sil_izq = $request->p_sil_izq;
                            $closet_acabado->p_sil_princ = $request->p_sil_princ;
                            $closet_acabado->p_sil_baja = $request->p_sil_baja;
                            //Cajones alineados
                            $closet_acabado->c_ali_der = $request->c_ali_der;
                            $closet_acabado->c_ali_izq = $request->c_ali_izq;
                            $closet_acabado->c_ali_princ = $request->c_ali_princ;
                            $closet_acabado->c_ali_baja = $request->c_ali_baja;
                            //Cajones cantos
                            $closet_acabado->c_cant_der = $request->c_cant_der;
                            $closet_acabado->c_cant_izq = $request->c_cant_izq;
                            $closet_acabado->c_cant_princ = $request->c_cant_princ;
                            $closet_acabado->c_cant_baja = $request->c_cant_baja;
                            //Cajones uniones
                            $closet_acabado->c_union_der = $request->c_union_der;
                            $closet_acabado->c_union_izq = $request->c_union_izq;
                            $closet_acabado->c_union_princ = $request->c_union_princ;
                            $closet_acabado->c_union_baja = $request->c_union_baja;
                            //Cajones silicon
                            $closet_acabado->c_sil_der = $request->c_sil_der;
                            $closet_acabado->c_sil_izq = $request->c_sil_izq;
                            $closet_acabado->c_sil_princ = $request->c_sil_princ;
                            $closet_acabado->c_sil_baja = $request->c_sil_baja;
                            //Cajones limpieza
                            $closet_acabado->c_limp_der = $request->c_limp_der;
                            $closet_acabado->c_limp_izq = $request->c_limp_izq;
                            $closet_acabado->c_limp_princ = $request->c_limp_princ;
                            $closet_acabado->c_limp_baja = $request->c_limp_baja;
                            //Cajones tornillos
                            $closet_acabado->c_torn_der = $request->c_torn_der;
                            $closet_acabado->c_torn_izq = $request->c_torn_izq;
                            $closet_acabado->c_torn_princ = $request->c_torn_princ;
                            $closet_acabado->c_torn_baja = $request->c_torn_baja;
                            //Cajones parches
                            $closet_acabado->c_parch_der = $request->c_parch_der;
                            $closet_acabado->c_parch_izq = $request->c_parch_izq;
                            $closet_acabado->c_parch_princ = $request->c_parch_princ;
                            $closet_acabado->c_parch_baja = $request->c_parch_baja;
                            $closet_acabado->save();

                        $closet_interior = new Closet_interior();
                            $closet_interior->id = $request->id;
                            //Puertas tiradores
                            $closet_interior->p_tira_der = $request->p_tira_der;
                            $closet_interior->p_tira_izq = $request->p_tira_izq;
                            $closet_interior->p_tira_princ = $request->p_tira_princ;
                            $closet_interior->p_tira_baja = $request->p_tira_baja;
                            //Puertas funcionamiento
                            $closet_interior->p_func_der = $request->p_func_der;
                            $closet_interior->p_func_izq = $request->p_func_izq;
                            $closet_interior->p_func_princ = $request->p_func_princ;
                            $closet_interior->p_func_baja = $request->p_func_baja;
                            //Cajones jaladeras
                            $closet_interior->c_jalad_der = $request->c_jalad_der;
                            $closet_interior->c_jalad_izq = $request->c_jalad_izq;
                            $closet_interior->c_jalad_princ = $request->c_jalad_princ;
                            $closet_interior->c_jalad_baja = $request->c_jalad_baja;
                            //Cajones rieles
                            $closet_interior->c_riel_der = $request->c_riel_der;
                            $closet_interior->c_riel_izq = $request->c_riel_izq;
                            $closet_interior->c_riel_princ = $request->c_riel_princ;
                            $closet_interior->c_riel_baja = $request->c_riel_baja;
                            //Cajones estantes
                            $closet_interior->c_estant_der = $request->c_estant_der;
                            $closet_interior->c_estant_izq = $request->c_estant_izq;
                            $closet_interior->c_estant_princ = $request->c_estant_princ;
                            $closet_interior->c_estant_baja = $request->c_estant_baja;
                            //Cajones entrepaños
                            $closet_interior->c_entr_der = $request->c_entr_der;
                            $closet_interior->c_entr_izq = $request->c_entr_izq;
                            $closet_interior->c_entr_princ = $request->c_entr_princ;
                            $closet_interior->c_entr_baja = $request->c_entr_baja;
                            //Cajones tubos colga
                            $closet_interior->c_tubos_der = $request->c_tubos_der;
                            $closet_interior->c_tubos_izq = $request->c_tubos_izq;
                            $closet_interior->c_tubos_princ = $request->c_tubos_princ;
                            $closet_interior->c_tubos_baja = $request->c_tubos_baja;
                            //Cajones daños
                            $closet_interior->c_danos_der = $request->c_danos_der;
                            $closet_interior->c_danos_izq = $request->c_danos_izq;
                            $closet_interior->c_danos_princ = $request->c_danos_princ;
                            $closet_interior->c_danos_baja = $request->c_danos_baja;
                            //Cajones abre correct
                            $closet_interior->c_correct_der = $request->c_correct_der;
                            $closet_interior->c_correct_izq = $request->c_correct_izq;
                            $closet_interior->c_correct_princ = $request->c_correct_princ;
                            $closet_interior->c_correct_baja = $request->c_correct_baja;
                            //Cajones pzas compl
                            $closet_interior->c_pzasc_der = $request->c_pzasc_der;
                            $closet_interior->c_pzasc_izq = $request->c_pzasc_izq;
                            $closet_interior->c_pzasc_princ = $request->c_pzasc_princ;
                            $closet_interior->c_pzasc_baja = $request->c_pzasc_baja;
                            //Cajones abatimiento
                            $closet_interior->c_abatim_der = $request->c_abatim_der;
                            $closet_interior->c_abatim_izq = $request->c_abatim_izq;
                            $closet_interior->c_abatim_princ = $request->c_abatim_princ;
                            $closet_interior->c_abatim_baja = $request->c_abatim_baja;
                            //Cajones visagras
                            $closet_interior->c_visagras_der = $request->c_visagras_der;
                            $closet_interior->c_visagras_izq = $request->c_visagras_izq;
                            $closet_interior->c_visagras_princ = $request->c_visagras_princ;
                            $closet_interior->c_visagras_baja = $request->c_visagras_baja;
                            $closet_interior->save();

                        $closet_otro = new Closet_otro();
                            $closet_otro->id = $request->id;
                            $closet_otro->pared_dan_der = $request->pared_dan_der;
                            $closet_otro->pared_dan_izq = $request->pared_dan_izq;
                            $closet_otro->pared_dan_princ = $request->pared_dan_princ;
                            $closet_otro->pared_dan_baja = $request->pared_dan_baja;
                            //Paredes limpieza
                            $closet_otro->pared_limp_der = $request->pared_limp_der;
                            $closet_otro->pared_limp_izq = $request->pared_limp_izq;
                            $closet_otro->pared_limp_princ = $request->pared_limp_princ;
                            $closet_otro->pared_limp_baja = $request->pared_limp_baja;
                            //Closet Cenefa sup
                            $closet_otro->clo_censup_der = $request->clo_censup_der;
                            $closet_otro->clo_censup_izq = $request->clo_censup_izq;
                            $closet_otro->clo_censup_princ = $request->clo_censup_princ;
                            $closet_otro->clo_censup_baja = $request->clo_censup_baja;
                            //Closet Cenefa inf
                            $closet_otro->clo_ceninf_der = $request->clo_ceninf_der;
                            $closet_otro->clo_ceninf_izq = $request->clo_ceninf_izq;
                            $closet_otro->clo_ceninf_princ = $request->clo_ceninf_princ;
                            $closet_otro->clo_ceninf_baja = $request->clo_ceninf_baja;
                            //Closet color madera
                            $closet_otro->clo_madera_der = $request->clo_madera_der;
                            $closet_otro->clo_madera_izq = $request->clo_madera_izq;
                            $closet_otro->clo_madera_princ = $request->clo_madera_princ;
                            $closet_otro->clo_madera_baja = $request->clo_madera_baja;
                            //Closet Alinec jalad
                            $closet_otro->clo_alin_der = $request->clo_alin_der;
                            $closet_otro->clo_alin_izq = $request->clo_alin_izq;
                            $closet_otro->clo_alin_princ = $request->clo_alin_princ;
                            $closet_otro->clo_alin_baja = $request->clo_alin_baja;
                            //Closet pandeadura
                            $closet_otro->clo_pande_der = $request->clo_pande_der;
                            $closet_otro->clo_pande_izq = $request->clo_pande_izq;
                            $closet_otro->clo_pande_princ = $request->clo_pande_princ;
                            $closet_otro->clo_pande_baja = $request->clo_pande_baja;
                            //Closet soporte
                            $closet_otro->clo_soporte_der = $request->clo_soporte_der;
                            $closet_otro->clo_soporte_izq = $request->clo_soporte_izq;
                            $closet_otro->clo_soporte_princ = $request->clo_soporte_princ;
                            $closet_otro->clo_soporte_baja = $request->clo_soporte_baja;
                            $closet_otro->save();
                    break;                
                }
            }
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function updateRecepcion (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $tipoRecepcion = $request->tipoRecepcion;
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();
        $usuario = DB::table('personal')->select('nombre','apellidos')->where('id','=',Auth::user()->id)->get();

        try{
            DB::beginTransaction();
            $recepcion = Recep_equipamiento::findOrFail($request->id);
                        $recepcion->fecha_revision = $hoy;
                        $recepcion->supervisor = $usuario[0]->nombre.' '.$usuario[0]->apellidos;
                        $recepcion->resultado = $request->resultado;
                        $recepcion->observacion = $request->comentario;

                    $observacion = new Obs_solic_equipamiento();
                        $observacion->solic_id = $request->id;
                        $observacion->comentario ='Recepción de equipamiento: '.$request->comentario;
                        $observacion->usuario = Auth::user()->usuario;
                        $observacion->save();

                    $solicitud = Solic_equipamiento::findOrFail($request->id);

                    $equipamiento = Equipamiento::findOrFail($solicitud->equipamiento_id);
                    $proveedor = $equipamiento->proveedor_id;
                    $credito = Credito::findOrFail( $solicitud->contrato_id);

                        if($recepcion->resultado == 2){
                            $solicitud->status = 4;
                            $msj= "Equipamiento aprobado: ".$equipamiento->equipamiento. "para el lote ".$credito->num_lote. " del proyecto ".$credito->fraccionamiento." etapa ".$credito->etapa;
                        }
                        else{
                            $solicitud->status = 0;
                            $msj= "Equipamiento rechazado: ".$equipamiento->equipamiento. "para el lote ".$credito->num_lote. " del proyecto ".$credito->fraccionamiento." etapa ".$credito->etapa;
                        }
                        $solicitud->recepcion = 1;
                        $solicitud->save();
                        $recepcion->save();
                        
                        //////////////NOTIFICACION PARA EL PROVEEDOR
                        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                        $fecha = Carbon::now();
                        
                        $arregloAceptado = [
                            'notificacion' => [
                                'usuario' => $imagenUsuario[0]->usuario,
                                'foto' => $imagenUsuario[0]->foto_user,
                                'fecha' => $fecha,
                                'msj' => $msj,
                                'titulo' => 'Recepcion de equipamiento'
                            ]
                        ];
                        //////////////NOTIFICACION PARA EL PROVEEDOR

                        User::findOrFail($proveedor)->notify(new NotifyAdmin($arregloAceptado));
            switch($tipoRecepcion){
                case 1:{
                    $cocina_acabado = Cocina_acabado::findOrFail($request->id);
                        $cocina_acabado->cubierta_acab_uniones  = $request->cubierta_acab_uniones;
                        $cocina_acabado->cubierta_acab_silicon  = $request->cubierta_acab_silicon;
                        $cocina_acabado->cubierta_acab_cortes  = $request->cubierta_acab_cortes;
                        $cocina_acabado->puerta_acab_alineados  = $request->puerta_acab_alineados;
                        $cocina_acabado->puerta_acab_cantos  = $request->puerta_acab_cantos;
                        $cocina_acabado->save();

                    $cocina_otra = Cocina_otra::findOrFail($request->id);
                        $cocina_otra->estufa_instalacion = $request->estufa_instalacion;
                        $cocina_otra->estufa_pzas_extra = $request->estufa_pzas_extra;
                        $cocina_otra->estufa_manuales = $request->estufa_manuales;
                        $cocina_otra->estufa_danos = $request->estufa_danos;
                        $cocina_otra->tarja_danos = $request->tarja_danos;
                        $cocina_otra->tarja_pzas_extra = $request->tarja_pzas_extra;
                        $cocina_otra->save();

                    $cocina_puerta = Cocina_puerta::findOrFail($request->id);
                        $cocina_puerta->puerta_danos = $request->puerta_danos;
                        $cocina_puerta->puerta_tornillos = $request->puerta_tornillos;
                        $cocina_puerta->puerta_abatimiento = $request->puerta_abatimiento;
                        $cocina_puerta->puerta_limpieza = $request->puerta_limpieza;
                        $cocina_puerta->puerta_jaladera = $request->puerta_jaladera;
                        $cocina_puerta->puerta_gomas = $request->puerta_gomas;
                        $cocina_puerta->cajones_uniones = $request->cajones_uniones;
                        $cocina_puerta->cajones_silicon = $request->cajones_silicon;
                        $cocina_puerta->cajones_limpieza = $request->cajones_limpieza;
                        $cocina_puerta->cajones_jaladeras = $request->cajones_jaladeras;
                        $cocina_puerta->cajones_cantos = $request->cajones_cantos;
                        $cocina_puerta->cajones_rieles = $request->cajones_rieles;
                        $cocina_puerta->cajones_estantes = $request->cajones_estantes;
                        $cocina_puerta->cajones_pzas_comp = $request->cajones_pzas_comp;
                        $cocina_puerta->alacena_entrepano = $request->alacena_entrepano;
                        $cocina_puerta->alacena_pistones = $request->alacena_pistones;
                        $cocina_puerta->alacena_jaladeras = $request->alacena_jaladeras;
                        $cocina_puerta->alacena_micro = $request->alacena_micro;
                        $cocina_puerta->alacena_cantos = $request->alacena_cantos;
                        $cocina_puerta->alacena_limpieza = $request->alacena_limpieza;
                        $cocina_puerta->alacena_parches = $request->alacena_parches;
                        $cocina_puerta->save();

                    
                    break;
                }
                case 2:{
                        $closet_acabado = Closet_acabado::findOrFail($request->id);
                            //Puertas alineados
                            $closet_acabado->p_ali_der = $request->p_ali_der;
                            $closet_acabado->p_ali_izq = $request->p_ali_izq;
                            $closet_acabado->p_ali_princ = $request->p_ali_princ;
                            $closet_acabado->p_ali_baja = $request->p_ali_baja;
                            //Puertas limpieza
                            $closet_acabado->p_limp_der = $request->p_limp_der;
                            $closet_acabado->p_limp_izq = $request->p_limp_izq;
                            $closet_acabado->p_limp_princ = $request->p_limp_princ;
                            $closet_acabado->p_limp_baja = $request->p_limp_baja;
                            //Puertas silicon
                            $closet_acabado->p_sil_der = $request->p_sil_der;
                            $closet_acabado->p_sil_izq = $request->p_sil_izq;
                            $closet_acabado->p_sil_princ = $request->p_sil_princ;
                            $closet_acabado->p_sil_baja = $request->p_sil_baja;
                            //Cajones alineados
                            $closet_acabado->c_ali_der = $request->c_ali_der;
                            $closet_acabado->c_ali_izq = $request->c_ali_izq;
                            $closet_acabado->c_ali_princ = $request->c_ali_princ;
                            $closet_acabado->c_ali_baja = $request->c_ali_baja;
                            //Cajones cantos
                            $closet_acabado->c_cant_der = $request->c_cant_der;
                            $closet_acabado->c_cant_izq = $request->c_cant_izq;
                            $closet_acabado->c_cant_princ = $request->c_cant_princ;
                            $closet_acabado->c_cant_baja = $request->c_cant_baja;
                            //Cajones uniones
                            $closet_acabado->c_union_der = $request->c_union_der;
                            $closet_acabado->c_union_izq = $request->c_union_izq;
                            $closet_acabado->c_union_princ = $request->c_union_princ;
                            $closet_acabado->c_union_baja = $request->c_union_baja;
                            //Cajones silicon
                            $closet_acabado->c_sil_der = $request->c_sil_der;
                            $closet_acabado->c_sil_izq = $request->c_sil_izq;
                            $closet_acabado->c_sil_princ = $request->c_sil_princ;
                            $closet_acabado->c_sil_baja = $request->c_sil_baja;
                            //Cajones limpieza
                            $closet_acabado->c_limp_der = $request->c_limp_der;
                            $closet_acabado->c_limp_izq = $request->c_limp_izq;
                            $closet_acabado->c_limp_princ = $request->c_limp_princ;
                            $closet_acabado->c_limp_baja = $request->c_limp_baja;
                            //Cajones tornillos
                            $closet_acabado->c_torn_der = $request->c_torn_der;
                            $closet_acabado->c_torn_izq = $request->c_torn_izq;
                            $closet_acabado->c_torn_princ = $request->c_torn_princ;
                            $closet_acabado->c_torn_baja = $request->c_torn_baja;
                            //Cajones parches
                            $closet_acabado->c_parch_der = $request->c_parch_der;
                            $closet_acabado->c_parch_izq = $request->c_parch_izq;
                            $closet_acabado->c_parch_princ = $request->c_parch_princ;
                            $closet_acabado->c_parch_baja = $request->c_parch_baja;
                            $closet_acabado->save();

                        $closet_interior = Closet_interior::findOrFail($request->id);
                            //Puertas tiradores
                            $closet_interior->p_tira_der = $request->p_tira_der;
                            $closet_interior->p_tira_izq = $request->p_tira_izq;
                            $closet_interior->p_tira_princ = $request->p_tira_princ;
                            $closet_interior->p_tira_baja = $request->p_tira_baja;
                            //Puertas funcionamiento
                            $closet_interior->p_func_der = $request->p_func_der;
                            $closet_interior->p_func_izq = $request->p_func_izq;
                            $closet_interior->p_func_princ = $request->p_func_princ;
                            $closet_interior->p_func_baja = $request->p_func_baja;
                            //Cajones jaladeras
                            $closet_interior->c_jalad_der = $request->c_jalad_der;
                            $closet_interior->c_jalad_izq = $request->c_jalad_izq;
                            $closet_interior->c_jalad_princ = $request->c_jalad_princ;
                            $closet_interior->c_jalad_baja = $request->c_jalad_baja;
                            //Cajones rieles
                            $closet_interior->c_riel_der = $request->c_riel_der;
                            $closet_interior->c_riel_izq = $request->c_riel_izq;
                            $closet_interior->c_riel_princ = $request->c_riel_princ;
                            $closet_interior->c_riel_baja = $request->c_riel_baja;
                            //Cajones estantes
                            $closet_interior->c_estant_der = $request->c_estant_der;
                            $closet_interior->c_estant_izq = $request->c_estant_izq;
                            $closet_interior->c_estant_princ = $request->c_estant_princ;
                            $closet_interior->c_estant_baja = $request->c_estant_baja;
                            //Cajones entrepaños
                            $closet_interior->c_entr_der = $request->c_entr_der;
                            $closet_interior->c_entr_izq = $request->c_entr_izq;
                            $closet_interior->c_entr_princ = $request->c_entr_princ;
                            $closet_interior->c_entr_baja = $request->c_entr_baja;
                            //Cajones tubos colga
                            $closet_interior->c_tubos_der = $request->c_tubos_der;
                            $closet_interior->c_tubos_izq = $request->c_tubos_izq;
                            $closet_interior->c_tubos_princ = $request->c_tubos_princ;
                            $closet_interior->c_tubos_baja = $request->c_tubos_baja;
                            //Cajones daños
                            $closet_interior->c_danos_der = $request->c_danos_der;
                            $closet_interior->c_danos_izq = $request->c_danos_izq;
                            $closet_interior->c_danos_princ = $request->c_danos_princ;
                            $closet_interior->c_danos_baja = $request->c_danos_baja;
                            //Cajones abre correct
                            $closet_interior->c_correct_der = $request->c_correct_der;
                            $closet_interior->c_correct_izq = $request->c_correct_izq;
                            $closet_interior->c_correct_princ = $request->c_correct_princ;
                            $closet_interior->c_correct_baja = $request->c_correct_baja;
                            //Cajones pzas compl
                            $closet_interior->c_pzasc_der = $request->c_pzasc_der;
                            $closet_interior->c_pzasc_izq = $request->c_pzasc_izq;
                            $closet_interior->c_pzasc_princ = $request->c_pzasc_princ;
                            $closet_interior->c_pzasc_baja = $request->c_pzasc_baja;
                            //Cajones abatimiento
                            $closet_interior->c_abatim_der = $request->c_abatim_der;
                            $closet_interior->c_abatim_izq = $request->c_abatim_izq;
                            $closet_interior->c_abatim_princ = $request->c_abatim_princ;
                            $closet_interior->c_abatim_baja = $request->c_abatim_baja;
                            //Cajones visagras
                            $closet_interior->c_visagras_der = $request->c_visagras_der;
                            $closet_interior->c_visagras_izq = $request->c_visagras_izq;
                            $closet_interior->c_visagras_princ = $request->c_visagras_princ;
                            $closet_interior->c_visagras_baja = $request->c_visagras_baja;
                            $closet_interior->save();

                        $closet_otro = Closet_otro::findOrFail($request->id);
                            $closet_otro->pared_dan_der = $request->pared_dan_der;
                            $closet_otro->pared_dan_izq = $request->pared_dan_izq;
                            $closet_otro->pared_dan_princ = $request->pared_dan_princ;
                            $closet_otro->pared_dan_baja = $request->pared_dan_baja;
                            //Paredes limpieza
                            $closet_otro->pared_limp_der = $request->pared_limp_der;
                            $closet_otro->pared_limp_izq = $request->pared_limp_izq;
                            $closet_otro->pared_limp_princ = $request->pared_limp_princ;
                            $closet_otro->pared_limp_baja = $request->pared_limp_baja;
                            //Closet Cenefa sup
                            $closet_otro->clo_censup_der = $request->clo_censup_der;
                            $closet_otro->clo_censup_izq = $request->clo_censup_izq;
                            $closet_otro->clo_censup_princ = $request->clo_censup_princ;
                            $closet_otro->clo_censup_baja = $request->clo_censup_baja;
                            //Closet Cenefa inf
                            $closet_otro->clo_ceninf_der = $request->clo_ceninf_der;
                            $closet_otro->clo_ceninf_izq = $request->clo_ceninf_izq;
                            $closet_otro->clo_ceninf_princ = $request->clo_ceninf_princ;
                            $closet_otro->clo_ceninf_baja = $request->clo_ceninf_baja;
                            //Closet color madera
                            $closet_otro->clo_madera_der = $request->clo_madera_der;
                            $closet_otro->clo_madera_izq = $request->clo_madera_izq;
                            $closet_otro->clo_madera_princ = $request->clo_madera_princ;
                            $closet_otro->clo_madera_baja = $request->clo_madera_baja;
                            //Closet Alinec jalad
                            $closet_otro->clo_alin_der = $request->clo_alin_der;
                            $closet_otro->clo_alin_izq = $request->clo_alin_izq;
                            $closet_otro->clo_alin_princ = $request->clo_alin_princ;
                            $closet_otro->clo_alin_baja = $request->clo_alin_baja;
                            //Closet pandeadura
                            $closet_otro->clo_pande_der = $request->clo_pande_der;
                            $closet_otro->clo_pande_izq = $request->clo_pande_izq;
                            $closet_otro->clo_pande_princ = $request->clo_pande_princ;
                            $closet_otro->clo_pande_baja = $request->clo_pande_baja;
                            //Closet soporte
                            $closet_otro->clo_soporte_der = $request->clo_soporte_der;
                            $closet_otro->clo_soporte_izq = $request->clo_soporte_izq;
                            $closet_otro->clo_soporte_princ = $request->clo_soporte_princ;
                            $closet_otro->clo_soporte_baja = $request->clo_soporte_baja;
                            $closet_otro->save();
                    break;                
                }
            }
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function getRecepcion (Request $request){

        $resultados = Recep_equipamiento::leftJoin('cocina_acabados','recep_equipamientos.id','=','cocina_acabados.id')
            ->leftJoin('cocina_puertas','recep_equipamientos.id','=','cocina_puertas.id')
            ->leftJoin('cocina_otras','recep_equipamientos.id','=','cocina_otras.id')
            ->leftJoin('closet_acabados','recep_equipamientos.id','=','closet_acabados.id')
            ->leftJoin('closet_interiores','recep_equipamientos.id','=','closet_interiores.id')
            ->leftJoin('closet_otros','recep_equipamientos.id','=','closet_otros.id')

            ->select('recep_equipamientos.id as solicitud_id',
                'cocina_acabados.cubierta_acab_uniones','cocina_acabados.cubierta_acab_silicon','cocina_acabados.cubierta_acab_cortes',
                'cocina_acabados.puerta_acab_alineados','cocina_acabados.puerta_acab_cantos',
                
                'cocina_otras.estufa_instalacion','cocina_otras.estufa_pzas_extra','cocina_otras.estufa_manuales','cocina_otras.estufa_danos',
                'cocina_otras.tarja_danos','cocina_otras.tarja_pzas_extra',
                
                'cocina_puertas.puerta_danos','cocina_puertas.puerta_tornillos','cocina_puertas.puerta_abatimiento','cocina_puertas.puerta_limpieza',
                'cocina_puertas.puerta_jaladera','cocina_puertas.puerta_gomas','cocina_puertas.cajones_uniones',
                'cocina_puertas.cajones_silicon','cocina_puertas.cajones_limpieza','cocina_puertas.cajones_jaladeras',
                'cocina_puertas.cajones_cantos', 'cocina_puertas.cajones_rieles','cocina_puertas.cajones_estantes','cocina_puertas.cajones_pzas_comp',
                'cocina_puertas.alacena_entrepano','cocina_puertas.alacena_pistones','cocina_puertas.alacena_jaladeras',
                'cocina_puertas.alacena_micro','cocina_puertas.alacena_cantos','cocina_puertas.alacena_limpieza','cocina_puertas.alacena_parches',

                'closet_acabados.p_ali_der','closet_acabados.p_ali_izq','closet_acabados.p_ali_princ','closet_acabados.p_ali_baja',
                'closet_acabados.p_limp_der','closet_acabados.p_limp_izq','closet_acabados.p_limp_princ','closet_acabados.p_limp_baja',
                'closet_acabados.p_sil_der','closet_acabados.p_sil_izq','closet_acabados.p_sil_princ','closet_acabados.p_sil_baja',
                'closet_acabados.c_ali_der','closet_acabados.c_ali_izq','closet_acabados.c_ali_princ','closet_acabados.c_ali_baja',
                'closet_acabados.c_cant_der','closet_acabados.c_cant_izq','closet_acabados.c_cant_princ','closet_acabados.c_cant_baja',
                'closet_acabados.c_union_der','closet_acabados.c_union_izq','closet_acabados.c_union_princ','closet_acabados.c_union_baja',
                'closet_acabados.c_sil_der','closet_acabados.c_sil_izq','closet_acabados.c_sil_princ','closet_acabados.c_sil_baja',
                'closet_acabados.c_limp_der','closet_acabados.c_limp_izq','closet_acabados.c_limp_princ','closet_acabados.c_limp_baja',
                'closet_acabados.c_torn_der','closet_acabados.c_torn_izq','closet_acabados.c_torn_princ','closet_acabados.c_torn_baja',
                'closet_acabados.c_parch_der','closet_acabados.c_parch_izq','closet_acabados.c_parch_princ','closet_acabados.c_parch_baja',

                'closet_interiores.p_tira_der','closet_interiores.p_tira_izq','closet_interiores.p_tira_princ','closet_interiores.p_tira_baja',
                'closet_interiores.p_func_der','closet_interiores.p_func_izq','closet_interiores.p_func_princ','closet_interiores.p_func_baja',
                'closet_interiores.c_jalad_der','closet_interiores.c_jalad_izq','closet_interiores.c_jalad_princ','closet_interiores.c_jalad_baja',
                'closet_interiores.c_riel_der','closet_interiores.c_riel_izq','closet_interiores.c_riel_princ','closet_interiores.c_riel_baja',
                'closet_interiores.c_estant_der','closet_interiores.c_estant_izq','closet_interiores.c_estant_princ','closet_interiores.c_estant_baja',
                'closet_interiores.c_entr_der','closet_interiores.c_entr_izq','closet_interiores.c_entr_princ','closet_interiores.c_entr_baja',
                'closet_interiores.c_tubos_der','closet_interiores.c_tubos_izq','closet_interiores.c_tubos_princ',
                'closet_interiores.c_tubos_baja','closet_interiores.c_danos_der','closet_interiores.c_danos_izq',
                'closet_interiores.c_danos_princ','closet_interiores.c_danos_baja','closet_interiores.c_correct_der',
                'closet_interiores.c_correct_izq','closet_interiores.c_correct_princ','closet_interiores.c_correct_baja',
                'closet_interiores.c_pzasc_der','closet_interiores.c_pzasc_izq','closet_interiores.c_pzasc_princ',
                'closet_interiores.c_pzasc_baja','closet_interiores.c_abatim_der','closet_interiores.c_abatim_izq',
                'closet_interiores.c_abatim_princ','closet_interiores.c_abatim_baja','closet_interiores.c_visagras_der',
                'closet_interiores.c_visagras_izq','closet_interiores.c_visagras_princ','closet_interiores.c_visagras_baja',

                'closet_otros.pared_dan_der','closet_otros.pared_dan_izq','closet_otros.pared_dan_princ',
                'closet_otros.pared_dan_baja','closet_otros.pared_limp_der','closet_otros.pared_limp_izq',
                'closet_otros.pared_limp_princ','closet_otros.pared_limp_baja','closet_otros.clo_censup_der',
                'closet_otros.clo_censup_izq','closet_otros.clo_censup_princ','closet_otros.clo_censup_baja',
                'closet_otros.clo_ceninf_der','closet_otros.clo_ceninf_izq','closet_otros.clo_ceninf_princ',
                'closet_otros.clo_ceninf_baja','closet_otros.clo_madera_der','closet_otros.clo_madera_izq',
                'closet_otros.clo_madera_princ','closet_otros.clo_madera_baja','closet_otros.clo_alin_der',
                'closet_otros.clo_alin_izq','closet_otros.clo_alin_princ','closet_otros.clo_alin_baja',
                'closet_otros.clo_pande_der','closet_otros.clo_pande_izq','closet_otros.clo_pande_princ',
                'closet_otros.clo_pande_baja','closet_otros.clo_soporte_der','closet_otros.clo_soporte_izq',
                'closet_otros.clo_soporte_princ','closet_otros.clo_soporte_baja'
                
            )
            ->where('recep_equipamientos.id','=',$request->id)
            ->get();

        return [
            'resultados' => $resultados,
        ];
    }

    public function recepcionClosetsPDF($id){
        $resultados = Recep_equipamiento::leftJoin('cocina_acabados','recep_equipamientos.id','=','cocina_acabados.id')
        ->leftJoin('cocina_puertas','recep_equipamientos.id','=','cocina_puertas.id')
        ->leftJoin('cocina_otras','recep_equipamientos.id','=','cocina_otras.id')
        ->leftJoin('closet_acabados','recep_equipamientos.id','=','closet_acabados.id')
        ->leftJoin('closet_interiores','recep_equipamientos.id','=','closet_interiores.id')
        ->leftJoin('closet_otros','recep_equipamientos.id','=','closet_otros.id')
        ->leftJoin('solic_equipamientos','recep_equipamientos.id','=','solic_equipamientos.id')
        ->leftJoin('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
        ->leftJoin('proveedores','equipamientos.proveedor_id','=','proveedores.id')
        ->leftJoin('contratos','solic_equipamientos.contrato_id','=','contratos.id')
        ->leftJoin('lotes','solic_equipamientos.lote_id','=','lotes.id')
        ->leftJoin('creditos','contratos.id','=','creditos.id')
        ->leftJoin('licencias', 'lotes.id', '=', 'licencias.id')
        ->leftJoin('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->leftJoin('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
        ->leftJoin('personal as c', 'clientes.id', '=', 'c.id')

        ->select('recep_equipamientos.id as solicitud_id',
            'cocina_acabados.cubierta_acab_uniones','cocina_acabados.cubierta_acab_silicon','cocina_acabados.cubierta_acab_cortes',
            'cocina_acabados.puerta_acab_alineados','cocina_acabados.puerta_acab_cantos',
            
            'cocina_otras.estufa_instalacion','cocina_otras.estufa_pzas_extra','cocina_otras.estufa_manuales','cocina_otras.estufa_danos',
            'cocina_otras.tarja_danos','cocina_otras.tarja_pzas_extra',
            
            'cocina_puertas.puerta_danos','cocina_puertas.puerta_tornillos','cocina_puertas.puerta_abatimiento','cocina_puertas.puerta_limpieza',
            'cocina_puertas.puerta_jaladera','cocina_puertas.puerta_gomas','cocina_puertas.cajones_uniones',
            'cocina_puertas.cajones_silicon','cocina_puertas.cajones_limpieza','cocina_puertas.cajones_jaladeras',
            'cocina_puertas.cajones_cantos', 'cocina_puertas.cajones_rieles','cocina_puertas.cajones_estantes','cocina_puertas.cajones_pzas_comp',
            'cocina_puertas.alacena_entrepano','cocina_puertas.alacena_pistones','cocina_puertas.alacena_jaladeras',
            'cocina_puertas.alacena_micro','cocina_puertas.alacena_cantos','cocina_puertas.alacena_limpieza','cocina_puertas.alacena_parches',

            'closet_acabados.p_ali_der','closet_acabados.p_ali_izq','closet_acabados.p_ali_princ','closet_acabados.p_ali_baja',
            'closet_acabados.p_limp_der','closet_acabados.p_limp_izq','closet_acabados.p_limp_princ','closet_acabados.p_limp_baja',
            'closet_acabados.p_sil_der','closet_acabados.p_sil_izq','closet_acabados.p_sil_princ','closet_acabados.p_sil_baja',
            'closet_acabados.c_ali_der','closet_acabados.c_ali_izq','closet_acabados.c_ali_princ','closet_acabados.c_ali_baja',
            'closet_acabados.c_cant_der','closet_acabados.c_cant_izq','closet_acabados.c_cant_princ','closet_acabados.c_cant_baja',
            'closet_acabados.c_union_der','closet_acabados.c_union_izq','closet_acabados.c_union_princ','closet_acabados.c_union_baja',
            'closet_acabados.c_sil_der','closet_acabados.c_sil_izq','closet_acabados.c_sil_princ','closet_acabados.c_sil_baja',
            'closet_acabados.c_limp_der','closet_acabados.c_limp_izq','closet_acabados.c_limp_princ','closet_acabados.c_limp_baja',
            'closet_acabados.c_torn_der','closet_acabados.c_torn_izq','closet_acabados.c_torn_princ','closet_acabados.c_torn_baja',
            'closet_acabados.c_parch_der','closet_acabados.c_parch_izq','closet_acabados.c_parch_princ','closet_acabados.c_parch_baja',

            'closet_interiores.p_tira_der','closet_interiores.p_tira_izq','closet_interiores.p_tira_princ','closet_interiores.p_tira_baja',
            'closet_interiores.p_func_der','closet_interiores.p_func_izq','closet_interiores.p_func_princ','closet_interiores.p_func_baja',
            'closet_interiores.c_jalad_der','closet_interiores.c_jalad_izq','closet_interiores.c_jalad_princ','closet_interiores.c_jalad_baja',
            'closet_interiores.c_riel_der','closet_interiores.c_riel_izq','closet_interiores.c_riel_princ','closet_interiores.c_riel_baja',
            'closet_interiores.c_estant_der','closet_interiores.c_estant_izq','closet_interiores.c_estant_princ','closet_interiores.c_estant_baja',
            'closet_interiores.c_entr_der','closet_interiores.c_entr_izq','closet_interiores.c_entr_princ','closet_interiores.c_entr_baja',
            'closet_interiores.c_tubos_der','closet_interiores.c_tubos_izq','closet_interiores.c_tubos_princ',
            'closet_interiores.c_tubos_baja','closet_interiores.c_danos_der','closet_interiores.c_danos_izq',
            'closet_interiores.c_danos_princ','closet_interiores.c_danos_baja','closet_interiores.c_correct_der',
            'closet_interiores.c_correct_izq','closet_interiores.c_correct_princ','closet_interiores.c_correct_baja',
            'closet_interiores.c_pzasc_der','closet_interiores.c_pzasc_izq','closet_interiores.c_pzasc_princ',
            'closet_interiores.c_pzasc_baja','closet_interiores.c_abatim_der','closet_interiores.c_abatim_izq',
            'closet_interiores.c_abatim_princ','closet_interiores.c_abatim_baja','closet_interiores.c_visagras_der',
            'closet_interiores.c_visagras_izq','closet_interiores.c_visagras_princ','closet_interiores.c_visagras_baja',

            'closet_otros.pared_dan_der','closet_otros.pared_dan_izq','closet_otros.pared_dan_princ',
            'closet_otros.pared_dan_baja','closet_otros.pared_limp_der','closet_otros.pared_limp_izq',
            'closet_otros.pared_limp_princ','closet_otros.pared_limp_baja','closet_otros.clo_censup_der',
            'closet_otros.clo_censup_izq','closet_otros.clo_censup_princ','closet_otros.clo_censup_baja',
            'closet_otros.clo_ceninf_der','closet_otros.clo_ceninf_izq','closet_otros.clo_ceninf_princ',
            'closet_otros.clo_ceninf_baja','closet_otros.clo_madera_der','closet_otros.clo_madera_izq',
            'closet_otros.clo_madera_princ','closet_otros.clo_madera_baja','closet_otros.clo_alin_der',
            'closet_otros.clo_alin_izq','closet_otros.clo_alin_princ','closet_otros.clo_alin_baja',
            'closet_otros.clo_pande_der','closet_otros.clo_pande_izq','closet_otros.clo_pande_princ',
            'closet_otros.clo_pande_baja','closet_otros.clo_soporte_der','closet_otros.clo_soporte_izq',
            'closet_otros.clo_soporte_princ','closet_otros.clo_soporte_baja',
            'creditos.modelo',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'proveedores.proveedor',
            'lotes.casa_muestra',
            'recep_equipamientos.supervisor',
            'recep_equipamientos.observacion',
            'recep_equipamientos.fecha_revision'
            
        )
        ->where('recep_equipamientos.id','=',$id)
        ->get();

        $pdf = \PDF::loadview('pdf.DocsPostVenta.RecepcionClosets', ['resultados' => $resultados]);
        //$pdf->setPaper('A4','landscape');
        return $pdf->stream('recepcion_de_closets.pdf');
    }

    public function recepcionCocinaPDF(Request $request){
        $resultados = Recep_equipamiento::leftJoin('cocina_acabados','recep_equipamientos.id','=','cocina_acabados.id')
        ->leftJoin('cocina_puertas','recep_equipamientos.id','=','cocina_puertas.id')
        ->leftJoin('cocina_otras','recep_equipamientos.id','=','cocina_otras.id')
        ->leftJoin('closet_acabados','recep_equipamientos.id','=','closet_acabados.id')
        ->leftJoin('closet_interiores','recep_equipamientos.id','=','closet_interiores.id')
        ->leftJoin('closet_otros','recep_equipamientos.id','=','closet_otros.id')
        ->leftJoin('solic_equipamientos','recep_equipamientos.id','=','solic_equipamientos.id')
        ->leftJoin('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
        ->leftJoin('proveedores','equipamientos.proveedor_id','=','proveedores.id')
        ->leftJoin('contratos','solic_equipamientos.contrato_id','=','contratos.id')
        ->leftJoin('lotes','solic_equipamientos.lote_id','=','lotes.id')
        ->leftJoin('creditos','contratos.id','=','creditos.id')
        ->leftJoin('licencias', 'lotes.id', '=', 'licencias.id')
        ->leftJoin('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->leftJoin('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
        ->leftJoin('personal as c', 'clientes.id', '=', 'c.id')

        ->select('recep_equipamientos.id as solicitud_id',
            'cocina_acabados.cubierta_acab_uniones','cocina_acabados.cubierta_acab_silicon','cocina_acabados.cubierta_acab_cortes',
            'cocina_acabados.puerta_acab_alineados','cocina_acabados.puerta_acab_cantos',
            
            'cocina_otras.estufa_instalacion','cocina_otras.estufa_pzas_extra','cocina_otras.estufa_manuales','cocina_otras.estufa_danos',
            'cocina_otras.tarja_danos','cocina_otras.tarja_pzas_extra',
            
            'cocina_puertas.puerta_danos','cocina_puertas.puerta_tornillos','cocina_puertas.puerta_abatimiento','cocina_puertas.puerta_limpieza',
            'cocina_puertas.puerta_jaladera','cocina_puertas.puerta_gomas','cocina_puertas.cajones_uniones',
            'cocina_puertas.cajones_silicon','cocina_puertas.cajones_limpieza','cocina_puertas.cajones_jaladeras',
            'cocina_puertas.cajones_cantos', 'cocina_puertas.cajones_rieles','cocina_puertas.cajones_estantes','cocina_puertas.cajones_pzas_comp',
            'cocina_puertas.alacena_entrepano','cocina_puertas.alacena_pistones','cocina_puertas.alacena_jaladeras',
            'cocina_puertas.alacena_micro','cocina_puertas.alacena_cantos','cocina_puertas.alacena_limpieza','cocina_puertas.alacena_parches',

            'closet_acabados.p_ali_der','closet_acabados.p_ali_izq','closet_acabados.p_ali_princ','closet_acabados.p_ali_baja',
            'closet_acabados.p_limp_der','closet_acabados.p_limp_izq','closet_acabados.p_limp_princ','closet_acabados.p_limp_baja',
            'closet_acabados.p_sil_der','closet_acabados.p_sil_izq','closet_acabados.p_sil_princ','closet_acabados.p_sil_baja',
            'closet_acabados.c_ali_der','closet_acabados.c_ali_izq','closet_acabados.c_ali_princ','closet_acabados.c_ali_baja',
            'closet_acabados.c_cant_der','closet_acabados.c_cant_izq','closet_acabados.c_cant_princ','closet_acabados.c_cant_baja',
            'closet_acabados.c_union_der','closet_acabados.c_union_izq','closet_acabados.c_union_princ','closet_acabados.c_union_baja',
            'closet_acabados.c_sil_der','closet_acabados.c_sil_izq','closet_acabados.c_sil_princ','closet_acabados.c_sil_baja',
            'closet_acabados.c_limp_der','closet_acabados.c_limp_izq','closet_acabados.c_limp_princ','closet_acabados.c_limp_baja',
            'closet_acabados.c_torn_der','closet_acabados.c_torn_izq','closet_acabados.c_torn_princ','closet_acabados.c_torn_baja',
            'closet_acabados.c_parch_der','closet_acabados.c_parch_izq','closet_acabados.c_parch_princ','closet_acabados.c_parch_baja',

            'closet_interiores.p_tira_der','closet_interiores.p_tira_izq','closet_interiores.p_tira_princ','closet_interiores.p_tira_baja',
            'closet_interiores.p_func_der','closet_interiores.p_func_izq','closet_interiores.p_func_princ','closet_interiores.p_func_baja',
            'closet_interiores.c_jalad_der','closet_interiores.c_jalad_izq','closet_interiores.c_jalad_princ','closet_interiores.c_jalad_baja',
            'closet_interiores.c_riel_der','closet_interiores.c_riel_izq','closet_interiores.c_riel_princ','closet_interiores.c_riel_baja',
            'closet_interiores.c_estant_der','closet_interiores.c_estant_izq','closet_interiores.c_estant_princ','closet_interiores.c_estant_baja',
            'closet_interiores.c_entr_der','closet_interiores.c_entr_izq','closet_interiores.c_entr_princ','closet_interiores.c_entr_baja',
            'closet_interiores.c_tubos_der','closet_interiores.c_tubos_izq','closet_interiores.c_tubos_princ',
            'closet_interiores.c_tubos_baja','closet_interiores.c_danos_der','closet_interiores.c_danos_izq',
            'closet_interiores.c_danos_princ','closet_interiores.c_danos_baja','closet_interiores.c_correct_der',
            'closet_interiores.c_correct_izq','closet_interiores.c_correct_princ','closet_interiores.c_correct_baja',
            'closet_interiores.c_pzasc_der','closet_interiores.c_pzasc_izq','closet_interiores.c_pzasc_princ',
            'closet_interiores.c_pzasc_baja','closet_interiores.c_abatim_der','closet_interiores.c_abatim_izq',
            'closet_interiores.c_abatim_princ','closet_interiores.c_abatim_baja','closet_interiores.c_visagras_der',
            'closet_interiores.c_visagras_izq','closet_interiores.c_visagras_princ','closet_interiores.c_visagras_baja',

            'closet_otros.pared_dan_der','closet_otros.pared_dan_izq','closet_otros.pared_dan_princ',
            'closet_otros.pared_dan_baja','closet_otros.pared_limp_der','closet_otros.pared_limp_izq',
            'closet_otros.pared_limp_princ','closet_otros.pared_limp_baja','closet_otros.clo_censup_der',
            'closet_otros.clo_censup_izq','closet_otros.clo_censup_princ','closet_otros.clo_censup_baja',
            'closet_otros.clo_ceninf_der','closet_otros.clo_ceninf_izq','closet_otros.clo_ceninf_princ',
            'closet_otros.clo_ceninf_baja','closet_otros.clo_madera_der','closet_otros.clo_madera_izq',
            'closet_otros.clo_madera_princ','closet_otros.clo_madera_baja','closet_otros.clo_alin_der',
            'closet_otros.clo_alin_izq','closet_otros.clo_alin_princ','closet_otros.clo_alin_baja',
            'closet_otros.clo_pande_der','closet_otros.clo_pande_izq','closet_otros.clo_pande_princ',
            'closet_otros.clo_pande_baja','closet_otros.clo_soporte_der','closet_otros.clo_soporte_izq',
            'closet_otros.clo_soporte_princ','closet_otros.clo_soporte_baja',
            'creditos.modelo',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'proveedores.proveedor',
            'lotes.casa_muestra',
            'recep_equipamientos.supervisor',
            'recep_equipamientos.observacion',
            'recep_equipamientos.fecha_revision'
            
        )
        ->where('recep_equipamientos.id','=',$request->id)
        ->get();

        $pdf = \PDF::loadview('pdf.DocsPostVenta.RecepcionCocina', ['resultados' => $resultados]);
        //$pdf->setPaper('A4','landscape');
        return $pdf->stream('recepcion_de_closets.pdf');
    }

    public function recepcionGeneralPDF(Request $request){
        $resultados = Recep_equipamiento::leftJoin('cocina_acabados','recep_equipamientos.id','=','cocina_acabados.id')
        ->leftJoin('cocina_puertas','recep_equipamientos.id','=','cocina_puertas.id')
        ->leftJoin('cocina_otras','recep_equipamientos.id','=','cocina_otras.id')
        ->leftJoin('closet_acabados','recep_equipamientos.id','=','closet_acabados.id')
        ->leftJoin('closet_interiores','recep_equipamientos.id','=','closet_interiores.id')
        ->leftJoin('closet_otros','recep_equipamientos.id','=','closet_otros.id')
        ->leftJoin('solic_equipamientos','recep_equipamientos.id','=','solic_equipamientos.id')
        ->leftJoin('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
        ->leftJoin('proveedores','equipamientos.proveedor_id','=','proveedores.id')
        ->leftJoin('contratos','solic_equipamientos.contrato_id','=','contratos.id')
        ->leftJoin('lotes','solic_equipamientos.lote_id','=','lotes.id')
        ->leftJoin('creditos','contratos.id','=','creditos.id')
        ->leftJoin('licencias', 'lotes.id', '=', 'licencias.id')
        ->leftJoin('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->leftJoin('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
        ->leftJoin('personal as c', 'clientes.id', '=', 'c.id')

        ->select('recep_equipamientos.id as solicitud_id','equipamientos.equipamiento',
            'creditos.modelo',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'proveedores.proveedor',
            'lotes.casa_muestra',
            'recep_equipamientos.supervisor',
            'recep_equipamientos.observacion',
            'recep_equipamientos.fecha_revision'
            
        )
        ->where('recep_equipamientos.id','=',$request->id)
        ->get();

        $pdf = \PDF::loadview('pdf.DocsPostVenta.RecepcionGeneral', ['resultados' => $resultados]);
        //$pdf->setPaper('A4','landscape');
        return $pdf->stream('recepcion_de_trabajos.pdf');
    }
}
