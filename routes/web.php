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
Route::get('/lote2','LoteController@index2');
Route::post('/lote/registrar','LoteController@store');
Route::put('/lote/actualizar','LoteController@update');
Route::put('/lote/actualizar2','LoteController@update2');
Route::put('/lote/actualizar3','LoteController@asignarMod');
Route::delete('/lote/eliminar','LoteController@destroy');
Route::post('/import', 'LoteController@import');
Route::get('/lote_aviso','LoteController@indexIniObra');
Route::put('/lotes/enviarAviObra','LoteController@enviarAviso');

///////////////////       RUTAS LICENCIA   ////////////////////////////////////
Route::get('/licencias','LicenciasController@index');
Route::put('/licencias/actualizar','LicenciasController@update');
Route::get('/licencias/resume','LicenciasController@resumeLicencias');
Route::post('/formSubmit/{id}','LicenciasController@formSubmit');
Route::get('/download/{fileName}' , 'LicenciasController@downloadFile');

///////////////////       RUTAS LICENCIA   ////////////////////////////////////
Route::get('/acta_terminacion','LicenciasController@indexActa');
Route::put('/acta_terminacion/actualizar','LicenciasController@updateActas');
Route::get('/licencias/resume','LicenciasController@resumeLicencias');
Route::get('/licencias/resume_excel','LicenciasController@exportExcel');

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
//Route::post('/precio_modelo/registrar','PrecioEtapaController@storePrecioModelo');

////////////////////        RUTAS PRECIO MODELO    /////////////////////////////////
Route::get('/precio_modelo','PrecioModeloController@index');
Route::post('/precio_modelo/registrar','PrecioModeloController@store');
Route::put('/precio_modelo/actualizar','PrecioModeloController@update');
Route::delete('/precio_modelo/eliminar','PrecioModeloController@destroy');

////////////////////        RUTAS SOBREPRECIO ETAPA    /////////////////////////////////
Route::get('/sobreprecio_etapa','SobreprecioEtapaController@index');
Route::post('/sobreprecio_etapa/registrar','SobreprecioEtapaController@store');
Route::put('/sobreprecio_etapa/actualizar','SobreprecioEtapaController@update');
Route::delete('/sobreprecio_etapa/eliminar','SobreprecioEtapaController@destroy');


////////////////////        RUTAS SOBREPRECIO MODELO    /////////////////////////////////
Route::get('/sobreprecio_modelo','SobreprecioModeloController@index');
Route::post('/sobreprecio_modelo/registrar','SobreprecioModeloController@store');
Route::put('/sobreprecio_modelo/actualizar','SobreprecioModeloController@update');
Route::delete('/sobreprecio_modelo/eliminar','SobreprecioModeloController@destroy');

////////////////////        RUTAS PAQUETES    /////////////////////////////////
Route::get('/paquete','PaqueteController@index');
Route::post('/paquete/registrar','PaqueteController@store');
Route::put('/paquete/actualizar','PaqueteController@update');
Route::delete('/paquete/eliminar','PaqueteController@destroy');

////////////////////        RUTAS PROMOCIONES    /////////////////////////////////
Route::get('/promocion','PromocionController@index');
Route::post('/promocion/registrar','PromocionController@store');
Route::put('/promocion/actualizar','PromocionController@update');
Route::delete('/promocion/eliminar','PromocionController@destroy');

////////////////////        RUTAS LOTES en PROMOCION    /////////////////////////////////
Route::get('/lote_promocion','LotePromocionController@index');
Route::post('/lote_promocion/registrar','LotePromocionController@store');
Route::put('/lote_promocion/actualizar','LotePromocionController@update');
Route::delete('/lote_promocion/eliminar','LotePromocionController@destroy');

////////////////////        RUTAS CONTRATISTAS    /////////////////////////////////
Route::get('/contratista','ContratistaController@index');
Route::post('/contratista/registrar','ContratistaController@store');
Route::put('/contratista/actualizar','ContratistaController@update');
Route::delete('/contratista/eliminar','ContratistaController@destroy');

////////////////////        RUTAS INICIO DE OBRA    /////////////////////////////////
Route::get('/iniobra','IniObraController@index');
Route::post('/iniobra/registrar','IniObraController@store');
Route::put('/iniobra/actualizar','IniObraController@update');
Route::delete('/iniobra/eliminar','IniObraController@destroy');

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
Route::get('/select_modelos_etapa','LoteController@select_modelos_etapa'); 
Route::get('/select_manzanas_etapa','LoteController@select_manzanas_etapa'); 
Route::get('/select_lotes_manzana','LoteController@select_lote_manzana'); 
Route::get('/select_precio_etapa','PrecioEtapaController@selectPrecioEtapa'); 
Route::get('/select_sobreprecios_etapa','SobreprecioEtapaController@select_sobreprecios_etapa'); 
Route::get('/select_contratistas','ContratistaController@selectContratista'); 
/***************************************************************************** */

/**********************************RUTAS OBSERVACION*************************** */
Route::post('/observacion/registrar','ObservacionController@store');
Route::get('/observacion/select_ultima','ObservacionController@select_ultima'); 
Route::get('/observacion','ObservacionController@index');



