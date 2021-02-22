<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc_puente extends Model
{
    protected $table = 'docs_puente'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [ 'puente_id',
                            'descripcion',
                            'archivo',
                            'clasificacion'
                        ];
}
