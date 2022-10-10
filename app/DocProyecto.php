<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocProyecto extends Model
{
    protected $fillable = [
        'lote_id', 'file_id', 'tipo', 'carpeta'
    ];
}
