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
       'comisiones',

       'calendario',
       'notifications',

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
        'digital_campain',

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
        'ruv',
        'seg_ruv',

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
        'estimaciones',

        //Ventas
        'lotes_disp',
        'mis_prospectos',
        'simulacion_credito',
        'hist_simulaciones',
        'hist_creditos',
        'contratos',
        'docs',
        'equipamientos',
        'digital_lead',
        'reubicaciones',

        //Saldos
        'edo_cuenta',
        'depositos',
        'gastos_admn',
        'cobro_credito',
        'dev_cancel',
        'dev_exc',
        'facturas',
        'ingresos_concretania',
        'dev_virtual',

        //Gestoria
        'expediente',
        'asig_gestor',
        'seg_tramite',
        'avaluos',
        'bonos_rec',

        //Postventa
        'entregas',
        'solic_detalles',

        //Comisiones
        'exp_comision',
        'gen_comision',
        'bono_com',

        //Acceso
        'usuarios',
        'roles',

        //RH
        'vehiculos',
        'mant_vehiculos',
        'admin_mant_vehiculos',

        //Oficina
        'inventarios',
        'prov_inventarios',

        //Reportes
        'mejora',
        'rep_publi',
        'rep_proy',
        'inventario',
        'rep_venta_canc',
        'rep_asesores',
        'rep_ini_term_ventas',
        'rep_recursos_propios',
        'rep_vent_modelos',
        'rep_detalles_post',
        'rep_acumulado',
        'rep_leads',
        'rep_entregas',

        //Cotizador Lotes
        'calc_lotes',
        'edit_cotizacion',
        'opc_cotizador',

        //CREDITOS PUENTE
        'bases',
        'solic_credito_puente',
        'seg_cp',
        'edo_cta_bancrea',
        
        //Pagos Internos
        'seg_pago'
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
        return $this->belongsTo('App\Personal');
    }

    public function solicitudes_pagos(){
        return $this->hasMany('App\Solicitudes_pago');
    }



}
