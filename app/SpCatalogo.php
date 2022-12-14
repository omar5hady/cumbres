<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpCatalogo extends Model
{
    protected $fillable = [
        'cargo', 'concepto', 'departamento_id'
    ];
}
