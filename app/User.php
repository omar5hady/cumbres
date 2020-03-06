<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','usuario', 'password', 'condicion', 'rol_id','foto_user',

       'administracion',
       'desarrollo',
       'precios',
       'obra',
       'ventas',
       'acceso',
       'reportes',
       'saldo',
       'gestoria',
       'postventa',

        //Administracion
        'departamentos',
        'personas',
        'empresas',
        'medios_public',
        'lugares_contacto',
        'servicios',
        'inst_financiamiento',
        'tipos_credito',
        'asig_servicios',
        'mis_asesores',
        'cuenta',
        'proveedores',

        //Desarrollo
        'fraccionamiento',
        'etapas',
        'modelos',
        'lotes',
        'asign_modelos',
        'licencias',
        'acta_terminacion',
        'p_etapa',
        'p_fraccionamiento',
        'descarga_actas',

        //Precios
        'agregar_sobreprecios',
        'precios_etapas',
        'sobreprecios',
        'paquetes',
        'promociones',

        //Obra
        'contratistas',
        'ini_obra',
        'aviso_obra',
        'partidas',
        'avance',

        //Ventas
        'lotes_disp',
        'mis_prospectos',
        'simulacion_credito',
        'hist_simulaciones',
        'hist_creditos',
        'contratos',
        'docs',
        'equipamientos',

        //Saldos
        'edo_cuenta',
        'depositos',
        'gastos_admn',
        'cobro_credito',
        'dev_cancel',
        'dev_exc',

        //Gestoria
        'expediente',
        'asig_gestor',
        'seg_tramite',
        'avaluos',

        //Postventa
        'entregas',
        'solic_detalles',

        //Acceso
        'usuarios',
        'roles',

        //Reportes
        'mejora',
        'rep_publi',
        'rep_proy'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rol(){
        return $this->belongsTo('App\Rol');
    }
 
    public function persona(){
        return $this->belongsTo('App\Persona');
    }


}
