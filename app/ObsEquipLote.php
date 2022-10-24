<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObsEquipLote extends Model
{
    protected $fillable = [
        'solicitud_id','observacion','usuario'
    ];

}
