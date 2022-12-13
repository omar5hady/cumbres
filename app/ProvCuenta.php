<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvCuenta extends Model
{
    protected $fillable = [
        'proveedor_id','banco','num_cuenta'
    ];
}
