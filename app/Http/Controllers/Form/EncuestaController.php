<?php

namespace App\Http\Controllers\Form;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contrato;
use Excel;
use File;

class EncuestaController extends Controller
{
    public function showEncuesta1(){
        return view('form/formulario');
    }

    // public function getDatosContrato(Request $request){
    //     $datos = Contrato::join('creditos','contratos.id','=','creditos.id')
    //             ->join('')
    // }

    public function pruebaExcel(Request $request){
        
        
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
    
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
    
                

                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                    $reader->ignoreEmpty();
                })->get();
                $i = 1;

                foreach ($data as $index => $pagina) {
                    if($i == $index)
                        return $pagina[12]['d'];
                }
    
                
    
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
        
    }
}
