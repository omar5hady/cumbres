<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fraccionamiento; //Importar el modelo
use App\Etapa;
use App\Modelo;
use App\Lote;
use File;

class FraccionamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $fraccionamientos = Fraccionamiento::orderBy('id','desc')->paginate(8);
        }
        else{
            $fraccionamientos = Fraccionamiento::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $fraccionamientos->total(),
                'current_page'  => $fraccionamientos->currentPage(),
                'per_page'      => $fraccionamientos->perPage(),
                'last_page'     => $fraccionamientos->lastPage(),
                'from'          => $fraccionamientos->firstItem(),
                'to'            => $fraccionamientos->lastItem(),
            ],
            'fraccionamientos' => $fraccionamientos
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
        $fraccionamiento = new Fraccionamiento();
        $fraccionamiento->nombre = $request->nombre;
        $fraccionamiento->tipo_proyecto = $request->tipo_proyecto;
        $fraccionamiento->calle = $request->calle;
        $fraccionamiento->colonia = $request->colonia;
        $fraccionamiento->estado = $request->estado;
        $fraccionamiento->ciudad = $request->ciudad;
        $fraccionamiento->delegacion = $request->delegacion;
        $fraccionamiento->cp = $request->cp;
        $fraccionamiento->save();

        
        $etapa = new Etapa();
        $etapa->fraccionamiento_id = $fraccionamiento->id;
        $etapa->num_etapa = "Sin Asignar";
        
        $etapa->personal_id = 1;
        $etapa->save();

        $modelo = new Modelo();
        $modelo->nombre = "Por Asignar";
        $modelo->fraccionamiento_id = $fraccionamiento->id;
        $modelo->tipo = $fraccionamiento->tipo_proyecto;
        $modelo->terreno = 0;
        $modelo->construccion = 0;
        $modelo->save();

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
        $fraccionamiento = Fraccionamiento::findOrFail($request->id);
        $fraccionamiento->nombre = $request->nombre;
        $fraccionamiento->tipo_proyecto = $request->tipo_proyecto;
        $fraccionamiento->calle = $request->calle;
        $fraccionamiento->colonia = $request->colonia;
        $fraccionamiento->estado = $request->estado;
        $fraccionamiento->ciudad = $request->ciudad;
        $fraccionamiento->delegacion = $request->delegacion;
        $fraccionamiento->cp = $request->cp;
        $fraccionamiento->save();
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
        $fraccionamiento = Fraccionamiento::findOrFail($request->id);

        $contadorModelo=Modelo::where('fraccionamiento_id','=',$fraccionamiento->id)->get()->count();
        $contadorEtapa=Etapa::where('fraccionamiento_id','=',$fraccionamiento->id)->get()->count();

        if($contadorEtapa == 1 && $contadorModelo == 1){
            $modelo=Modelo::select('id')
                ->where('nombre','=','Por Asignar')
                ->where('fraccionamiento_id','=',$fraccionamiento->id)->get();
            $modeloDelete = Modelo::findOrFail($modelo[0]->id);
            $modeloDelete->delete();

            $etapa=Etapa::select('id')
                ->where('num_etapa','=','Sin Asignar')
                ->where('fraccionamiento_id','=',$fraccionamiento->id)->get();
            $etapaDelete = Etapa::findOrFail($etapa[0]->id);
            $etapaDelete->delete();
        }

        $fraccionamiento->delete();
           
  
    }

    public function selectFraccionamiento(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        
        $fraccionamientos = Fraccionamiento::select('nombre','id')->get();
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFraccionamientoVue(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;

        $fraccionamientos = Fraccionamiento::select('nombre','id')
        ->where('nombre','like','%'.$filtro.'%')->get();
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFraccionamientoConLotes(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $fraccionamientos = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')->
        select('fraccionamientos.nombre','fraccionamientos.id')
        ->groupBy('fraccionamientos.id')->get();
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFrac_Tipo(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $fraccionamiento = Fraccionamiento::select('nombre','id')
        ->where('tipo_proyecto', '=', $buscar)->get();
        return['fraccionamientos' => $fraccionamiento];
    }



     //funciones para carga y descarga de planos

     public function formSubmitPlanos(Request $request, $id)
     {
 
         $fileName = time().'.'.$request->archivo_planos->getClientOriginalExtension();
         $moved =  $request->archivo_planos->move(public_path('/files/fraccionamientos/planos'), $fileName);
 
         if($moved){
             if(!$request->ajax())return redirect('/');
             $planos = Fraccionamiento::findOrFail($request->id);
             $planos->archivo_planos = $fileName;
             $planos->id = $id;
             $planos->save(); //Insert
     
             }
         
         return response()->json(['success'=>'You have successfully upload file.']);
     }
 
     public function downloadFilePlanos($fileName){
         
         $pathtoFile = public_path().'/files/fraccionamientos/planos/'.$fileName;
         return response()->download($pathtoFile);
     }


      //funciones para carga y descarga de archivos de escrituras

      public function formSubmitEscrituras(Request $request, $id)
      {
  
          $fileName = time().'.'.$request->archivo_escrituras->getClientOriginalExtension();
          $moved =  $request->archivo_escrituras->move(public_path('/files/fraccionamientos/escrituras'), $fileName);
  
          if($moved){
              if(!$request->ajax())return redirect('/');
              $escrituras = Fraccionamiento::findOrFail($request->id);
              $escrituras->archivo_escrituras = $fileName;
              $escrituras->id = $id;
              $escrituras->save(); //Insert
      
              }
          
          return response()->json(['success'=>'You have successfully upload file.']);
      }
  
      public function downloadFileEscrituras($fileName){
          
          $pathtoFile = public_path().'/files/fraccionamientos/escrituras/'.$fileName;
          return response()->download($pathtoFile);
      }

      public function uploadPlantillaTelecom (Request $request, $id){

        $plantillaAnterior = Fraccionamiento::select('plantilla_telecom','id')
                                            ->where('id','=',$id)
                                            ->get();

  if($plantillaAnterior->isEmpty()==1){
        $fileName = uniqid().'.'.$request->plantilla_telecom->getClientOriginalExtension();
        $moved =  $request->plantilla_telecom->move(public_path('/files/fraccionamientos/plantillasTelecom/'), $fileName);

        if($moved){
            if(!$request->ajax())return redirect('/');
            $plantillaTelecom = Fraccionamiento::findOrFail($request->id);
            $plantillaTelecom->plantilla_telecom = $fileName;
            $plantillaTelecom->id = $id;
            $plantillaTelecom->save(); //Insert
    
            }
        return back();
      }
    else{
        $pathAnterior = public_path().'/files/fraccionamientos/plantillasTelecom/'.$plantillaAnterior[0]->plantilla_telecom;
        File::delete($pathAnterior); 
        
        $fileName = uniqid().'.'.$request->plantilla_telecom->getClientOriginalExtension();
        $moved =  $request->plantilla_telecom->move(public_path('/files/fraccionamientos/plantillasTelecom/'), $fileName);

        if($moved){
            if(!$request->ajax())return redirect('/');
            $plantillaTelecom = Fraccionamiento::findOrFail($request->id);
            $plantillaTelecom->plantilla_telecom = $fileName;
            $plantillaTelecom->id = $id;
            $plantillaTelecom->save(); //Insert
    
            }
        return back();

    }
      }

      public function downloadPlantillaTelecom ($fileName){
        $pathtoFile = public_path().'/files/fraccionamientos/plantillasTelecom/'.$fileName;
        return response()->download($pathtoFile);
    }

}
