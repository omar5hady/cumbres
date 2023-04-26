<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalVaccine extends Model
{
    protected $fillable = [
        'record_id',
        'vacuna',
        'lote',
    ];
}
