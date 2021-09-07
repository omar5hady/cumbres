<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reubicacion extends Model
{
    protected $table = 'reubicaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
                    'contrato_id','lote_id','cliente_id',
                    'asesor_id','promocion','tipo_credito',
                    'institucion','valor_terreno','observacion'
                ];
}
