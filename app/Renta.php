<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Renta extends Model
{
    protected $table = 'rentas';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
            'lote_id',
            //Arrendatario
            'tipo_arrendatario',
            'nombre_arrendatario',
            'tel_arrendatario',
            'clv_lada_arr',
            //Moral arrendatario
            'representante_arrendatario',
            'dir_arrendatario',
            'cp_arrendatario',
            'col_arrendatario',
            'estado_arrendatario',
            'municipio_arrendatario',
            'rfc_arrendatario',
            //Aval (Fiador)
            'tipo_aval',
            'nombre_aval',
            'tel_aval',
            'clv_lada_aval',
                //Moral aval
            'representante_aval',
            'dir_aval',
            'cp_aval',
            'col_aval',
            'estado_aval',
            'municipio_aval',
            //Testigo
            'nombre',
            //Datos contrato
            'monto_renta',
            'status',
            'motivo_cancel',
            'fecha_firma',
            'fecha_ini',
            'fecha_fin',
            'num_meses',
            'dep_garantia',
            'servicios',
            'muebles',
            'luz',
            'agua',
            'gas',
            'archivo_contrato',
            'adendum',
            'email_fisc', 'tel_fisc', 'nombre_fisc',
            'direccion_fisc', 'col_fisc', 'cp_fisc', 'rfc_fisc',
            'cfi_fisc', 'regimen_fisc',
            'banco_fisc', 'num_cuenta_fisc',
            'clabe_fisc'
    ];

    public function lote(){
        return $this->belongsTo('App/Lote');
    }
}
