<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpFile extends Model
{
    protected $fillable = [
        'solic_id', 'file_id', 'tipo', 'carpeta'
    ];
}
