<?php

use App\Http\Controllers\Rh\FondoAhorro;

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

Route::get('/integracionCobros/exportFormat','CobrosController@exportFormat');
Route::get('/clientes/upApp','ClienteController@upApp');
Route::get('/getClavesLadas','PersonalController@getClavesLadas');

Route::group(['middleware' => ['guest']],function(){

    Route::get('/','Auth\LoginController@showLoginForm');
    Route::get('/login','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');

    Route::get('/encuesta1','Form\EncuestaController@showEncuesta1');
    Route::post('/encuesta1/pruebaExcel','Form\EncuestaController@pruebaExcel');
    

    
});


// rutas usuarios autenticados
Route::group(['middleware' => ['auth']],function(){

    //Notificaciones
    Route::post('/notification/get','NotificationController@get');
    Route::get('/notification/getListado','NotificationController@getListado');
    Route::get('/notification/getRol','NotificationController@getRol');
    Route::get('/notification/getUser','NotificationController@getUser');

    //para desloguearse
    Route::post('/logout','Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    //RUTAS PARA LOS ASESORES
    Route::group(['middleware' => ['Asesor']],function(){
        
    });
    
    //RUTAS PARA GERENTE DE OBRA
    Route::group(['middleware' => ['Gerente_obra']],function(){
    });
    
    // RUTAS PARA GERENTE DE PROYECTOS
    Route::group(['middleware' => ['Gerente_proyectos']],function(){
    }); 

    // RUTAS PARA GERENTE DE VENTAS
    Route::group(['middleware' => ['Gerente_ventas']],function(){
    }); 

    // RUTAS PARA EL ADMINISTRADOR
    Route::group(['middleware' => ['Administrador']],function(){

    ///////////////////        RUTAS CUENTA      //////////////////////////////////
        Route::get('/cuenta','CuentasController@index');
        Route::post('/cuenta/registrar','CuentasController@store');
        Route::put('/cuenta/actualizar','CuentasController@update');
        Route::delete('/cuenta/eliminar','CuentasController@destroy');
        Route::get('/select_cuenta','CuentasController@selectCuenta');
         
    ///////////////////        RUTAS DEPARTAMENTO    //////////////////////////////////
        Route::get('/departamento','DepartamentoController@index');
        Route::post('/departamento/registrar','DepartamentoController@store');
        Route::put('/departamento/actualizar','DepartamentoController@update');
        Route::delete('/departamento/eliminar','DepartamentoController@destroy');
        
    ///////////////////        RUTAS Roles    //////////////////////////////////
        Route::get('/rol','RolController@index');
        Route::post('/rol/registrar','RolController@store');
        Route::put('/rol/actualizar','RolController@update');
        Route::delete('/rol/eliminar','RolController@destroy');
    
    ///////////////////        RUTAS Usuarios    //////////////////////////////////
        Route::get('/usuarios','UserController@index');
        Route::get('/usuarios/selectUser','UserController@selectUser');
        Route::get('/usuarios/getNombre','UserController@getNombre');
        Route::get('/usuario/datos','UserController@obtenerDatos');
        Route::get('/usuario/privilegios','UserController@getPrivilegios');
        Route::post('/usuarios/registrar','UserController@store');
        Route::post('/usuarios/asignar','UserController@asignar');
        Route::put('/usuarios/actualizar','UserController@update');
        Route::put('/usuarios/activar','UserController@activar');
        Route::put('/usuarios/desactivar','UserController@desactivar');
        Route::put('/usuarios/act_privilegios','UserController@updatePrivilegios');
        Route::put('/usuarios/asignar/gerente','UserController@asignarGerentes');
        

    ////////////////// VENDEDORES //////////////////////////////////////////
        Route::get('/asesores','UserController@indexAsesores');
        Route::get('/asesores/clientes','ClienteController@indexProspectos');
        Route::get('/asesores/excel','UserController@excelAsesores');
        Route::get('/select/asesores','UserController@selectAsesores');
        Route::get('/select/asesores2','UserController@selectAsesores2');
        Route::put('/cliente/reasignar','ClienteController@asignarCliente');
        Route::get('/clientes/buscar','ClienteController@buscarNombre');

        Route::get('/asesores/getAsesoresProyecto','UserController@getAsesoresProyecto');
        Route::post('/asesores/asignarProyecto','UserController@asignarProyecto');
        Route::post('/clientes/storeObsGerente','ClienteController@storeObsGerente');
        Route::delete('/asesores/deleteAsignarProy','UserController@deleteAsignarProy');

        Route::get('/pruebaLead','ClienteController@asignarClienteAleatorio');
        
        Route::post('/cliente/reasignar2','ClienteController@asignarCliente2'); // desde vista mis Prospectos
        Route::post('/asesores/formSubmitComprobante/{id}','VendedoresController@formSubmitComprobante');
        Route::post('/asesores/formSubmitINE/{id}','VendedoresController@formSubmitINE');
        Route::post('/asesores/formSubmitCV/{id}','VendedoresController@formSubmitCV');
        Route::get('/asesores/downloadFile/{fileName}' , 'VendedoresController@downloadFile'); //descarga de acta

        Route::put('/asesores/actPeriodoVacacional','VendedoresController@actPeriodoVacacional');

        Route::get('/getBirthdayPeople','ClienteController@getBirthdayPeople');

        //////////// REPORTE VENTAS POR VENDEDOR ///////////////
            Route::get('/vendedores/reporte','VendedoresController@ventasVendedorReporte');

    ///////////////////        RUTAS Prospectos    //////////////////////////////////
        Route::get('/clientes','ClienteController@index');
        Route::get('/clientes_simulacion','ClienteController@clientesSimulacion');
        Route::post('/clientes/registrar','ClienteController@store');
        Route::post('/clientes/storeObservacion','ClienteController@storeObservacion');
        Route::post('/clientes/registrar_coacreditado','ClienteController@storeCoacreditado');
        Route::put('/clientes/actualizar','ClienteController@update');
        Route::put('/clientes/actualizar2','ClienteController@updateProspecto');
        Route::get('/clientes/observacion','ClienteController@listarObservacion');
        Route::get('/clientes/clientesPorReasignar','ClienteController@clientesPorReasignar');
        Route::put('/clientes/desactivar','ClienteController@desactivar');
        Route::put('/clientes/activar','ClienteController@activar');
        Route::put('/clientes/reasignarProspecto','ClienteController@reasignarProspecto'); // Opcion para que el asesor solicite la reasignacion.
        Route::put('/clientes/setVendedorAux','ClienteController@setVendedorAux'); //Opcion para reasiganar el prospecto e indicar al vendedor auxiliar
        Route::get('/prospectos/excel','ClienteController@exportExcelClientesAsesor');
        Route::get('/prospectos/getAsesores','ClienteController@getAsesores');
        Route::get('/prospectos/excel/gerente','ClienteController@exportExcelClientesGerente');
    ///////////////////        RUTAS Medios Publicitarios    //////////////////////////////////
        Route::get('/medio_publicitario','MedioPublicitarioController@index');
        Route::post('/medio_publicitario/registrar','MedioPublicitarioController@store');
        Route::put('/medio_publicitario/actualizar','MedioPublicitarioController@update');
        Route::delete('/medio_publicitario/eliminar','MedioPublicitarioController@destroy');

        Route::post('/customsms', 'TwilioSmsController@sendMessage');

    ///////////////////        RUTAS Inst. Financiamiento    //////////////////////////////////
        Route::get('/institucion_financiamiento','InstitucionFinanciamientoController@index');
        Route::post('/institucion_financiamiento/registrar','InstitucionFinanciamientoController@store');
        Route::put('/institucion_financiamiento/actualizar','InstitucionFinanciamientoController@update');
        Route::delete('/institucion_financiamiento/eliminar','InstitucionFinanciamientoController@destroy');

    ///////////////////        RUTAS Tipos de credito    //////////////////////////////////
        Route::get('/tipo_credito','TipoCreditoController@index');
        Route::post('/tipo_credito/registrar','TipoCreditoController@store');
        Route::put('/tipo_credito/actualizar','TipoCreditoController@update');
        Route::delete('/tipo_credito/eliminar','TipoCreditoController@destroy');

    ///////////////////        RUTAS Lugares de Contacto    //////////////////////////////////
        Route::get('/lugar_contacto','LugarContactoController@index');
        Route::post('/lugar_contacto/registrar','LugarContactoController@store');
        Route::put('/lugar_contacto/actualizar','LugarContactoController@update');
        Route::delete('/lugar_contacto/eliminar','LugarContactoController@destroy');
    
    ///////////////////     RUTAS FRACCIONAMIENTO     ////////////////////////////////
        Route::get('/fraccionamiento','FraccionamientoController@index');
        Route::get('/fraccionamiento/excel','FraccionamientoController@excelIndex');
        Route::post('/fraccionamiento/registrar','FraccionamientoController@store');
        Route::put('/fraccionamiento/actualizar','FraccionamientoController@update');
        Route::delete('/fraccionamiento/eliminar','FraccionamientoController@destroy');
        Route::post('/formSubmitPlanos/{id}','FraccionamientoController@formSubmitPlanos'); //carga de planos
        Route::get('/downloadPlanos/{fileName}' , 'FraccionamientoController@downloadFilePlanos'); //descarga de planos
        Route::post('/formSubmitEscrituras/{id}','FraccionamientoController@formSubmitEscrituras'); //carga de escrituras
        Route::get('/downloadEscrituras/{fileName}' , 'FraccionamientoController@downloadFileEscrituras'); //descarga de escrituras
        Route::post('/formSubmitLogoFraccionamiento/{id}','FraccionamientoController@formSubmitLogoFraccionamiento');
        Route::get('/downloadLogoFraccionamiento/{fileName}' , 'FraccionamientoController@downloadFileLogoFraccionamiento');
        Route::get('/fraccionamiento/datos' , 'FraccionamientoController@getDatos');
        Route::get('/fraccionamiento/getArchivos' , 'FraccionamientoController@getArchivos');
        Route::delete('/fraccionamiento/deletePlanos','FraccionamientoController@deletePlanos');
        Route::delete('/fraccionamiento/deleteEscrituras','FraccionamientoController@deleteEscrituras');
        
    /////////////////////   RUTAS ETAPAS        //////////////////////////////////////
        Route::get('/etapa','EtapaController@index');
        Route::get('/etapa/excel','EtapaController@indexExcel');
        Route::post('/etapa/registrar','EtapaController@store');
        Route::put('/etapa/actualizar','EtapaController@update');
        Route::delete('/etapa/eliminar','EtapaController@destroy');

        Route::post('/formSubmitReglamento/{id}','EtapaController@uploadReglamento');
        Route::post('/formSubmitCartaServicios/{id}','EtapaController@uploadPlantillaCartaServicios');
        Route::post('/formSubmitCartaServicios2/{id}','EtapaController@uploadPlantillaCartaServicios2');
        Route::get('/downloadReglamento/{fileName}' , 'EtapaController@downloadReglamento');
        Route::get('/downloadPlantilla/cartaServicios/{fileName}' , 'EtapaController@downloadPlantillaCartaServicios');
        Route::get('/downloadPlantilla/cartaServicios2/{fileName}' , 'EtapaController@downloadPlantillaCartaServicios2');
        Route::post('/etapas/costoMantenimiento/registrar/{id}','EtapaController@registrarCostoMantenimiento');
        Route::post('/formSubmitTelecom/{id}','EtapaController@uploadPlantillaTelecom');
        Route::get('/downloadPlantilla/ServiciosTelecom/{fileName}' , 'EtapaController@downloadPlantillaTelecom');
        Route::get('/downloadCartaBienvenida/{fileName}' , 'EtapaController@downloadCartaBienvenida');
        Route::get('/downloadFactibilidad/{fileName}' , 'EtapaController@downloadFactibilidad');
        Route::post('/formSubmitCartaBienvenida/{id}','EtapaController@uploadCartaBienvenida');
        Route::post('/formSubmitFactibilidad/{id}','EtapaController@formSubmitFactibilidad');

    ///////////////////     RUTAS PERSONAL      ////////////////////////////////////
        Route::get('/personal','PersonalController@index');
        Route::post('/personal/registrar','PersonalController@store');
        Route::put('/personal/actualizar','PersonalController@update');
        Route::put('/personal/desactivar','PersonalController@desactivar');
        Route::put('/personal/activar','PersonalController@activar');
        Route::delete('/personal/eliminar','PersonalController@destroy');

        Route::get('/personal/indexClientes','PersonalController@indexClientes');
        Route::get('/personal/excelClientes','PersonalController@excelClientes');

    ////////////////////        RUTAS EMPRESA     /////////////////////////////////
        Route::get('/empresa','EmpresaController@index');
        Route::get('/empresa/datos','EmpresaController@getDatosEmpresa');
        Route::post('/empresa/registrar','EmpresaController@store');
        Route::put('/empresa/actualizar','EmpresaController@update');
        Route::delete('/empresa/eliminar','EmpresaController@destroy');

    ////////////////////        RUTAS EMPRESA VERIFICADORA     /////////////////////////////////
        Route::get('/empresa/indexVerificadoras','EmpresaController@indexVerificadoras');
        Route::post('/empresa/storeVerificadora','EmpresaController@storeVerificadora');
        Route::put('/empresa/updateVerificadora','EmpresaController@updateVerificadora');
        Route::delete('/empresa/destroyVerificadora','EmpresaController@destroyVerificadora');
        Route::get('/empresa/selectEmpresaVerificadora','EmpresaController@selectEmpresaVerificadora');
    ////////////////////        RUTAS MODELOS     /////////////////////////////////
        Route::get('/modelo','ModeloController@index'); 
        Route::post('/modelo/registrar','ModeloController@store');
        Route::put('/modelo/actualizar','ModeloController@update');
        Route::delete('/modelo/eliminar','ModeloController@destroy');
        Route::post('/formSubmitModelo/{id}','ModeloController@formSubmit');
        Route::get('/downloadModelo/{fileName}' , 'ModeloController@downloadFile');
        Route::post('/formSubmitModelo/especificaciones/obra/{id}','ModeloController@formSubmitEspecObra');
        Route::get('/downloadModelo/obra/{fileName}' , 'ModeloController@downloadFileEspecObra');
        
    ////////////////////        RUTAS LOTES    /////////////////////////////////
        Route::get('/lote','LoteController@index'); 
        Route::get('/lote/dispEspecificaciones','VersionModeloController@indexLotes'); 
        Route::get('/asignar_modelo/excel','LoteController@exportExcelAsignarModelo');
        Route::get('/lote2','LoteController@index2');
        Route::get('/lotesDisponibles','LoteController@indexLotesDisponibles');
        Route::post('/lote/registrar','LoteController@store');
        Route::put('/lote/actualizar','LoteController@update');
        Route::put('/lote/actualizar2','LoteController@update2');
        Route::put('/lote/actualizar3','LoteController@asignarMod');
        Route::delete('/lote/eliminar','LoteController@destroy');
        Route::post('/import', 'LoteController@import');
        Route::get('/lote_aviso','LoteController@indexIniObra');
        Route::get('/lote_aviso/historial','LoteController@indexHistorialIniObra');
        Route::get('/lote_aviso/excelIniciosObra','LoteController@excelIniciosObra');
        Route::put('/lotes/enviarAviObra','LoteController@enviarAviso');
        Route::put('/lotes/enviarAviObra/actualizar','LoteController@actNumInicio');
        Route::get('/lotes/export_excel/{fraccionamiento_id}','LoteController@excelLotes');
        Route::get('/lotes/resume_excel_lotes_disp','LoteController@exportExcelLotesDisp');
        Route::get('/lotes/con_precio_base','LoteController@LotesConPrecioBase');
        Route::get('/preciosVivienda/excel','LoteController@excelPrecioBase');
        Route::put('/lotes/actualizar/ajuste','LoteController@updateAjuste');
        Route::put('/lotes/masa/empresa','LoteController@asignarEmpresa');
        Route::get('/lotes/empresa/select','LoteController@selectEmpresaConstructora'); 

        Route::post('/lote/subirColindancias','LoteController@formSubmitColindancias');
        Route::get('/lote/colindancias/{fileName}' , 'LoteController@downloadFile');

    ///////////////////         RUTAS RENTAS        ////////////////////////////////
        Route::post('/arrendador/storeArrendador','RentasController@storeArrendador');
        Route::put('/arrendador/updateArrendador','RentasController@updateArrendador');
        Route::post('/testigo/storeTestigo','RentasController@storeTestigo');
        Route::put('/testigo/updateTestigo','RentasController@updateTestigo');
        Route::put('/lotes/cambiarStatusLote','RentasController@cambiarStatusLote');

        Route::get('/lotes/indexLotesRentas','LoteController@indexLotesRentas');
        Route::get('/lotes/getRentasDisponibles','RentasController@getRentasDisponibles');
        Route::get('/lotes/getDatosLoteRenta','RentasController@getDatosLoteRenta');
        Route::get('/rentas/getArrendador','RentasController@getArrendador');
        Route::get('/rentas/getTestigos','RentasController@getTestigos');
        Route::get('/rentas/pruebaSms','RentasController@pruebaSms');
        Route::put('/lotes/updateDatosRenta','RentasController@updateDatosRenta');
        Route::post('/rentas/storeRenta','RentasController@storeRenta');
        Route::put('/rentas/updateRenta','RentasController@updateRenta');
        Route::post('/rentas/storeDeposito','RentasController@storeDeposito');
        Route::put('/rentas/updateDeposito','RentasController@updateDeposito');
        Route::delete('/rentas/deleteDeposito','RentasController@deleteDeposito');
        Route::get('/rentas/indexRentas','RentasController@indexRentas');
        Route::get('/rentas/indexEdoCta','RentasController@indexEdoCta');
        Route::get('/rentas/indexEdoCtaExcel','RentasController@indexEdoCtaExcel');
        Route::get('/rentas/getDatosRenta','RentasController@getDatosRenta');
        Route::get('/rentas/printContrato','RentasController@printContrato');
        Route::get('/rentas/printPagares','RentasController@printPagares');
        Route::get('/rentas/printDepositoGarantia','RentasController@printDepositoGarantia');
        Route::get('/rentas/printAdendum','RentasController@printAdendum');
        Route::put('/rentas/changeStatus','RentasController@changeStatus');
        Route::post('/rentas/formSubmitContrato/{id}','RentasController@formSubmitContrato'); //carga de archivo

        Route::post('/rentas/formSubmitArchivo/{id}','RentasController@formSubmitArchivo'); //carga de archivo

        Route::get('/rentas/pagosPorVencer','RentasController@getPagosPorVencer');
        Route::get('/rentas/cambiarPagoAVencido','RentasController@cambiarPagoAVencido');
        
    ////////////////////        RUTAS APARTADOS    /////////////////////////////////
        Route::post('/apartado/registrar','ApartadoController@store');
        Route::put('/apartado/actualizar','ApartadoController@update');
        Route::delete('/apartado/eliminar','ApartadoController@destroy');

    ///////////////////       RUTAS LICENCIA   ////////////////////////////////////
        Route::get('/licencias','LicenciasController@index');
        Route::put('/licencias/actualizar','LicenciasController@update');
        Route::put('/licencias/actualizarMasa','LicenciasController@updateMasa');
        Route::put('/licencias/updateMasaLicencias','LicenciasController@updateMasaLicencias');
        Route::get('/licencias/resume','LicenciasController@resumeLicencias');
        Route::post('/formSubmitLicencias/{id}','LicenciasController@formSubmit'); //carga de licencias
        Route::get('/downloadLicencias/{fileName}' , 'LicenciasController@downloadFile'); //descarga de licencias
        Route::get('/licencias/excel','LicenciasController@exportExcelLicencias'); //excel de las licencias

        Route::get('/licencias/indexDescargas','LicenciasController@indexDescargas');
        Route::get('/licencias/excelDescargas','LicenciasController@excelDescargas');

    ///////////////////       RUTAS RUV   ////////////////////////////////////
        Route::get('/ruv/indexLotes','RuvController@indexLotes');
        Route::get('/ruv/excelRuv','RuvController@excelRuv');
        Route::get('/ruv/historialSolicitudes','RuvController@historialSolicitudes');
        Route::post('/ruv/solicitar','RuvController@solicitarRuv');

        Route::get('/ruv/indexRuv','RuvController@indexRuv');
        Route::put('/ruv/cargaInfo','RuvController@cargaInfo');
        Route::put('/ruv/obtenerCuv','RuvController@obtenerCuv');
        Route::put('/ruv/asignarVerificador','RuvController@asignarVerificador');
        Route::put('/ruv/revDocumental','RuvController@revDocumental');
        Route::put('/ruv/dtu','RuvController@dtu');

        Route::get('/ruv/indexComentarios','RuvController@indexComentarios');
        Route::post('/ruv/storeComentarios','RuvController@storeComentarios');

    ///////////////// RUTAS SOLICITUDES DE PAGO ////////////////////
        Route::get('/solic_pago/indexSolicitudes','SolicitudPagosController@indexSolicitudes');
        Route::get('/solic_pago/indexComentarios','SolicitudPagosController@indexComentarios');
        Route::post('/solic_pago/storeComentarios','SolicitudPagosController@storeComentarios');
        Route::post('/solic_pago/storeSinOrden/{concepto}','SolicitudPagosController@storeSinOrden');
        Route::post('/solic_pago/storeConOrden/{concepto}','SolicitudPagosController@storeConOrden');
        Route::post('/solic_pago/putSolicCheque/{id}','SolicitudPagosController@putSolicCheque');
        Route::post('/solic_pago/putCotizacion/{id}','SolicitudPagosController@putCotizacion');
        Route::post('/solic_pago/putPagoPartes/{id}','SolicitudPagosController@putPagoPartes');
        Route::post('/solic_pago/putFactura/{id}','SolicitudPagosController@putFactura');
        Route::post('/solic_pago/putOrden/{id}','SolicitudPagosController@putOrden');
        Route::post('/solic_pago/putCheque/{id}','SolicitudPagosController@putCheque');
        Route::post('/solic_pago/storeDocumento/{id}/{nombre}','SolicitudPagosController@storeDocumento');
        Route::get('/solic_pago/getDocumentos', 'SolicitudPagosController@getDocumentos');
        Route::delete('/solic_pago/deleteArchivo', 'SolicitudPagosController@deleteArchivo');

        Route::post('/solic_pago/autorizarOrden','SolicitudPagosController@autorizarOrden');
        Route::post('/solic_pago/autorizarSolicitud','SolicitudPagosController@autorizarSolicitud');
        Route::post('/solic_pago/vistoBuenoSolicitud','SolicitudPagosController@vistoBuenoSolicitud');
        Route::post('/solic_pago/vistoBuenoOrden','SolicitudPagosController@vistoBuenoOrden');
        Route::post('/solic_pago/pagarSolicitud','SolicitudPagosController@pagarSolicitud');
        Route::post('/solic_pago/cancelarSolicitud','SolicitudPagosController@cancelarSolicitud');

    ///////////////////// RUTAS INGRESOS CONCRETANIA
        Route::get('/ingresosConcretania/pendeintesIngresar','DepositoController@pendeintesIngresar');
        Route::get('/ingresosConcretania/pendeintesIngresarExcel','DepositoController@pendeintesIngresarExcel');
        Route::get('/ingresosConcretania/historialIngresos','DepositoController@historialIngresos');
        Route::put('/ingresosConcretania/guardarIngreso','DepositoController@guardarIngreso');

        Route::get('/ingresosConcretania/indexEdoCuentaTerreno','CreditoController@indexEdoCuentaTerreno');
        Route::get('/ingresosConcretania/indexEdoCuentaTerreno/excel','CreditoController@indexEdoCuentaTerrenoExcel');
        Route::get('/ingresosConcretania/indexTerrenosCerrados','CreditoController@indexTerrenosCerrados');
        
    ///////////////////       RUTAS LICENCIA-ACTA  ////////////////////////////////////
        Route::get('/acta_terminacion','LicenciasController@indexActa');
        Route::put('/acta_terminacion/actualizar','LicenciasController@updateActas');
        Route::get('/licencias/resume_excel','LicenciasController@exportExcel');
        Route::put('/acta_terminacion/updateMasaActas','LicenciasController@updateMasaActas');
        Route::post('/formSubmitActa/{id}','LicenciasController@formSubmitActa'); //carga de acta
        Route::get('/downloadActa/{fileName}' , 'LicenciasController@downloadFileActa'); //descarga de acta
        Route::post('/formSubmitPredial/{id}','LicenciasController@formSubmitPredial'); //carga de predial
        Route::get('/downloadPredial/{fileName}' , 'LicenciasController@downloadFilePredial'); //descarga de predial
        Route::get('/acta_terminacion/excel','LicenciasController@exportExcelActaTerminacion'); //excel de las actas
        
    ////////////////////        RUTAS PRECIO TERRENO    /////////////////////////////////
        Route::get('/precio/terrenos/list','PrecioTerrenoController@show');
        Route::post('/precio/terrenos/storage','PrecioTerrenoController@storage');
        Route::put('/precio/terrenos/edit','PrecioTerrenoController@edit');
        Route::delete('/precio/terrenos/delete','PrecioTerrenoController@destroy');

    ///////////////////     RUTAS ABONO A TERRENOS //////
        Route::get('/terrenos/getTerrenosAdeudo','TerrenoController@getTerrenosAdeudo');
        Route::get('/terrenos/getPagosPendientes','TerrenoController@getPagosPendientes');
        Route::post('/terrenos/storeAdelanto','TerrenoController@storeAdelanto');

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
        Route::get('/sobreprecios','SobreprecioEtapaController@ListarSobreprecio');
        Route::post('/sobreprecios/registrar','SobreprecioEtapaController@registrarSobreprecio');
        Route::put('/sobreprecios/actualizar','SobreprecioEtapaController@actualizarSobreprecio');
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

    ////////////////////        RUTAS Servicios   /////////////////////////////////
        Route::get('/servicio','ServicioController@index');
        Route::post('/servicio/registrar','ServicioController@store');
        Route::put('/servicio/actualizar','ServicioController@update');
        Route::delete('/servicio/eliminar','ServicioController@destroy');
    
    ////////////////////        RUTAS Servicios Etapas  /////////////////////////////////
        Route::post('/servicio_etapa/registrar','ServEtapaController@store');
        Route::delete('/servicio_etapa/eliminar','ServEtapaController@destroy');
        
    ////////////////////        RUTAS PROMOCIONES    /////////////////////////////////
        Route::get('/promocion','PromocionController@index');
        Route::get('/selectPromocion','PromocionController@selectPromocion');
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
        Route::get('/iniobra/excelAvisos','IniObraController@excelAvisos');
        Route::post('/iniobra/registrar','IniObraController@store');
        Route::put('/iniobra/actualizar','IniObraController@ActualizarIniObra');
        Route::get('/iniobra/obtenerCabecera','IniObraController@obtenerCabecera');
        Route::get('/iniobra/obtenerDetalles','IniObraController@obtenerDetalles');
        Route::delete('/iniobra/contrato/eliminar','IniObraController@eliminarContrato');
        Route::post('/iniobra/lote/registrar','IniObraController@agregarIniObraLotes');
        Route::delete('/iniobra/lote/eliminar','IniObraController@eliminarIniObraLotes');
        Route::get('/iniobra/pdf','IniObraController@contratoObraPDF')->name('contratos.pdf');
        Route::get('/iniobra/relacion/excel/{id}','IniObraController@exportExcel');
        Route::get('/licencias/indexVisita','LicenciasController@indexVisita');
        Route::get('/licencias/excelVisita','LicenciasController@excelVisita');
        Route::put('/licencias/progFechaVisita','LicenciasController@AsigFechaVisita');
        Route::get('/avisoObra/siroc','IniObraController@imprimirSiroc');

        Route::post('/formSubmitContratoObra/{id}','IniObraController@formSubmitContratoObra'); 
        Route::post('/formSubmitRegistroObra/{id}','IniObraController@formSubmitRegistroObra'); 
        Route::get('/downloadContratoObra/{fileName}' , 'IniObraController@downloadFile'); 
        Route::get('/downloadRegistroObra/{fileName}' , 'IniObraController@downloadRegistroObra'); 

    /////////////////////////// RUTAS ESTIMACIONES ////////////////
        Route::get('/estimaciones/getSinEstimaciones','IniObraController@getSinEstimaciones');
        Route::post('/estimaciones/import','IniObraController@import');
        Route::get('/estimaciones/indexEstimaciones','IniObraController@indexEstimaciones');
        Route::get('/estimaciones/getPartidas','IniObraController@getPartidas');
        Route::get('/estimaciones/getEstimacionesDep','IniObraController@getEstimacionesDep');
        Route::get('/estimaciones/getPartidasDep','IniObraController@getPartidasDep');
        Route::get('/estimaciones/getNivelesDep','IniObraController@getNivelesDep');
        Route::put('/estimaciones/updatePartidasDep','IniObraController@updatePartidasDep');
        
        Route::get('/estimaciones/excelEstimaciones','IniObraController@excelEstimaciones');
        Route::get('/estimaciones/excelEstimacionesDep','IniObraController@excelEstimacionesDep');

        Route::get('/estimaciones/updateAvace','IniObraController@updateAvace');

        Route::put('/estimaciones/updateImporTotal','IniObraController@updateImporTotal');
        Route::put('/estimaciones/editCantTope','IniObraController@editCantTope');
        Route::put('/estimaciones/finalizarEstimacion','IniObraController@finalizarEstimacion');

        Route::post('/estimaciones/store','IniObraController@storeEstimacion');
        Route::post('/estimaciones/update','IniObraController@updateEstimacion');
        Route::post('/estimaciones/storeAnticipo','IniObraController@storeAnticipo');
        Route::post('/estimaciones/storeFG','IniObraController@storeFG');

        Route::post('/estimaciones/storeImporteExtra','IniObraController@storeImporteExtra');
        Route::post('/estimaciones/storeConceptoExtra','IniObraController@storeConceptoExtra');
        Route::post('/estimaciones/storeObs','EstimacionController@storeObs');

        Route::get('/estimaciones/resumen','EstimacionController@resumen');
        Route::get('/estimaciones/excelEdoCuenta','IniObraController@excelEdoCuenta');

        Route::get('/estimaciones/resumenEdoCuenta','EstimacionController@resumenEdoCuenta');


    ////////////////////        RUTAS PARTIDAS   /////////////////////////////////
        Route::get('/partidas','PartidaController@index');
        Route::post('/partidas/registrar','PartidaController@registrar');
        Route::put('/partidas/actualizar','PartidaController@update');
        Route::delete('/partidas/eliminar','PartidaController@destroy');

    
    /**********************************RUTAS OBSERVACION LOTES*************************** */
        Route::post('/observacion/registrar','ObservacionController@store');
        Route::get('/observacion/select_ultima','ObservacionController@select_ultima'); 
        Route::get('/observacion','ObservacionController@index');

    /**********************************RUTAS OBSERVACION AUDITORIA*************************** */
        Route::post('/auditar/registrarObservacion','ObsAuditoriaController@store');
        Route::get('/auditar/indexObservacion','ObsAuditoriaController@index');

    /**********************************RUTAS OBSERVACION COMISIÓN*************************** */
        Route::post('/comision/registrarObservacion','ObsComisionController@store');
        Route::get('/comision/indexObservacion','ObsComisionController@index');

    /***************************************************************************** */
    ////////////////////        RUTAS ENTREGA    //////////////////////////////
        Route::post('/entrega/registrar','EntregaController@store');

    ///////////////////         RUTAS POSTVENTA     ///////////////////////////
        Route::get('/postventa/index','EntregaController@indexPendientes');
        Route::get('/postventa/excel','EntregaController@indexPendientesExcel');
        Route::get('/postventa/indexEntregas','EntregaController@indexEntregas');
        Route::get('/postventa/excelEntregas','EntregaController@excelEntregas');
        
        Route::get('/postventa/indexObservacion','EntregaController@indexObservaciones');
        Route::post('/postventa/registrarObservacion','EntregaController@storeObservacion');
        Route::put('/lotes/setFechaEntregaObra','EntregaController@setFechaObra');

        Route::put('/postventa/setFechaProg','EntregaController@setFechaProgramada');
        Route::put('/postventa/setHoraProg','EntregaController@setHoraProgramada');
        Route::put('/postventa/finalizarEntrega','EntregaController@finalizarEntrega');

        Route::get('/postventa/cartaMantenimiento/{id}','EntregaController@cartaCuotaMantenimiento');
        Route::get('/postventa/polizaDeGarantia/{id}','EntregaController@polizaDeGarantia');
        Route::get('/postventa/cartaAlarma','EntregaController@cartaAlarma');

        Route::get('/postventa/cartaRecepcion/{id}','EntregaController@cartaRecepcion');

        Route::put('/postventa/datosDeposito/registrar','EntregaController@setDatosCuenta');
        Route::put('/postventa/actualizarCorreoAdmin','EntregaController@actualizarCorreoAdmin');

        /////////////////////////////    RUTAS SOLIC DETALLES        //////////////////////////
            Route::get('/postventa/getDatosLoteEntregado','EntregaController@getDatosLoteEntregado');

        ////////////////////////////     RUTAS REVISION PREVIA      ///////////////////////////
            Route::get('/postventa/indexSinRevision','RevisionPreviaController@indexSinRevision');
            Route::get('/postventa/indexSinRevisionContratista','RevisionPreviaController@indexSinRevisionContratista');
            Route::post('/postventa/finalizarRevisionPrevia','RevisionPreviaController@storeRevision');
            Route::get('/postventa/checklist/pdf/{folio}','RevisionPreviaController@DetallesPDF');

    ////////////////////////////     RUTAS calculadora de lotes     ///////////////////////////
    Route::get('/calc/descuentos','CalculadoraLotesController@listarPorcentaje');
    Route::put('/calc/descuentos/edita','CalculadoraLotesController@editaPorcentaje');
    Route::get('/calc/lotes','CalculadoraLotesController@listarPrecios');
    Route::put('/calc/enter/edita','CalculadoraLotesController@editEnterPrice');
    Route::put('/calc/window/edita','CalculadoraLotesController@editWindowPrice');
    Route::put('/calc/window/add','CalculadoraLotesController@addWindowPrice');
    Route::get('/get/fraccionamientos/lotes','CalculadoraLotesController@selectFraccionamientoLotes');
    Route::get('/get/etapas/lotes','CalculadoraLotesController@selectEtapa_proyectoLotes');
    Route::get('/get/lotes/lotes','CalculadoraLotesController@lotesDisponiblesLotes');
    Route::post('/calc/guardar/cotizacion','CalculadoraLotesController@guardaCotizacion');
    Route::post('/calc/actualizar/cotizacion','CalculadoraLotesController@actualizarCotizacion');
    Route::get('/calc/generar/pdf/{idCotizacion}','CalculadoraLotesController@generaPdf');
    Route::get('/get/cotizacion/clientes','CalculadoraLotesController@getClientes');
    Route::post('/edita/cotizacion/cancelar','CalculadoraLotesController@cancelaCotizacion');
    Route::post('/edita/cotizacion/aprovar','CalculadoraLotesController@aprovarCotizacion');
    Route::get('/get/cotizacion/editar','CalculadoraLotesController@getCotizacionEdita');

    Route::get('/contrato/contratoLote/pdf/{id}','ContratoController@contratoLote')->name('contrato_promesa_credito.pdf');
    Route::get('/contrato/anexoA/{id}','ContratoController@printAnexoA')->name('anexoA.pdf');

    Route::get('/contrato/reportEli','ContratoController@reportEli');

    /**********************************RUTAS AVANCE*************************** */
        Route::get('/avance','AvanceController@index');
        Route::get('/avance/urbanizacion','AvanceController@indexUrbanizacion');
        Route::get('/avanceProm','AvanceController@indexProm');
        Route::put('/avance/actualizar','AvanceController@update');
        Route::put('/avance/setAvanceUrb','AvanceController@setAvanceUrb');
        Route::get('/avances/resume_excel','AvanceController@exportExcel');
        Route::get('/avances/res_partidas/{contrato}','AvanceController@excelLotesPartidas');

    /**********************************RUTAS SIMULACION*************************** */
        Route::get('/simulaciones_credito','CreditoController@indexCreditos');
        Route::get('/historial_simulaciones_credito','CreditoController@indexHistorial');
        Route::put('/creditos/actualizarProspecto','CreditoController@updateDatosCliente');
        Route::post('/creditos/registrar','CreditoController@store');
        Route::put('/creditos/aceptar','CreditoController@aceptarSolicitud');
        Route::put('/creditos/rechazar','CreditoController@rechazarSolicitud');
        Route::get('/historial_creditos','CreditoController@HistorialDeCreditos');
        Route::get('/historial_simulaciones/descargar','CreditoController@ExportarHistorialSimulacion');
        Route::put('/creditos/cambiarTitular','CreditoController@cambiarTitularCredito');

        Route::post('/creditos_select/registrar','CreditoController@storeCreditoSelect');
        Route::put('/creditos/seleccionar','CreditoController@seleccionarCredito');
        Route::put('/creditos_select/actualizar','CreditoController@updateDatosCredito');
        Route::get('/inst_select/observacion','ObservacionInstSeleccionadaController@index');
        Route::put('/creditos_select/setFechaVigencia','CreditoController@updateFechaVigencia');
        
    
        Route::get('/serviciosTelecom/pdf/{id}','ServicioController@servicioTelecomPdf')->name('servicios.pdf');
        Route::get('/cartaServicios/pdf/{id}','ServicioController@cartaDeServicioPdf')->name('CartaDeservicios.pdf');
        

        Route::put('/creditos/updateCostoDescuento','CreditoController@updateCostoDescuento');
        Route::put('/creditos/updateDescuentoTerreno','CreditoController@updateDescuentoTerreno');
        Route::put('/creditos/updateCostoAlarma','CreditoController@updateCostoAlarma');
        Route::put('/creditos/updateCostoCuotaMant','CreditoController@updateCostoCuotaMant');
        Route::put('/creditos/updateCostoProtecciones','CreditoController@updateCostoProtecciones');
        
        Route::post('/users/foto/{id}','UserController@updateProfile');
        Route::put('/users/update/password','UserController@updatePassword');

        Route::get('/comments/reminder/', 'UserController@reminderCommentario');

    /**************************** RUTAS MODULO CONTRATOS  ***************************/
        Route::get('/contratos','ContratoController@indexContrato');
        Route::get('/creditos_aprobados','ContratoController@indexCreditosAprobados');
        Route::get('/contratos/pagos','ContratoController@listarPagos');
        Route::get('/credito/datos_credito','ContratoController@getDatosCredito');
        Route::post('/contrato/registrar','ContratoController@store');
        Route::put('/contrato/actualizarCredito','ContratoController@updateDatosCredito');
        Route::post('/contrato/pagos/agregar','ContratoController@agregarPago');
        Route::put('/contrato/pagos/actualizar','ContratoController@actualizarPago');
        Route::delete('/contrato/pagos/eliminar','ContratoController@eliminarPago');
        Route::put('/contrato/actualizar','ContratoController@actualizarContrato');
        Route::put('/contrato/reasignar','ContratoController@reasignarCliente');
        Route::put('/contrato/reasignar2','ContratoController@reasignarCliente2');

        Route::post('/contratos/formSubmitFisc/{id}','ContratoController@formSubmitFisc');
        Route::post('/contratos/formSubmitConstFisc/{id}','ContratoController@formSubmitConstFisc');
        Route::get('/contratos/downloadFileFisc/{fileName}' , 'ContratoController@downloadFileFisc');
        Route::get('/contratos/downloadFileConstFisc/{fileName}' , 'ContratoController@downloadFileConstFisc');

        Route::get('/contratoCompraVenta/pdf/{id}','ContratoController@contratoCompraVentaPdf')->name('contratoCompraVenta.pdf');
        Route::get('/pagareContrato/pdf/{id}','ContratoController@pagareContratopdf')->name('pagare.pdf');
        Route::get('/descargarReglamento/contrato/{id}','EtapaController@descargarReglamentoContrato');
        Route::get('/contratoCompraVenta/reservaDeDominio/pdf/{id}','ContratoController@contratoConReservaDeDominio')->name('contrato_reserva_de_dominio.pdf');
        Route::get('/contrato/promesaCredito/pdf/{id}','ContratoController@contratoDePromesaCredito')->name('contrato_promesa_credito.pdf');
        Route::get('/contrato/modelo/caracteristicas/pdf/{id}','ModeloController@modeloArchivoContrato');
        Route::put('/contrato/status/fecha','ContratoController@statusContrato');
        Route::get('/contratos/excel','ContratoController@excelContratos');
        Route::get('/contratos/validarLotes','ContratoController@validarLoteEnContrato');
        Route::put('/contratos/entregarExp','ContratoController@entregarExp');

        Route::post('/contratos/auditar','ObsAuditoriaController@auditar');

        Route::put('/contrato/cambiarProceso','ContratoController@cambiarProceso');

        Route::put('/contrato/sendExp','ExpedienteController@sendExp');
        Route::put('/contrato/receivedExp','ExpedienteController@receivedExp');
        Route::get('/contrato/getObsExpEntregados','ExpedienteController@getObsExpEntregados');
        Route::post('/contratos/storeObsExpEntregado','ExpedienteController@storeObsExpEntregado');

    /************************** RUTAS Depositos y Pagares ***************************/
        Route::get('/pagares','DepositoController@indexPagares');
        Route::get('/pagares/excel','DepositoController@excelPagares');
        Route::get('/depositos/excel','DepositoController@excelDepositos');
        Route::get('/depositos','DepositoController@indexDepositos');
        Route::get('/depositos/historial','DepositoController@historialDepositos');
        Route::get('/depositos/historial/excel','DepositoController@excelHistorialDepositos');
        Route::post('/deposito/registrar','DepositoController@store');
        Route::put('/deposito/actualizar','DepositoController@update');
        Route::get('deposito/reciboPDF/{id}','DepositoController@reciboPDF');
        Route::delete('/deposito/eliminar','DepositoController@delete');

        Route::get('/pagaresLotes/getDatosPago','CalculadoraLotesController@getDatosPago');
        

    //////////////////////////////// RUTAS MODULO SALDOS ////////////////////////////
        Route::get('/estadoCuenta/index','DepositoController@indexEstadoCuenta');
        Route::get('/estadoCuenta/excel','DepositoController@excelEstadoCuenta');
        Route::get('/estadoCuenta/estadoPDF/{id}','DepositoController@estadoPDF');

    //////////////////////////////// RUTAS MODULO facturas ////////////////////////////

        //Contrato
        Route::get('/facturas/contratos/get','FacturasController@listarFacturaContratos');//by Rafael Rivera
        Route::post('/facturas/contratos/update','FacturasController@cargarFacturaContratos');//by Rafael Rivera
        Route::post('/facturas/contratos/concretania/update','FacturasController@cargarFacturaContratosConcretania');//by Rafael Rivera
        Route::get('/facturas/contratos/download/{name}','FacturasController@descargaFacturaC');//by Rafael Rivera
        Route::get('/facturas/contratos/concretania/download/{name}','FacturasController@descargaFacturaCon');//by Rafael Rivera
        //Depositos
        Route::get('/facturas/depositos/get','FacturasController@listarFacturaDepositos');//by Rafael Rivera
        Route::post('/facturas/depositos/update','FacturasController@cargarFacturaDepositos');//by Rafael Rivera
        Route::get('/facturas/terreno/download/{name}','FacturasController@descargaFacturaTer');//by Rafael Rivera
        Route::get('/facturas/depositos/interes/download/{name}','FacturasController@descargaFacturaInt');
        Route::get('/facturas/depositos/download/{name}','FacturasController@descargaFacturaD');//by Rafael Rivera
        //cobro de credito
        Route::get('/facturas/liq/credito/get','FacturasController@listarFacturaLiqCredito');//by Rafael Rivera
        Route::post('/facturas/liq/credito/update','FacturasController@cargarFacturaLiqCredito');//by Rafael Rivera
        Route::get('/facturas/liq/credito/download/{name}','FacturasController@descargaFacturaLC');//by Rafael Rivera
        //cobro de credito
        Route::get('/facturas/dep/credito/get','FacturasController@listarFacturaDepCredito');//by Rafael Rivera
        Route::post('/facturas/dep/credito/update','FacturasController@cargarFacturaDepCredito');//by Rafael Rivera
        Route::get('/facturas/dep/credito/download/{name}','FacturasController@descargaFacturaDC');//by Rafael Rivera
        Route::get('/facturas/dep/credito/downloadt/{name}','FacturasController@descargaFacturaDCT');//by Rafael Rivera

    /************************** RUTAS ESTADISTICAS ***************************/
        Route::get('/estadisticas/datos_extra','EstadisticasController@estad_datos_extra');
        Route::get('/estadisticas/res_proyecto','EstadisticasController@resumenProyecto');
        Route::get('/estadisticas/res_proyecto/excel','EstadisticasController@excelResumen');
        Route::get('/estadisticas/publicidad','MedioPublicitarioController@estadisticas');

        /////////// REPORTES ////////////////
            Route::get('/reprotes/inventario','ReportesController@reporteInventario');
            Route::get('/reprotes/reporteEmpresas','ReportesController@reporteEmpresas');
            Route::get('/reprotes/reporteEmpresasExcel','ReportesController@reporteEmpresasExcel');
            Route::get('/reprotes/reporteVendedores','ReportesController@reporteVendedores');
            Route::get('/reprotes/reporteLotesVentas','ReportesController@reporteLotesVentas');
            Route::get('/reprotes/excelReporteLotesVentas','ReportesController@excelReporteLotesVentas');
            Route::get('/reprotes/reporteVentas','ReportesController@reporteVentas');
            Route::get('/reprotes/reporteVentasExcel','ReportesController@reporteVentasExcel');

            Route::get('/reprotes/reporteAcumulado','ReportesController@reporteAcumulado');
            Route::get('/reprotes/excelExpedientes','ReportesController@excelExpedientes');
            Route::get('/reprotes/excelEscrituras','ReportesController@excelEscrituras');
            Route::get('/reprotes/excelIngresos','ReportesController@excelIngresos');

            Route::get('/reprotes/reporteRecursosPropios','ReportesController@reporteRecursosPropios');
            Route::get('/reprotes/excelReporteRecursosPropios','ReportesController@excelRecursosPropios');

            Route::get('/reprotes/reporteCasasCreditoPuente','ReportesController@reporteCasasCreditoPuente');
            Route::get('/reprotes/excelCasasCreditoPuente','ReportesController@excelCasasCreditoPuente');
            Route::get('/reportes/reporteDetalles','ReportesController@reporteDetalles');
            Route::get('/reportes/reporteDetallesExcel','ReportesController@reporteDetallesExcel');

            Route::get('/reportes/reporteModelos','ReportesController@reporteModelos');
            Route::get('/reportes/excelReporteModelos','ReportesController@excelReporteModelos');

            Route::get('/reportes/revicionprevia','ReportesController@revicionPreviaRep');
            Route::get('/reportes/reporteEntregas','ReportesController@reporteEntregas');

            Route::get('/reportes/digitalLeads','DigitalLeadController@reporteLeads');
            Route::get('/reportes/prospectos','DigitalLeadController@reportesProspectos');
            Route::get('/excel/reportes/prospectos','DigitalLeadController@excelReportesProspectos');

    ///////////////////        RUTAS NOTARIA     //////////////////////////////////
        Route::get('/notaria','NotariaController@index');
        Route::post('/notaria/registrar','NotariaController@store');
        Route::put('/notaria/actualizar','NotariaController@update');
        Route::delete('/notaria/eliminar','NotariaController@destroy');

    /************************** RUTAS EXPEDIENTE ***************************/
        Route::get('/expediente/listarContratos','ExpedienteController@indexContratos');
        Route::post('/expediente/solicitarAvaluo','AvaluoController@store');
        Route::post('/expediente/solicitarAviso','AvisoPreventivoController@store');
        Route::put('/expediente/AvaluoNoAplica','AvaluoController@noAplicaAvaluo');
        Route::put('/expediente/AvisoNoAplica','AvisoPreventivoController@noAplicaAviso');
        Route::get('/observacionExpediente','ExpedienteController@listarObservaciones');
        Route::post('/observacionExpediente/registrar','ExpedienteController@storeObservacion');
        Route::get('/expediente/Excel','ExpedienteController@exportExcel');
        Route::put('/expediente/fechaRecibido','AvisoPreventivoController@registrarFechaRecibido');
        Route::get('/expediente/solicitudPDF/{id}','AvisoPreventivoController@solicitudPDF'); 
        Route::post('/expediente/integrar','ExpedienteController@store');

        Route::delete('/expediente/regresarExpediente','ExpedienteController@regresarExpediente');

        Route::post('expediente/formSubmitEscrituras/{id}','ReportesController@formSubmitEscrituras'); //carga de Avaluo
        Route::get('expediente/downloadEscrituras/{fileName}' , 'ReportesController@downloadFile'); //descarga de Avaluo

    ////////////////////////// RUTAS COMISIONES //////////////////////////////
        Route::get('/comision/listarContratos','ComisionesController@indexContratos');
        Route::get('/comision/historialExpedientes','ComisionesController@historialExpedientes');
        Route::get('/comision/ventasAsesor','ComisionesController@ventasAsesor');
        Route::put('/comision/agregarExpediente','ComisionesController@agregarExpediente');
        
        Route::get('/comision/detalleComision','ComisionesController@detalleComision');
        Route::get('/comision/bonosPorPagar','ComisionesController@bonosPorPagar');
        Route::get('/comision/bonos','ComisionesController@bonos');
        Route::put('/comision/generarBono','ComisionesController@generarBono');
        Route::put('/comision/noAplica','ComisionesController@noAplicaComision');

        /////////////////////////////////////////////
        Route::get('/comision/getComision','ComisionesVentaController@getComision');
        Route::post('/comision/storeComision','ComisionesVentaController@storeComision');
        Route::get('/comision/indexComisiones','ComisionesVentaController@indexComisiones');
        Route::get('/comision/detalleComision','ComisionesVentaController@getDetalle');

        Route::put('/comision/desartarCambio','ComisionesVentaController@desartarCambio');
        Route::get('/comision/excel','ComisionesVentaController@excelDetalle');

    ////////////////////////// RUTAS ASIGNAR GESTOR /////////////////////////////

        Route::get('/expediente/indexAsignarGestor','ExpedienteController@indexAsignarGestor');
        Route::put('/expediente/asignarGestor','ExpedienteController@asignarGestor');

    ///////////////////////// RUTAS AVALUOS /////////////////////////////////////
        Route::get('/avaluos/index','AvaluoController@index');
        Route::get('/historial/avaluos/index','AvaluoController@indexHistorial');
        Route::get('/historial/avaluos/excel','AvaluoController@excelHistorial');
        Route::put('/avaluos/fechaSolicitud','AvaluoController@setFechaSolicitud');
        Route::put('/avaluos/fechaPago','AvaluoController@setFechaPago');
        Route::post('/avaluos/fechaConcluido','AvaluoController@setFechaConcluido');
        Route::put('/avaluos/update/fechaConcluido','AvaluoController@updateFechaConcluido');
        Route::put('/avaluos/enviarVentas','AvaluoController@enviarVentas');
        Route::get('/avaluos/historialVisita','HistVisitasController@index');
        Route::get('/getGastoAvaluo','GastosAdministrativosController@getDatosAvaluo');
        Route::get('/getAvaluos','GastosAdministrativosController@getAvaluos');
        Route::post('/avaluos/storeVisita','HistVisitasController@store');
        Route::post('/avaluos/storeStatus','HistVisitasController@storeStatus');
        Route::get('/avaluos/historialStatus','HistVisitasController@indexStatus');
        Route::post('/gastos/storeAvaluo','GastosAdministrativosController@storeAvaluo');
        Route::put('/gastos/updateAvaluo','GastosAdministrativosController@updateAvaluo');
        Route::post('/formSubmitAvaluo/{id}','AvaluoController@formSubmitAvaluo'); //carga de Avaluo
        Route::get('/downloadAvaluo/{fileName}' , 'AvaluoController@downloadFile'); //descarga de Avaluo

        Route::get('/avaluos/listarObs','AvaluoController@listarObservaciones');
        Route::post('/avaluos/storeObservacion', 'AvaluoController@storeObservacion');

    //////////////////// RUTAS GASTOS ADMINISTRATIVOS /////////////////////////////
        Route::get('/gastos/index','GastosAdministrativosController@index');
        Route::get('/gastos/excel','GastosAdministrativosController@excel');
        Route::get('/gastos/indexContratos','GastosAdministrativosController@indexContratos');
        Route::post('/gastos/registrar','GastosAdministrativosController@store');
        Route::put('/gastos/actualizar','GastosAdministrativosController@update');
        Route::delete('/gastos/eliminar','GastosAdministrativosController@delete');

    //////////////////// RUTAS SEGUIMIENTO TRAMITE /////////////////////////////////
        Route::get('/expediente/ingresarIndex','ExpedienteController@indexIngresarExp');
        Route::get('/expediente/excelIngresarExp','ExpedienteController@excelIngresarExp');
        Route::get('/expediente/autorizadosIndex','ExpedienteController@indexAutorizados');
        Route::get('/expediente/excelAutorizados','ExpedienteController@excelAutorizados');
        Route::get('/expediente/liquidacionIndex','ExpedienteController@indexLiquidacion');
        Route::get('/expediente/excelLiquidacion','ExpedienteController@excelLiquidacion');
        Route::get('/expediente/ProgramacionIndex','ExpedienteController@indexProgramacion');
        Route::get('/expediente/excelProgramacion','ExpedienteController@excelProgramacion');
        Route::get('/expediente/enviadosIndex','ExpedienteController@indexEnviados');
        Route::put('/expediente/ingresarExp','ExpedienteController@ingresarExp');
        Route::put('/expediente/inscInfonavit','ExpedienteController@inscribirInfonavit');
        Route::put('/expediente/InfonavitNoAplica','ExpedienteController@noAplicaInfonavit');
        Route::put('/expediente/generarLiquidacion','ExpedienteController@setLiquidacion');
        Route::put('/expediente/generarInstruccionNot','ExpedienteController@generarInstruccionNot');
        Route::put('/update/montocredito/liquidacion','ExpedienteController@updateMontoCredito');

        Route::post('/expediente/generarPagares','ExpedienteController@generarPagares');
        Route::get('/expediente/pagaresExpediente','ExpedienteController@pagaresExpediente');
        Route::get('/expediente/gastosExpediente','GastosAdministrativosController@getGastos');
        Route::get('/expediente/liquidacionPDF/{id}','ExpedienteController@liquidacionPDF');

    //////////////////////  RUTAS COBRO CREDITO //////////////////////////////////////
        Route::get('/cobroCredito/indexCreditos','InstSeleccionadasController@indexCreditoSel');
        Route::get('/cobroCredito/indexDepositos','InstSeleccionadasController@indexDepCredito');
        Route::get('/cobroCredito/indexDepositos/historial','InstSeleccionadasController@historialDepositos');
        Route::get('/cobroCredito/indexDepositos/excelHistorialDep','InstSeleccionadasController@excelHistorialDep');
        Route::post('/cobroCredito/registrar','InstSeleccionadasController@storeDepositoCredito');
        Route::put('/cobroCredito/update','InstSeleccionadasController@updateDepositoCredito');
        Route::get('/cobroCredito/excel','InstSeleccionadasController@excelCobroCredito');
        Route::put('/cobroCredito/finalizar','InstSeleccionadasController@finalizar');
        Route::delete('/cobroCredito/eliminar','InstSeleccionadasController@eliminar');

        Route::get('/archivos/indexDocs','ModeloController@indexDocs');
        Route::get('/archivos/reglamentoEtapa/{etapa_id}','EtapaController@descargaReglamentoDocs');
        Route::get('/archivos/catalogoEspecificaciones/{modelo_id}','ModeloController@descargaCatalogoDocs');
        Route::get('/archivos/cartaServicios/{etapa_id}','ServicioController@cartaDeServicioDocs');
        Route::get('/archivos/cartaServiciosTelecomunicaciones/{etapa_id}','ServicioController@cartaDeTelecomunicacionesDocs');

    ////////////////////// RUTAS DEVOLUCION (CANCELACION)
        Route::get('/devolucion/index','DevolucionController@indexCancelaciones');
        Route::get('/devolucion/excel','DevolucionController@excelDevoluciones');
        Route::post('/devolucion/registrar','DevolucionController@storeDevolucion');
        Route::get('/devolucion/indexDevoluciones','DevolucionController@indexDevoluciones');
        Route::get('/devoluciones/excel','DevolucionController@excelHistDev');

        Route::get('/devolucionVirtual/index','DevolucionController@cancelacionesVirtuales');
        Route::post('/devolucionVirtual/store','DevolucionController@storeDevolucionVirtual');
        Route::get('/devolucionVirtual/indexDevoluciones','DevolucionController@indexDevolucionesVirtuales');

    ////////////////////// RUTAS DEVOLUCION (Credito)
        Route::get('/credito_devolucion/index','InstSeleccionadasController@indexDevolucion');
        Route::get('/credito_devolucion/excel','InstSeleccionadasController@excelDevolucion');
        Route::post('/credito_devolucion/registrar','InstSeleccionadasController@storeDevolucion');
        Route::get('/credito_devolucion/indexDevoluciones','InstSeleccionadasController@indexHistorialDev');
        Route::get('/devoluciones_credito/excel','InstSeleccionadasController@excelHistDev');

        Route::get('/devoluciones/listarObservacionesCanc','ObsDevolucionController@listarObservacionesCanc');
        Route::post('/devoluciones/storeObservacionCanc', 'ObsDevolucionController@storeObservacionCanc');
        Route::get('/devoluciones/listarObservacionesCredit','ObsDevolucionController@listarObservacionesCredit');
        Route::post('/devoluciones/storeObservacionCredit', 'ObsDevolucionController@storeObservacionCredit');

        Route::get('/credito_devolucion/observacionIndex','ObservacionInstSeleccionadaController@indexCobro');
        Route::post('/credito_devolucion/storeObs','ObservacionInstSeleccionadaController@storeCobro');

    ////////////////////// RUTAS PROVEEDOR
        Route::get('/proveedor','ProveedorController@index');
        Route::post('/proveedor/registrar','ProveedorController@store');
        Route::put('/proveedor/actualizar','ProveedorController@update');
        Route::get('/select_proveedor','ProveedorController@selectProveedor');

    ////////////////////// RUTAS EQUIPAMIENTO
        Route::get('/equipamiento','EquipamientoController@index');
        Route::post('/equipamiento/registrar','EquipamientoController@store');
        Route::put('/equipamiento/actualizar','EquipamientoController@update');
        Route::put('/equipamiento/eliminar','EquipamientoController@destroy');
        Route::put('/equipamiento/activar','EquipamientoController@activar');
        
        Route::get('/select_equipamientos','EquipamientoController@selectEquipamiento');
        Route::post('/equipamiento/solicitar_equipamiento','EquipamientoController@solicitud_equipamiento');
        Route::get('/index/equipamiento/lote','EquipamientoController@index_equipamientos_lotes');
        Route::delete('/equipamiento/lote/eliminar','EquipamientoController@eliminarSolicitud_lote');

    ///////////////////// RUTAS SOLIC EQUIPAMIENTO
        Route::get('/equipamiento/indexContrato','EquipamientoController@indexContratos');
        Route::put('/equipamiento/terminarSolicitud','EquipamientoController@terminarSolicitud');
        
        Route::put('/equipamiento/actCosto','SolEquipamientoController@actCosto');
        Route::put('/equipamiento/actAnticipo','SolEquipamientoController@actAnticipo');
        Route::put('/equipamiento/actLiquidacion','SolEquipamientoController@actLiquidacion');
        Route::put('/equipamiento/actColocacion','SolEquipamientoController@actColocacion');
        Route::put('/equipamiento/set_instalacion','SolEquipamientoController@setInstalacion');
        Route::get('/equipamiento/indexHistorial','SolEquipamientoController@indexHistorial');
        Route::post('/equipamiento/indexHistorial/upfile1','SolEquipamientoController@upComprPago1');
        Route::post('/equipamiento/indexHistorial/upfile2','SolEquipamientoController@upComprPago2');
        Route::get('/equipamiento/indexHistorial/downloadFile1/{fileName}','SolEquipamientoController@downloadPago1');
        Route::get('/equipamiento/indexHistorial/downloadFile2/{fileName}','SolEquipamientoController@downloadPago2');
        Route::get('/equipamiento/contRea','SolEquipamientoController@indexRea');
        Route::put('/equipamiento/reubicar','SolEquipamientoController@reubicar');
        Route::put('/equipamiento/bloquearAnticipo','SolEquipamientoController@bloquearAnticipo');
        Route::put('/equipamiento/bloquearLiquidacion','SolEquipamientoController@bloquearLiquidacion');
        
        Route::get('/equipamiento/indexObservacion','ObsservacionEquipamientoController@index');
        Route::post('/equipamiento/registrarObservacion','ObsservacionEquipamientoController@store');
        Route::post('/equipamiento/storeRecepcion','RecepEquipamientoController@storeRecepcion');
        Route::put('/equipamiento/updateRecepcion','RecepEquipamientoController@updateRecepcion');

        Route::get('/equipamiento/getResultados','RecepEquipamientoController@getRecepcion');

        Route::get('/equipamiento/recepcionClosets/{id}','RecepEquipamientoController@recepcionClosetsPDF');
        Route::get('/equipamiento/recepcionCocina/{id}','RecepEquipamientoController@recepcionCocinaPDF');
        Route::get('/equipamiento/recepcionGeneral/{id}','RecepEquipamientoController@recepcionGeneralPDF');


    /////////////////////// RUTAS CATALOGO DETALLES ///////////////////////////////
        Route::get('/detalles/generales','CatalogoDetalleController@indexGenerales');
        Route::get('/catalogoDetalle/indexSubCategoria','CatalogoDetalleController@indexSubCategoria');
        Route::get('/catalogoDetalle/indexDetalles','CatalogoDetalleController@indexDetalles');
        Route::get('/catalogoDetalle/selectGeneral','CatalogoDetalleController@selectGeneral');
        Route::get('/catalogoDetalle/selectSub','CatalogoDetalleController@selectSub');
        Route::get('/catalogoDetalle/selectDetalle','CatalogoDetalleController@selectDetalle');
        Route::get('/catalogoDetalle/getDatosDetalle','CatalogoDetalleController@getDatosDetalle');

        Route::post('/detalles/generales/registrar','CatalogoDetalleController@storeGenerales');
        Route::put('/detalles/generales/actualizar','CatalogoDetalleController@updateGenerales');

        Route::post('/catalogoDetalle/storeSubconcepto','CatalogoDetalleController@storeSubconcepto');
        Route::put('/catalogoDetalle/updateSubconcepto','CatalogoDetalleController@updateSubconcepto');

        Route::post('/catalogoDetalle/storeDetalle','CatalogoDetalleController@storeDetalle');
        Route::put('/catalogoDetalle/updateDetalle','CatalogoDetalleController@updateDetalle');

        Route::put('/catalogoDetalle/activarDetalle','CatalogoDetalleController@activarDetalle');
        Route::put('/catalogoDetalle/desactivarDetalle','CatalogoDetalleController@desactivarDetalle');
    //////////////////////// RUTAS SOLIC DETALLES //////////////////////
        Route::post('/detalles/storeSolicitud','SolicDetallesController@storeSolicitud');
        Route::get('/detalles/indexSolicitudes','SolicDetallesController@indexSolicitudes');
        Route::get('/detalles/excelSolicitudes','SolicDetallesController@excelSolicitudes');

        Route::get('/detalles/agendaContratista','SolicDetallesController@agendaContratista');

        Route::get('/detalles/indexDescripciones','SolicDetallesController@indexDescripciones');
        Route::get('/detalles/reporteDetalles/pdf/{folio}','SolicDetallesController@reportePDF');
        Route::get('/detalles/reporteDetalles/reporteConclusionPDF/{folio}','SolicDetallesController@reporteConclusionPDF');
        Route::put('/detalles/updateFecha','SolicDetallesController@updateFecha');
        Route::put('/detalles/finalizarReporte','SolicDetallesController@finalizarReporte');
        Route::put('/detalles/updateHora','SolicDetallesController@updateHora');
        Route::put('/detalles/updateResultado','SolicDetallesController@updateResultado');
    
    /////////////////////////// RUTAS CREDITOS PUENTE ////////////////////////
        Route::get('/c_puente/indexSinCredito','CreditoPuenteController@indexSinCredito');
        Route::get('/cPuentes/getLotes','CreditoPuenteController@getLotes');
        Route::get('/cPuentes/getModelosPuente','CreditoPuenteController@getModelosPuente');
        Route::post('/cPuentes/storeSolicitud','CreditoPuenteController@storeSolicitud');
        Route::put('/cPuentes/cancelarCredito','CreditoPuenteController@cancelarCredito');
        Route::post('/cPuentes/agregarLote','CreditoPuenteController@agregarLote');
        Route::delete('/cPuentes/eliminarLote','CreditoPuenteController@eliminarLote');
        Route::put('/cPuentes/actualizarPrecio','CreditoPuenteController@actualizarPrecio');
        Route::put('/cPuentes/actualizarSolicitud','CreditoPuenteController@actualizarSolicitud');
        Route::put('/cPuentes/cambiarChk','CreditoPuenteController@cambiarChk');
        Route::delete('/cPuentes/deleteChk','CreditoPuenteController@deleteChk');
        Route::post('/cPuentes/storeObs','CreditoPuenteController@storeObs');
        Route::post('/cPuentes/addDocChk','CreditoPuenteController@addDocChk');
        Route::post('/cPuentes/saveDoc','CreditoPuenteController@saveDoc');
        Route::put('/cPuentes/confirmarEntrega/documento','CreditoPuenteController@confirmarEntregaDoc');
        Route::put('/cPuentes/actualizarFolio','CreditoPuenteController@actualizarFolio');
        Route::put('/cPuentes/insertFechaFirma','CreditoPuenteController@insertFechaFirma');

        Route::post('/cPuentes/pagos/formSubmit','CreditoPuenteController@formSubmit');
        Route::get('/cPuentes/pagos/{tipo}/{fileName}' , 'CreditoPuenteController@downloadFile');

        Route::put('/cPuentes/ingresarExpTecnico','CreditoPuenteController@ingresarExpTecnico');
        Route::put('/cPuentes/resBanco','CreditoPuenteController@resBanco');
        Route::put('/cPuentes/numCuenta','CreditoPuenteController@numCuenta');

        Route::get('/cPuentes/indexCreditos','CreditoPuenteController@indexCreditos');
        Route::get('/cPuentes/indexCreditosAvances','CreditoPuenteController@indexCreditosAvances');
        Route::get('/cPuentes/selectLotes','CreditoPuenteController@selectLotes');
        Route::get('/cPuentes/getPreciosModelo','CreditoPuenteController@getPreciosModelo');
        Route::get('/cPuentes/getLotesPuente','CreditoPuenteController@getLotesPuente');
        Route::get('/cPuentes/lotesAvance','CreditoPuenteController@lotesAvance');
        Route::get('/cPuentes/getObs','CreditoPuenteController@getObs');
        Route::get('/cPuentes/getPlanos','CreditoPuenteController@getPlanos');
        Route::get('/cPuentes/getChecklist','CreditoPuenteController@getChecklist');
        Route::get('/cPuentes/getChkSinSolic','CreditoPuenteController@getChkSinSolic');

        Route::get('/cPuentes/getModelosBase','CreditoPuenteController@getModelosBase');
        Route::get('/cPuentes/getEdoCuenta','CreditoPuenteController@getEdoCuenta');

        Route::get('/cPuentes/tiie','CreditoPuenteController@tiie');
        Route::get('/cPuentes/calcularInteres','CreditoPuenteController@calcularInteres');

        Route::post('/cPuentes/storeCargo','CreditoPuenteController@storeCargo');
        Route::post('/cPuentes/storeAbono','CreditoPuenteController@storeAbono');
        Route::post('/cPuentes/storeAbonoBBVA','CreditoPuenteController@storeAbonoBBVA');
        Route::post('/cPuentes/storeIntereses','CreditoPuenteController@storeIntereses');
        Route::post('/cPuentes/storeInteresesBBVA','CreditoPuenteController@storeInteresesBBVA');
        Route::put('/cPuentes/storePago','CreditoPuenteController@storePago');
        Route::get('/cPuentes/interesCargos','CreditoPuenteController@calcularInteresPagos');
        Route::get('/cPuentes/getInteresePeriodo','CreditoPuenteController@getInteresePeriodo');

        Route::get('/cPuentes/selectCreditosPuente','CreditoPuenteController@selectCreditosPuente');

        Route::get('/cPuentes/resumenCreditos','CreditoPuenteController@resumenCreditos');

        Route::get('/cPuentes/getInteresBBVA','CreditoPuenteController@getInteresBBVA');
        
    //////////////////// RUTAS BONOS RECOMENDADOS ///////////////////////
        Route::get('/cat_bono/index','CatalogoBonoController@index');
        Route::post('/cat_bono/store', 'CatalogoBonoController@store');
        Route::put('/cat_bono/update', 'CatalogoBonoController@update');
        Route::delete('/cat_bono/delete', 'CatalogoBonoController@delete');

        Route::get('/bono_recomendado/index','BonoRecomendadoController@index');
        Route::get('/bono_recomendado/excel','BonoRecomendadoController@excel');
        Route::get('/bono_recomendado/listarObs','BonoRecomendadoController@listarObservaciones');
        Route::post('/bono_recomendado/storeObservacion', 'BonoRecomendadoController@storeObservacion');

        Route::post('/bono_recomendado/aprobarBono', 'BonoRecomendadoController@aprobarBono');
        Route::post('/bono_recomendado/cancelarBono', 'BonoRecomendadoController@cancelarBono');
        Route::post('/bono_recomendado/autorizarBono', 'BonoRecomendadoController@autorizarBono');
        Route::post('/bono_recomendado/generarPago', 'BonoRecomendadoController@generarPago');
        Route::put('/bono_recomendado/update', 'BonoRecomendadoController@update');

        //////////////////////// RUTAS CONTRATISTA //////////////////////
         Route::get('/solicitudes/indexContratista','SolicDetallesController@indexContratista');
         Route::get('/detalles/indexContratista','SolicDetallesController@indexDetallesContratista');
         Route::put('/detalles/updateCosto','SolicDetallesController@updateCosto');
         Route::put('/detalles/updateFechaConcluido','SolicDetallesController@updateFechaConcluido');

        /////////////////////// RUTAS DROPBOX /////////////////////////
        Route::post('/dropbox/files/{id}/{sub}', 'DropboxFilesController@store');
        Route::delete('/files/delete', 'DropboxFilesController@destroy');
        Route::get('/files/{carpeta}/{file}/download', 'DropboxFilesController@download');

        ///////////////////// RUTAS MENSAJES NOTIFICACION GENERAL /////////////////////
        Route::get('/notificacion/gral', 'NotificacionesAvisosController@getAvisos');
        Route::put('/notificacion/setEnterado','NotificacionesAvisosController@setEnterado');
        Route::post('/notificacion/storeAviso','NotificacionesAvisosController@storeAviso');
        Route::put('/notificacion/updateAviso','NotificacionesAvisosController@updateAviso');

        Route::get('/notificacion/indexAvisos', 'NotificacionesAvisosController@indexAvisos');
        
        /////////////////////// RUTAS BONOS VENTAS /////////////////////
        Route::get('/bonos_ventas/index_contratos', 'BonoVentaController@indexContratos');
        Route::get('/bonos_ventas/indexBonos', 'BonoVentaController@indexBonos');
        Route::post('/bonos_ventas/storeBono', 'BonoVentaController@storeBono');
        Route::post('/bonos_ventas/segundoBono', 'BonoVentaController@segundoBono');

        Route::get('/bonos_ventas/listarObs','BonoVentaController@listarObservaciones');
        Route::post('/bonos_ventas/storeObservacion', 'BonoVentaController@storeObservacion');
        Route::get('bonos_ventas/reciboPDF/{id}','BonoVentaController@reciboPDF');


        /////////////////////// RUTAS Versiones archivos modelo /////////////////////////
        Route::get('/modelos/archivos/versiones', 'VersionModeloController@getVersiones');
        Route::post('/modelos/archivos/formSubmit/{id}/{version}','VersionModeloController@formSubmit');
        Route::put('/modelos/archivos/updateVersionLote','VersionModeloController@updateVersionLote');
        Route::delete('/modelos/archivos/delete', 'VersionModeloController@delete');


        //////////////////// RUTAS PARA LEADS ////////////////////////
        Route::get('/campanias/index','CampaniaController@indexCampanias');
        Route::get('/campanias/excelLeads','DigitalLeadController@excelLeads');
        Route::put('/campanias/update', 'CampaniaController@update');
        Route::post('/campanias/store', 'CampaniaController@store');
        Route::delete('/campanias/delete', 'CampaniaController@delete');

        Route::get('/leads/getCliente','DigitalLeadController@getCliente');
        

        Route::get('/leads/index','DigitalLeadController@index');
        Route::delete('/leads/delete','DigitalLeadController@delete');
        Route::get('/leads/getObs','DigitalLeadController@getObs');
        Route::post('/leads/store', 'DigitalLeadController@store');
        Route::put('/leads/update', 'DigitalLeadController@update');
        Route::put('/leads/changeStatus', 'DigitalLeadController@changeStatus');
        Route::post('/leads/storeObs', 'DigitalLeadController@storeObs');
        Route::post('/leads/sendProspectos', 'DigitalLeadController@sendProspectos');

        Route::put('/leads/asignarLead','DigitalLeadController@asignarLead');
        Route::get('/comments/reminderCommentarioLead/', 'DigitalLeadController@reminderCommentarioLead');
        Route::put('/comments/leadEnterado', 'DigitalLeadController@leadEnterado');

        //////////////////// RUTAS BASE PRESUPUESTAL ///////////////////

        Route::post('/basePresupuestal/storeBases', 'BasePresupuestalController@storeBases');
        Route::get('/basePresupuestal/getBaseActiva', 'BasePresupuestalController@getBaseActiva');

        /////////////////// RUTAS REUBICACIONES ////////////////////////
        Route::get('/reubicaciones/getReubicaciones','ReubicacionController@getReubicaciones');
        Route::post('/reubicaciones/store','ReubicacionController@store');
        Route::delete('/reubicaciones/delete','ReubicacionController@delete');

        Route::get('/reubicaciones/depositosPorReubicar','ReubicacionController@depositosPorReubicar');
        Route::get('/reubicaciones/depositosPorReubicarGCC','ReubicacionController@depositosPorReubicarGCC');
        Route::post('/reubicaciones/storeDepositoReubicacion','ReubicacionController@storeDepositoReubicacion');
        Route::get('/reubicaciones/indexGCC','ReubicacionController@indexGCC');
        Route::get('/reubicaciones/indexConc','ReubicacionController@indexConc');

        Route::post('/reubicaciones/storeConc','ReubicacionController@storeConc');
        Route::post('/reubicaciones/storeGCC','ReubicacionController@storeGCC');

        Route::get('/reubicaciones/getComentarios','ReubicacionController@getComentarios');
        Route::post('/reubicaciones/agregarComentario','ReubicacionController@agregarComentario');

        //////////////////// RUTAS VEHICULOS //////////////////////////
        Route::get('/vehiculos/index','VehiculosController@index');
        Route::get('/vehiculos/getMarcas','VehiculosController@getMarcas');
        Route::post('/vehiculos/store','VehiculosController@store');
        Route::put('/vehiculos/update','VehiculosController@update');

        Route::get('/vehiculos/getComoDato','VehiculosController@getComoDato');
        Route::get('/vehiculos/getSolicitudes','VehiculosController@getSolicitudes');
        Route::get('/vehiculos/getRetenciones','VehiculosController@getRetenciones');
        Route::get('/vehiculos/getSolicitudesExcel','VehiculosController@getSolicitudesExcel');
        Route::post('/vehiculos/storeSolicitud','VehiculosController@storeSolicitud');
        Route::put('/vehiculos/updateSolicitud','VehiculosController@updateSolicitud');

        Route::put('/vehiculos/setRecepJefe','VehiculosController@setRecepJefe');
        Route::put('/vehiculos/setRecepRH','VehiculosController@setRecepRH');
        Route::put('/vehiculos/setRecepControl','VehiculosController@setRecepControl');
        Route::put('/vehiculos/setRecepDireccion','VehiculosController@setRecepDireccion');
        Route::put('/vehiculos/recep/changeStatus','VehiculosController@changeStatus');
        Route::put('/vehiculos/retenerPago','VehiculosController@retenerPago');
        Route::post('/vehiculos/storeRetencion','VehiculosController@storeRetencion');

        Route::get('/vehiculos/getObservaciones','VehiculosController@getObservaciones');
        Route::post('/vehiculos/storeObs','VehiculosController@storeObs');
        Route::delete('/vehiculos/eliminarRetencion','VehiculosController@eliminarRetencion');
        ////////////////////// RUTAS PRESTAMOS////////////////////
        Route::get('/prestamos/getDataPrestamos','PrestamosController@getDataPrestamos');
        Route::get('/prestamos/getColaborador','PrestamosController@dataColaborador');
        Route::post('/prestamos/registrarPrestamo','PrestamosController@registrarPrestamo');
        Route::put('/prestamos/firmar','PrestamosController@firmarPrestamo');
        Route::put('/prestamos/aprobar_rh','PrestamosController@aprobar_rh');
        Route::post('/prestamos/post_obs','PrestamosController@observaciones_prestamos');
        Route::get('/prestamos/get_obs','PrestamosController@getObservaciones');
        Route::put('/prestamos/editarPrestamo','PrestamosController@editarPrestamo');
        Route::post('/prestamos/post_tablaPagosAprobada','PrestamosController@generaTablaPagos');
        Route::get('/prestamos/getTablaPagos','PrestamosController@getTablaPagos');
        Route::put('/prestamos/capturar_pago','PrestamosController@capturar_pago');
        Route::delete('/prestamos/eliminarSolicitud','PrestamosController@eliminarSolicitud');
        Route::get('/prestamos/getUsers','PrestamosController@getUsers');
        Route::put('/prestamos/guardaTablaPagos','PrestamosController@guardaTablaPagos');
        
        
        

        //////////////////////// RUTAS INVENTARIOS DE PRODUCTOS ///////////////////
        Route::get('/inventarios/getInventario','InventariosController@getInventario');
        Route::get('/inventarios/getProveedores','InventariosController@getProveedores');
        Route::get('/inventarios/returnProveedor','InventariosController@returnProveedor');

        Route::post('/inventarios/storeInventario','InventariosController@storeInventario');
        Route::put('/inventarios/updateInventario','InventariosController@updateInventario');
        Route::post('/inventarios/storeProveedor','InventariosController@storeProveedor');
        Route::put('/inventarios/updateProveedor','InventariosController@updateProveedor');

        Route::post('/inventarios/storeCompra','InventariosController@storeCompra');
        Route::get('/inventarios/getCompras','InventariosController@getCompras');

        Route::post('/inventarios/storeSalida','InventariosController@storeSalida');
        Route::get('/inventarios/getSalidas','InventariosController@getSalidas');

        Route::delete('/inventarios/deleteEntrada','InventariosController@deleteEntrada');
        Route::delete('/inventarios/deleteSalida','InventariosController@deleteSalida');


        ///////////////////// RUTAS INTEGRACION DE COBROS //////////////
        Route::get('/integracionCobros/getContratos','CobrosController@getContratos');
        Route::get('/integracionCobros/getIntegraciones','CobrosController@getIntegraciones');
        Route::get('/integracionCobros/getCobros','CobrosController@getCobros');
        Route::post('/integracionCobros/generarIntegración','CobrosController@generarIntegración');
        Route::post('/integracionCobros/storeCobro','CobrosController@storeCobro');
        Route::put('/integracionCobros/updateCobro','CobrosController@updateCobro');
        Route::put('/integracionCobros/finalizarIntegracion','CobrosController@finalizarIntegracion');
        Route::delete('/integracionCobros/eliminarCobro','CobrosController@eliminarCobro');
        //Route::get('/integracionCobros/exportFormat','CobrosController@exportFormat');
        Route::get('/integracionCobros/printConvenioModificatorio','CobrosController@printConvenioModificatorio');

        /////////////////////// Rutas Fondo de ahorro
        Route::resource('/fondo-ahorro', Rh\FondoAhorroController::class);

    });


    ///////////////////       RUTAS SELECT    ////////////////////////////////////
        Route::get('/select_departamentos','DepartamentoController@selectDepartamento');
        Route::get('/select_colonias','CiudadController@selectColonias');
        Route::get('/select_colonias_vue','CiudadController@selectColoniasVue');
        Route::get('/select_numcontratos_obra','IniObraController@select_numContrato');
        Route::get('/select_empresas','EmpresaController@selectEmpresa');
        Route::get('/select_ciudades','CiudadController@selectCiudades');
        Route::get('/select_personal','PersonalController@selectNombre'); //Nombre completo de persona (Directivos activos)
        Route::get('/select_fraccionamiento','FraccionamientoController@selectFraccionamiento'); 
        Route::get('/selectFraccionamientoConInventario','FraccionamientoController@selectFraccionamientoConInventario'); 
        Route::get('/select_fraccionamiento2','FraccionamientoController@selectFraccionamientoVue'); 
        Route::get('/select_coacreditadoVue','ClienteController@selectCoacreditadoVue'); 
        Route::get('/contador_etapa','EtapaController@contEtapa'); 
        Route::get('/select_etapa_proyecto','EtapaController@selectEtapa_proyecto'); 
        Route::get('/selectEtapaDisp','EtapaController@selectEtapaDisp');
        Route::get('/select_etapa','EtapaController@selectEtapa'); 
        Route::get('/select_modelo_proyecto','ModeloController@selectModelo_proyecto');
        Route::get('/selectModeloDisp','ModeloController@selectModeloDisp');
        Route::get('/select_manzana_proyecto','LoteController@selectManzana_proyecto'); 
        Route::get('/selectManzana_dist','CreditoController@selectManzana'); 
        Route::get('/select_construcc_terreno','ModeloController@selectConsYTerreno'); 
        Route::get('/select_modelos_etapa','LoteController@select_modelos_etapa'); 
        Route::get('/select_manzanas_etapa','LoteController@select_manzanas_etapa'); 
        Route::get('/select_lotes_manzana','LoteController@select_lote_manzana'); 
        Route::get('/select_precio_etapa','PrecioEtapaController@selectPrecioEtapa'); 
        Route::get('/select_sobreprecios_etapa','SobreprecioEtapaController@select_sobreprecios_etapa'); 
        Route::get('/select_contratistas','ContratistaController@selectContratista'); 
        Route::get('/select_contratistas2','ContratistaController@selectContratistaVue'); 
        Route::get('/select_manzana_lotes','IniObraController@select_manzana_lotes');
        Route::get('/select_lotes_obra','IniObraController@select_lotes'); 
        Route::get('/select_datos_lotes','IniObraController@select_datos_lotes'); 
        Route::get('/select_roles','RolController@selectRol');
        Route::get('/select_personas_sin_user','PersonalController@select_Pers_sinUser');
        Route::get('/select_medio_publicidad','MedioPublicitarioController@selectMedioPublicitario');
        Route::get('/select_lugar_contacto','LugarContactoController@selectLugar_contacto');
        Route::get('/select_vendedores','UserController@selectVendedores');
        Route::get('/select_clientes','ClienteController@selectClientes');
        Route::get('/select_inst_financiamiento','InstitucionFinanciamientoController@select_institucion_financiamiento');
        Route::get('/select_tipoCredito','TipoCreditoController@select_tipoCredito');
        Route::get('/select_datos_apartado','ApartadoController@select_datos_apartado');
        Route::get('/select_institucion','TipoCreditoController@select_institucion');
        Route::get('/select_estados','CiudadController@selectEstados');
        Route::get('/select_lotes_disp','LoteController@select_lotes_disp');
        Route::get('/select_etapas_disp','LoteController@select_etapas_disp');
        Route::get('/select_manzanas_disp','LoteController@select_manzanas_disp');
        Route::get('/select_datos_lotes_disp','LoteController@select_datos_lotes_disp');
        Route::get('/select_paquetes','PaqueteController@select_paquetes');
        Route::get('/select_datos_paquetes','PaqueteController@select_datos_paquetes');
        Route::get('/select_servicios','ServicioController@selectServicio');
        Route::get('/select_tipcreditos_simulacion','CreditoController@selectTipCreditosSimulacion');
        Route::get('/servicios_etapas','ServEtapaController@index');
        Route::get('/select_gerentes','UserController@select_users_gerentes');
        Route::get('/select_rfcs','PersonalController@selectRFC');
        Route::get('/select_notarias','NotariaController@select_notarias');
        Route::get('/select_gestores','PersonalController@select_gestores');
        Route::get('/selectGerentesVentas','UserController@selectGerentesVentas');

        Route::get('/select_lotes_entregados','LoteController@select_lotes_entregados');
        Route::get('/select_etapas_entregados','LoteController@select_etapas_entregados');
        Route::get('/select_manzanas_entregados','LoteController@select_manzanas_entregados');

        Route::get('/select_datos_notaria','NotariaController@getDatosNotaria'); 
        Route::get('/select_ultima_fecha_instalacion','EntregaController@select_ultimaFecha_instalacion');
        Route::get('/selectCreditoPuente','CuentasController@selectCreditoPuente');

        Route::get('/select_clientesVenta','PersonalController@selectClientesVenta');
        Route::get('/clientes/getDatosVentas','PersonalController@getDatosCliente');

        Route::get('/campanias/campaniaActiva','CampaniaController@campaniaActiva');
        Route::get('/ruvs/selectRuv','RuvController@selectRuv');
        

        Route::get('/getPendientesPagos','DepositoController@getPagosVencidos');
///////////////////////////////////////////////////////////////////////////////////////////////////////////
        Route::get('/asignarPartidasUrb','AvanceController@addPartidasUrbanizacion');
        Route::get('/asignarLotesDep','DepositoController@asignarLotes');

        Route::get('/ventasAbastos','ReportesController@ventasAbastos');

});

// clave pusher: p3TvG4EPftYJPU7
