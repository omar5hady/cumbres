<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanoProyecto extends Model
{
    protected $fillable = [
        'lote_id', 'file_id', 'tipo', 'description'
    ];
}
