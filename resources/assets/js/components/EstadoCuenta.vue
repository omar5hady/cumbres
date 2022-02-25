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
                        <i class="fa fa-align-justify"></i>Estado de cuenta
                         <!--   Boton descargar excel    -->
                        <a class="btn btn-success" v-bind:href="'/estadoCuenta/excel?buscar=' + buscar + '&buscar2=' + buscar2 + 
                            '&b_manzana=' + b_manzana + '&b_lote=' + b_lote + '&b_status=' + b_status + '&criterio=' + criterio + 
                            '&credito=' + b_credito +'&b_empresa='+b_empresa">
                            <i class="fa fa-file-text"></i>&nbsp; Descargar excel
                        </a>
                        <!---->

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                        <option value="contratos.fecha">Fecha de venta</option>
                                    </select>

                                    <select class="form-control col-md-7" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <input v-else-if="criterio=='contratos.id'" type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,buscar2,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input v-else-if="criterio=='c.nombre'" type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,buscar2,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input v-else-if="criterio=='contratos.fecha'" type="date"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,buscar2,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input v-if="criterio=='c.nombre'" type="text"  v-model="buscar2" @keyup.enter="listarContratos(1,buscar,buscar2,b_manzana,b_lote,criterio)" class="form-control" placeholder="Apellidos">
                                    <input v-else-if="criterio=='contratos.fecha'" type="date"  v-model="buscar2" @keyup.enter="listarContratos(1,buscar,buscar2,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                   
                                </div>

                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">

                                    <select class="form-control"  v-model="buscar2"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <input type="text" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                   
                                </div>

                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    
                                    <input type="text" v-model="b_lote" class="form-control" placeholder="Lote a buscar">
                                    <select class="form-control" v-model="b_credito"> 
                                        <option value="">Tipo de Credito</option>
                                        <option v-for="credito in arrayCreditos" :key="credito.nombre" :value="credito.nombre" v-text="credito.nombre"></option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <input type="text" v-model="b_direccion" class="form-control" placeholder="Direccion oficial">
                                </div>   

                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="b_status">
                                            <option value="">Seleccionar Status</option>
                                            <option value="0">Cancelado</option>
                                            <option value="1">Pendiente</option>
                                            <option value="2">No firmado</option>
                                            <option value="3">Firmado</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarContratos(1,buscar,buscar2,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Total: '+ contador"> </span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th>Opciones</th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Avance</th>
                                        <th>Precio de venta</th>
                                        <th>Valor a escriturar</th>
                                        <th>Pagares</th>
                                        <th>Total enganche</th>
                                        <th>Depositos</th>
                                        <th>Pendiente enganche</th>
                                        <th>Crédito</th>
                                        <th>Crédito cobrado</th>
                                        <th>Gastos administrativos</th>
                                        <th>Fecha de firma de escrituras</th>
                                        <th>Descuento</th>
                                        <th>Saldo </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.folio" v-on:dblclick="abrirPDF(contratos.folio)" title="Doble click">
                                        <td class="td2">
                                            <button type="button" title="Ver datos del cliente" @click="abrirModal('ver_personal',contratos)" class="btn btn-primary btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                           <td class="td2">
                                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.folio}}</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ contratos.folio">Contrato de compra venta</a>
                                                    <a class="dropdown-item" v-if="contratos.tipo_credito!='Crédito Directo'" target="_blank" v-bind:href="'/contrato/promesaCredito/pdf/'+contratos.folio">Contrato</a>
                                                    <a class="dropdown-item" v-if="contratos.tipo_credito=='Crédito Directo' && contratos.modelo != 'Terreno'" target="_blank" v-bind:href="'/contratoCompraVenta/reservaDeDominio/pdf/'+contratos.folio">Contrato</a>
                                                    <a class="dropdown-item" v-if="contratos.tipo_credito=='Crédito Directo' && contratos.modelo == 'Terreno'" target="_blank" v-bind:href="'/contrato/contratoLote/pdf/'+contratos.folio">Contrato</a>
                                                    <a v-if="contratos.liquidado == 1" class="dropdown-item" target="_blank" v-bind:href="'/expediente/liquidacionPDF/'+contratos.folio">Liquidación</a>
                                                </div>
                                            </td>
                                        <td class="td2">
                                            <a href="#" v-text="contratos.nombre_cliente"></a>
                                        </td>
                                        <td class="td2" v-text="contratos.fraccionamiento"></td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2" v-text="contratos.avance+'%'"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.precio_venta)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.valor_escrituras)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.pagares)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.enganche_total)"></td>

                                        <td class="td2" v-text="'$'+formatNumber(
                                            parseFloat(contratos.depositos))"></td>

                                        <td class="td2" v-text="'$'+formatNumber(contratos.pendiente_enganche)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.credito_solic)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.cobrado)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.gastos)"></td>
                                        <td v-if="contratos.fecha_firma_esc" v-text="this.moment(contratos.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-else></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.descuento)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.saldo)"></td>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,buscar2,b_manzana,b_lote,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,b_manzana,b_lote,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,b_manzana,b_lote,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,b_manzana,b_lote,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,buscar2,b_manzana,b_lote,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal para mostrar los datos del cliente -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-5">
                                        <input type="text" disabled v-model="nombre_cliente" class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Sexo</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="sexo_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" v-model="clv_lada" disabled class="form-control col-md-3">
                                            <input type="text" v-model="celular_cliente" disabled class="form-control">
                                        </div>
                                    </div>
                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+celular_cliente"><i class="fa fa-phone fa-lg"></i></a>
                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+clv_lada+celular_cliente+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>             
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="telefono_cliente" class="form-control">
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input">Email</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="email_cliente" disabled class="form-control">
                                    </div>
                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+email_cliente"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="direccion_cliente" disabled class="form-control">
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input">C.P</label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="cp_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="colonia_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="estado_cliente" disabled class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="ciudad_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de nacimiento</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="fechanac_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">CURP</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="curp_cliente" disabled class="form-control">
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="rfc_cliente" disabled class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="homoclave_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">NSS</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="nss_cliente" disabled class="form-control">
                                    </div>
                                </div>

                                <hr>

                                <!-- DATOS FISCALES -->
                                <template> 
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                        </div>
                                        <h5 style="text-align:center;">DATOS FISCALES</h5>
                                        <div class="col-md-3" v-if="datosFiscales.archivo_fisc != null">
                                            <button 
                                            type="button" @click="verImagen(datosFiscales.archivo_fisc)" class="btn btn-dark btn-sm"
                                                title="Ver Archivo Fiscal"
                                            >
                                                <i class="icon-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo Electrónico</label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="datosFiscales.email_fisc" disabled class="form-control">
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono</label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="datosFiscales.tel_fisc" disabled class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="datosFiscales.nombre_fisc" disabled class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="datosFiscales.direccion_fisc" disabled class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Colonia</label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="datosFiscales.col_fisc" disabled class="form-control">
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">CP</label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="datosFiscales.cp_fisc" disabled class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">RFC</label>
                                        <div class="col-md-3">
                                            <input disabled type="text" maxlength="13"  style="text-transform:uppercase"
                                            v-model="datosFiscales.rfc_fisc" class="form-control" placeholder="RFC">
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Uso del C.F.D.I.</label>
                                        <div class="col-md-5">
                                            <input disabled type="text" style="text-transform:uppercase"
                                            v-model="datosFiscales.cfi_fisc" class="form-control" placeholder="C.F.D.I.">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Régimen Fiscal del cliente</label>
                                        <div class="col-md-6">
                                            <input disabled type="text" 
                                            v-model="datosFiscales.regimen_fisc" class="form-control" placeholder="Régimen">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Banco</label>
                                        <div class="col-md-4">
                                            <input disabled type="text" 
                                            v-model="datosFiscales.banco_fisc" class="form-control" placeholder="Banco">
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">No. Cuenta</label>
                                        <div class="col-md-4">
                                            <input disabled type="text" 
                                            v-model="datosFiscales.num_cuenta_fisc" class="form-control" placeholder="# Cuenta">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Clabe</label>
                                        <div class="col-md-4">
                                            <input disabled type="text" 
                                            v-model="datosFiscales.clabe_fisc" class="form-control" placeholder="Clabe">
                                        </div>
                                    </div>

                                    
                                </template>
                                
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
        data(){
            return{
                observacion:'',

                arrayContratos : [],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayCreditos:[],

                 //para los datos del cliente
                nombre_cliente: '',
                sexo_cliente: '',
                telefono_cliente: '',
                celular_cliente:'',
                clv_lada:'',
                email_cliente: '',
                direccion_cliente: '',
                cp_cliente: 0,
                colonia_cliente: '',
                estado_cliente: '',
                ciudad_cliente: '',
                fechanac_cliente:'',
                curp_cliente:'',
                rfc_cliente:'',
                homoclave_cliente:'',
                nss_cliente:0,
                tipoeconomia_cliente:'',
                empresa_cliente:'',
                gironegocio_cliente:'',
                domicilio_empresa:'',
                cpempresa_cliente: 0,
                coloniaempresa_cliente: '',
                estadoempresa_cliente: '',
                ciudadempresa_cliente: '',
                emailinstitucional_cliente: '',
                telefonoempresa_cliente: '',
                ext_cliente: 0,
                edocivil_cliente: '',
                depeconomicos_cliente: 0,
                contador:0,
                b_direccion:'',
                datosFiscales:{},

                modal: 0,
                tituloModal: '',
           
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
                buscar : '',
                buscar2: '',
                b_manzana: '',
                b_lote: '',
                b_status: 3,
                b_credito:'',
                b_empresa:'',
                empresas:[],               
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

        },

        
        methods : {

            /**Metodo para mostrar los registros */
            listarContratos(page, buscar, buscar2, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/estadoCuenta/index?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&b_manzana=' + b_manzana + 
                    '&b_lote=' + b_lote + '&b_status=' + me.b_status + '&criterio=' + criterio + '&credito=' + this.b_credito
                    + '&b_direccion=' + this.b_direccion+'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    me.contador = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            verImagen(imagen){
                let url = '/files/datosFisc/'+imagen;
                Swal.fire({
                imageUrl: url,
                imageWidth: 600,
                imageHeight: 800,
                imageAlt: 'Datos Fiscales',
                })
            },
            abrirPDF(id){
                const win = window.open('/estadoCuenta/estadoPDF/'+id, '_blank');
                win.focus();
            },
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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
                me.buscar2=""
                
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

            selectCreditos(){
                let me = this;
                me.arrayCreditos=[];
                var url = '/select_tipoCredito';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditos = respuesta.Tipos_creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina(page,buscar,buscar2,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar,buscar2,b_manzana,b_lote,criterio);
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
              cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre_cliente = '';
                this.sexo_cliente= '';
                this.telefono_cliente = '';
                this.celular_cliente = '';
                this.email_cliente = '';
                this.direccion_cliente = '';
                this.cp_cliente=0;
                this.colonia_cliente = '';
                this.estado_cliente='';
                this.ciudad_cliente='';
                this.fechanac_cliente='';
                this.curp_cliente='';
                this.rfc_cliente='';
                this.homoclave_cliente='';
                this.nss_cliente=0;
                this.tipoeconomia_cliente='';
                this.empresa_cliente='';
                this.gironegocio_cliente='';
                this.domicilio_empresa='';
                this.cpempresa_cliente=0;
                this.coloniaempresa_cliente='';
                this.estadoempresa_cliente='';
                this.ciudadempresa_cliente='';
                this.emailinstitucional_cliente='';
                this.telefonoempresa_cliente='';
                this.ext_cliente=0;
                this.edocivil_cliente='';
                this.depeconomicos_cliente=0;
            },

            abrirModal(accion,data =[]){
                        switch(accion){
                            case 'ver_personal':
                            {
                                this.modal =1;
                                this.tituloModal='Datos del prospecto';
                                this.tipoAccion=3;
                                this.nombre_cliente = data['nombre_cliente'];
                                if(data['sexo'] == "M"){
                                    this.sexo_cliente= 'Masculino';
                                }else{
                                    this.sexo_cliente= 'Femenino';
                                }
                                this.telefono_cliente = data['telefono'];
                                this.celular_cliente = data['celular'];
                                this.clv_lada = data['clv_lada'];
                                this.email_cliente = data['email'];
                                this.direccion_cliente =data['direccion'];
                                this.cp_cliente= data['cp'];
                                this.colonia_cliente = data['colonia'];
                                this.estado_cliente=data['estado'];
                                this.ciudad_cliente=data['ciudad'];
                                this.fechanac_cliente=data['f_nacimiento'];
                                this.curp_cliente=data['curp'];
                                this.rfc_cliente=data['rfc'];
                                this.homoclave_cliente=data['homoclave'];
                                this.nss_cliente=data['nss'];
                                this.tipoeconomia_cliente=data['tipo_economia'];
                                this.empresa_cliente=data['empresa'];
                                this.gironegocio_cliente=data['puesto'];
                                this.domicilio_empresa=data['direccion_empresa'];
                                this.cpempresa_cliente=data['cp_empresa'];
                                this.coloniaempresa_cliente=data['colonia_empresa'];
                                this.estadoempresa_cliente=data['estado_empresa'];
                                this.ciudadempresa_cliente=data['ciudad_empresa'];
                                this.emailinstitucional_cliente=data['email_institucional'];
                                this.telefonoempresa_cliente=data['telefono_empresa'];
                                this.ext_cliente=data['ext_empresa'];
                                
                                this.datosFiscales.email_fisc = data['email_fisc'];
                                this.datosFiscales.tel_fisc = data['tel_fisc'];
                                this.datosFiscales.nombre_fisc = data['nombre_fisc'];
                                this.datosFiscales.direccion_fisc = data['direccion_fisc'];
                                this.datosFiscales.col_fisc = data['col_fisc'];
                                this.datosFiscales.cp_fisc = data['cp_fisc'];
                                this.datosFiscales.rfc_fisc = data['rfc_fisc'];
                                this.datosFiscales.archivo_fisc = data['archivo_fisc'];

                                this.datosFiscales.cfi_fisc = data['cfi_fisc'];
                                this.datosFiscales.regimen_fisc = data['regimen_fisc'];
                                this.datosFiscales.banco_fisc = data['banco_fisc'];
                                this.datosFiscales.num_cuenta_fisc = data['num_cuenta_fisc'];
                                this.datosFiscales.clabe_fisc = data['clabe_fisc'];

                                switch(data['edo_civil']){
                                    case 1: {
                                        this.edocivil_cliente = 'Casado - separacion de bienes';
                                        break;
                                    }
                                    case 2:{
                                        this.edocivil_cliente = 'Casado - sociedad conyugal';
                                        break;
                                    }
                                    case 3:{
                                        this.edocivil_cliente = 'Divorciado';
                                        break;
                                    }
                                    case 4:{
                                        this.edocivil_cliente = 'Soltero';
                                        break;
                                    }
                                    case 5:{
                                        this.edocivil_cliente = 'Union libre';
                                        break;
                                    }
                                    case 6:{
                                        this.edocivil_cliente = 'Viudo';
                                        break;
                                    }
                                    default:{
                                        this.edocivil_cliente = 'Otro';
                                        break;
                                    }
                                }
                                
                                this.depeconomicos_cliente=data['num_dep_economicos'];
                            }
                        }
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
            
        },
       
        mounted() {
            this.listarContratos(1,this.buscar,this.buscar2,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
            this.selectCreditos();
            this.getEmpresa();
        }
    }
</script>
<style>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
    color: #27417b;
    }
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

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
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
