<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lotes_individuales extends Model
{
    protected $table = 'lotes_individuales';
    protected $fillable = [
        'id', 'costom2', 'etapa_id'
    ];
}
