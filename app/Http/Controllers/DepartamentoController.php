<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Departamento;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $departamentos = Departamento::orderBy('id_departamento','desc')->paginate(5);
        }
        else{
            $departamentos = Departamento::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_departamento','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $departamentos->total(),
                'current_page'  => $departamentos->currentPage(),
                'per_page'      => $departamentos->perPage(),
                'last_page'     => $departamentos->lastPage(),
                'from'          => $departamentos->firstItem(),
                'to'            => $departamentos->lastItem(),
            ],
            'departamentos' => $departamentos
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
    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $departamento = new Departamento();
        $departamento->departamento = $request->departamento;
        $departamento->user_alta = $request->user_alta;
        $departamento->save();
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
    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $departamento = Departamento::findOrFail($request->id_departamento);
        $departamento->departamento = $request->departamento;
        $departamento->user_alta = $request->user_alta;
        $departamento->save();
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
        $departamento = Departamento::findOrFail($request->id_departamento);
        $departamento->delete();
    }
}
