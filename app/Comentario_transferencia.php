<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario_transferencia extends Model
{
    protected $table = 'comentarios_transferencias'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['deposito_id','dep_conc','dep_gcc',
                            'comentario', 'usuario'
                        ];

   
}
