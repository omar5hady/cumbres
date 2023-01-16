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
                    <i class="fa fa-align-justify"></i> Solicitud de Pago
                    <Button v-if="vista == 0" :btnClass="'btn-success'" :icon="'icon-plus'" @click="vistaFormulario('nuevo')">
                        Nueva solicitud
                    </Button>
                    <Button :btnClass="'btn-light'" :icon="'icon-close'" @click="cerrarFormulario()" v-if="vista == 1">
                        Regresar
                    </Button>
                </div>
                <div class="card-body">
                    <template v-if="vista == 0">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Proveedor:">
                                    <input type="text" class="form-control col-md-6" @keyup.enter="indexSolicitudes(1)" v-model="b_proveedor" placeholder="Proveedor a buscar">
                                </div>
                            </div>
                            <div class="col-md-12" v-if="encargado == 1 || admin == 1">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Solicitante:">
                                    <input type="text" class="form-control col-md-6" @keyup.enter="indexSolicitudes(1)" v-model="b_solicitante" placeholder="Solicitante a buscar">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Fecha de solicitud:">
                                    <input type="date" class="form-control col-md-4" @keyup.enter="indexSolicitudes(1)" v-model="b_fecha1">
                                    <input type="date" class="form-control col-md-4" @keyup.enter="indexSolicitudes(1)" v-model="b_fecha2">
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <Button :btnClass="'btn-primary'" :icon="'fa fa-search'" @click="indexSolicitudes(1)">
                                        Buscar
                                    </Button>
                                    <a class="btn btn-success" href="#">
                                        <i class="fa fa-file-text"></i>&nbsp; Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 0, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_status==0}"
                                role="tab">{{ (b_status == 0) ? `Nuevos: ${arraySolic.total ? arraySolic.total : 0}` : 'Nuevos' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 1, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_status==1}"
                                role="tab">{{ (b_status == 1) ? `En Proceso: ${arraySolic.total}` : 'En Proceso' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 2, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_status==2}"
                                role="tab">{{ (b_status == 2) ? `Aprobadas: ${arraySolic.total}` : 'Aprobadas' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 3, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_status==3}"
                                role="tab">{{ (b_status == 3) ? `Pagadas: ${arraySolic.total}` : 'Pagadas' }}</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">
                            <!-- Listado por Nuevas solicitudes -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status==0 }" v-if="b_status == 0">
                                <TableComponent :cabecera="[
                                    '',
                                    'Proveedor',
                                    'Solicitante',
                                    'Fecha solic',
                                    'Importe',
                                    'Tipo de pago',
                                    '  ',
                                    ' '
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-for="solic in arraySolic.data" :key="solic.id">
                                            <td class="td2">
                                                <Button :btnClass="'btn-warning'" :size="'btn-sm'" title="Editar" v-if="solic.vb_gerente == 0"
                                                    @click="vistaFormulario('actualizar', solic)" :icon="'icon-pencil'"
                                                ></Button>
                                               <Button :btnClass="'btn-primary'" :size="'btn-sm'" title="Ver solicitud"
                                                    :icon="'icon-eye'" @click="vistaFormulario('ver', solic)"
                                               ></Button>
                                               <Button :btnClass="'btn-danger'" :size="'btn-sm'" title="Eliminar" v-if="solic.vb_gerente == 0"
                                                    :icon="'icon-trash'" @click="deleteSolic(solic.id)"
                                               ></Button>
                                            </td>
                                            <td class="td2" v-text="solic.proveedor"></td>
                                            <td class="td2" v-text="solic.solicitante"></td>
                                            <td class="td2"
                                                v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                            </td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                            <td class="td2">
                                                {{ solic.tipo_pago == 0 ? 'Caja Fuerte' : 'Bancario' }}
                                            </td>
                                            <td class="td2">
                                                <template v-if="encargado == 0 && !gerente">
                                                        {{ (solic.vb_gerente == 0)
                                                            ? 'Solicitud pendiente de revisar'
                                                            : 'Solicitd pendiente de autorizar'
                                                        }}
                                                </template>
                                                <template v-else>
                                                    <Button :btnClass="'btn-primary'" v-if="solic.vb_gerente == 0"
                                                        title="Validar revisión de solicitud" :icon="'icon-check'"
                                                        @click="changeVbGerente(solic.id,1)"
                                                    >Revisar
                                                    </Button>
                                                    <Button :btnClass="'btn-scarlet'" v-if="solic.vb_gerente < 2 && gerente"
                                                        title="Autorizar solicitud" @click="changeVbGerente(solic.id,2)" :icon="'icon-check'"
                                                    >Autorizar Solicitud
                                                    </Button>
                                                </template>

                                            </td>
                                            <td>
                                                <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                    @click="verObs(solic)"
                                                >Observaciones
                                                </Button>
                                            </td>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                            <!-- Listado por En Proceso -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status==1 }" v-if="b_status == 1">
                                <TableComponent :cabecera="[
                                    '',
                                    'Proveedor',
                                    'Solicitante',
                                    'Fecha solic',
                                    'Importe',
                                    'Tipo de pago',
                                    '  ',
                                    ' '
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-for="solic in arraySolic.data" :key="solic.id">
                                            <td class="td2">
                                                <Button :btnClass="'btn-primary'" title="Ver solicitd" :size="'btn-sm'"
                                                    @click="vistaFormulario('ver', solic)" :icon="'icon-eye'"
                                                ></Button>

                                            </td>
                                            <td class="td2" v-text="solic.proveedor"></td>
                                            <td class="td2" v-text="solic.solicitante"></td>
                                            <td class="td2"
                                                v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                            </td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                            <td class="td2">
                                                {{ solic.tipo_pago == 0 ? 'Caja Fuerte' : 'Bancario' }}
                                            </td>
                                            <td class="td2">
                                                <template v-if="admin == 0">
                                                        {{ (solic.vb_gerente == 0)
                                                            ? 'Solicitud pendiente de revisar'
                                                            : 'Solicitd pendiente de autorizar'
                                                        }}
                                                </template>
                                                <template v-else>
                                                    <button class="btn btn-primary" v-if="solic.vb_gerente == 0"
                                                        title="Validar revisión de solicitud"
                                                        @click="changeVbGerente(solic.id,1)"
                                                    >
                                                        Revisar
                                                    </button>
                                                    <button class="btn btn-scarlet" v-if="solic.vb_gerente < 2 && gerente"
                                                        title="Autorizar solicitud"
                                                        @click="changeVbGerente(solic.id,2)"
                                                    >
                                                        Autorizar Solicitud
                                                    </button>
                                                </template>
                                            </td>
                                            <td>
                                                <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                    @click="verObs(solic)"
                                                >Observaciones
                                                </Button>
                                            </td>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                        </div>
                        <Nav :current="arraySolic.current_page ? arraySolic.current_page : 1"
                            :last="arraySolic.last_page ? arraySolic.last_page : 1"
                            @changePage="indexSolicitudes">
                        </Nav>
                    </template>
                    <template v-if="vista == 1">
                        <div id="accordion" role="tablist">
                            <div class="card mb-0">
                                <div class="card-header" id="headingOne" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false"
                                            aria-controls="collapseOne" class="collapsed"
                                            >Solicitud
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseOne" role="tabpanel"
                                    aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Empresa solicitante </label>
                                                <select class="form-control" v-model="solicitudData.empresa_solic" :disabled="tipoAccion == 3">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Proveedor </label>
                                                <v-select v-if="tipoAccion == 1"
                                                    :on-search="selectProveedores"
                                                    label="proveedor"
                                                    :options="proveedoresForm"
                                                    placeholder="Buscar contratista..."
                                                    :onChange="getDatosProveedor"
                                                >
                                                </v-select>
                                                <input type="text" class="form-control" v-if="tipoAccion > 1"
                                                    v-model="solicitudData.proveedor" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label> Forma de pago </label>
                                            <select class="form-control" v-model="solicitudData.tipo_pago" :disabled="tipoAccion == 3 && admin < 2"
                                                @change="solicitudData.forma_pago = ''">
                                                <option value="0">Caja Fuerte</option>
                                                <option value="1">Bancario</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" v-if="solicitudData.tipo_pago == 1">
                                                <label for="">&nbsp;</label>
                                                <select class="form-control" v-model="solicitudData.forma_pago"
                                                    :disabled="tipoAccion == 3 && admin < 2"
                                                >
                                                    <option value="">Metodo de pago</option>
                                                    <option value="0">Transferencia</option>
                                                    <option value="1">Cheque</option>
                                                </select>
                                            </div>
                                        </div>

                                        <template v-if="tipoAccion == 3 && solicitudData.tipo_pago == 1">
                                            <div class="col-md-3">
                                                <label for="">Banco</label>
                                                <input type="text" class="form-control"
                                                    v-model="solicitudData.banco" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Num. Cuenta</label>
                                                <input type="text" class="form-control"
                                                    v-model="solicitudData.num_cuenta" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Clabe</label>
                                                <input type="text" class="form-control"
                                                    v-model="solicitudData.clabe" disabled>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">&nbsp;</label>
                                                <button class="btn btn-warning form-control" @click="abrirModal('banco', solicitudData)">Cambiar Cuenta</button>
                                            </div>
                                        </template>

                                        <div class="col-md-3">
                                            <label> Importe </label>
                                            <input class="form-control" pattern="\d*" maxlength="10"
                                                v-if="tipoAccion != 3"
                                                v-on:keypress="$root.isNumber($event)"
                                                type="text" v-model="solicitudData.importe">
                                            <h6 v-if="tipoAccion == 3" class="form-control">${{$root.formatNumber(solicitudData.importe)}}</h6>
                                        </div>

                                        <div class="col-md-3" v-if="solicitudData.importe > 0 && tipoAccion != 3">
                                            <label>&nbsp;</label>
                                            <h6 class="form-control">${{$root.formatNumber(solicitudData.importe)}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-0" v-if="solicitudData.importe > 0">
                            <div class="card-header" id="headingTwo" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo" class="collapsed"
                                        >Detalle
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse" id="collapseTwo" role="tabpanel"
                                aria-labelledby="headingTwo" data-parent="#accordion">

                                <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px; padding-bottom: 10px;" >
                                    <div class="col-md-12">
                                        <div class="form-group"><center><h3></h3></center></div>
                                    </div>
                                    <div class="col-md-12">
                                        <center>
                                            <h6 style="color:#"> Detalle de la solicitud </h6>
                                        </center>
                                    </div>

                                    <template v-if="tipoAccion < 3">
                                        <div class="col-md-4">
                                            <label for="">Obra <span style="color:red;" v-show="datosDetalle.obra == ''">(*)</span></label>
                                            <input type="text" name="obra" list="obraname" class="form-control"
                                                v-model="datosDetalle.obra" @keyup="getProyectos(datosDetalle.obra)" @change="selectEtapa(datosDetalle.obra)">
                                            <datalist id="obraname">
                                                <option value="">Seleccione</option>
                                                <option value="OFICINA">OFICINA</option>
                                                <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.nombre" v-text="proyecto.nombre"></option>
                                            </datalist>
                                        </div>
                                        <div class="col-md-3" v-if="datosDetalle.obra != 'OFICINA' && datosDetalle.obra != ''">
                                            <label for="">&nbsp;</label>
                                            <select class="form-control" v-model="datosDetalle.sub_obra">
                                                <option value="">Etapa</option>
                                                <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.num_etapa" v-text="etapa.num_etapa"></option>
                                            </select>
                                        </div>
                                        <div v-if="datosDetalle.obra != 'OFICINA' && datosDetalle.obra != ''" class="col-md-5"></div>
                                        <div v-else class="col-md-8"></div>
                                        <div class="col-md-6">
                                            <label for="">Cargo <span style="color:red;" v-show="datosDetalle.cargo == ''">(*)</span></label>
                                            <select class="form-control" v-model="datosDetalle.cargo" @change="getConceptos(datosDetalle.cargo), datosDetalle.concepto = ''">
                                                <option value="">Seleccione</option>
                                                <option v-for="cargo in arrayCargos" :key="cargo.cargo" :value="cargo.cargo" v-text="cargo.cargo"></option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Concepto <span style="color:red;" v-show="datosDetalle.concepto == ''">(*)</span></label>
                                            <select class="form-control" v-model="datosDetalle.concepto">
                                                <option value="">Seleccione</option>
                                                <option v-for="concepto in arrayConceptos" :key="concepto.id" :value="concepto.concepto" v-text="concepto.concepto"></option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Tipo de Mov.</label>
                                            <select class="form-control" v-model="datosDetalle.tipo_mov">
                                                <option value="0">Anticipo</option>
                                                <option value="1">Liquidación</option>
                                                <option value="2">Pago Cta</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="">Observación</label>
                                            <textarea rows="3" class="form-control" v-model="datosDetalle.observacion"></textarea>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <label for="">Importe total <span style="color:red;" v-show="datosDetalle.total <= 0">(*)</span></label>
                                            <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                                type="text" v-model="datosDetalle.total">
                                        </div>
                                        <div class="col-md-2" v-if="datosDetalle.total > 0">
                                            <label for="">&nbsp;</label>
                                            <label class="form-control">${{$root.formatNumber(datosDetalle.total)}}</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Este pago <span style="color:red;" v-show="datosDetalle.pago <= 0">(*)</span></label>
                                            <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                                @change="verificarMonto()"
                                                type="text" v-model="datosDetalle.pago">
                                        </div>
                                        <div class="col-md-2" v-if="datosDetalle.pago > 0">
                                            <label for="">&nbsp;</label>
                                            <label class="form-control">${{$root.formatNumber(datosDetalle.pago)}}</label>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="">&nbsp;</label>
                                            <button class="btn btn-success form-control" @click="addDetalle()" title="Añadir"><i class="icon-plus"></i></button>
                                        </div>
                                    </template>

                                    <div class="col-md-12">
                                        <div class="form-group"><center><h3></h3></center></div>
                                    </div>

                                    <div class="col-md-12">
                                        <center>
                                            <TableComponent :cabecera="[
                                                '','Obra', 'Cargo', 'Subconcepto', 'Obs.', 'Tipo Mov.', 'Este pago'
                                            ]">
                                                <template v-slot:tbody>
                                                    <tr v-for="det in solicitudData.detalle"
                                                        :key="det.id+det.obra+det.sub_obra+det.cargo+det.concepto+det.pago">
                                                        <td>
                                                            <button class="btn btn-danger" title="Eliminar" v-if="tipoAccion != 3"
                                                                @click="removeDetalle(det)"
                                                            >
                                                                <i class="icon-trash"></i>
                                                            </button>
                                                        </td>
                                                        <td class="td2">{{det.obra}} {{det.sub_obra }}</td>
                                                        <td class="td2">{{det.cargo}}</td>
                                                        <td>{{det.concepto}}</td>
                                                        <td>{{det.observacion}}</td>
                                                        <td class="td2">
                                                            {{
                                                                (det.tipo_mov == 0) ? 'Anticipo'
                                                                : (det.tipo_mov == 1) ? 'Liquidación'
                                                                : 'Pago Cta'
                                                            }}
                                                        </td>
                                                        <td class="td2">
                                                            ${{$root.formatNumber(det.pago)}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        <th>$ {{$root.formatNumber(solicitudData.saldo = sumaDet)}}</th>
                                                    </tr>
                                                </template>
                                            </TableComponent>
                                        </center>
                                    </div>

                                </div>
                            </div>
                            </div>

                            <div class="card mb-0" v-if="tipoAccion > 1">
                            <div class="card-header" id="headingThree" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree" class="collapsed"
                                        >Archivos Adjuntos
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse" id="collapseThree" role="tabpanel"
                                aria-labelledby="headingTwo" data-parent="#accordion">

                                <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px; padding-bottom: 10px;" >
                                    <div class="col-md-12">
                                        <div class="form-group"><center><h3></h3></center></div>
                                    </div>

                                    <template v-if="tipoAccion == 2">
                                        <div class="form-sub">
                                            <form method="post" @submit="formSubmitFile"
                                                enctype="multipart/form-data"
                                            >
                                                <div class="form-opc">
                                                    <div class="form-archivo">
                                                        <input ref="fileSelector"
                                                            v-show="false"
                                                            type="file" accept="application/pdf"
                                                            v-on:change="onChangeFile"
                                                        />

                                                        <label class="label-button" @click="onSelectFile">
                                                            Elige el archivo a cargar
                                                            <i class="fa fa-upload"></i>
                                                        </label>
                                                        <div :class="(newArchivo.nom_archivo == 'Seleccione Archivo')
                                                            ? 'text-file-hide' : 'text-file'"
                                                            v-text="newArchivo.nom_archivo"
                                                        ></div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 form-control-label" for="text-input">Tipo de Archivo</label>
                                                            <div class="col-md-9">
                                                                <input type="text" name="categoria" list="categoria" class="form-control" v-model="newArchivo.tipo" placeholder="Tipo de Archivo">
                                                                <datalist id="categoria">
                                                                    <option value="FACTURA">FACTURA</option>
                                                                    <option value="COTIZACION">COTIZACIÓN</option>
                                                                    <option value="VENTANILLA PAGO">VENTANILLA PAGO</option>
                                                                    <option value="RECEPCION">RECEPCIÓN DE TRABAJO</option>
                                                                </datalist>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="boton-modal" v-if="cargando==0">
                                                        <button v-show="newArchivo.nom_archivo !='Seleccione Archivo' && newArchivo.tipo != ''"
                                                            type="submit" class="btn btn-scarlet"
                                                        >
                                                            Subir Archivo
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </template>

                                    <div class="col-md-12">
                                        <div class="form-group"><center><h3></h3></center></div>
                                    </div>

                                    <div class="col-md-12" v-if="solicitudData.files.length">
                                        <center>
                                            <TableComponent :cabecera="[
                                                '','Tipo','Archivo', ' '
                                            ]">
                                                <template v-slot:tbody>
                                                    <tr v-for="p in solicitudData.files"
                                                        :key="p.id">
                                                        <td>
                                                            <button class="btn btn-danger" title="Eliminar" v-if="tipoAccion != 3"
                                                                @click="deleteFile(p.id)"
                                                            >
                                                                <i class="icon-trash"></i>
                                                            </button>
                                                        </td>
                                                        <td class="td2">{{p.tipo}}</td>
                                                        <td class="td2">{{p.file.name}}</td>
                                                        <td class="td2">
                                                            <a :href="p.file.public_url"
                                                            target="_blank" class="btn btn-success"
                                                                title="Ver Archivo"
                                                            ><i class="fa fa-download"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </TableComponent>
                                        </center>
                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button class="btn btn-success" @click="storeSolic()"
                                    v-if="solicitudData.importe > 0
                                        && solicitudData.importe == solicitudData.saldo
                                        && tipoAccion == 1">
                                    Guardar Solicitud
                                </button>
                                <button class="btn btn-success" @click="updateSolicitud()"
                                    v-if="solicitudData.importe > 0
                                        && solicitudData.importe == solicitudData.saldo
                                        && tipoAccion == 2">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!--Inicio del modal observaciones-->
                <ModalComponent v-if="modal == 1"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                            <template v-slot:tbody>
                                <tr v-for="observacion in arrayObs" :key="observacion.id">
                                    <td v-text="observacion.usuario" ></td>
                                    <td v-text="observacion.comentario" ></td>
                                    <td v-text="observacion.created_at"></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </template>
                </ModalComponent>

                <!--Inicio del modal cuentas-->
                <ModalComponent v-if="modal == 2"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <TableComponent :cabecera="['','Banco','Fecha']">
                            <template v-slot:tbody>
                                <tr v-for="cuenta in arrayCuentas" :key="cuenta.id">
                                    <td class="td2" style="width:10%">
                                        <button type="button" class="btn btn-success btn-sm"
                                            @click="changeCuenta(cuenta)" title="Seleccionar cuenta">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-text="cuenta.banco" ></td>
                                    <td class="td2" v-text="cuenta.num_cuenta" ></td>
                                    <td class="td2" v-text="cuenta.clabe" ></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </template>
                </ModalComponent>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from "../Componentes/ModalComponent.vue";
import TableComponent from "../Componentes/TableComponent.vue";
import Button from "../Componentes/ButtonComponent.vue";
import Nav from "../Componentes/NavComponent.vue";
import RowModal from "../Componentes/ComponentesModal/RowModalComponent.vue";
import vSelect from 'vue-select';

export default {
    components: {
        ModalComponent,
        TableComponent,
        Button, RowModal,Nav,
        vSelect
    },
    props: {
        encargado:{type: String},
        usuario:{type: String},
    },
    data() {
        return {
            arrayGerentes:[
                'eli_hdz',
                'sajid.m',
                'bd_raul',
                'lucy.hdz',
                'cp.martin',
                'ing_david',
                'meza.marco60',
                'guadalupe.ff',
                'shady'
            ],
            arraySolic: [],
            empresas: [],
            arrayProveedores : [],
            proveedoresForm : [],
            arrayProyectos : [],
            arrayEtapas : [],
            arrayCargos: [],
            arrayConceptos: [],
            arrayObs : [],
            arrayCuentas : [],

            solicitudData:{},
            datosDetalle:{},

            b_proveedor : '',
            b_solicitante : '',
            b_status : 0,
            b_fecha1 : '',
            b_fecha2 : '',
            loading : false,
            id : '',
            comentario : '',

            modal: 0,
            tituloModal: "",
            newArchivo:{},
            proyectoSel:'',
            etapaSel:'',
            tipoAccion:1,
            cargando:0,

            vista:0,
            admin:0,
            gerente: null,
        };
    },
    computed: {
        sumaDet: function(){
            let me = this;
            let total = 0.0;
            me.solicitudData.detalle.forEach( e => total += Math.round(e.pago) )
            return Math.round(total);
        },
    },
    methods: {
        onChangeFile(e){
            this.newArchivo.file = e.target.files[0];
            this.newArchivo.nom_archivo = e.target.files[0].name;
        },
        changeVbGerente(id,estado){
            let me = this;

            let titulo = '¿Seguro de revisar esta solicitud?';
            if(estado == 2)
                titulo = '¿Seguro de autorizar esta solicitud?';


            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/changeVbGerente/${id}`,{
                        'id': id,
                        'estado' : estado
                    }).then(function (response){
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })




        },
        onSelectFile(){
            this.$refs.fileSelector.click()
        },
        formSubmitFile(e) {
            e.preventDefault();
            let currentObj = this;
            this.cargando = 1;

            let formData = new FormData();
            formData.append('file', this.newArchivo.file);
            formData.append('id', this.solicitudData.id);
            formData.append('tipo', this.newArchivo.tipo);
            let me = this;
            axios.post('/files-solic', formData)
            .then(function (response) {
                me.cargando = 0;
                me.solicitudData.files = response.data.data;
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Archivo guardado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
            }).catch(function (error) {
                currentObj.output = error;
                this.cargando = 0;
            });
        },
        deleteDetalle(id){
            axios.delete(`/solic-pagos/deleteDetalle/${id}`,
                {params: {'id': this.id}}).then(function (response){
            }).catch(function (error){
                console.log(error);
            });
        },
        deleteFile(id){
            let me = this;

            swal({
                title: '¿Desea eliminar el archivo?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {

                    axios.delete(`/files-solic/${id}`,
                        {params: {'id': id}}).then(function (response){
                    }).catch(function (error){
                        console.log(error);
                    });

                    me.solicitudData.files = me.solicitudData.files.filter(
                            a => a.id != id
                    )
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Archivo removido'
                    })
                }
            })
        },
        changeCuenta(cuenta){
            let me = this;
            axios.put(`/solic-pagos/changeCuenta/${me.solicitudData.id}`,{
                'id': me.solicitudData.id,
                'banco' : cuenta.banco,
                'num_cuenta' : cuenta.num_cuenta,
                'clabe' : cuenta.clabe,
            }).then(function (response){
                me.solicitudData.banco = cuenta.banco
                me.solicitudData.num_cuenta = cuenta.num_cuenta
                me.solicitudData.clabe = cuenta.clabe
                me.cerrarModal();
                //Se muestra mensaje Success
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Solicitud actualizada'
                })
            }).catch(function (error){
            });
        },
        removeDetalle(det){
            let me = this;

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
                    if(det.id != null)
                        me.deleteDetalle(det.id);

                    me.solicitudData.detalle = me.solicitudData.detalle.filter(
                            a => a != det
                    )
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Detalle removido'
                    })
                }
            })
        },
        cerrarModal(){
            this.modal = 0;
            this.tituloModal = '';
            this.arrayObs = [];
        },
        addDetalle(){
            let me = this;
            if(me.verificarCaptura()){
                me.solicitudData.detalle.push(me.datosDetalle);
                me.limpiarFormularioDetalle();
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Detalle agregado'
                })
            }
            else{
                window.alert("Verifica los campos faltantes");
            }

        },
        verificarCaptura(){
            let me = this;
            let res = false;
            if( me.datosDetalle.obra != ''
                && me.datosDetalle.cargo != ''
                && me.datosDetalle.concepto != ''
                && me.datosDetalle.total > 0
                && me.datosDetalle.pago > 0
            )res = true;
            return res;
        },
        limpiarFormularioDetalle(){
            this.datosDetalle = {
                id  : '',
                obra: '',
                sub_obra: '',
                cargo : '',
                concepto : '',
                observacion : '',
                tipo_mov : 0,
                total : 0,
                pago : 0,
                saldo : 0,
                pendiente_id : null
            }
        },
        vistaFormulario(accion,data=[]){
            this.vista = 1;
            this.getCargos();
            this.limpiarFormularioDetalle();

            switch(accion){
                case 'nuevo':{
                    this.tipoAccion = 1;
                    this.solicitudData={
                        id : '',
                        empresa_solic : '',
                        proveedor_id : '',
                        importe : 0,
                        saldo : 0,
                        tipo_pago : 0,
                        forma_pago : '',
                        fecha_compra: '',
                        banco : '',
                        num_cuenta : '',
                        clabe : '',
                        num_factura : '',
                        detalle : []
                    }
                    break;
                }
                case 'actualizar':{
                    this.newArchivo = {
                        description: "",
                        tipo: "",
                        file: "",
                        nom_archivo: 'Seleccione Archivo'
                    };
                    this.tipoAccion = 2;
                    this.solicitudData = data;
                    this.cargando = 0;
                    break;
                }
                case 'ver':{
                    this.tipoAccion = 3;
                    this.solicitudData = data;
                    break;
                }
            }
        },
        cerrarFormulario(){
            this.vista = 0;
            this.solicitudData = {};
            this.indexSolicitudes(this.arraySolic.current_page);
        },
        deleteSolic(id){
            let me = this;
            swal({
                title: '¿Desea eliminar esta solicitud?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    axios.delete(`/solic-pagos/${id}`, {
                            params: {'id': id}
                    }).then(function (response){
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                        toast({
                            type: 'success',
                            title: 'Solicitud eliminada correctamente'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }
            })


        },
        /**Metodo para registrar  */
        storeSolic(){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/solic-pagos',{
                'solicitud': me.solicitudData,
            }).then(function (response){
                me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                me.cerrarFormulario();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Solicitud creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },
        updateSolicitud(){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.put(`/solic-pagos/${me.solicitudData.id}`,{
                'solicitud': me.solicitudData,
            }).then(function (response){
                me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                me.cerrarFormulario();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Solicitud actualizada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },
        checkGerente(){
            let me = this;
            me.gerente = me.arrayGerentes.find(element => element == me.usuario)
        },
        /**Metodo para mostrar los registros */
        indexSolicitudes(page) {
            let me = this;
            me.arraySolic = [];
            var url =
                "/solic-pagos?page=" + page
                + '&b_proveedor=' + me.b_proveedor
                + '&b_solicitante=' + me.b_solicitante
                + '&b_fecha1=' + me.b_fecha1
                + '&b_fecha2=' + me.b_fecha2
                + '&b_status=' + me.b_status;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arraySolic = respuesta.solicitudes;
                    me.admin = respuesta.admin;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getProveedores(){
            let me = this;
            me.arrayProveedores=[];
            var url = '/select_proveedor?proveedor';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProveedores = respuesta.proveedor;
            })
            .catch(function (error) {
                console.log(error);
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
            });
        },
        getCuentasProv(id){
            let me = this;
            me.arrayCuentas=[];
            var url = '/proveedor/getCuentasProv?id='+id;
            axios.get(url).then(function (response) {
                var respuesta = response;
                me.arrayCuentas = respuesta.data;
            })
            .catch(function (error) {
            });
        },
        getProyectos(search){
            let me = this;
            me.arrayProyectos = [];
            var url = '/select_fraccionamiento2?filtro='+search;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.arrayProyectos = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        verificarMonto(){
            let me = this;

            if(parseFloat(me.datosDetalle.total) < parseFloat(me.datosDetalle.pago))
                me.datosDetalle.pago = me.datosDetalle.total

            me.saldo = parseFloat(me.solicitudData.importe);
            me.solicitudData.detalle.forEach(e => {
                me.saldo -= parseFloat(e.pago);
            });

            if(me.saldo < me.datosDetalle.pago)
                me.datosDetalle.pago = me.saldo;
        },
        getCargos(){
            let me = this;
            me.arrayCargos=[];
            var url = '/sp/getCargos';
            axios.get(url).then(function (response) {
                me.arrayCargos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getConceptos(cargo){
            let me = this;
            me.arrayConceptos=[];
            var url = '/sp/getConceptos?cargo='+cargo;
            axios.get(url).then(function (response) {
                me.arrayConceptos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectEtapa(buscar){
            let me = this;
            me.datosDetalle.sub_obra = '';
            me.arrayEtapas=[];
            var url = '/select_etapa?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapas = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectProveedores(search,loading){
            let me = this;
            loading(true)
            me.proveedoresForm=[];
            var url = '/select_proveedor?proveedor='+search;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                q: search
                me.proveedoresForm = respuesta.proveedor;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
                loading(false)
            });
        },
        getDatosProveedor(val1){
            let me = this;
            //me.loading = true;
            me.solicitudData.proveedor_id = val1.id;
            me.solicitudData.proveedor = val1.proveedor;
        },
        cerrarModal() {
            this.modal = 0;
            this.planos = [];
            this.newArchivo = {};
            this.lotes_ini = [];
            this.proyectoSel = '';
            this.etapaSel = '';
            this.indexSolicitudes(this.arraySolic.current_page)
            this.cargando = 0;
        },
        /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
        abrirModal(accion, data = []) {
            let me = this;
            switch (accion) {
                case 'banco':{
                    me.modal = 2;
                    me.tituloModal = 'Cambiar cuenta bancaria';
                    me.getCuentasProv(me.solicitudData.proveedor_id);
                    break;
                }
            }
        },
        verObs(solicitud){
            let me = this;
            me.arrayObs = solicitud.obs;
            me.id = solicitud.id;
            me.comentario = '';
            me.modal = 1;
            me.tituloModal = 'Observaciones';
        }
    },
    mounted() {
        this.indexSolicitudes(1);
        this.checkGerente();
        this.getEmpresa();
        this.getProveedores();
        this.$root.selectFraccionamientos();
    }
};
</script>
<style>
    .text-formfile{
        color: grey;
        display:flex;
        padding-top: 13px;
        justify-content: left;

    }
    .contenedor-modal{
        display: block;
        flex-direction: column;
        margin: auto;
        overflow-x: auto;
        width: fit-content;
        max-width: 100%;
    }

    .label-button{
        border-style: solid;
        cursor:pointer;
        color: #fff;
        background-color: #00ADEF;
        border-color: #00ADEF;
        padding: 10px;
        margin: 15px;
    }

    .label-button:hover {
        color: #fff;
        background-color: #1b8eb7;
        border-color: #00b0bb;
    }
    .form-sub{
        border: 1px solid #c2cfd6;
        margin-top: 20px;
        width: 100%;
    }
    .form-opc{
        display: flex;
        flex-direction: column;
    }
    .form-archivo{
        display: flex;
        flex-direction: row;
        width: 100%;
    }
    .text-file{
        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .text-file-hide{
        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .boton-modal{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
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
