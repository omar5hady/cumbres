<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Lotes
                        <!--   Boton   -->
                        <button type="button" class="btn btn-success" @click="abrirModal('lote','asignar')" >
                            <i class="icon-pencil"></i>&nbsp;Asignar Modelos
                        </button>
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="lotes.calle">Calle</option>
                                        <option value="lotes.credito_puente">Credito Puente</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar), selectModelo(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar2" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio=='modelos.nombre'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-if="criterio=='lotes.calle'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)" class="form-control" placeholder="Texto a buscar">
                                                                        
                                    <input type="text" v-if="criterio=='lotes.credito_puente'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="criterio=='lotes.fraccionamiento_id'">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_modelo" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)">
                                        <option value="">Modelo</option>
                                        <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                    </select>
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar3" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)" class="form-control" placeholder="Manzana a buscar">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="criterio=='lotes.fraccionamiento_id'">
                            <div class="col-md-8">
                                <div class="input-group">
                                   
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)" class="form-control" placeholder="Lote a buscar">
                                     <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_puente" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)"> 
                                        <option value="">Credito Puente</option>
                                        <option v-for="puente in arrayPuentes" :key="puente.credito_puente" :value="puente.credito_puente" v-text="puente.credito_puente"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_habilitado" >
                                        <option value="1">Habilitado</option>
                                        <option value="0">Deshabilitado</option>
                                        <option value="">Todos</option>
                                    </select>
                                    <button type="submit" @click="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,b_habilitado,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a class="btn btn-success" v-bind:href="'/asignar_modelo/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3  + '&bmodelo=' + b_modelo + '&blote=' + b_lote + '&b_habilitado='+ b_habilitado+'&criterio=' + criterio + '&b_puente=' + b_puente" >
                                        <i class="icon-pencil"></i>&nbsp;Excel
                                    </a>
                                    <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Total: '+ contador"> </span>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected">
                                        </th>
                                        <th>Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Etapa comercial</th>
                                        <th>Etapa de servicio</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Sublote</th>
                                        <th>Modelo</th>
                                        <th>Calle</th>
                                        <th>Numero</th>
                                        <th>Interior</th>
                                        <th>Terreno mts&sup2;</th>
                                        <th>Construcción mts&sup2;</th>
                                        <th>Credito puente</th>
                                        <th>Avance</th>
                                        <th>Casa en venta</th>
                                        <th>Canal de ventas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLote" :key="lote.id">
                                        <td class="td2">
                                        <input type="checkbox"  @click="select" :id="lote.id" :value="lote.id" v-model="lotes_ini" >
                                        </td>

                                        <td class="td2">
                                            <button type="button" @click="abrirModal('lote','actualizar',lote)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarLote(lote)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>

                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2">
                                            <span v-if = "lote.etapas!='Sin Asignar'" class="badge badge-success" v-text="lote.etapas"></span>
                                            <span v-else class="badge badge-danger"> Por Asignar </span>
                                        </td> 
                                        <td class="td2" v-text="lote.etapa_servicios"></td>
                                        <td class="td2" v-text="lote.manzana"></td>
                                        <td class="td2" v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.sublote"></td>
                                        <td class="td2">
                                            <span v-if = "lote.modelo!='Por Asignar'" class="badge badge-success" v-text="lote.modelo"></span>
                                            <span v-else class="badge badge-danger"> Por Asignar </span>
                                        </td> 
                                        <td class="td2" v-text="lote.calle"></td>
                                        <td class="td2" v-text="lote.numero"></td>
                                        <td class="td2" v-text="lote.interior"></td>
                                        <td class="td2" v-text="lote.terreno"></td>
                                        <td class="td2" v-text="lote.construccion"></td>
                                        <td class="td2" v-text="lote.credito_puente"></td>
                                        <td class="td2" v-text="lote.avance+'%'"></td>
                                        <td class="td2">
                                            <span v-if = "lote.casa_muestra==0 && lote.lote_comercial==0 && lote.habilitado==1" class="badge badge-success">Activo</span>
                                            <span v-else class="badge badge-danger">Inactivo</span>
                                        </td> 
                                        <td class="td2" v-text="lote.comentarios"></td>
                                    </tr>                               
                                </tbody>
                            </table> 
                        </div> 
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,buscar2,buscar3,b_modelo, b_lote,b_habilitado,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,b_modelo, b_lote,b_habilitado,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,b_modelo, b_lote,b_habilitado,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,b_modelo, b_lote,b_habilitado,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,buscar2,buscar3,b_modelo, b_lote,b_habilitado,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id),selectModelo(fraccionamiento_id),selectManzana(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                      
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="manzana" class="form-control" placeholder="Manzana">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"># Lote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="num_lote" class="form-control" placeholder="num_lote">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sublote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="sublote" class="form-control" placeholder="sublote">
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" @click="selectConsYTerreno(modelo_id)" v-model="modelo_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="calle" class="form-control" placeholder="Calle">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="numero" class="form-control" placeholder="Numero">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="interior" class="form-control" placeholder="Interior">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Terreno (mts&sup2;)</label>
                                    <div class="col-md-4" >
                                     
                                        <input type="text"  v-model="terreno" class="form-control" placeholder="Terreno">
                                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Construcción (mts&sup2;)</label>
                                    <div class="col-md-7">

                                        <input type="text" v-model="construccion" disabled class="form-control" placeholder="Construccion">
                                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Credito puente</label>
                                    <div class="col-md-7">

                                        <input type="text" v-model="credito_puente"  class="form-control" placeholder="Credito puente">
                                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de termino (Ventas)</label>
                                    <div class="col-md-7">

                                        <input type="date" v-model="fecha_termino_ventas"  class="form-control" placeholder="Credito puente">
                                  
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Comentarios </label>
                                    <div class="col-md-7">
                                        <textarea rows="3" cols="30" v-model="comentarios" class="form-control" placeholder="Comentarios"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="modelo_id!=0">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado </label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="habilitado">
                                            <option value="0">Deshabilitar</option>
                                            <option value="1">Habilitar</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="modelo_id!=0">
                                    <label class="col-md-3 form-control-label" for="text-input">Porcentaje por venta </label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="extra" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Porcentaje extra por venta">
                                    </div>

                                    <label class="col-md-3 form-control-label" for="text-input">Porcentaje por venta para externos </label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="extra_ext" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Porcentaje extra por venta">
                                    </div>

                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Casa muestra</label>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="casa_muestra" id="radio1" type="radio" value="1" name="radios">
                                            <label class="form-check-label" for="radio1">Si </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="casa_muestra" id="radio2" type="radio" value="0" name="radios">
                                            <label class="form-check-label" for="radio2">No </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lote comercial</label>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="lote_comercial" id="radio3" type="radio" value="1" name="radios2">
                                            <label class="form-check-label" for="radio3">Si </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="lote_comercial" id="radio4" type="radio" value="0" name="radios2">
                                            <label class="form-check-label" for="radio4">No </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Regimen condominio</label>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="regimen" id="radio5" type="radio" value="1" name="radios3">
                                            <label class="form-check-label" for="radio5">Si </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="regimen" id="radio6" type="radio" value="0" name="radios3">
                                            <label class="form-check-label" for="radio6">No </label>
                                        </div>
                                    </div>
                                
                                

                                    

                                    <!-- Hidden para agregar el id de la empresa -->
                                    
                                  <div class="form-group row">
                                    <div class="col-md-4">
                                        <input type="hidden" value="1" v-model="empresa_id" class="form-control">
                                    </div>
                                </div>

                                </div>
                                <!-- Div para mostrar los errores que mande validerModelo -->
                                <div v-show="errorLote" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLote" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarLote()">Actualizar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!-- Modal para asignar modelo -->
             <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                         <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id),selectModelo(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                             <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" @click="selectConsYTerreno(modelo_id)" v-model="modelo_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <button type="button"  class="btn btn-primary" @click="asignarModelos()">Actualizar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            


                    
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
 import _ from 'lodash'
    export default {
        data(){
            return{
                proceso:false,
                allSelected: false,
                lotes_ini : [],
                id: 0,
                fraccionamiento_id : 0,
                etapa_id: 0,
                b_modelo: '',
                b_habilitado: 1,
                b_lote: '',
                manzana: '',
                num_lote: 0,
                sublote: '',
                modelo_id: 0,
                empresa_id: 0,
                calle: '',
                numero: '',
                interior: '',
                terreno : 0,
                construccion : 0,
                casa_muestra: 0,
                lote_comercial: 0,
                habilitado: 0,
                credito_puente:'',
                comentarios: '',
                regimen:0,
                extra:0,
                extra_ext:0,
                file: '',
                modelostc :'',
                arrayLote : [],
                modal : 0,
                tituloModal : '',
                modal2: 0,
                tituloModal2: '',
                contador: 0,
                modal3: 0,
                tituloModal3: '',
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar2 : '',
                buscar3 : '',
                buscar : '',
                b_puente: '',
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayModelos : [],
                arrayModelosTC: [],
                arrayEmpresas : [],
                arrayManzanas: [],
                arrayPuentes: [],

                fecha_termino_ventas: ''
            }
        },
        computed:{

            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },

            arrayProyectos(){ 
                return _.uniqBy(this.arrayLote, 'proyecto');
            }

        },

        
        methods : {
            selectAll: function() {
            this.lotes_ini = [];

            if (!this.allSelected) {
                for (var lote in this.arrayLote
                ) {
                    this.lotes_ini.push(this.arrayLote[lote].id.toString());
                }
            }
            },

             select: function() {
                this.allSelected = false;
            },
            asignarModelos(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController

               Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Etapa y modelo se asignaran a los lotes seleccionados",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        var tamaño=me.lotes_ini.length;
                        var i=1;
                        var aviso =0;
                        me.lotes_ini.forEach(element => {
                            if(i == tamaño)
                        aviso = tamaño;
                    axios.put('/lote/actualizar3',{
                    
                    'id': element,
                    'modelo_id' : this.modelo_id,
                    'etapa_id' : this.etapa_id,
                    'aviso' : aviso
                    }); 
                    i++;
                });
                   // me.listarLote(1,'','','','','','','lote');   
                    me.listarLote(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_modelo,me.b_lote,me.b_habilitado,me.criterio);
                    me.cerrarModal2();
                    Swal({
                        title: 'Hecho!',
                        text: 'Los modelos se han asignado',
                        type: 'success',
                        animation: false,
                        customClass: 'animated bounceInRight'
                    })
                    }})
            },

            /**Metodo para mostrar los registros */
            listarLote(page, buscar, buscar2, buscar3, b_modelo, b_lote,b_habilitado, criterio){
                let me = this;
                var url = '/lote?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3  + '&bmodelo=' + b_modelo + '&blote=' + b_lote + '&b_habilitado='+ b_habilitado+'&criterio=' + criterio + '&b_puente=' + me.b_puente;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                    me.contador = respuesta.contadorAsignarModelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPuente(){
                let me = this;

                me.arrayPuentes=[];
                var url = '/selectCreditoPuente';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPuentes = respuesta.creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page, buscar, buscar2, buscar3, b_modelo, b_lote,b_habilitado, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLote(page,buscar,buscar2,buscar3, b_modelo, b_lote,b_habilitado, criterio);
            },

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
                }
                
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapa(buscar){
                let me = this;
                if(me.modal == 0){
                
                me.buscar2=""
                me.buscar3=""
                }
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            

            selectModelo(buscar){
                let me = this;
              
                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            selectConsYTerreno(buscar){
                let me = this;
              
                me.arrayModelosTC=[];
                var url = '/select_construcc_terreno?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelosTC = respuesta.modelosTc;

                    if(me.terreno==0)
                        me.terreno = me.arrayModelosTC[0].terreno;
                    me.construccion = me.arrayModelosTC[0].construccion;


                })
                .catch(function (error) {
                    console.log(error);
                });
            },        

            actualizarLote(){
                if (this.proceso === true) {
                    return;
                } 
                if(this.validarLote()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/lote/actualizar2',{
                    'id' : this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,
                    'sublote': this.sublote,
                    'modelo_id': this.modelo_id,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'casa_muestra': this.casa_muestra,
                    'lote_comercial': this.lote_comercial,
                    'habilitado': this.habilitado,
                    'credito_puente':this.credito_puente,
                    'comentarios': this.comentarios,
                    'regimen':this.regimen,
                    'fecha_termino_ventas' : this.fecha_termino_ventas,
                    'extra' : this.extra,
                    'extra_ext' : this.extra_ext,
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarLote(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_modelo,me.b_lote,me.b_habilitado,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarLote(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.manzana=data['manzana'];
                this.num_lote=data['num_lote'];
                this.sublote=data['sublote'];
                this.modelo_id=data['modelo_id'];
                this.empresa_id=data['empresa_id'];
                this.calle=data['calle'];
                this.numero=data['numero'];
                this.interior=data['interior'];
                this.terreno=data['terreno'];
                this.construccion=data['construccion'];
                this.casa_muestra=data['casa_muestra'];
                this.lote_comercial=data['lote_comercial'];
                this.credito_puente=data['credito_puente'];
                this.comentarios=data['comentarios'];
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/lote/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Lote borrado correctamente.',
                        'success'
                        )
                        me.listarLote(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_modelo,me.b_lote,me.b_habilitado,me.criterio);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarLote(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];

                if(!this.fraccionamiento_id) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("El nombre del proyecto para el Lote no puede ir vacio.");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            validarManzana(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];
                 if(this.fraccionamiento_id == 0) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("Seleccione primero el fraccionamiento a asignar las manzanas");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            cerrarModal(){
                this.proceso=false;
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id= '';
                this.manzana= '';
                this.num_lote= '';
                this.sublote= '';
                this.modelo_id= '';
                this.empresa_id= 1;
                this.calle= '';
                this.numero= '';
                this.interior= '';
                this.terreno = '';
                this.construccion = '';
                this.casa_muestra= '';
                this.lote_comercial= '';
                this.credito_puente='';
                this.comentarios= '';
                this.fecha_termino_ventas = '';
                
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.fraccionamiento_id=0;
                this.tituloModal2 = '';
                this.lotes_ini=[];
                this.allSelected = false;
            },

            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.tipoAccion = 1;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(lote, accion,data =[]){
                switch(lote){
                    case "lote":
                    {
                   if(this.lotes_ini.length<1 && accion=='asignar'){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }
                        switch(accion){
                            case 'actualizar':
                            {
                                this.modelo_id=data['modelo_id'];
                                this.etapa_id=data['etapa_id'];
                                if(data['modelo']!='Por Asignar')
                                    this.modelo_id=data['modelo_id'];
                                if(data['etapas']!='Sin Asignar')
                                    this.etapa_id=data['etapa_id'];
                                    
                                this.proceso=false;
                                this.modal =1;
                                this.tituloModal='Actualizar Lote';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.extra = data['extra'];
                                this.extra_ext = data['extra_ext'];
                                
                                this.manzana=data['manzana'];
                                this.num_lote=data['num_lote'];
                                this.sublote=data['sublote'];
                                
                                this.empresa_id=data['empresa_id'];
                                this.calle=data['calle'];
                                this.numero=data['numero'];
                                this.interior=data['interior'];
                                this.terreno=data['terreno'];
                                this.construccion=data['construccion'];
                                this.casa_muestra=data['casa_muestra'];
                                this.lote_comercial=data['lote_comercial'];
                                this.habilitado=data['habilitado'];
                                this.credito_puente=data['credito_puente'];
                                this.comentarios=data['comentarios'];
                                this.regimen = data['regimen_condom'];
                                this.fecha_termino_ventas = data['fecha_termino_ventas'];
                 
                                break;
                            }

                            case 'asignar':
                            {
                                this.proceso=false;
                                this.modal2 =1;
                                this.tituloModal2= 'Asignar Modelo';
                                this.tipoAccion=3;
                                this.etapa_id=0;
                                this.modelo_id=0;
                                this.fraccionamiento_id=0;
                                break;
                            }

                            
                            
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectModelo(this.fraccionamiento_id);
                this.selectConsYTerreno(this.modelo_id);

            }
        },
        mounted() {
            this.listarLote(1,this.buscar,this.buscar2,this.buscar3,this.b_modelo,this.b_lote,this.b_habilitado, this.criterio);
            this.selectPuente();
            this.selectFraccionamientos();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
         overflow-y: auto;
        
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .table2 {
    margin: auto;
    border-collapse: collapse;
    overflow-x: auto;
    display: block;
    width: fit-content;
    max-width: 100%;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }

    .td2, .th2 {
    border: solid rgb(200, 200, 200) 1px;
    padding: .5rem;
    }

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

    .td2 {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
    }

    .td2:first-of-type, th:first-of-type {
    border-left: none;
    }

    .td2:last-of-type, th:last-of-type {
    border-right: none;
    } 
</style>
