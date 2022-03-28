<?php
namespace App\Http\Controllers;
use App\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Lote_promocion;
use App\Lote;
use App\User;
use Auth;
use DB;
//Controlador para el modelo Lote_promocion.
class LotePromocionController extends Controller
{
    //Función que retorna los lotes asignados a una promoción
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $promocion_id = $request->promocion_id;
                
            $lotes_promocion = Lote_promocion::join('lotes','lotes_promocion.lote_id','=','lotes.id')
            ->join('promociones','lotes_promocion.promocion_id','=','promociones.id')
            ->select('lotes.num_lote as lote','promociones.nombre as promocion', 'lotes.manzana as manzana',
                    'lotes_promocion.id','lotes_promocion.lote_id','lotes_promocion.promocion_id')
            ->where('lotes_promocion.promocion_id', '=', $promocion_id)
            ->orderBy('lotes.manzana', 'asc')
            ->orderBy('lotes.num_lote', 'asc')->paginate(8);
            
        return [
            'pagination' => [
                'total'         => $lotes_promocion->total(),
                'current_page'  => $lotes_promocion->currentPage(),
                'per_page'      => $lotes_promocion->perPage(),
                'last_page'     => $lotes_promocion->lastPage(),
                'from'          => $lotes_promocion->firstItem(),
                'to'            => $lotes_promocion->lastItem(),
            ],
            'lotes_promocion' => $lotes_promocion
        ];
    }
    //Función para registrar un lote a una promoción.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $id = $request->lote_id;
        $lote_promocion = new Lote_promocion();
        $lote_promocion->lote_id = $request->lote_id;
        $lote_promocion->promocion_id = $request->promocion_id;
        $lote_promocion->save();
    }

    //funcion para actualizar un lote a una promoción
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote_promocion = Lote_promocion::findOrFail($request->id);
        $lote_promocion->lote_id = $request->lote_id;
        $lote_promocion->promocion_id = $request->promocion_id;

        $lote_promocion->save();
    }
    //Función para eliminar el registro
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lote_promocion = Lote_promocion::findOrFail($request->id);
        $lote_promocion->delete();
    }
}
