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

// rutas usuarios invitados
Route::group(['middleware' => ['guest']],function(){

    Route::get('/','Auth\LoginController@showLoginForm');
    Route::get('/login','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');
});


// rutas usuarios autenticados
Route::group(['middleware' => ['auth']],function(){

    //Notificaciones
    Route::post('/notification/get','NotificationController@get');
    Route::get('/notification/getListado','NotificationController@getListado');

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
        Route::get('/select/asesores','UserController@selectAsesores');
        Route::put('/cliente/reasignar','ClienteController@asignarCliente');

    ///////////////////        RUTAS Prospectos    //////////////////////////////////
        Route::get('/clientes','ClienteController@index');
        Route::get('/clientes_simulacion','ClienteController@clientesSimulacion');
        Route::get('/clientes/obtenerDatos','ClienteController@obtenerDatos');
        Route::post('/clientes/registrar','ClienteController@store');
        Route::post('/clientes/storeObservacion','ClienteController@storeObservacion');
        Route::post('/clientes/registrar_coacreditado','ClienteController@storeCoacreditado');
        Route::put('/clientes/actualizar','ClienteController@update');
        Route::put('/clientes/actualizar2','ClienteController@updateProspecto');
        Route::get('/clientes/observacion','ClienteController@listarObservacion');
        Route::put('/clientes/desactivar','ClienteController@desactivar');
        Route::put('/clientes/activar','ClienteController@activar');
        Route::get('/prospectos/excel','ClienteController@exportExcelClientesAsesor');
        Route::get('/prospectos/excel/gerente','ClienteController@exportExcelClientesGerente');
    ///////////////////        RUTAS Medios Publicitarios    //////////////////////////////////
        Route::get('/medio_publicitario','MedioPublicitarioController@index');
        Route::post('/medio_publicitario/registrar','MedioPublicitarioController@store');
        Route::put('/medio_publicitario/actualizar','MedioPublicitarioController@update');
        Route::delete('/medio_publicitario/eliminar','MedioPublicitarioController@destroy');

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
        Route::post('/fraccionamiento/registrar','FraccionamientoController@store');
        Route::put('/fraccionamiento/actualizar','FraccionamientoController@update');
        Route::delete('/fraccionamiento/eliminar','FraccionamientoController@destroy');
        Route::post('/formSubmitPlanos/{id}','FraccionamientoController@formSubmitPlanos'); //carga de planos
        Route::get('/downloadPlanos/{fileName}' , 'FraccionamientoController@downloadFilePlanos'); //descarga de planos
        Route::post('/formSubmitEscrituras/{id}','FraccionamientoController@formSubmitEscrituras'); //carga de escrituras
        Route::get('/downloadEscrituras/{fileName}' , 'FraccionamientoController@downloadFileEscrituras'); //descarga de escrituras

        
       
        

    /////////////////////   RUTAS ETAPAS        //////////////////////////////////////
        Route::get('/etapa','EtapaController@index');
        Route::post('/etapa/registrar','EtapaController@store');
        Route::put('/etapa/actualizar','EtapaController@update');
        Route::delete('/etapa/eliminar','EtapaController@destroy');

        Route::post('/formSubmitReglamento/{id}','EtapaController@uploadReglamento');
        Route::post('/formSubmitCartaServicios/{id}','EtapaController@uploadPlantillaCartaServicios');
        Route::get('/downloadReglamento/{fileName}' , 'EtapaController@downloadReglamento');
        Route::get('/downloadPlantilla/cartaServicios/{fileName}' , 'EtapaController@downloadPlantillaCartaServicios');
        Route::post('/etapas/costoMantenimiento/registrar/{id}','EtapaController@registrarCostoMantenimiento');
        Route::post('/formSubmitTelecom/{id}','EtapaController@uploadPlantillaTelecom');
        Route::get('/downloadPlantilla/ServiciosTelecom/{fileName}' , 'EtapaController@downloadPlantillaTelecom');

        
    ///////////////////     RUTAS PERSONAL      ////////////////////////////////////
        Route::get('/personal','PersonalController@index');
        Route::post('/personal/registrar','PersonalController@store');
        Route::put('/personal/actualizar','PersonalController@update');
        Route::put('/personal/desactivar','PersonalController@desactivar');
        Route::put('/personal/activar','PersonalController@activar');
        Route::delete('/personal/eliminar','PersonalController@destroy');
    
    ////////////////////        RUTAS EMPRESA     /////////////////////////////////
        Route::get('/empresa','EmpresaController@index');
        Route::get('/empresa/datos','EmpresaController@getDatosEmpresa');
        Route::post('/empresa/registrar','EmpresaController@store');
        Route::put('/empresa/actualizar','EmpresaController@update');
        Route::delete('/empresa/eliminar','EmpresaController@destroy');
        
    ////////////////////        RUTAS MODELOS     /////////////////////////////////
        Route::get('/modelo','ModeloController@index'); 
        Route::post('/modelo/registrar','ModeloController@store');
        Route::put('/modelo/actualizar','ModeloController@update');
        Route::delete('/modelo/eliminar','ModeloController@destroy');
        Route::post('/formSubmitModelo/{id}','ModeloController@formSubmit');
        Route::get('/downloadModelo/{fileName}' , 'ModeloController@downloadFile');
    
    ////////////////////        RUTAS LOTES    /////////////////////////////////
        Route::get('/lote','LoteController@index'); 
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
        Route::put('/lotes/enviarAviObra','LoteController@enviarAviso');
        Route::get('/lotes/export_excel/{fraccionamiento_id}','LoteController@excelLotes');
        Route::get('/lotes/resume_excel_lotes_disp','LoteController@exportExcelLotesDisp');
        Route::get('/lotes/con_precio_base','LoteController@LotesConPrecioBase');
        Route::put('/lotes/actualizar/ajuste','LoteController@updateAjuste');
        
    ////////////////////        RUTAS APARTADOS    /////////////////////////////////
        Route::post('/apartado/registrar','ApartadoController@store');
        Route::put('/apartado/actualizar','ApartadoController@update');
        Route::delete('/apartado/eliminar','ApartadoController@destroy');

    ///////////////////       RUTAS LICENCIA   ////////////////////////////////////
        Route::get('/licencias','LicenciasController@index');
        Route::put('/licencias/actualizar','LicenciasController@update');
        Route::put('/licencias/actualizarMasa','LicenciasController@updateMasa');
        Route::get('/licencias/resume','LicenciasController@resumeLicencias');
        Route::post('/formSubmitLicencias/{id}','LicenciasController@formSubmit'); //carga de licencias
        Route::get('/downloadLicencias/{fileName}' , 'LicenciasController@downloadFile'); //descarga de licencias
        Route::get('/licencias/excel','LicenciasController@exportExcelLicencias'); //excel de las licencias
        
    ///////////////////       RUTAS LICENCIA-ACTA  ////////////////////////////////////
        Route::get('/acta_terminacion','LicenciasController@indexActa');
        Route::put('/acta_terminacion/actualizar','LicenciasController@updateActas');
        Route::get('/licencias/resume_excel','LicenciasController@exportExcel');
        Route::post('/formSubmitActa/{id}','LicenciasController@formSubmitActa'); //carga de acta
        Route::get('/downloadActa/{fileName}' , 'LicenciasController@downloadFileActa'); //descarga de acta
        Route::post('/formSubmitPredial/{id}','LicenciasController@formSubmitPredial'); //carga de predial
        Route::get('/downloadPredial/{fileName}' , 'LicenciasController@downloadFilePredial'); //descarga de predial
        Route::get('/acta_terminacion/excel','LicenciasController@exportExcelActaTerminacion'); //excel de las actas
    
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
        Route::put('/iniobra/actualizar','IniObraController@ActualizarIniObra');
        Route::get('/iniobra/obtenerCabecera','IniObraController@obtenerCabecera');
        Route::get('/iniobra/obtenerDetalles','IniObraController@obtenerDetalles');
        Route::delete('/iniobra/contrato/eliminar','IniObraController@eliminarContrato');
        Route::post('/iniobra/lote/registrar','IniObraController@agregarIniObraLotes');
        Route::delete('/iniobra/lote/eliminar','IniObraController@eliminarIniObraLotes');
        Route::get('/iniobra/pdf/{id}','IniObraController@contratoObraPDF')->name('contratos.pdf');
        Route::get('/iniobra/relacion/excel/{id}','IniObraController@exportExcel');
        Route::get('/licencias/indexVisita','LicenciasController@indexVisita');
        Route::put('/licencias/progFechaVisita','LicenciasController@AsigFechaVisita');


    ////////////////////        RUTAS PARTIDAS   /////////////////////////////////
        Route::get('/partidas','PartidaController@index');
        Route::post('/partidas/registrar','PartidaController@registrar');
        Route::put('/partidas/actualizar','PartidaController@update');
        Route::delete('/partidas/eliminar','PartidaController@destroy');

    
    /**********************************RUTAS OBSERVACION LOTES*************************** */
        Route::post('/observacion/registrar','ObservacionController@store');
        Route::get('/observacion/select_ultima','ObservacionController@select_ultima'); 
        Route::get('/observacion','ObservacionController@index');
    /***************************************************************************** */
    
    /**********************************RUTAS AVANCE*************************** */
        Route::get('/avance','AvanceController@index');
        Route::get('/avanceProm','AvanceController@indexProm');
        Route::put('/avance/actualizar','AvanceController@update');
        Route::get('/avances/resume_excel/{contrato}','AvanceController@exportExcel');
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
        

        
        Route::post('/users/foto/{id}','UserController@updateProfile');
        Route::put('/users/update/password','UserController@updatePassword');

        /**************************** RUTAS MODULO CONTRATOS  ***************************/
        Route::get('/contratos','ContratoController@indexContrato');
        Route::get('/creditos_aprobados','ContratoController@indexCreditosAprobados');
        Route::get('/contratos/pagos','ContratoController@listarPagos');
        Route::get('/credito/datos_credito','ContratoController@getDatosCredito');
        Route::post('/contrato/registrar','ContratoController@store');
        Route::put('/contrato/actualizarCredito','ContratoController@updateDatosCredito');
        Route::post('/contrato/pagos/agregar','ContratoController@agregarPago');
        Route::delete('/contrato/pagos/eliminar','ContratoController@eliminarPago');
        Route::put('/contrato/actualizar','ContratoController@actualizarContrato');
        Route::put('/contrato/reasignar','ContratoController@reasignarCliente');

        Route::get('/contratoCompraVenta/pdf/{id}','ContratoController@contratoCompraVentaPdf')->name('contratoCompraVenta.pdf');
        Route::get('/pagareContrato/pdf/{id}','ContratoController@pagareContratopdf')->name('pagare.pdf');
        Route::get('/descargarReglamento/contrato/{id}','EtapaController@descargarReglamentoContrato');
        Route::get('/contratoCompraVenta/reservaDeDominio/pdf/{id}','ContratoController@contratoConReservaDeDominio')->name('contrato_reserva_de_dominio.pdf');
        Route::get('/contrato/promesaCredito/pdf/{id}','ContratoController@contratoDePromesaCredito')->name('contrato_promesa_credito.pdf');
        Route::get('/contrato/modelo/caracteristicas/pdf/{id}','ModeloController@modeloArchivoContrato');
        Route::put('/contrato/status/fecha','ContratoController@statusContrato');
        Route::get('/contratos/excel','ContratoController@excelContratos');
        Route::get('/contratos/validarLotes','ContratoController@validarLoteEnContrato');

        /************************** RUTAS Depositos y Pagares ***************************/
        Route::get('/pagares','DepositoController@indexPagares');
        Route::get('/depositos','DepositoController@indexDepositos');
        Route::post('/deposito/registrar','DepositoController@store');
        Route::put('/deposito/actualizar','DepositoController@update');
        Route::get('deposito/reciboPDF/{id}','DepositoController@reciboPDF');
        Route::delete('/deposito/eliminar','DepositoController@delete');

        //////////////////////////////// RUTAS MODULO SALDOS ////////////////////////////
        Route::get('/estadoCuenta/index','DepositoController@indexEstadoCuenta');
        Route::get('/estadoCuenta/estadoPDF/{id}','DepositoController@estadoPDF');


        /************************** RUTAS ESTADISTICAS ***************************/
        Route::get('/estadisticas/datos_extra','EstadisticasController@estad_datos_extra');

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

        ////////////////////////// RUTAS ASIGNAR GESTOR /////////////////////////////

        Route::get('/expediente/indexAsignarGestor','ExpedienteController@indexAsignarGestor');
        Route::put('/expediente/asignarGestor','ExpedienteController@asignarGestor');

        ///////////////////////// RUTAS AVALUOS /////////////////////////////////////
        Route::get('/avaluos/index','AvaluoController@index');
        Route::put('/avaluos/fechaSolicitud','AvaluoController@setFechaSolicitud');
        Route::put('/avaluos/fechaPago','AvaluoController@setFechaPago');
        Route::put('/avaluos/fechaConcluido','AvaluoController@setFechaConcluido');
        Route::put('/avaluos/enviarVentas','AvaluoController@enviarVentas');
        Route::get('/avaluos/historialVisita','HistVisitasController@index');
        Route::get('/getGastoAvaluo','GastosAdministrativosController@getDatosAvaluo');
        Route::post('/avaluos/storeVisita','HistVisitasController@store');
        Route::post('/avaluos/storeStatus','HistVisitasController@storeStatus');
        Route::get('/avaluos/historialStatus','HistVisitasController@indexStatus');
        Route::post('/gastos/storeAvaluo','GastosAdministrativosController@storeAvaluo');
        Route::put('/gastos/updateAvaluo','GastosAdministrativosController@updateAvaluo');

        //////////////////// RUTAS GASTOS ADMINISTRATIVOS /////////////////////////////
        Route::get('/gastos/index','GastosAdministrativosController@index');
        Route::get('/gastos/indexContratos','GastosAdministrativosController@indexContratos');
        Route::post('/gastos/registrar','GastosAdministrativosController@store');
        Route::put('/gastos/actualizar','GastosAdministrativosController@update');
        Route::delete('/gastos/eliminar','GastosAdministrativosController@delete');

        //////////////////// RUTAS SEGUIMIENTO TRAMITE /////////////////////////////////
        Route::get('/expediente/ingresarIndex','ExpedienteController@indexIngresarExp');
        Route::get('/expediente/autorizadosIndex','ExpedienteController@indexAutorizados');
        Route::get('/expediente/liquidacionIndex','ExpedienteController@indexLiquidacion');
        Route::get('/expediente/ProgramacionIndex','ExpedienteController@indexProgramacion');
        Route::put('/expediente/ingresarExp','ExpedienteController@ingresarExp');
        Route::put('/expediente/inscInfonavit','ExpedienteController@inscribirInfonavit');
        Route::put('/expediente/InfonavitNoAplica','ExpedienteController@noAplicaInfonavit');
        Route::put('/expediente/generarLiquidacion','ExpedienteController@setLiquidacion');
        Route::put('/expediente/generarInstruccionNot','ExpedienteController@generarInstruccionNot');

        Route::post('/expediente/generarPagares','ExpedienteController@generarPagares');

        Route::get('/expediente/pagaresExpediente','ExpedienteController@pagaresExpediente');
        Route::get('/expediente/gastosExpediente','GastosAdministrativosController@getGastos');

        Route::get('/expediente/liquidacionPDF/{id}','ExpedienteController@liquidacionPDF');

        //////////////////////  RUTAS COBRO CREDITO //////////////////////////////////////
        Route::get('/cobroCredito/indexCreditos','InstSeleccionadasController@indexCreditoSel');
        Route::get('/cobroCredito/indexDepositos','InstSeleccionadasController@indexDepCredito');
        Route::post('/cobroCredito/registrar','InstSeleccionadasController@storeDepositoCredito');
        Route::put('/cobroCredito/update','InstSeleccionadasController@updateDepositoCredito');
        Route::get('/cobroCredito/excel','InstSeleccionadasController@excelCobroCredito');

        Route::get('/archivos/indexDocs','ModeloController@indexDocs');
        Route::get('/archivos/reglamentoEtapa/{etapa_id}','EtapaController@descargaReglamentoDocs');
        Route::get('/archivos/catalogoEspecificaciones/{modelo_id}','ModeloController@descargaCatalogoDocs');
        Route::get('/archivos/cartaServicios/{etapa_id}','ServicioController@cartaDeServicioDocs');
        Route::get('/archivos/cartaServiciosTelecomunicaciones/{etapa_id}','ServicioController@cartaDeTelecomunicacionesDocs');

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
        Route::get('/select_fraccionamiento2','FraccionamientoController@selectFraccionamientoVue'); 
        Route::get('/select_coacreditadoVue','ClienteController@selectCoacreditadoVue'); 
        Route::get('/select_Frac_Tipo','FraccionamientoController@selectFrac_Tipo');
        Route::get('/contador_etapa','EtapaController@contEtapa'); 
        Route::get('/select_etapa_proyecto','EtapaController@selectEtapa_proyecto'); 
        Route::get('/select_etapa','EtapaController@selectEtapa'); 
        Route::get('/select_modelo_proyecto','ModeloController@selectModelo_proyecto');
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
        Route::get('/select_fraccionamientoLote','FraccionamientoController@selectFraccionamientoConLotes'); 
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

        Route::get('/select_datos_notaria','NotariaController@getDatosNotaria'); 
    
    
});

// clave pusher: p3TvG4EPftYJPU7