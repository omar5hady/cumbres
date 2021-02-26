<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puente_checklist extends Model
{
    protected $table = 'puente_checklist'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'solicitud_id','documento_id','listo'];
}
