<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistDonativo extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'user_id',
        'item_id',
        'status',
    ];
}
