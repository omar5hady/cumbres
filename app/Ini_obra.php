<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ini_obra extends Model
{
    protected $table = 'ini_obras'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','contratista_id','f_ini','f_fin','clave',
                           'total_costo_directo','total_costo_idirecto','total_importe','anticipo','total_anticipo',
                            'tipo','iva','descripcion_corta','descripcion_larga','total_superficie','documento',
                            'porc_garantia_ret','garantia_ret','num_casas', 'calle1', 'calle2' ,'registro_obra',
                            'fin_estimaciones', 'total_original'
                        ];

    public function fraccionamiento(){
        return $this->hasMany('App\Fraccionamiento');
    }


    public function contratista(){
        return $this->hasMany('App\Contratista');
    }

    public function ini_obra_lote(){
        return $this->hasMany('App\Ini_obra_lote');
    }

    public function estimaciones(){
        return $this->hasMany('App\Estimaciones');
    }


}
