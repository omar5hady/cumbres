<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'id','sexo', 'tipo_casa', 'email_institucional', 'lugar_contacto','proyecto_interes_id',
        'publicidad_id','edo_civil','nss','curp','vendedor_id','empresa','coacreditado','clasificacion',
        'sexo_coa','tipo_casa_coa','email_institucional_coa','empresa_coa','edo_civil_coa','nss_coa',
        'curp_coa','nombre_coa','apellidos_coa','f_nacimiento_coa','rfc_coa','homoclave_coa','direccion_coa',
        'colonia_coa','cp_coa','telefono_coa','ext_coa','celular_coa','email_coa','parentesco_coa',
        'nacionalidad','nacionalidad_coa','puesto','lugar_nacimiento','precio_rango','ingreso'
    ];
 
    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function vendedor(){
        return $this->belongsTo('App\Vendedor');
    }

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }

    public function medio_publicidad(){
        return $this->belongsTo('App\Medio_publicidad');
    }

    public function cliente_observacion(){
        return $this->hasMany('App\Cliente_observacion');
    }

    public function Apartado(){
        return $this->hasMany('App\Apartado');
    }

    public function Credito(){
        return $this->hasMany('App\Credito');
    }
}
