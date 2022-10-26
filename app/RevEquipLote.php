<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RevEquipLote extends Model
{
    protected $fillable = [
        'solicitud_id','categoria','subcategoria','concepto','check1','check2','check3'
    ];
}
