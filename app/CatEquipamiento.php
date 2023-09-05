<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatEquipamiento extends Model
{
    protected $fillable = [
        'modelo_id',
        'cocina_tradicional',
        'vestidor',
        'closets',
        'canceles',
        'persianas',
        'calentador_paso',
        'calentador_solar',
        'espejos',
        'tanque_estacionario',
        'cocina'
    ];
}
