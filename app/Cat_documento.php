<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cat_documento extends Model
{
    protected $table = 'cat_documentos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'documento','categoria'];
}
