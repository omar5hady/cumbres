<?php

namespace App\Http\Controllers\Modelo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modelo;
use App\CatEquipamiento;
use Auth;

class EquipamientoController extends Controller
{
    public function store(Request $request){
        $search = CatEquipamiento::where('modelo_id','=',$request->modelo_id)
            ->where('status','=',1)->get();

        if(sizeof($search)>0){
            $e_ant = CatEquipamiento::findOrFail($search[0]->id);
            $e_ant->status = 0;
            $e_ant->save();
        }
        $e = new CatEquipamiento();
        $e->modelo_id = $request->modelo_id;
        $e->cocina_tradicional = $request->cocina_tradicional;
        $e->vestidor = $request->vestidor;
        $e->closets = $request->closets;
        $e->canceles = $request->canceles;
        $e->persianas = $request->persianas;
        $e->calentador_paso = $request->calentador_paso;
        $e->calentador_solar = $request->calentador_solar;
        $e->espejos = $request->espejos;
        $e->tanque_estacionario = $request->tanque_estacionario;
        $e->cocina = $request->cocina;
        $e->usuario = Auth::user()->usuario;
        $e->save();
    }

    public function update(Request $request){
        $e_ant = CatEquipamiento::findOrFail($request->id);
        $e_ant->status = 0;
        $e_ant->save();

        $e = new CatEquipamiento();
        $e->modelo_id = $request->modelo_id;
        $e->cocina_tradicional = $request->cocina_tradicional;
        $e->vestidor = $request->vestidor;
        $e->closets = $request->closets;
        $e->canceles = $request->canceles;
        $e->persianas = $request->persianas;
        $e->calentador_paso = $request->calentador_paso;
        $e->calentador_solar = $request->calentador_solar;
        $e->espejos = $request->espejos;
        $e->tanque_estacionario = $request->tanque_estacionario;
        $e->cocina = $request->cocina;
        $e->usuario = Auth::user()->usuario;
        $e->save();
    }

    public function index(Request $request){
        $catalogo = CatEquipamiento::join('modelos as m','cat_equipamientos.modelo_id','=','m.id')
            ->join('fraccionamientos as f','m.fraccionamiento_id','=','f.id')
            ->select('f.nombre as proyecto', 'f.id as proyecto_id', 'm.nombre as modelo','cat_equipamientos.*')
            ->where('cat_equipamientos.status','=',$request->b_status);
            if($request->b_modelo != '')
                $catalogo = $catalogo->where('cat_equipamientos.modelo_id','=',$request->b_modelo);
            if($request->b_proyecto != '')
                $catalogo = $catalogo->where('f.id','=',$request->b_proyecto);
            if($request->b_fecha1 != '')
                $catalogo = $catalogo->whereBetween('cat_equipamientos.created_at', [$request->b_fecha1, $request->b_fecha2]);

        $catalogo = $catalogo->paginate(8);
        return $catalogo;
    }
}
