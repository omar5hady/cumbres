<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Digital_lead extends Model
{
    protected $table = 'digital_leads'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'medio_contacto',
        'medio_publicidad',
        'campaña_id',
        'nombre',
        'apellidos',
        'email',
        'celular',
        'telefono',
        'proyecto_interes',
        'modelo_interes',
        'rango1',
        'rango2',
        'edo_civil',
        'perfil_cliente',
        'ingresos',
        'coacreditado',
        'hijos',
        'num_hijos',
        'mascotas',
        'tam_mascota',
        'tipo_credito',
        'tipo_uso',
        'empresa',
        'status',
        'vendedor_asign'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function obs_leads(){
        return $this->hasMany('App\Obs_lead');
    }

    public function campania(){
        return $this->belongsTo('App\Campania');
    }
}
