<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premios extends Model
{
    protected $fillable = [
        'lead_id', 'premio', 'RFC', 'status','descripcion'
    ];
}
