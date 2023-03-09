<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistDescartado extends Model
{
    protected $fillable = [
        'asesor_id', 'prospecto_id'
    ];
}
