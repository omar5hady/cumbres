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
Route::get('/modelo','ModeloController@index');
Route::post('/modelo/registrar','ModeloController@store');
Route::put('/modelo/actualizar','ModeloController@update');
Route::delete('/modelo/eliminar','ModeloController@destroy');
Route::post('/formSubmit/{id}','ModeloController@formSubmit');
Route::get('/download/{fileName}' , 'ModeloController@downloadFile');

////////////////////        RUTAS LOTES    /////////////////////////////////
Route::get('/lote','LoteController@index');
Route::post('/lote/registrar','LoteController@store');
Route::put('/lote/actualizar','LoteController@update');
Route::delete('/lote/eliminar','LoteController@destroy');
Route::post('/lote/registrar_manzana','LoteController@storeManzana');
Route::post('/import', 'LoteController@import');

////////////////////        RUTAS TERRENOS    /////////////////////////////////
Route::get('/terreno','TerrenoController@index');
Route::post('/terreno/registrar','TerrenoController@store');
Route::put('/terreno/actualizar','TerrenoController@update');
Route::delete('/terreno/eliminar','TerrenoController@destroy');

////////////////////        RUTAS PRECIO ETAPA    /////////////////////////////////
Route::get('/precio_etapa','PrecioEtapaController@index');
Route::post('/precio_etapa/registrar','PrecioEtapaController@store');
Route::put('/precio_etapa/actualizar','PrecioEtapaController@update');
Route::delete('/precio_etapa/eliminar','PrecioEtapaController@destroy');

/***************************************************************************** */
///////////////////       RUTAS SELECT    ////////////////////////////////////
Route::get('/select_departamentos','DepartamentoController@selectDepartamento');
Route::get('/select_colonias','CiudadController@selectColonias');
Route::get('/select_empresas','EmpresaController@selectEmpresa');
Route::get('/select_ciudades','CiudadController@selectCiudades');
Route::get('/select_personal','PersonalController@selectNombre'); //Nombre completo de persona (Directivos activos)
Route::get('/select_fraccionamiento','FraccionamientoController@selectFraccionamiento'); 
Route::get('/select_Frac_Tipo','FraccionamientoController@selectFrac_Tipo');
Route::get('/contador_etapa','EtapaController@contEtapa'); 
Route::get('/select_etapa_proyecto','EtapaController@selectEtapa_proyecto'); 
Route::get('/select_modelo_proyecto','ModeloController@selectModelo_proyecto');
Route::get('/select_manzana_proyecto','LoteController@selectManzana_proyecto'); 
Route::get('/select_construcc_terreno','ModeloController@selectConsYTerreno'); 


