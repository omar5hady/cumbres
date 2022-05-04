<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Rentas
                    <!--   Boton agregar    -->
                    <button @click="nuevoContrato()"  type="button" class="btn btn-primary" v-if="listado == 1">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="salir()" class="btn btn-success" v-if="listado > 1">
                        <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                    </button>
                    <button type="button" @click="actualizar = 1" class="btn btn-warning" v-if="listado > 1 && actualizar == 0 && datosRenta.status == 1">
                        <i class="icon-pencil"></i>&nbsp;Vista actualizar
                    </button>
                    <button type="button" @click="actualizar = 0, getDatos(datosRenta.id)" class="btn btn-warning" v-if="listado > 1 && actualizar == 1 && datosRenta.status == 1">
                        <i class="icon-pencil"></i>&nbsp;Ocultar vista actualizar
                    </button>

                    <div style="text-align: right;" v-if="listado == 2">
                        <label for="text-input"> <strong>Status</strong> </label>
                        <select v-model="datosRenta.status" 
                        v-if="datosRenta.status != 0"
                        @change="selectStatus(datosRenta.status)">
                            <option value="0">Cancelar</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Firmar</option>
                        </select>
                    </div>       
                </div>
                

                <!----------------- Listado Contratos ------------------------------>
                <!-- Div Card Body para listar -->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="b_proyecto" @change="selectEtapas(b_proyecto)">
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayProyectos" :key="proyecto.id"  :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-4" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id"  :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="b_direccion" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Dirección"/>
                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Arrendatario"/>
                                </div>
                                <button type="submit" @click="listarContratos(1)" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th># Contrato</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Dirección</th>
                                        <th>Costo de renta</th>
                                        <th>Fecha de termino</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contrato in arrayContratos.data" :key="contrato.id">
                                        <td>
                                            <button 
                                                type="button" title="Subir contrato" @click="vistaArchivo(contrato.id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-files-o"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="contrato.id"></td>
                                        <td class="td2">
                                            <a v-on:dblclick="verContrato(contrato.id)" 
                                            title="Ver contrato"
                                            href="#" v-text="contrato.nombre_arrendatario.toUpperCase()"></a>
                                        </td>
                                        <td class="td2" v-text="contrato.proyecto"></td>
                                        <td class="td2" v-text="contrato.etapa"></td>
                                        <td class="td2" v-if="contrato.interior == null" v-text="contrato.calle+' Num. ' + contrato.numero"></td>
                                        <td class="td2" v-else v-text="contrato.calle+' Num. ' + contrato.numero + '-'+ contrato.interior"></td>
                                        <td class="td2" v-text="'$' +formatNumber(contrato.monto_renta)"></td>
                                        <td class="td2" v-text="this.moment(contrato.fecha_fin).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2">
                                            <span v-if="contrato.status == 0" class="badge badge-danger">Cancelado</span>
                                            <span v-if="contrato.status == 1" class="badge badge-warning">Pendiente</span>
                                            <span v-if="contrato.status == 2" class="badge badge-success">Vigente</span>
                                            <span v-if="contrato.status == 3" class="badge badge-primary">Finalizado</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayContratos.current_page > 5" @click="listarContratos(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page > 1"
                                    @click="listarContratos(arrayContratos.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayContratos.current_page-3 >= 1"
                                    @click="listarContratos(arrayContratos.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page-2 >= 1"
                                    @click="listarContratos(arrayContratos.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page-1 >= 1"
                                    @click="listarContratos(arrayContratos.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayContratos.current_page+1 <= arrayContratos.last_page">
                                    <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+1)" 
                                    v-text="arrayContratos.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayContratos.current_page+2 <= arrayContratos.last_page">
                                    <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+2)"
                                     v-text="arrayContratos.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayContratos.current_page+3 <= arrayContratos.last_page">
                                    <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+3)"
                                    v-text="arrayContratos.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayContratos.current_page < arrayContratos.last_page"
                                    @click="listarContratos(arrayContratos.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page < 5 && arrayContratos.last_page > 5" @click="listarContratos(arrayContratos.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>

                <!----------------- Vista para crear un contrato ------------------------------>
                <!-- Div Card Body para registrar simulacion -->
                <template v-else-if="listado == 3 || listado == 2">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                    <h5 v-if="datosRenta.status == 0" style="color:red;" align="right" 
                                        v-text="' Cancelado '"
                                    ></h5>
                                    <h5 v-if="datosRenta.status == 1" style="color:orange;" align="right" 
                                        v-text="' Pendiente '"
                                    ></h5>
                                    <h5 v-else-if="datosRenta.status == 2" style="color:green;" align="right" 
                                        v-text="' Vigente '"
                                    ></h5>
                                </center>
                            </div>
                        </div>
                        <!-- Acordeon -->
                        <div id="accordion" role="tablist">
                            <div class="card mb-0">
                                <div class="card-header" id="headingOne" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false"
                                            aria-controls="collapseOne" class="collapsed"
                                            >Arrendatario
                                        </a>
                                    </h5>
                                </div>
                                <!--Datos Arrendatario-->
                                <div class="collapse" id="collapseOne" role="tabpanel"
                                    aria-labelledby="headingOne" data-parent="#accordion">
                                    <div
                                        class="form-group row border border-primary"
                                        style="margin-right:0px; margin-left:0px;"
                                    >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <center><h3></h3></center>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">
                                                    Tipo<span style="color:red;" v-show="datosRenta.tipo_arrendatario == ''">(*)</span>
                                                </label>
                                                <select :disabled="listado == 2 && actualizar == 0"
                                                    v-model="datosRenta.tipo_arrendatario"
                                                    class="form-control"
                                                >
                                                    <option value="0">Persona Física</option>
                                                    <option value="1">Persona Moral</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="">
                                                    Nombre<span style="color:red;" v-show="datosRenta.nombre_arrendatario == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.nombre_arrendatario"
                                                    placeholder="Nombre"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">
                                                        Dirección<span style="color:red;" v-show="datosRenta.dir_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        class="form-control" v-model="datosRenta.dir_arrendatario"
                                                        placeholder="Dirección"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">
                                                        CP<span style="color:red;" v-show="datosRenta.cp_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        @keyup="selectColonias(datosRenta.cp_arrendatario,0), datosRenta.col_arrendatario =''"
                                                        class="form-control" v-model="datosRenta.cp_arrendatario"
                                                        placeholder="Código Postal"
                                                        maxlength="5"
                                                        pattern="\d*"
                                                        v-on:keypress="isNumber($event)"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">
                                                        Colonia<span style="color:red;" v-show="datosRenta.col_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" name="city3" 
                                                        :disabled="listado == 2 && actualizar == 0"
                                                        list="cityname3" 
                                                        class="form-control" v-model="datosRenta.col_arrendatario">
                                                    <datalist id="cityname3">
                                                        <option value="">Seleccione</option>
                                                        <option v-for="colonias in arrayColonias" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>    
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">
                                                        Municipio<span style="color:red;" v-show="datosRenta.municipio_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        class="form-control" v-model="datosRenta.municipio_arrendatario"
                                                        placeholder="Municipio"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">
                                                        Estado<span style="color:red;" v-show="datosRenta.estado_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        class="form-control" v-model="datosRenta.estado_arrendatario"
                                                        placeholder="Colonia"
                                                    />
                                                </div>
                                            </div>
                                        <!-- Datos de arrendatario moral -->
                                        <template v-if="datosRenta.tipo_arrendatario == 1">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">
                                                        RFC<span style="color:red;" v-show="datosRenta.rfc_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        maxlength="13"
                                                        class="form-control" v-model="datosRenta.rfc_arrendatario"
                                                        placeholder="RFC"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="">
                                                        Representante<span style="color:red;" v-show="datosRenta.representante_arrendatario == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        class="form-control" v-model="datosRenta.representante_arrendatario"
                                                        placeholder="Representante legal"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-1"></div>
                                        </template>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Celular
                                                    <span style="color:red;" v-show="datosRenta.tel_arrendatario == ''">(*)</span>
                                                </label>
                                                <div class="input-group">
                                                    <select :disabled="listado == 2 && actualizar == 0"
                                                        v-model="datosRenta.clv_lada_arr"
                                                        class="form-control col-md-5"
                                                    >
                                                        <option value="">Clave lada</option>
                                                        <option v-for="clave in arrayClaves" :key="clave.clave + clave.pais"
                                                            :value="clave.clave"
                                                            v-text="clave.pais +' +' +clave.clave"
                                                        ></option>
                                                    </select>
                                                    <input type="text" :disabled="listado == 2 && actualizar == 0"
                                                        pattern="\d*"
                                                        maxlength="10"
                                                        class="form-control col-md-7"
                                                        v-on:keypress="isNumber($event)"
                                                        v-model="datosRenta.tel_arrendatario"
                                                        placeholder="Celular"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin datos prospecto-->
                                </div>
                            </div>

                            <div class="card mb-0">
                                <div class="card-header" id="headingTwo" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo" class="collapsed"
                                            >Fiador
                                        </a>
                                    </h5>
                                </div>
                                <!--Datos Fiador-->
                                <div class="collapse" id="collapseTwo" role="tabpanel"
                                    aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div
                                        class="form-group row border border-primary"
                                        style="margin-right:0px; margin-left:0px;"
                                    >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <center><h3></h3></center>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">
                                                    Tipo<span style="color:red;" v-show="datosRenta.tipo_aval == ''">(*)</span>
                                                </label>
                                                <select :disabled="listado == 2 && actualizar == 0"
                                                    v-model="datosRenta.tipo_aval"
                                                    class="form-control"
                                                >
                                                    <option value="0">Persona Física</option>
                                                    <option value="1">Persona Moral</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="">
                                                    Nombre<span style="color:red;" v-show="datosRenta.nombre_aval == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.nombre_aval"
                                                    placeholder="Nombre"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <!-- Datos de arrendatario moral -->
                                        <template v-if="datosRenta.tipo_aval == 1">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">
                                                        Representante<span style="color:red;" v-show="datosRenta.representante_aval == ''">(*)</span>
                                                    </label>
                                                    <input type="text" :disabled="
                                                            listado == 2 && actualizar == 0"
                                                        class="form-control" v-model="datosRenta.representante_aval"
                                                        placeholder="Representante legal"
                                                    />
                                                </div>
                                            </div>
                                        </template>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">
                                                    Dirección<span style="color:red;" v-show="datosRenta.dir_aval == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.dir_aval"
                                                    placeholder="Dirección"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">
                                                    CP<span style="color:red;" v-show="datosRenta.cp_aval == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    @keyup="selectColonias(datosRenta.cp_aval,1), datosRenta.col_aval =''"
                                                    class="form-control" v-model="datosRenta.cp_aval"
                                                    placeholder="Código Postal"
                                                    maxlength="5"
                                                    pattern="\d*"
                                                    v-on:keypress="isNumber($event)"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">
                                                    Colonia<span style="color:red;" v-show="datosRenta.col_aval == ''">(*)</span>
                                                </label>
                                                <input type="text" name="city3" 
                                                    :disabled="listado == 2 && actualizar == 0"
                                                    list="cityname3" 
                                                    class="form-control" v-model="datosRenta.col_aval">
                                                <datalist id="cityname3">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="colonias in arrayColonias" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>    
                                                </datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">
                                                    Municipio<span style="color:red;" v-show="datosRenta.municipio_aval == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.municipio_aval"
                                                    placeholder="Municipio"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">
                                                    Estado<span style="color:red;" v-show="datosRenta.estado_aval == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.estado_aval"
                                                    placeholder="Colonia"
                                                />
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Celular
                                                    <span style="color:red;" v-show="datosRenta.tel_aval == ''">(*)</span>
                                                </label>
                                                <div class="input-group">
                                                    <select :disabled="listado == 2 && actualizar == 0"
                                                        v-model="datosRenta.clv_lada_aval"
                                                        class="form-control col-md-5"
                                                    >
                                                        <option value="">Clave lada</option>
                                                        <option v-for="clave in arrayClaves" :key="clave.clave + clave.pais"
                                                            :value="clave.clave"
                                                            v-text="clave.pais +' +' +clave.clave"
                                                        ></option>
                                                    </select>
                                                    <input type="text" :disabled="listado == 2 && actualizar == 0"
                                                        pattern="\d*"
                                                        maxlength="10"
                                                        class="form-control col-md-7"
                                                        v-on:keypress="isNumber($event)"
                                                        v-model="datosRenta.tel_aval"
                                                        placeholder="Celular"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin datos prospecto-->
                                </div>
                            </div>
                            <!--Datos Testigo-->
                            <div class="card mb-0">
                                <div class="card-header" id="headingThree" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree" class="collapsed"
                                            >Testigo
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseThree" role="tabpanel"
                                    aria-labelledby="headingThree" data-parent="#accordion">
                                    <div
                                        class="form-group row border border-primary"
                                        style="margin-right:0px; margin-left:0px;"
                                    >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <center><h3></h3></center>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="">
                                                    Nombre<span style="color:red;" v-show="datosRenta.nombre == ''">(*)</span>
                                                </label>
                                                <input type="text" :disabled="
                                                        listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.nombre"
                                                    placeholder="Nombre"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <!-- Fin datos prospecto-->
                                </div>
                            </div>
                            <!--Datos Renta-->
                            <div class="card mb-0">
                                <div class="card-header" id="headingFour" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour" class="collapsed"
                                            >Datos de la Renta
                                        </a>
                                    </h5>
                                </div>
                                <!--Datos Renta-->
                                <div class="collapse" id="collapseFour" role="tabpanel"
                                    aria-labelledby="headingFour" data-parent="#accordion">
                                    <div
                                        class="form-group row border border-primary"
                                        style="margin-right:0px; margin-left:0px;"
                                    >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <center><h3></h3></center>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">
                                                    Fraccionamiento
                                                </label>
                                                <select v-if="listado == 3" class="form-control" v-model="datosRenta.fraccionamiento_id" @change="selectEtapaDisp(datosRenta.fraccionamiento_id)">
                                                        <option value=''> Seleccione </option>
                                                        <option v-for="proyecto in arrayProyectosInv" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                                </select>
                                                <input type="text" disabled v-else
                                                    class="form-control" v-model="datosRenta.proyecto"
                                                    placeholder="Proyecto"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">
                                                    Etapa
                                                </label>
                                                <select v-if="listado == 3" class="form-control" v-model="datosRenta.etapa_id" @change="getRentasDisponibles(datosRenta.etapa_id, datosRenta.fraccionamiento_id)">
                                                        <option value=''> Seleccione </option>
                                                        <option v-for="etapa in arrayEtapasInv" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                                </select>
                                                <input type="text" disabled v-else
                                                    class="form-control" v-model="datosRenta.etapa"
                                                    placeholder="Etapa"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>

                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="">
                                                    Casa
                                                </label>
                                                <select v-if="listado == 3" class="form-control" v-model="datosRenta.lote_id" @change="getDatosLoteRenta()">
                                                    <option value=''> Seleccione </option>
                                                    <template v-for="lote in arrayDisponibles">
                                                        <option v-if="lote.interior == null"  :key="lote.id" :value="lote.id" v-text="lote.calle+' No.'+lote.numero"></option>
                                                        <option v-else  :key="lote.id" :value="lote.id" v-text="lote.calle + ' Num. ' + lote.numero + ' ' + lote.interior"></option>
                                                    </template>
                                                </select>
                                                <input type="text" disabled v-else
                                                    class="form-control"
                                                    :value="datosRenta.calle + ' Num.' + datosRenta.numero + ' ' + datosRenta.interior"
                                                    placeholder="Etapa"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-5"></div>

                                        <template v-if="datosRenta.lote_id != ''">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Modelo</label>
                                                    <input type="text" 
                                                        disabled
                                                        class="form-control" v-model="datosRenta.modelo"
                                                        placeholder="Modelo"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Precio de renta</label>
                                                    <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.monto_renta)"></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Vigencia del contrato</label>
                                                    <select :disabled="listado == 2" @change="calcularFechaFin()" class="form-control" v-model="datosRenta.num_meses">
                                                        <option value='0'> Seleccione </option>
                                                        <option value=1> 1 mes </option>
                                                        <option value=2> 2 meses </option>
                                                        <option value=3> 3 meses </option>
                                                        <option value=4> 4 meses </option>
                                                        <option value=5> 5 meses </option>
                                                        <option value=6> 6 meses </option>
                                                        <option value=7> 7 meses </option>
                                                        <option value=8> 8 meses </option>
                                                        <option value=9> 9 meses </option>
                                                        <option value=10> 10 meses </option>
                                                        <option value=11> 11 meses </option>
                                                        <option value=12> 12 meses </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="">Déposito de Garantia</label>
                                                    <input type="text" v-if="listado == 3" 
                                                        :disabled="listado == 2 && actualizar == 0"
                                                        class="form-control" v-model="datosRenta.dep_garantia"
                                                        placeholder="Deposito de garantia"
                                                        v-on:keypress="isNumber($event)"
                                                    />
                                                    <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.dep_garantia)"></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-7"></div>

                                            <!-- MUEBLES -->
                                            <div class="col-md-3">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <button type="button" v-if="datosRenta.muebles == 1"
                                                                class="btn btn-success"
                                                                :disabled="listado == 2 && actualizar == 0"
                                                                @click="datosRenta.muebles=0, adendum = 0"
                                                            >
                                                                Si
                                                            </button>
                                                            <button type="button" v-if="datosRenta.muebles == 0"
                                                                class="btn btn-primary"
                                                                :disabled="listado == 2 && actualizar == 0"
                                                                @click="datosRenta.muebles=1, adendum = 0"
                                                            >
                                                                No
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" disabled placeholder="¿Muebles?"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group mb-3" v-if="datosRenta.muebles == 0">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <button type="button" v-if="datosRenta.adendum == 1"
                                                                class="btn btn-success"
                                                                :disabled="listado == 2 && actualizar == 0"
                                                                @click="datosRenta.adendum=0"
                                                            >
                                                                Si
                                                            </button>
                                                            <button type="button" v-if="datosRenta.adendum == 0"
                                                                class="btn btn-primary"
                                                                :disabled="listado == 2 && actualizar == 0"
                                                                @click="datosRenta.adendum=1"
                                                            >
                                                                No
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" disabled placeholder="Adendum?"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6"></div>

                                            <!-- SERVICIOS -->
                                            <div class="col-md-3">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <button type="button" v-if="datosRenta.servicios == 1"
                                                                class="btn btn-success"
                                                                :disabled="listado == 2 && actualizar == 0"
                                                                @click="datosRenta.servicios=0"
                                                            >
                                                                Si
                                                            </button>
                                                            <button type="button" v-if="datosRenta.servicios == 0"
                                                                class="btn btn-primary"
                                                                :disabled="listado == 2 && actualizar == 0"
                                                                @click="datosRenta.servicios=1, limpiarServicios()"
                                                            >
                                                                No
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" disabled placeholder="¿Servicios?"/>
                                                </div>
                                            </div>
                                            <div class="col-md-9"></div>

                                            <template v-if="datosRenta.servicios == 1">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Luz</label>
                                                        <input type="text" v-if="listado == 3" 
                                                            :disabled="listado == 2 && actualizar == 0"
                                                            class="form-control" v-model="datosRenta.luz"
                                                            placeholder="Deposito de garantia"
                                                            v-on:keypress="isNumber($event)"
                                                        />
                                                        <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.luz)"></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Agua</label>
                                                        <input type="text" v-if="listado == 3" 
                                                            :disabled="listado == 2 && actualizar == 0"
                                                            class="form-control" v-model="datosRenta.agua"
                                                            placeholder="Deposito de garantia"
                                                            v-on:keypress="isNumber($event)"
                                                        />
                                                        <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.agua)"></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Gas</label>
                                                        <input type="text" v-if="listado == 3" 
                                                            :disabled="listado == 2 && actualizar == 0"
                                                            class="form-control" v-model="datosRenta.gas"
                                                            placeholder="Deposito de garantia"
                                                            v-on:keypress="isNumber($event)"
                                                        />
                                                        <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.gas)"></h6>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Televisión</label>
                                                        <input type="text" v-if="listado == 3" 
                                                            :disabled="listado == 2 && actualizar == 0"
                                                            class="form-control" v-model="datosRenta.television"
                                                            placeholder="Deposito de garantia"
                                                            v-on:keypress="isNumber($event)"
                                                        />
                                                        <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.television)"></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Telefonía</label>
                                                        <input type="text" v-if="listado == 3" 
                                                            :disabled="listado == 2 && actualizar == 0"
                                                            class="form-control" v-model="datosRenta.telefonia"
                                                            placeholder="Deposito de garantia"
                                                            v-on:keypress="isNumber($event)"
                                                        />
                                                        <h6 style="color:blue;" v-text="'$'+formatNumber(datosRenta.telefonia)"></h6>
                                                    </div>
                                                </div>
                                            </template>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Fecha de inicio</label>
                                                    <input type="date" 
                                                        :disabled="listado == 2"
                                                        class="form-control" v-model="datosRenta.fecha_ini"
                                                        placeholder="Fecha de inicio"
                                                        @change="calcularFechaFin()"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Fecha de finalización</label>
                                                    <input type="date" 
                                                    disabled
                                                    class="form-control" v-model="datosRenta.fecha_fin"
                                                    placeholder="Fecha de inicio"
                                                />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row" v-if="datosRenta.pagares.length">
                                                    <div class="table-responsive col-md-12">
                                                        <table class="table table-bordered table-striped table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan=3 class="text-center th2" 
                                                                    style="color:white; background:#252f35">PAGOS</th>
                                                                </tr>
                                                                <tr>
                                                                    <th># Pago</th>
                                                                    <th>Fecha de pago</th>
                                                                    <th>Monto</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody >
                                                                <tr v-for="pago in datosRenta.pagares" :key="pago.id">
                                                                    <td v-text="'Pago no. ' + pago.num_pago"></td>
                                                                    <td v-text="this.moment(pago.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                                                    <td v-text="'$'+formatNumber(pago.importe)"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Fecha de firma</label>
                                                    <input type="date" 
                                                    :disabled="listado == 2 && actualizar == 0"
                                                    class="form-control" v-model="datosRenta.fecha_firma"
                                                    placeholder="Fecha de firma"
                                                />
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <!-- Fin datos prospecto-->
                                </div>
                            </div>
                            
                            <!--- Botones y div para errores -->
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                        <div v-show="errorContrato" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div
                                                    v-for="error in errorMostrarMsjContrato"
                                                    :key="error"
                                                    v-text="error"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            @click="salir()"
                                        >
                                            Cerrar
                                        </button>
                                        <button
                                            type="button"
                                            v-if="listado == 3"
                                            class="btn btn-primary"
                                            @click="storeRenta()"
                                        >
                                            Enviar
                                        </button>
                                        <button
                                            type="button"
                                            v-if="listado == 2 && actualizar == 1"
                                            class="btn btn-primary"
                                            @click="updateRenta()"
                                        >
                                            Actualizar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" v-if="listado == 2 && actualizar == 0">
                                <div class="col-md-12">
                                    <div style="text-align: right;" >
                                        <button type="button"
                                            class="btn btn-primary"
                                            @click="modal=1"
                                        >
                                            Imprimir contrato
                                        </button>
                                        
                                        <button type="button"
                                            class="btn btn-warning"
                                            @click="modal=2"
                                        >
                                            Depósito de Garantia
                                        </button>
                                        <a class="btn btn-scarlet btn-sm" target="_blank" v-bind:href="'/rentas/printPagares?id='+datosRenta.id">Imprimir pagares</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!-- Inicio Modal -->
        <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal >=1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div v-if="modal < 3" class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Seleccionar apoderado legal</h4>
                        <button type="button" class="close" @click="modal=0" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row" v-if="datosRenta.tipo_arrendador == 1">
                            <label class="col-md-2 form-control-label" for="text-input">
                                Apoderado legal:
                            </label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="apoderado">
                                    <option value="ING. DAVID CALVILLO MARTINEZ">ING. DAVID CALVILLO MARTINEZ</option>
                                    <option value="ING. ALEJANDRO F. PEREZ ESPINOSA">ING. ALEJANDRO F. PEREZ ESPINOSA</option>
                                    <option value="C.P. MARTIN HERRERA SANCHEZ">C.P. MARTIN HERRERA SANCHEZ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="modal == 1">
                            <label class="col-md-2 form-control-label" for="text-input">
                                Testigo:
                            </label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="testigo">
                                    <option v-for="testigo in arrayTestigos" :key="testigo.id"  :value="testigo.nombre" v-text="testigo.nombre"></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal=0">Cerrar</button>
                        <a v-if="modal == 1" class="btn btn-primary" v-bind:href="'/rentas/printContrato?id=' + datosRenta.id + '&representante=' + apoderado + '&testigo=' + testigo"  target="_blank">
                            <i></i>Imprimir
                        </a>
                        <a class="btn btn-danger btn-sm" target="_blank" 
                            v-if="modal == 1 && datosRenta.adendum == 1"
                            v-bind:href="'/rentas/printAdendum?id='+datosRenta.id + '&representante=' + apoderado">ADENDUM
                        </a>
                        <a v-if="modal == 2" class="btn btn-primary" v-bind:href="'/rentas/printDepositoGarantia?id=' + datosRenta.id + '&representante=' + apoderado"  target="_blank">
                            <i></i>Imprimir
                        </a>
                    </div>
                </div> 
                <!-- /.modal-content -->
            </div>
            <div v-if="modal == 3" class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cambiar Estatus</h4>
                        <button type="button" class="close" @click="modal=0" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body modal-body2">
                         <div class="form-group row" v-if="datosRenta.status == 2">
                            <label class="col-md-3 form-control-label" for="text-input">¿Se requiere factura?</label>
                            <div class="col-md-3">
                                <select class="form-control" v-model="datosRenta.facturar">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        
                        <template v-if="datosRenta.facturar == 1">
                                <hr>
                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <center><h6>Datos Fiscales</h6></center>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo electrónico</label>
                                    <div class="col-md-4">
                                        <input type="email" v-model="datosRenta.email_fisc" class="form-control" placeholder="Email">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-3">
                                        <input type="text" v-on:keypress="isNumber($event)" pattern="\d*" v-model="datosRenta.tel_fisc" class="form-control" maxlength="10" placeholder="Telefono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="datosRenta.nombre_fisc" class="form-control" placeholder="Nombre completo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="datosRenta.direccion_fisc" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="datosRenta.col_fisc" class="form-control" placeholder="Colonia">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">C.P.</label>
                                    <div class="col-md-3">
                                        <input type="text" v-on:keypress="isNumber($event)" pattern="\d*" v-model="datosRenta.cp_fisc" class="form-control" placeholder="Código Postal">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="13"  style="text-transform:uppercase"
                                        v-model="datosRenta.rfc_fisc" class="form-control" placeholder="RFC">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Uso del C.F.D.I.</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="datosRenta.cfi_fisc">
                                            <option value="Adquisición de mercancias">Adquisición de mercancias</option>
                                            <option value="Devoluciones, Descuentos o bonificaciones">Devoluciones, Descuentos o bonificaciones</option>
                                            <option value="Gastos en general">Gastos en general</option>
                                            <option value="Construcciones">Construcciones</option>
                                            <option value="Mobiliario y equipo de oficina por inversiones">Mobiliario y equipo de oficina por inversiones</option>
                                            <option value="Equipo de transporte">Equipo de transporte</option>
                                            <option value="Equipo de computo y accesorios">Equipo de computo y accesorios</option>
                                            <option value="Dados, troqueles, moldes, matrices y herramental">Dados, troqueles, moldes, matrices y herramental</option>
                                            <option value="Comunicaciones telefónicas">Comunicaciones telefónicas</option>
                                            <option value="Comunicaciones satelitales">Comunicaciones satelitales</option>
                                            <option value="Otra maquinaria y equipo">Otra maquinaria y equipo</option>
                                            <option value="Honorarios médicos, dentales y gastos hospitalarios">Honorarios médicos, dentales y gastos hospitalarios</option>
                                            <option value="Gastos médicos por incapacidad o discapacidad">Gastos médicos por incapacidad o discapacidad</option>
                                            <option value="Gastos funerales">Gastos funerales</option>
                                            <option value="Donativos">Donativos</option>
                                            <option value="Intereses reales efectivamente pagados por créditos hipotecarios">Intereses reales efectivamente pagados por créditos hipotecarios</option>
                                            <option value="Aportaciones voluntarias al SAR">Aportaciones voluntarias al SAR</option>
                                            <option value="Primas por seguros de gastos médicos">Primas por seguros de gastos médicos</option>
                                            <option value="Gastos de transportación escolar obligatoria">Gastos de transportación escolar obligatoria</option>
                                            <option value="Depósitos en cuentas para el ahorro">Depósitos en cuentas para el ahorro</option>
                                            <option value="Pagos por servicios educativos (colegiaturas)">Pagos por servicios educativos (colegiaturas)</option>
                                            <option value="Sin efectos fiscales">Sin efectos fiscales</option>
                                            <option value="Pagos">Pagos</option>
                                            <option value="Nómina">Nómina</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Régimen Fiscal del cliente</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="datosRenta.regimen_fisc">
                                            <option value="Sueldos y Salarios e Ingresos Asimilados a Salarios">Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
                                            <option value="Arrendamiento">Arrendamiento</option>
                                            <option value="Régimen e Enajenación o Adquisición de Bienes">Régimen e Enajenación o Adquisición de Bienes</option>
                                            <option value="Demás ingresos">Demás ingresos</option>
                                            <option value="Residentes en el Extranjero sin Establecimiento Permanente en México">Residentes en el Extranjero sin Establecimiento Permanente en México</option>
                                            <option value="Ingresos por Dividendos (socios y accionistas)">Ingresos por Dividendos (socios y accionistas)</option>
                                            <option value="Personas Físicas con Actividades Empresariales y Profesionales">Personas Físicas con Actividades Empresariales y Profesionales</option>
                                            <option value="Ingresos por intereses">Ingresos por intereses</option>
                                            <option value="Régimen de los ingresos por obtención de premios">Régimen de los ingresos por obtención de premios</option>
                                            <option value="Sin obligaciones fiscales">Sin obligaciones fiscales</option>
                                            <option value="Incorporación Fiscal">Incorporación Fiscal</option>
                                            <option value="Régimen de las Actividades Empresariales con ingresos a través de Plataforma">Régimen de las Actividades Empresariales con ingresos a través de Plataforma</option>
                                            <option value="Régimen Simplificado de Confianza">Régimen Simplificado de Confianza</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-4">
                                        <input type="text" 
                                        v-model="datosRenta.banco_fisc" class="form-control" placeholder="Banco">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">No. Cuenta</label>
                                    <div class="col-md-4">
                                        <input type="text" v-on:keypress="isNumber($event)" pattern="\d*"
                                        v-model="datosRenta.num_cuenta_fisc" class="form-control" placeholder="# Cuenta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Clabe</label>
                                    <div class="col-md-4">
                                        <input type="text" 
                                        v-model="datosRenta.clabe_fisc" class="form-control" placeholder="Clabe">
                                    </div>
                                </div>
                            </template>

                            <hr>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                <div class="col-md-9">
                                    <input type="date" v-model="datosRenta.fecha_firma" class="form-control" placeholder="Fecha status">
                                </div>
                            </div>

                            <div class="form-group row" v-if="datosRenta.status == 0">
                                <label class="col-md-3 form-control-label" for="text-input">Motivo de cancelación</label>
                                <div class="col-md-9">
                                    <textarea rows="3" cols="30" v-model="motivo_cancel" class="form-control" placeholder="Observaciones"></textarea>
                                </div>
                            </div>

                    </div>

                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal=0, getDatos(datosRenta.id)">Cerrar</button>
                        <template v-if="datosRenta.fecha_firma != null">
                            <button type="button" v-if="datosRenta.status == 0 || (datosRenta.status == 2 && datosRenta.facturar == 0)" class="btn btn-primary" @click="changeStatus(datosRenta.status)">Guardar</button>
                            <button type="button" 
                                v-if="datosRenta.status == 2 && datosRenta.facturar == 1 
                                    && datosRenta.email_fisc != '' && datosRenta.email_fisc != null
                                    && datosRenta.tel_fisc != '' && datosRenta.tel_fisc != null
                                    && datosRenta.nombre_fisc != '' && datosRenta.nombre_fisc != null
                                    && datosRenta.direccion_fisc != '' && datosRenta.direccion_fisc != null
                                    && datosRenta.regimen_fisc != '' && datosRenta.cfi_fisc != null
                                    && datosRenta.cp_fisc != '' && datosRenta.cp_fisc != null" 
                                class="btn btn-success" @click="changeStatus(datosRenta.status)"
                            >Guardar</button>
                            
                        </template>
                        
                    </div>
                </div> 
                <!-- /.modal-content -->
            </div>
            <div v-if="modal == 4" class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Subir contrato</h4>
                        <button type="button" class="close" @click="modal=0" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body" v-if="modal == 4">
                        <form  method="post" @submit="formSubmitContrato" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Archivo</label>
                                <div class="col-md-9">
                                    <input type="file" accept="application/pdf" class="form-control" v-on:change="onArchivo">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success">Guardar Archivo.</button>
                                </div>
                            </div>
                            
                            <br/>
                        </form>
                    </div>

                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal=0">Cerrar</button>
                        <a v-if="modal == 1" class="btn btn-primary" v-bind:href="'/rentas/printContrato?id=' + datosRenta.id + '&representante=' + apoderado + '&testigo=' + testigo"  target="_blank">
                            <i></i>Imprimir
                        </a>
                        <a class="btn btn-danger btn-sm" target="_blank" 
                            v-if="modal == 1 && datosRenta.adendum == 1"
                            v-bind:href="'/rentas/printAdendum?id='+datosRenta.id + '&representante=' + apoderado">ADENDUM
                        </a>
                        <a v-if="modal == 2" class="btn btn-primary" v-bind:href="'/rentas/printDepositoGarantia?id=' + datosRenta.id + '&representante=' + apoderado"  target="_blank">
                            <i></i>Imprimir
                        </a>
                        <template v-if="modal == 4 && datosRenta.archivo_contrato != null">
                            <button type="button" class="btn btn-success" @click="verArchivo(datosRenta.archivo_contrato)">Ver archivo</button>
                        </template>
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
export default {
    props: {
        rolId: { type: String }
    },
    data() {
        return {
            arrayContratos : [],
            arrayCasas: [],
            datosRenta: {},
            arrayProyectos: [],
            arrayProyectosInv: [],
            arrayEtapas: [],
            arrayEtapasInv: [],
            arrayDisponibles: [],
            arrayClaves: [],
            arrayColonias: [],
            arrayTestigos : [],
            errorMostrarMsjContrato: [],
            errorContrato: 0,
            listado: 1,
            b_cliente: '',
            b_proyecto: '',
            b_etapa: '',
            b_direccion: '',
            b_status: '',
            b_fecha_fin: '',
            status:0,
            tituloModal: '',
            modal:0,
            fecha_status:'',
            apoderado : 'C.P. MARTIN HERRERA SANCHEZ',
            testigo: 'JUAN URIEL ALFARO GALVAN',
            motivo_cancel : '',
            actualizar : 0,
            archivo: ''
        };
    },
    computed: {
        
    },
    methods: {
        onArchivo(e){
            this.archivo = e.target.files[0];
        },
        formSubmitContrato(e) {

            e.preventDefault();

            let currentObj = this;
            let formData = new FormData();
        
            formData.append('archivo', this.archivo);
            let me = this;
            axios.post('/rentas/formSubmitContrato/'+me.datosRenta.id, formData)
            .then(function (response) {
                currentObj.success = response.data.success;
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Archivo guardado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                    })
                me.salir();
                me.modal = 0;
            }).catch(function (error) {
                currentObj.output = error;
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
        formatNumber(value) {
            let val = (value/1).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        verArchivo(archivo){
            window.open('/files/rentas/contratos/'+archivo, '_blank')
        },
        vistaArchivo(id){
            this.modal = 4;
            this.getDatos(id);
            this.archivo = '';
        },
        calcularFechaFin(){
            let me = this;
            me.datosRenta.pagares = [];
            if( me.datosRenta.fecha_ini != ''){
                let fecha_fin = new Date(
                    me.datosRenta.fecha_ini.substring(5,7)
                    +'/'+me.datosRenta.fecha_ini.substring(8,10)
                    +'/'+me.datosRenta.fecha_ini.substring(0, 4)
                );
                let mes, dia, anio = ''
                dia = fecha_fin.getDate();
                mes = parseInt(fecha_fin.getMonth()+1);
                anio = fecha_fin.getFullYear();
                
                for(let i = 1; i<=me.datosRenta.num_meses; i++){
                    var auxMes = mes + i - 1; 
                    var auxAnio = anio;
                    if( auxMes > 12)
                    {
                        auxAnio = auxAnio + 1;
                        auxMes = auxMes - 12;
                    }
                    if(auxMes < 10)
                        auxMes = '0'+(auxMes);
                    
                    me.datosRenta.pagares.push({
                        id:i,
                        num_pago:i,
                        fecha: auxAnio+'-'+auxMes+'-01',
                        importe: me.datosRenta.monto_renta
                    });
                }
                if(dia == 1){
                    dia = 30
                    mes = (mes + parseInt(me.datosRenta.num_meses)-1);
                } 
                else {
                    mes = (mes + parseInt(me.datosRenta.num_meses));
                    dia = dia -1;
                }
                
                
                
                if( mes > 12)
                {
                    anio = anio + 1;
                    mes = mes - 12;
                }
                if(mes == 2 && dia > 28)
                    dia = 28;
                if(mes < 10)
                    mes = '0'+(mes);
                if(dia < 10)
                    dia = '0'+dia;

                me.datosRenta.fecha_fin = anio +'-'+ mes +'-'+ dia;
            }
        },
        getClavesLadas(){
            let me = this;
            me.arrayClaves=[];
            var url = '/getClavesLadas';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayClaves = respuesta.claves;
            })
            .catch(function (error) {
                console.log(error);
            });
        
        },
        getTestigos(){
            let me = this;
            
            me.arrayTestigos=[];
            var url = '/rentas/getTestigos';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayTestigos = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        /**Metodo para mostrar los registros */
        listarContratos(page){
            let me = this;
            me.arrayContratos = [];
            var url = '/rentas/indexRentas?page=' + page
                + '&b_proyecto=' + this.b_proyecto
                + '&b_etapa=' + this.b_etapa
                + '&b_direccion=' + this.b_direccion
                + '&b_status=' + this.b_status
                + '&b_cliente=' + this.b_cliente;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayContratos = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectFraccionamientos(){
            let me = this;
            me.arrayProyectos=[];
            var url = '/select_fraccionamiento';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProyectos = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectStatus(status){
            let me = this;
            if(status==2 || status==0 ){
                me.modal = 3;
            }else{ 
                me.changeStatus(status);
            }  
        },
        changeStatus(status){
            let me = this;
            swal({
                title: 'Esta seguro de cambiar el status de este contrato?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    Swal.showLoading()
                    axios.put('/rentas/changeStatus',{
                        'id': this.datosRenta.id,
                        'status': status,
                        'motivo_cancel' : this.motivo_cancel,
                        'datosRenta' : this.datosRenta,
                        }).then(function (response){
                        me.salir();
                        me.modal = 0;
                        Swal.enableLoading()
                        swal(
                            'Cambio de status!',
                            'Cambios realizados con éxito.',
                            'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                        Swal.enableLoading()
                    });

                } else if (result.dismiss === swal.DismissReason.cancel
                    )me.getDatos(me.datosRenta.id);
            })   
        },
        selectFraccionamientosInventario(){
            let me = this;
            me.arrayProyectosInv=[];
            var url = '/selectFraccionamientoConInventario?renta=1';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProyectosInv = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        //Select todas las etapas
        selectEtapas(buscar){
            let me = this;
            me.b_etapa='';
            
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
        selectEtapaDisp(buscar){
            let me = this;
            me.arrayEtapasInv=[];
            var url = '/selectEtapaDisp?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapasInv = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getRentasDisponibles(etapa, fraccionamiento){
            let me = this;
            me.arrayDisponibles=[];
            var url = '/lotes/getRentasDisponibles?etapa_id=' + etapa + '&fraccionamiento_id=' + fraccionamiento;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayDisponibles = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosLoteRenta(){
            let me = this;
            me.datosRenta.modelo = '';
            me.datosRenta.monto_renta = 0;
            var url = '/lotes/getDatosLoteRenta?id=' + me.datosRenta.lote_id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datosRenta.modelo = respuesta.modelo;
                me.datosRenta.monto_renta = respuesta.precio_renta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectColonias(cp,aval){
            let me = this;
            me.arrayColonias=[];
            var url = '/select_colonias?buscar=' + cp;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayColonias = respuesta.colonias;
                if(me.arrayClaves.length>0){
                    if(aval == 0){
                        me.datosRenta.col_arrendatario = me.arrayColonias[0].colonia;
                        me.datosRenta.municipio_arrendatario = me.arrayColonias[0].ciudad;
                        me.datosRenta.estado_arrendatario = me.arrayColonias[0].estado;
                    }
                    else{
                        me.datosRenta.col_aval = me.arrayColonias[0].colonia;
                        me.datosRenta.municipio_aval = me.arrayColonias[0].ciudad;
                        me.datosRenta.estado_aval = me.arrayColonias[0].estado;
                    }
                    
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        limpiarDatosRenta(){
            this.errorContrato = 0;
            this.errorMostrarMsjContrato = [];
            this.datosRenta = {
                'fraccionamiento_id':'',
                'etapa_id': '',
                'lote_id': '',
                'tipo_arrendatario': 0,
                'nombre_arrendatario': '',
                'tel_arrendatario': '',
                'clv_lada_arr':52,
                //Moral arrendatario
                'representante_arrendatario': '',
                'dir_arrendatario': '',
                'cp_arrendatario': '',
                'col_arrendatario': '',
                'estado_arrendatario': '',
                'municipio_arrendatario': '',
                'rfc_arrendatario':'',
                //Aval (Fiador)
                'tipo_aval': 0,
                'nombre_aval': '',
                'tel_aval':'',
                'clv_lada_aval':52,
                //Moral aval
                'representante_aval': '',
                'dir_aval': '',
                'cp_aval': '',
                'col_aval': '',
                'estado_aval': '',
                'municipio_aval': '',
                //Testigo
                'nombre': '',
                //Datos contrato
                'monto_renta' : '',
                'status': '',
                'fecha_firma' : '',
                'fecha_ini' : '',
                'fecha_fin' : '',
                'num_meses' : 0,
                'modelo' : '',
                'pagares': [],
                'dep_garantia' : 0,
                'muebles' : 0,
                'servicios' : 0,
                'luz' : 0,
                'agua' : 0,
                'gas' : 0,
                'television' : 0,
                'telefonia' : 0,
                'adendum'  : 0
            };
        },
        limpiarServicios(){
            let me = this;
            me.datosRenta.luz = 0;
            me.datosRenta.agua = 0;
            me.datosRenta.gas = 0;
            me.datosRenta.television = 0;
            me.datosRenta.telefonia = 0;
        },
        getDatos(id){
            let me = this;
            me.limpiarDatosRenta();
            var url = '/rentas/getDatosRenta?id='+id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datosRenta = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        
        },
        verContrato(id){
            let me = this;
            me.getDatos(id);
            me.listado = 2;
            me.selectFraccionamientosInventario();
            me.getClavesLadas();
            me.actualizar = 0;
            
        },
        nuevoContrato(){
            let me = this;
            me.listado = 3;
            me.selectFraccionamientosInventario();
            me.getClavesLadas();
            me.limpiarDatosRenta();
        },
        validarRegistro(){
            this.errorContrato=0;
            this.errorMostrarMsjContrato=[];

            if(this.datosRenta.lote_id == '') 
                this.errorMostrarMsjContrato.push("Seleccionar propiedad a rentar");

            if(this.datosRenta.num_meses == 0 || this.datosRenta.fecha_ini == '')
                this.errorMostrarMsjContrato.push("Completar formulario de renta");

            if(this.datosRenta.nombre_arrendatario == '' || this.datosRenta.dir_arrendatario == '' ||
                this.datosRenta.col_arrendatario == '' || this.datosRenta.tel_arrendatario == ''
            )this.errorMostrarMsjContrato.push("Completar formulario de arrendatario");

            if(this.datosRenta.nombre_aval == '' || this.datosRenta.dir_aval == '' ||
                this.datosRenta.col_aval == '' || this.datosRenta.tel_aval == ''
            )this.errorMostrarMsjContrato.push("Completar formulario de fiador");
            
            if(this.errorMostrarMsjContrato.length)//Si el mensaje tiene almacenado algo en el array
                this.errorContrato = 1;

            return this.errorContrato;
        },
        storeRenta(){
            let me = this;
            if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
            {
                return;
            }
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/rentas/storeRenta',{
                'datosRenta' : this.datosRenta,
            }).then(function (response){
                me.salir();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        updateRenta(){
            let me = this;
            if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
            {
                return;
            }
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.put('/rentas/updateRenta',{
                'datosRenta' : this.datosRenta,
            }).then(function (response){
                me.salir();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta actualizada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        salir(){
            let me = this;
            me.limpiarDatosRenta();
            me.listado = 1;
            me.listarContratos();
        }
    },
    mounted() {
        this.selectFraccionamientos();
        this.listarContratos(1);
        this.getTestigos();
    }
};
</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }
    .modal-body {
        height: 200px;
        width: 100%;
        overflow-y: auto;
    }
    .modal-body2{
        height: 400px;
    }
    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error {
        display: flex;
        justify-content: center;
    }
    .text-error {
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
        .table2 {
            margin: auto;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
            width: fit-content;
            max-width: 100%;
            box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .td2,
        .th2 {
            border: solid rgb(200, 200, 200) 1px;
            padding: 0.5rem;
        }

        .td2 {
            white-space: nowrap;
            border-bottom: none;
            color: rgb(20, 20, 20);
        }

        .td2:first-of-type,
        th:first-of-type {
            border-left: none;
        }

        .td2:last-of-type,
        th:last-of-type {
            border-right: none;
        }
    }
</style>
