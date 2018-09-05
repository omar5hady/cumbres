<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('contenido/contenido');
});

///////////////////        RUTAS DEPARTAMENTO    //////////////////////////////////
Route::get('/departamento','DepartamentoController@index');
Route::post('/departamento/registrar','DepartamentoController@store');
Route::put('/departamento/actualizar','DepartamentoController@update');
Route::delete('/departamento/eliminar','DepartamentoController@destroy');

///////////////////     RUTAS FRACCIONAMIENTO     ////////////////////////////////
Route::get('/fraccionamiento','FraccionamientoController@index');
Route::post('/fraccionamiento/registrar','FraccionamientoController@store');
Route::put('/fraccionamiento/actualizar','FraccionamientoController@update');
Route::delete('/fraccionamiento/eliminar','FraccionamientoController@destroy');

/////////////////////   RUTAS ETAPAS        //////////////////////////////////////
Route::get('/etapa','EtapaController@index');
Route::post('/etapa/registrar','EtapaController@store');
Route::put('/etapa/actualizar','EtapaController@update');
Route::delete('/etapa/eliminar','EtapaController@destroy');

///////////////////     RUTAS PERSONAL      ////////////////////////////////////
Route::get('/personal','PersonalController@index');
Route::post('/personal/registrar','PersonalController@store');
Route::put('/personal/actualizar','PersonalController@update');
Route::put('/personal/desactivar','PersonalController@desactivar');
Route::put('/personal/activar','PersonalController@activar');
Route::delete('/personal/eliminar','PersonalController@destroy');

////////////////////        RUTAS EMPRESA     /////////////////////////////////
Route::get('/empresa','EmpresaController@index');
Route::post('/empresa/registrar','EmpresaController@store');
Route::put('/empresa/actualizar','EmpresaController@update');
Route::delete('/empresa/eliminar','EmpresaController@destroy');

////////////////////        RUTAS MODELOS     /////////////////////////////////
Route::get('/modelo','ModelosController@index');
Route::post('/modelo/registrar','ModelosController@store');
Route::put('/modelo/actualizar','ModelosController@update');
Route::delete('/modelo/eliminar','ModelosController@destroy');


/***************************************************************************** */
///////////////////       RUTAS SELECT    ////////////////////////////////////
Route::get('/select_departamentos','DepartamentoController@selectDepartamento');
Route::get('/select_colonias','CiudadController@selectColonias');
Route::get('/select_empresas','EmpresaController@selectEmpresa');
Route::get('/select_ciudades','CiudadController@selectCiudades');
Route::get('/select_personal','PersonalController@selectNombre'); //Nombre completo de persona (Directivos activos)
Route::get('/select_fraccionamiento','FraccionamientoController@selectFraccionamiento'); 
Route::get('/contador_etapa','EtapaController@contEtapa'); 

