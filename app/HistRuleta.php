<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistRuleta extends Model
{
    protected $fillable = [
        'ip',
        'lead_id',
        'nombre'
    ];
}
