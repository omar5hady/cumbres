<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote_promocion;
use App\Lote;
use Auth;
use App\User;
use App\Notifications\NotifyAdmin;
use DB;
use Carbon\Carbon;

class LotePromocionController extends Controller
{
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
            ->orderBy('lotes_promocion.id', 'promociones.nombre')->paginate(8);
        
            
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $id = $request->lote_id;
        $lote_promocion = new Lote_promocion();
        $lote_promocion->lote_id = $request->lote_id;
        $lote_promocion->promocion_id = $request->promocion_id;

        $lote_promocion->save();

        $lotes_promocion = Lote_promocion::join('lotes','lotes_promocion.lote_id','=','lotes.id')
            ->join('promociones','lotes_promocion.promocion_id','=','promociones.id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->select('lotes.num_lote as lote','promociones.nombre as promocion','fraccionamientos.nombre as proyecto',
                    'promociones.v_fin')
            ->where('lotes.id', '=', $id)->get();

            setlocale(LC_TIME, 'es');
            $fecha_fin = new Carbon($lotes_promocion[0]->v_fin);
            $lotes_promocion[0]->v_fin = $fecha_fin->formatLocalized('%d-%m-%Y');

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $msj = "Aprovecha la promoción para el lote # ". $lotes_promocion[0]->lote . " del proyecto " . $lotes_promocion[0]->proyecto . " (Vencimiento: " . $lotes_promocion[0]->v_fin . ") ";
            $iniciosObra = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => $msj,
                    'titulo' => 'Nueva promoción'
                ]
            ];

            $users = User::select('id')->where('rol_id','=','4')
            ->orWhere('rol_id','=','2')
            ->orWhere('rol_id','=','8')->get();

            foreach($users as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($iniciosObra));
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //funcion para actualizar los datos
    public function update(Request $request)
    {
       if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote_promocion = Lote_promocion::findOrFail($request->id);
        $lote_promocion->lote_id = $request->lote_id;
        $lote_promocion->promocion_id = $request->promocion_id;

        $lote_promocion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $lote_promocion = Lote_promocion::findOrFail($request->id);
        $lote_promocion->delete();
    }
}
