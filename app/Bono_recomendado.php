<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bono_recomendado extends Model
{
    protected $table = 'bonos_recomendados'; // se referencia a que tabla pertenece el modelo
    
    protected $fillable = [
        'id',
        'fecha_aut1',
        'fecha_aut2',
        'monto',
        'descripcion',
        'ini_promo',
        'fin_promo',
        'recomendado',
        'fecha_pago',
        'status',

        'proyecto_rec',
        'etapa_rec',
        'manzana_rec',
        'lote_rec',
        'fecha_compra_rec',
        'obs',
        'num_recibo',
        'banco'

    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato()
    {
        return $this->belongsTo('App\Contrato');
    }

}
