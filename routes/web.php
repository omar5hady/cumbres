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

Route::get('/departamento','DepartamentoController@index');
Route::post('/departamento/registrar','DepartamentoController@store');
Route::put('/departamento/actualizar','DepartamentoController@update');
Route::delete('/departamento/eliminar','DepartamentoController@destroy');

//Rutas Fraccionamiento
Route::get('/fraccionamiento','FraccionamientoController@index');
Route::post('/fraccionamiento/registrar','FraccionamientoController@store');
Route::put('/fraccionamiento/actualizar','FraccionamientoController@update');
Route::delete('/fraccionamiento/eliminar','FraccionamientoController@destroy');

Route::get('/select_ciudades','CiudadController@selectCiudades');

//Rutas Personal
Route::get('/personal','PersonalController@index');
Route::post('/personal/registrar','PersonalController@store');
Route::put('/personal/actualizar','PersonalController@update');
Route::put('/personal/desactivar','PersonalController@desactivar');
Route::put('/personal/activar','PersonalController@activar');
Route::delete('/personal/eliminar','PersonalController@destroy');

//Rutas Empresa
Route::get('/empresa','EmpresaController@index');
Route::post('/empresa/registrar','EmpresaController@store');
Route::put('/empresa/actualizar','EmpresaController@update');
Route::delete('/empresa/eliminar','EmpresaController@destroy');

Route::get('/select_departamentos','DepartamentoController@selectDepartamento');
Route::get('/select_colonias','CiudadController@selectColonias');
Route::get('/select_empresas','EmpresaController@selectEmpresa');


