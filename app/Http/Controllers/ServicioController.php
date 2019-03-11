<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio;

class ServicioController extends Controller
{
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $servicios = Servicio::orderBy('descripcion','asc')->paginate(5);
        }
        else{
            $servicios = Servicio::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','asc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $servicios->total(),
                'current_page'  => $servicios->currentPage(),
                'per_page'      => $servicios->perPage(),
                'last_page'     => $servicios->lastPage(),
                'from'          => $servicios->firstItem(),
                'to'            => $servicios->lastItem(),
            ],
            'servicios' => $servicios
        ];

    }

    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = new Servicio();
        $servicios->descripcion = $request->descripcion;
        $servicios->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::findOrFail($request->id);
        $servicios->descripcion = $request->descripcion;
        $servicios->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::findOrFail($request->id);
        $servicios->delete();
    }

    public function selectServicio(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::select('id','descripcion')
        ->orderBy('descripcion', 'asc')->get();
 
        return ['servicios' => $servicios];
    }

    public function servicioPdf(Request $request)
    {
        
        $servicios = Servicio::orderBy('descripcion','asc')->take(1)->get();


            $pdf = \PDF::loadview('pdf.cartaDeServicios',['servicios' => $servicios]);
            return $pdf->stream('servicios.pdf');
            // return ['cabecera' => $cabecera];
     }
}
