<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Base_presupuestal extends Model
{
    protected $table = 'bases_presupuestales'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
       'modelo_id',
       'activo',
       'valor_venta',
       'credito_id',
        //// COMISIONES BANCARIAS :
            'insc_conjunto',
            'int_nafin',
            'gastos_esc',
            'comision_int',
            'int_cpuente',
            'int_pago_terreno',
        ///// BASES PRESUPUESTALES
            'valor_terreno',
            'permisos',
            'presupuesto_urb',
            'presupuesto_edif',
            'equipamiento',
            'escritura_gcc',
            'laboratorio',
            'gastos_ind_op',
            'gastos_comerc',
            'comicion_venta',
            'fianzas',
            'partida_inflacionaria',
            'adicional_terreno',
        ///// INDICIES PRESUPUESTALES
            'ct_urbanizado',
            'ct_brena',
            'c_pavimentacion',
            'c_edificacion1',
            'c_edificacion2',
            'precio_venta_const',
            'c_adquisicion_brena'
    ];
}
