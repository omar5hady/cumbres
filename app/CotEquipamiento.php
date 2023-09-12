<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotEquipamiento extends Model
{
    protected $fillable = [
        'lote_id',
        'cliente_id',
        'cocina_tradicional',
        'vestidor',
        'closets',
        'canceles',
        'persianas',
        'calentador_paso',
        'calentador_solar',
        'espejos',
        'tanque_estacionario',
        'cocina',
        'usuario'
    ];
}
