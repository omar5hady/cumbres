<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Aviso de obra Departamentos
                    <!--   Boton Nuevo    -->
                    <button type="button" v-if="rolId!=9 && listado == 1" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <!---->
                </div>
                <LoadingComponent v-if="loadingComp"></LoadingComponent>

                <template v-else>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="ini_obras.clave">Clave</option>
                                            <option value="contratistas.nombre">Contratista</option>
                                            <option value="ini_obras.f_ini">Fecha de inicio</option>
                                            <option value="ini_obras.f_fin">Fecha de termino</option>
                                            <option value="ini_obras.fraccionamiento_id">Proyecto</option>
                                        </select>
                                        <select class="form-control" v-if="criterio=='ini_obras.fraccionamiento_id'"  @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                            <input v-else-if="criterio=='ini_obras.f_ini'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1)" class="form-control">
                                            <input v-else-if="criterio=='ini_obras.f_fin'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1)" class="form-control">
                                        <input v-else type="text" v-model="buscar" @keyup.enter="listarAvisos(1)" class="form-control" placeholder="Texto a buscar">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_empresa" >
                                            <option value="">Empresa constructora</option>
                                            <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                        </select>
                                        <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <TableComponent :cabecera="[
                                'Opciones','Clave','Contratista','Fraccionamiento','Importe total',
                                'Fecha de inicio ','Fecha de termino',''
                            ]">
                                <template v-slot:tbody>
                                    <tr v-on:dblclick="verAviso(avisoObra.id)" v-for="avisoObra in arrayAvisoObra" :key="avisoObra.id" title="Ver detalle">
                                        <td>
                                            <button type="button" v-if="rolId!=9 && rolId != 11" class="btn btn-danger btn-sm" @click="eliminarContrato(avisoObra.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button type="button" v-if="rolId!=9 && rolId != 11" class="btn btn-warning btn-sm" @click="actualizarContrato(avisoObra.id)">
                                                <i class="icon-pencil"></i>
                                            </button>
                                            <button title="Subir contrato" v-if="rolId!=9 && rolId != 11" type="button" @click="abrirModal('subirArchivo',avisoObra)" class="btn btn-default btn-sm">
                                                <i class="icon-cloud-upload"></i>
                                            </button>
                                            <a title="Descargar contrato" class="btn btn-default btn-sm" v-if="avisoObra.documento != '' && avisoObra.documento != null"  v-bind:href="'/downloadContratoObra/'+avisoObra.documento">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" v-text="avisoObra.clave"></a>
                                        </td>
                                        <td class="td2" v-text="avisoObra.contratista"></td>
                                        <td class="td2" v-text="avisoObra.proyecto"></td>
                                        <td class="td2" v-text="'$'+formatNumber(avisoObra.total_importe)"></td>
                                        <td class="td2" v-text="avisoObra.f_ini"></td>
                                        <td class="td2" v-text="avisoObra.f_fin"></td>
                                        <td>
                                            <a class="btn btn-success" v-bind:href="'/avisoObra/siroc?id='+ avisoObra.id">
                                                <i class="fa fa-file-text"></i>&nbsp; SIROC
                                            </a>
                                            <button title="Subir registro de obra" type="button" @click="abrirModal('subirArchivo2',avisoObra)" class="btn btn-default btn-sm">
                                                <i class="icon-cloud-upload"></i>
                                            </button>
                                            <a title="Descargar registro de obra" class="btn btn-default btn-sm" v-if="avisoObra.registro_obra != '' && avisoObra.registro_obra != null"  v-bind:href="'/downloadRegistroObra/'+avisoObra.registro_obra">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </template>

                    <!-- Div Card Body para nuevo registro -->
                    <template v-else-if="listado == 0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Contratista </label>
                                        <v-select
                                            :on-search="selectContratista"
                                            label="nombre"
                                            :options="arrayContratista"
                                            placeholder="Buscar contratista..."
                                            :onChange="getDatosContratista"
                                        >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Clave </label>
                                    <input type="text" class="form-control" v-model="datosContrato.clave" placeholder="CLV-00-00">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Fecha de inicio </label>
                                    <input type="date" class="form-control" v-model="datosContrato.f_ini">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Fecha de termino </label>
                                    <input type="date" class="form-control" v-model="datosContrato.f_fin">
                                </div>
                                <div class="col-md-3">
                                    <label for="">% Anticipo </label>
                                    <input type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="datosContrato.anticipo" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-3">
                                    <label for="">% Garantia Ret </label>
                                    <input type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="datosContrato.porc_garantia_ret" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-3">
                                        <label for="">% Costo Indirecto </label>
                                        <input type="number" class="form-control" min="0" max="100" v-model="datosContrato.costo_indirecto_porcentaje" v-on:keypress="isNumber($event)">
                                    </div>

                                <div class="col-md-4">
                                    <label for="">Costo Directo </label>
                                    <input type="text" pattern="\d*" class="form-control" v-model="datosContrato.total_costo_directo" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-4" v-show="datosContrato.total_costo_directo > 0">
                                    <label for=""></label>
                                    <h6>${{ formatNumber(datosContrato.total_costo_directo) }}</h6>
                                </div>

                                <div class="col-md-4" ></div>


                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Fraccionamiento </label>
                                        <v-select
                                            :on-search="selectFraccionamiento"
                                            label="nombre"
                                            :options="arrayFraccionamientos"
                                            placeholder="Buscar proyecto..."
                                            :onChange="getDatosFraccionamiento"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Dirección del proyecto:</label>
                                        <input class="form-control"  type="text" v-model="datosContrato.direccion_proy">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Num. Torres:</label>
                                        <input class="form-control"  type="text" v-model="datosContrato.num_torres">
                                    </div>
                                </div>


                                    <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorAvisoObra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjAvisoObra" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row border">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Lote</label>
                                        <div class="form-inline">
                                            <select class="form-control" v-model="datosContrato.lote_id" @change="selectDatosLotes(datosContrato.lote_id)">
                                                <option value="">Seleccione</option>
                                                <template v-for="lotes in arrayLotes">
                                                    <option v-if="lotes.sublote == null" :key="lotes.id"
                                                        :value="lotes.id" v-text="lotes.num_lote + ' - ' + lotes.fecha_fin">
                                                    </option>
                                                    <option v-else :key="lotes.id" :value="lotes.id"
                                                        v-text="lotes.num_lote + ' ' + lotes.sublote + ' - ' + lotes.fecha_fin"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <TableComponent :cabecera="[
                                        'Opciones','Descripcion','Departamento','Nivel','M&sup2;','Termino'
                                    ]">
                                        <template v-slot:tbody>
                                            <template v-if="arrayAvisoObraLotes.length">
                                                <tr v-for="(detalle,index) in arrayAvisoObraLotes" :key="detalle.id">
                                                    <td>
                                                        <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                            <i class="icon-close"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <input v-model="detalle.descripcion" type="text" class="form-control">
                                                    </td>
                                                    <td v-if="detalle.sublote == null" v-text="detalle.lote"></td>
                                                    <td v-else v-text="detalle.lote + ' ' + detalle.sublote"></td>
                                                    <td v-text="detalle.manzana"></td>
                                                    <td>{{detalle.superficie}} m&sup2; </td>
                                                    <td>
                                                        <input type="date" v-model="detalle.fin_obra" class="form-control">
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #CEECF5;">
                                                    <td align="right" colspan="4"></td>
                                                    <td align="right"> <strong>{{ total_construccion=totalSuperficie}}m&sup2;</strong> </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr>
                                                    <td colspan="9">
                                                        No hay lotes seleccionados
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                    </TableComponent>
                                </div>
                            </div>

                            <!-- Parametros para contrato -->
                            <div class="form-group row border"  v-if="arrayAvisoObraLotes.length">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo</label>
                                        <div class="form-inline">
                                        <select class="form-control" v-model="datosContrato.tipo">
                                            <option value="Departamentos">Departamentos</option>
                                        </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>IVA</label>
                                        <div class="form-inline">
                                        <select class="form-control" v-model="datosContrato.iva">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="registrarAvisoObra()"> Guardar </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!--Div para ver detalle del aviso -->
                    <template v-else-if="listado == 2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Contratista </strong></label>
                                        <p v-text="datosContrato.contratista"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Clave</strong> </label>
                                    <p v-text="datosContrato.clave"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Fecha de inicio</strong></label>
                                    <p v-text="datosContrato.f_ini"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Fecha de termino </strong></label>
                                    <p v-text="datosContrato.f_fin"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>% Anticipo </strong></label>
                                    <p v-text="datosContrato.anticipo+'%'"></p>
                                </div>

                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>% Garantia Ret </strong></label>
                                    <p v-text="datosContrato.porc_garantia_ret+'%'"></p>
                                </div>

                                <div class="col-md-4" v-show="datosContrato.total_importe > 0">
                                    <label style="color:#2271b3;" for=""><strong>% Importe Total </strong></label>
                                    <h6>${{ formatNumber(datosContrato.total_importe) }}</h6>
                                </div>

                                <div class="col-md-8" ></div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong>Fraccionamiento </strong></label>
                                        <p v-text="datosContrato.proyecto"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong>Dirección del proyecto:</strong></label>
                                        <p v-text="datosContrato.direccion_proy"></p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label style="color:#2271b3;"><strong>Total de Anticipo</strong></label>
                                        <div class="form-inline">
                                        <p v-text="'$'+formatNumber(datosContrato.total_anticipo)"></p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <TableComponent :cabecera="[
                                        'Descripcion','Departamento','Nivel','M&sup2;'
                                    ]">
                                        <template v-slot:tbody>
                                            <tr v-for="detalle in arrayAvisoObraLotes" :key="detalle.id">
                                                <td v-text="detalle.descripcion"></td>
                                                <td v-text="detalle.lote"></td>
                                                <td v-text="detalle.manzana"></td>
                                                <td style="text-align: right;">{{detalle.construccion}} m&sup2;</td>
                                            </tr>

                                            <tr style="background-color: #CEECF5;">
                                                <td align="right" colspan="4"> <strong>{{ formatNumber(datosContrato.total_superficie)}} m&sup2;</strong> </td>
                                            </tr>
                                        </template>
                                    </TableComponent>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                </div>
                                <!-- <div class="col-md-9">
                                    <a class="btn btn-success" v-bind:href="'/iniobra/relacion/excel/'+ datosContrato.id " >
                                        <i></i>Exportar relacion en excel
                                    </a>
                                </div> -->
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" @click="modal = 2"><i class="fa fa-print"></i>&nbsp; Imprimir Contrato</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Div Card Body para actualizar registros -->
                    <template v-else-if="listado == 3">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Contratista </label>
                                        <v-select
                                            :on-search="selectContratista"
                                            label="nombre"
                                            :options="arrayContratista"
                                            placeholder="Buscar contratista..."
                                            :onChange="getDatosContratista"
                                        >
                                        </v-select>
                                            <input type="text" class="form-control" readonly  v-model="datosContrato.contratista">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Clave </label>
                                    <input type="text" class="form-control" v-model="datosContrato.clave" placeholder="CLV-00-00">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Fecha de inicio </label>
                                    <input type="date" class="form-control" v-model="datosContrato.f_ini">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Fecha de termino </label>
                                    <input type="date" class="form-control" v-model="datosContrato.f_fin">
                                </div>
                                <div class="col-md-3">
                                    <label for="">% Anticipo </label>
                                    <input type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="datosContrato.anticipo" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-3">
                                    <label for="">% Garantia Ret </label>
                                    <input type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="datosContrato.porc_garantia_ret" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-3">
                                        <label for="">% Costo Indirecto </label>
                                        <input type="number" class="form-control" min="0" max="100" v-model="datosContrato.costo_indirecto_porcentaje" v-on:keypress="isNumber($event)">
                                    </div>

                                <div class="col-md-4">
                                    <label for="">Costo Directo </label>
                                    <input type="text" pattern="\d*" class="form-control" v-model="datosContrato.total_costo_directo" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-4" v-show="datosContrato.total_costo_directo > 0">
                                    <label for=""></label>
                                    <h6>${{ formatNumber(datosContrato.total_costo_directo) }}</h6>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fraccionamiento </label>
                                        <input type="text" class="form-control" readonly  v-model="datosContrato.proyecto">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Dirección del proyecto:</label>
                                        <input class="form-control"  type="text" v-model="datosContrato.direccion_proy">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Num Torres:</label>
                                        <input class="form-control"  type="text" v-model="datosContrato.num_torres">
                                    </div>
                                </div>

                                    <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorAvisoObra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjAvisoObra" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lote</label>
                                        <div class="form-inline">
                                        <select class="form-control" v-model="datosContrato.lote_id"  @change="selectDatosLotes(datosContrato.lote_id)">
                                            <option value="">Seleccione</option>
                                            <template v-for="lotes in arrayLotes">
                                                <option v-if="lotes.sublote == null" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                                <option v-else :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote + ' ' + lotes.sublote"></option>
                                            </template>

                                        </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <TableComponent :cabecera="[
                                        'Opciones','Descripcion','Departamento','Nivel','M&sup2;'
                                    ]">
                                        <template v-slot:tbody>
                                            <template v-if="arrayAvisoObraLotes.length">
                                                <tr v-for="detalle in arrayAvisoObraLotes" :key="detalle.id">
                                                    <td>
                                                        <button @click="eliminarLote(detalle)" type="button" class="btn btn-danger btn-sm">
                                                            <i class="icon-close"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <input v-model="detalle.descripcion" type="text" class="form-control">
                                                    </td>
                                                    <td v-if="detalle.sublote == null" v-text="detalle.lote"></td>
                                                    <td v-else v-text="detalle.lote + ' ' + detalle.sublote"></td>
                                                    <td v-text="detalle.manzana"></td>
                                                    <td style="text-align: right;">{{detalle.construccion}}m&sup2;</td>
                                                </tr>

                                                <tr style="background-color: #CEECF5;">
                                                    <td colspan="4"></td>
                                                    <td align="right"> <strong>{{ total_construccion=totalConstruccion}}m&sup2;</strong> </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr>
                                                    <td colspan="9">
                                                        No hay lotes seleccionados
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                    </TableComponent>
                                </div>
                            </div>
                            <!-- Parametros para contrato -->
                            <div class="form-group row border"  v-if="arrayAvisoObraLotes.length">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>IVA</label>
                                        <div class="form-inline">
                                            <select class="form-control" v-model="datosContrato.iva">
                                                <option value="0">No</option>
                                                <option value="1">Si</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="actualizarAvisoObra()"> Actualizar </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!-- Modal para la carga pdf -->
        <ModalComponent v-if="modal == 1"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div v-if="tipoAccion == 1">
                    <form  method="post" @submit="formSubmit" enctype="multipart/form-data">
                        <strong>Seleccionar contrato</strong>

                        <input type="file" class="form-control" v-on:change="onImageChange">
                        <br/>
                        <button type="submit" class="btn btn-success">Cargar</button>
                    </form>
                </div>
                <div v-else>
                    <form  method="post" @submit="formSubmit2" enctype="multipart/form-data">
                        <strong>Seleccionar archivo</strong>

                        <input type="file" class="form-control" v-on:change="onImageChange2">
                        <br/>
                        <button type="submit" class="btn btn-success">Cargar</button>
                    </form>
                </div>
            </template>
        </ModalComponent>


        <!-- Modal para la carga pdf -->
        <ModalComponent v-if="modal==2"
            :titulo="'Seleccionar apoderado legal'"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div>
                    <select class="form-control" v-model="apoderado">
                        <option value="ING. DAVID CALVILLO MARTINEZ">ING. DAVID CALVILLO MARTINEZ</option>
                        <option value="ING. ALEJANDRO F. PEREZ ESPINOSA">ING. ALEJANDRO F. PEREZ ESPINOSA</option>
                        <option value="ING. JUAN URIEL ALFARO GALVÁN">ING. JUAN URIEL ALFARO GALVÁN</option>
                    </select>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <a class="btn btn-primary" v-bind:href="'/iniobra/pdf?id=' + datosContrato.id + '&apoderado=' + apoderado"  target="_blank">
                    <i></i>Imprimir
                </a>
            </template>
        </ModalComponent>
        <!--Fin del modal-->
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    import TableComponent from '../../Componentes/TableComponent.vue';
    import ModalComponent from '../../Componentes/ModalComponent.vue';
    import LoadingComponent from '../../Componentes/LoadingComponent.vue';
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                arrayAvisoObraLotes :[],
                arrayDatosLotes : [],
                arrayLotes : [],
                arrayAvisoObra : [],
                arrayProyectos : [], //Array proyectos busquedas
                arrayFraccionamientos : [], //Array para proyectos Vue Select
                arrayContratista : [],
                empresas : [],

                total_construccion : 0,

                buscar : '',
                b_empresa:'',
                criterio : 'ini_obras.clave',

                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                pdf:'',
                listado:1,
                errorAvisoObra : 0,
                errorMostrarMsjAvisoObra : [],
                proceso : false,
                loading : true,
                loadingComp : true,

                datosContrato : {
                    contratista_id : '',
                    contratista : '',
                    fraccionamiento_id : '',
                    proyecto : '',
                    num_torres : 1,
                    id : '',

                    costo_indirecto_porcentaje : 0,
                    anticipo : 0, // Porc Anticipo,
                    total_anticipo : 0,
                    total_costo_indirecto : 0,
                    total_costo_directo : 0,
                    total_importe : 0,
                    clave : '',
                    f_ini : '',
                    f_fin : '',
                    tipo: 'Departamentos',
                    garantia_ret : 0,
                    num_casas : 0,
                    direccion_proy : '',
                    iva : 0,
                    porc_garantia_ret : 0

                },
                datosDep : {
                    lote : '',
                    lote_id : '',
                    construccion : 0,
                    manzana : '',
                    descripcion : '',
                    costo_directo : 0,
                    costo_indirecto : 0,
                    modelo : '',
                    empresa_constructora : ''
                },
                apoderado:'ING. DAVID CALVILLO MARTINEZ',
            }
        },
        components:{
            vSelect,
            LoadingComponent,
            TableComponent,
            ModalComponent
        },
        computed:{
            totalCostoDirecto: function(){
                var resultado_costo_directo =0.0;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                    resultado_costo_directo = parseFloat(resultado_costo_directo) + parseFloat(this.arrayAvisoObraLotes[i].costo_directo)
                }
                return Math.round(resultado_costo_directo*100)/100;
            },
            totalCostoIndirecto: function(){
                var resultado_costo_indirecto =0.0;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                    resultado_costo_indirecto = parseFloat(resultado_costo_indirecto) + parseFloat(this.arrayAvisoObraLotes[i].costo_indirecto)
                }
                return Math.round(resultado_costo_indirecto*100)/100;
            },
            totalImporte: function(){
                var resultado_importe_total =0.0;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                    resultado_importe_total = parseFloat(resultado_importe_total) + parseFloat(this.arrayAvisoObraLotes[i].costo_directo) + parseFloat(this.arrayAvisoObraLotes[i].costo_indirecto)
                }
                return Math.round(resultado_importe_total*100)/100;
            },
            totalConstruccion: function(){
                var resultado_construccion_total =0.0;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                    resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.arrayAvisoObraLotes[i].construccion)
                }
                return Math.round(resultado_construccion_total*100)/100;
            },
            totalSuperficie: function(){
                var resultado_construccion_total =0.0;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                    resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.arrayAvisoObraLotes[i].superficie)
                }
                return Math.round(resultado_construccion_total*100)/100;
            }
        },
        methods : {
            onImageChange(e){
                console.log(e.target.files[0]);
                this.pdf = e.target.files[0];
            },
            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;

                let formData = new FormData();

                formData.append('pdf', this.pdf);
                axios.post('/formSubmitContratoObra/'+this.datosContrato.id, formData)
                .then(function (response) {

                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                })
                .catch(function (error) {
                    currentObj.output = error;
                });
            },
            onImageChange2(e){
                console.log(e.target.files[0]);
                this.pdf = e.target.files[0];
            },
            formSubmit2(e) {
                e.preventDefault();
                let currentObj = this;

                let formData = new FormData();

                formData.append('pdf', this.pdf);
                axios.post('/formSubmitRegistroObra/'+this.datosContrato.id, formData)
                .then(function (response) {

                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                })
                .catch(function (error) {
                    currentObj.output = error;
                });
            },
            /**Metodo para mostrar los registros */
            listarAvisos(page){
                let me = this;
                me.loadingComp = true;
                var url = '/iniobra?page=' + page + '&buscar=' + me.buscar
                + '&criterio=' + me.criterio
                + '&empresa=' + me.b_empresa
                + '&tipo=Departamentos';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoObra = respuesta.ini_obra.data;
                    me.pagination = respuesta.pagination;
                    me.loadingComp = false;
                })
                .catch(function (error) {
                    console.log(error);
                    me.loadingComp = false;
                });
            },
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectContratista(search, loading){
                let me = this;
                loading(true)
                var url = '/select_contratistas2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayContratista = respuesta.contratistas;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosContratista(val1){
                let me = this;
                me.loading = true;
                me.datosContrato.contratista_id = val1.id;
                me.datosContrato.contratista = val1.nombre;
            },
            selectFraccionamiento(search, loading){
                let me = this;
                loading(true)
                var url = '/select_fraccionamiento2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento?tipo_proyecto=2';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosFraccionamiento(val1){
                let me = this;
                me.loading = true;
                me.datosContrato.fraccionamiento_id = val1.id;
                me.selectLotes(me.datosContrato.fraccionamiento_id)
            },
            selectLotes(proyecto){
                let me = this;
                me.datosContrato.num_torres = 1;
                me.arrayLotes=[];
                var url = '/select_lotes_obra?buscar=' + '&buscar2=' + proyecto;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;

                    me.arrayLotes.forEach( d => me.selectDatosLotes(d.id))
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDatosLotes(buscar){
                let me = this;

                me.arrayDatosLotes = [];
                var url = '/select_datos_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    const respuesta = response.data;
                    me.arrayDatosLotes = respuesta.lotesDatos;

                    me.datosDep.lote_id = me.arrayDatosLotes[0].lote_id;
                    me.datosDep.lote = me.arrayDatosLotes[0].num_lote;
                    me.datosDep.sublote = me.arrayDatosLotes[0].sublote;
                    me.datosDep.construccion = me.arrayDatosLotes[0].construccion;
                    me.datosDep.manzana = me.arrayDatosLotes[0].manzana;
                    me.datosDep.modelo=me.arrayDatosLotes[0].modelo;
                    me.datosDep.descripcion=me.arrayDatosLotes[0].modelo;
                    me.datosDep.empresa_constructora = me.arrayDatosLotes[0].emp_constructora;

                    me.agregarLote();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            encuentra(id,emp_constructora){
                let sw = false;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++)
                {
                    if(this.arrayAvisoObraLotes[i].lote_id == id )
                        sw=true;
                    if(emp_constructora != '' )
                        if(this.arrayAvisoObraLotes[i].emp_constructora != emp_constructora){
                            sw=true;
                        }
                }
                return sw;
            },
            agregarLote(){
                let me = this;
                if(me.datosDep.descripcion == ''){
                }else{
                    if(me.encuentra(me.datosDep.lote_id, me.datosDep.empresa_constructora)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote no se puede agregar',
                        })
                    }else{
                        me.arrayAvisoObraLotes.push({
                            lote_id: me.datosDep.lote_id,
                            lote: me.datosDep.lote,
                            sublote: me.datosDep.sublote,
                            superficie: me.datosDep.construccion,
                            manzana: me.datosDep.manzana,
                            descripcion: me.datosDep.descripcion,
                            importe: 0,
                            modelo:me.datosDep.modelo,
                            costo_directo: 0,
                            costo_indirecto: 0,
                            obra_extra:0,
                            emp_constructora:me.datosDep.empresa_constructora
                        });
                        me.datosDep.lote = '';
                        me.datosDep.lote_id =0;
                        me.datosDep.construccion = 0;
                        me.datosDep.manzana='';
                        me.datosDep.descripcion='';
                        me.datosDep.costo_directo = 0;
                        me.datosDep.costo_indirecto = 0;
                        me.datosDep.modelo='';
                        me.datosDep.empresa_constructora='';
                    }
                }
            },
            registrarLote(){
                let me = this;
                if(me.datosDep.descripcion == ''){
                }else{
                    if(me.encuentra(me.lote_id)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote ya se encuentra agregado',
                        })
                    }else{
                        axios.post('/iniobra/lote/registrar',{
                            'id': this.datosContrato.id,
                            'lote': this.datosDep.lote,
                            'manzana' : this.datosDep.manzana,
                            'modelo' : this.datosDep.modelo,
                            'superficie' : this.datosDep.construccion,
                            'costo_directo' : 0,
                            'costo_indirecto' : 0,
                            'importe' : 0,
                            'descripcion' : this.datosDep.descripcion,
                            'lote_id' : this.datosDep.lote_id,
                        }).then(function (response){
                            //Obtener detalle
                                me.arrayAvisoObraLotes=[];
                                var urld = '/iniobra/obtenerDetalles?id=' + me.datosContrato.id;
                                axios.get(urld).then(function (response) {
                                    var respuesta = response.data;
                                    me.arrayAvisoObraLotes = respuesta.detalles;
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });

                        }).catch(function (error){
                            console.log(error);
                        });
                        me.datosDep.lote = '';
                        me.datosDep.lote_id =0;
                        me.datosDep.construccion = 0;
                        me.datosDep.manzana='';
                        me.datosDep.descripcion='';
                        me.datosDep.costo_directo = 0;
                        me.datosDep.costo_indirecto = 0;
                        me.datosDep.modelo='';
                    }
                }
            },
            eliminarLote(id){
                swal({
                    title: '¿Desea remover este lote?',
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
                axios.delete('/iniobra/lote/eliminar',
                        {params: {'id': id}}).then(function (response){

                         //Obtener detalle
                            me.arrayAvisoObraLotes=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.datosContrato.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.arrayAvisoObraLotes = respuesta.detalles;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayAvisoObraLotes.splice(index,1);
            },
            /**Metodo para registrar  */
            registrarAvisoObra(){
                if(this.proceso==true)
                    return;
                if(this.validarAviso()) //Se verifica si hay un error (campo vacio)
                    return;
                this.proceso=true;
                let me = this;
                me.datosContrato.total_costo_indirecto = parseFloat((me.datosContrato.costo_indirecto_porcentaje/100) * me.datosContrato.total_costo_directo).toFixed(2);
                me.datosContrato.total_importe = parseFloat(me.datosContrato.total_costo_directo) + parseFloat(me.datosContrato.total_costo_indirecto);
                me.datosContrato.total_anticipo = parseFloat((me.datosContrato.anticipo/100)*me.datosContrato.total_importe.toFixed(2)).toFixed(2);
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/iniobra/registrar',{
                    'fraccionamiento_id': this.datosContrato.fraccionamiento_id,
                    'contratista_id': this.datosContrato.contratista_id,
                    'clave': this.datosContrato.clave,
                    'f_ini': this.datosContrato.f_ini,
                    'f_fin': this.datosContrato.f_fin,
                    'calle1': this.datosContrato.calle1,
                    'calle2': this.datosContrato.calle2,
                    'total_importe' :this.datosContrato.total_importe,
                    'total_costo_directo':this.datosContrato.total_costo_directo,
                    'total_costo_indirecto':this.datosContrato.total_costo_indirecto,
                    'anticipo':this.datosContrato.anticipo,
                    'total_anticipo':this.datosContrato.total_anticipo,
                    'data':this.arrayAvisoObraLotes,
                    'emp_constructora':this.arrayAvisoObraLotes[0].emp_constructora,
                    'costo_indirecto_porcentaje':this.datosContrato.costo_indirecto_porcentaje,
                    'tipo':this.datosContrato.tipo,
                    'iva':this.datosContrato.iva,
                    'descripcion_larga':this.datosContrato.descripcion_larga,
                    'descripcion_corta':this.datosContrato.descripcion_corta,
                    'total_superficie':this.total_construccion,
                    'direccion_proy':this.datosContrato.direccion_proy,
                    'num_torres':this.datosContrato.num_torres,
                    'porc_garantia_ret': this.datosContrato.porc_garantia_ret
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarAvisos(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Etapa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                    me.proceso=false;
                });
            },
             /**Metodo para actualizar  */
            actualizarAvisoObra(){
                if(this.proceso==true)
                    return;
                if(this.validarAviso()) //Se verifica si hay un error (campo vacio)
                    return;

                this.proceso=true;
                let me = this;
                me.datosContrato.total_costo_indirecto = parseFloat((me.datosContrato.costo_indirecto_porcentaje/100) * me.datosContrato.total_costo_directo).toFixed(2);
                me.datosContrato.total_importe = parseFloat(me.datosContrato.total_costo_directo) + parseFloat(me.datosContrato.total_costo_indirecto);
                me.datosContrato.total_anticipo = parseFloat((me.datosContrato.anticipo/100)*me.datosContrato.total_importe.toFixed(2)).toFixed(2);
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/iniobra/actualizar',{
                    'id':this.datosContrato.id,
                    'fraccionamiento_id': this.datosContrato.fraccionamiento_id,
                    'contratista_id': this.datosContrato.contratista_id,
                    'calle1': '',
                    'calle2': '',
                    'clave': this.datosContrato.clave,
                    'f_ini': this.datosContrato.f_ini,
                    'f_fin': this.datosContrato.f_fin,
                    'total_importe' :this.datosContrato.total_importe,
                    'total_costo_directo': this.datosContrato.total_costo_directo,
                    'total_costo_indirecto': this.datosContrato.total_costo_indirecto,
                    'anticipo':this.datosContrato.anticipo,
                    'total_anticipo':this.datosContrato.total_anticipo,
                    'data':this.arrayAvisoObraLotes,
                    'costo_indirecto_porcentaje': this.datosContrato.costo_indirecto_porcentaje,
                    'tipo':this.datosContrato.tipo,
                    'iva':this.datosContrato.iva,
                    'descripcion_larga': '',
                    'descripcion_corta': '',
                    'total_superficie':this.total_construccion,
                    'direccion_proy':this.datosContrato.direccion_proy,
                    'porc_garantia_ret' : this.datosContrato.porc_garantia_ret,
                    'num_torres' : this.datosContrato.num_torres
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarAvisos(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },

            limpiarDatos(){
                this.arrayAvisoObraLotes=[];
                this.arrayLotes=[];
                this.arrayDatosLotes=[];

                this.datosContrato = {
                    contratista_id : '',
                    contratista : '',
                    fraccionamiento_id : '',
                    num_torres : 1,
                    id : '',

                    costo_indirecto_porcentaje : 0,
                    anticipo : 0, // Porc Anticipo,
                    total_anticipo : 0,
                    total_costo_indirecto : 0,
                    total_costo_directo : 0,
                    total_importe : 0,
                    clave : '',
                    f_ini : '',
                    f_fin : '',
                    tipo: 'Departamentos',
                    porc_garantia_ret : 0,
                    garantia_ret : 0,
                    num_casas : 0,
                    direccion_proy : '',
                    iva : 0,
                };
                this.datosDep = {
                    lote : '',
                    lote_id : 0,
                    construccion : 0,
                    manzana : '',
                    descripcion : '',
                    costo_directo : 0,
                    costo_indirecto : 0,
                    modelo : '',
                    empresa_constructora : ''
                };
            },
            eliminarContrato(id){
                this.datosContrato.id=id;
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
                axios.delete('/iniobra/contrato/eliminar',
                        {params: {'id': this.datosContrato.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Contrato borrada correctamente.',
                        'success'
                        )
                         me.listarAvisos(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarAviso(){
                this.errorAvisoObra=0;
                this.errorMostrarMsjAvisoObra=[];
                if(this.datosContrato.contratista_id==0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Seleccionar un contratista.");
                if(this.datosContrato.fraccionamiento_id==0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Seleccionar un fraccionamiento.");
                if(this.datosContrato.clave=='') //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Ingresar clave.");
                if(this.arrayAvisoObraLotes.length<=0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("No se ha ingresado ningun lote");
                if(this.errorMostrarMsjAvisoObra.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorAvisoObra = 1;
                return this.errorAvisoObra;
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
            mostrarDetalle(){
                this.limpiarDatos();
                this.listado=0;
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verAviso(id){
                let me= this;
                this.listado=2;
                //Obtener datos de cabecera
                me.obtenerDatos(id)

            },
            obtenerDatos(id){
                let me = this;
                let arrayAvisoT=[];
                var url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    arrayAvisoT = respuesta.ini_obra;
                    me.datosContrato = arrayAvisoT[0]
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                me.arrayAvisoObraLotes=[];
                var urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoObraLotes = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.pdf='';
            },
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'subirArchivo':
                    {
                        this.modal =1;
                        this.tipoAccion = 1;
                        this.tituloModal='Subir contrato';
                        this.datosContrato.id=data['id'];
                        this.pdf=data['documento'];
                        break;
                    }
                    case 'subirArchivo2':
                    {
                        this.modal =1;
                        this.tipoAccion = 2;
                        this.tituloModal='Subir registro de obra';
                        this.datosContrato.id=data['id'];
                        this.pdf=data['documento'];
                        break;
                    }
                }
            },
            actualizarContrato(id){
                let me= this;
                this.listado=3;
                //Obtener datos de cabecera
                let arrayAvisoT=[];
                me.obtenerDatos(id)
                me.selectLotes(me.datosContrato.fraccionamiento_id)
            }

        },
        mounted() {
            this.listarAvisos(1);
            this.selectProyectos();
            this.getEmpresa();
        }
    }
</script>
<style>
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
        }
    }
</style>
