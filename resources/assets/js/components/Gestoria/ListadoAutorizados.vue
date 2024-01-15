<template>
    <div>
        <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-control" v-model="search.b_empresa" >
                        <option value="">Empresa constructora</option>
                        <option v-for="empresa in $root.$data.empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-10">
                <div class="input-group">
                    <!--Criterios para el listado de busqueda -->
                    <select class="form-control col-md-5" v-model="search.criterio">
                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                        <option value="c.nombre">Cliente</option>
                        <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                        <option value="contratos.id"># Folio</option>
                    </select>
                    <select class="form-control" v-if="search.criterio=='lotes.fraccionamiento_id'"
                        v-model="search.buscar" @change="$root.selectEtapa(search.buscar), search.b_etapa = ''">
                        <option value="">Seleccione</option>
                        <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id" :value="proyecto.id"
                            v-text="proyecto.nombre">
                        </option>
                    </select>
                    <select class="form-control" v-else-if="search.criterio=='expedientes.gestor_id'" v-model="search.buscar">
                        <option value="">Gestor</option>
                        <option v-for="gestor in $root.$data.arrayGestores" :key="gestor.id" :value="gestor.id"
                            v-text="gestor.nombre_gestor">
                        </option>
                    </select>
                    <input v-else type="text"  v-model="search.buscar" @keyup.enter="$emit('listarData', search)" class="form-control" placeholder="Texto a buscar">
                </div>
                <div class="input-group">
                    <select class="form-control col-md-6" v-if="search.criterio=='lotes.fraccionamiento_id'" v-model="search.b_etapa">
                        <option value="">Etapa</option>
                        <option v-for="e in $root.$data.etapas" :key="e.id" :value="e.id"
                            v-text="e.num_etapa">
                        </option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="text" v-if="search.criterio=='lotes.fraccionamiento_id'" v-model="search.b_manzana" class="form-control" placeholder="Manzana a buscar">
                    <input type="text" v-if="search.criterio=='lotes.fraccionamiento_id'" v-model="search.b_lote" class="form-control" placeholder="Lote a buscar">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-10">

                <div class="input-group">
                    <Button @click="$emit('listarData', search)"
                        icon="fa fa-search">Buscar
                    </Button>
                    <a :href="'/expediente/excelAutorizados?buscar=' + search.buscar + '&b_etapa=' + search.b_etapa + '&b_manzana=' + search.b_manzana + '&b_lote=' +
                        search.b_lote +  '&criterio=' + search.criterio+'&b_empresa='+search.b_empresa"
                        class="btn btn-success">
                        <i class="fa fa-file-text"></i> Excel
                    </a>

                </div>
            </div>
        </div>
        <!-- A partir del 1ero de diciembre del 2022 se solicito que se hablara tema tecnico con los clientes de las privadas Villa del Rey y Alcazar -->
        <!-- A partir del 1ero de marzo del 2023 se solicito que se hablara tema tecnico con los clientes de privada Torino, Mzn 16 del Lote 1 al 19 y
                    Mzn 12 de lote y al 18 -->
        <!-- Lo anterior aplica para ventas o firmas de escritura a partir de la fecha -->
        <!-- Agregar fecha de venta y fecha de firma -->
        <TableComponent :cabecera="[
                '','Detener','Celular','Email','# Ref','Cliente','Asesor','Proyecto','Etapa','Manzana',
                '# Lote','Modelo','Dirección','Avance obra','Firma Contrato',
                'Resultado avaluo','Aviso preventivo','Tipo de Crédito','Institución de Fin.',
                'Monto autorizado','Fecha vigencia','Valor de la vivienda','Valor escriturar','Crédito Puente',
                'Fecha ultimo pagare','Saldo','Inscripción Infonavit','Observaciones'
            ]"
        >
            <template v-slot:tbody>
                <tr v-for="item in arrayData" :key="item.id"
                    :style="{ backgroundColor : !item.detenido ? '#FFFFFF' : '#FF5F5F'}">
                    <td class="td2">
                        <Button v-if="item.detenido == 0" btnClass="btn-danger btn-sm"
                            @click="$emit('regresarExpediente', item.folio)"
                            icon="fa fa-exclamation-triangle">
                        </Button>
                    </td>
                    <td class="td2">
                        <Button v-if="item.detenido == 0"
                            @click="$emit('cambiarProceso', { folio: item.folio, opc:1 })"
                            btnClass="btn-danger btn-sm" title="Detener solicitud"
                            icon="fa fa-hand-paper-o">
                        </Button>
                        <Button v-if="item.detenido == 1" @click="$emit('cambiarProceso', { folio: item.folio, opc: 0 })"
                            btnClass="btn-success btn-sm" title="Reanudar solicitud"
                            icon="fa fa-play">
                        </Button>
                    </td>
                    <td class="td2" >
                        <a title="Llamar" class="btn btn-dark" :href="'tel:'+item.celular">
                            <i class="fa fa-phone fa-lg"></i>
                        </a>
                        <a title="Enviar whatsapp" class="btn btn-success" target="_blank"
                            :href="'https://api.whatsapp.com/send?phone=+52'+item.celular+'&text=Hola'">
                            <i class="fa fa-whatsapp fa-lg"></i>
                        </a>
                    </td>
                    <td class="td2">
                        <a title="Enviar correo" class="btn btn-secondary"
                            :href="`mailto:${item.email};${item.email_institucional ? item.email_institucional : ''}`">
                            <i class="fa fa-envelope-o fa-lg"></i>
                        </a>
                    </td>

                    <td class="td2">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{item.folio}}</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                            <a v-if="item.sit_fg" class="dropdown-item"
                                @click="$emit('abrirModal',{ opcion: 'sit_fg', data: item })">
                                Doc Sit. Geologica
                            </a>
                            <a class="dropdown-item" v-if="item.pdf != '' && item.pdf != null" :href="'/downloadAvaluo/'+item.pdf">
                                Avaluo
                            </a>
                            <a class="dropdown-item" @click="$emit( 'abrirPDF',item )">Estado de cuenta</a>
                            <a class="dropdown-item" target="_blank" :href="'/contratoCompraVenta/pdf/'+ item.folio">
                                Contrato de compra venta
                            </a>
                            <a class="dropdown-item" target="_blank" :href="'/cartaServicios/pdf/'+ item.folio">
                                Carta de servicios
                            </a>
                            <a class="dropdown-item" target="_blank" :href="'/serviciosTelecom/pdf/'+ item.folio">
                                Servicios de telecomunición
                            </a>
                            <a class="dropdown-item" :href="'/descargarReglamento/contrato/'+ item.folio">
                                Reglamento
                            </a>
                            <a class="dropdown-item" @click="$emit( 'catEspecificaciones', item.folio )">
                                Catalogo de especificaciones
                            </a>
                            <a v-if="item.foto_predial" class="dropdown-item" :href="'/downloadPredial/'+ item.foto_predial">
                                Predial
                            </a>
                            <a v-if="item.num_licencia" class="dropdown-item" :href="'/downloadLicencias/'+item.foto_lic"
                                v-text="'Licencia: '+item.num_licencia">
                            </a>
                        </div>
                    </td>
                    <td class="td2" :style="{ color : item.emp_constructora == 'Grupo Constructor Cumbres' ? '#2C36C2' : '#000000'}"
                        v-text="item.nombre_cliente">
                    </td>
                    <td class="td2" v-text="item.nombre_vendedor"></td>
                    <td class="td2" v-text="item.proyecto"></td>
                    <td class="td2" v-text="item.etapa"></td>
                    <td class="td2" v-text="item.manzana"></td>
                    <td class="td2" >
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            {{item.num_lote}} {{ item.sublote ? item.sublote : '' }}
                        </a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                            <a v-if ="item.foto_predial" class="dropdown-item" :href="'/downloadPredial/'+item.foto_predial">Descargar predial</a>
                            <a v-if ="item.foto_lic" class="dropdown-item" :href="'/downloadLicencias/'+item.foto_lic">Descargar licencia</a>
                            <a v-if ="item.foto_acta" class="dropdown-item" :href="'/downloadActa/'+item.foto_acta">Descargar Acta de termino</a>
                        </div>
                    </td>
                    <td class="td2" v-text="item.modelo"></td>
                    <td class="td2" v-text="item.calle + ' '+ item.numero + ' '+ item.interior"></td>
                    <td class="td2" v-text="item.avance_lote+ '%'"></td>
                    <td class="td2" v-text="item.fecha_status"></td>

                    <td v-if="item.avaluo_preventivo!='0000-01-01'" class="td2">
                        <span v-text="'$'+ $root.formatNumber(item.resultado)"></span>
                    </td>
                    <td v-if="item.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                    <td @dblclick="$emit('abrirModal', { opcion:'fecha_recibido', data: item})"
                        v-if="item.aviso_prev!='0000-01-01' && !item.aviso_prev_venc && item.aviso_prev!=null"
                        class="td2"
                    >
                        <a href="#" v-text="'Fecha solicitud: ' + this.moment(item.aviso_prev).locale('es').format('DD/MMM/YYYY')"></a>
                    </td>

                    <td  @dblclick="$emit('abrirModal', { opcion:'fecha_recibido',data: item })"
                        v-else-if="item.aviso_prev!='0000-01-01' && item.aviso_prev_venc" class="td2"
                    >
                        <span v-if = "item.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: '
                        + this.moment(item.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                        <span v-if = "item.diferencia < 0 && item.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: '
                        + this.moment(item.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                        <span v-if = "item.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: '
                        + this.moment(item.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                    </td>

                    <td v-else-if="item.aviso_prev=='0000-01-01' || item.aviso_prev==null" class="td2"
                        v-text="'No aplica'">
                    </td>
                    <td class="td2" v-text="item.tipo_credito"></td>
                    <td class="td2" v-text="item.institucion"></td>
                    <td class="td2" v-text="'$'+ $root.formatNumber(item.credito_solic)"></td>
                    <td class="td2" v-if="band==0" @dblclick="band=1" title="Doble click">
                        <span v-if="!item.fecha_vigencia"></span>
                        <span v-else-if = "item.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: '
                        + this.moment(item.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                        <span v-else-if = "item.vigencia <= 0 && item.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: '
                        + this.moment(item.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                        <span v-else-if = "item.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: '
                        + this.moment(item.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                    </td>
                    <td class="td2" v-if="band==1">
                        <input type="date" @keyup.esc="band=0" class="form-control Fields"
                        @change="$emit( 'actualizarVigencia', { folio: item.folio, valor: $event.target.value })" :id="item.folio"
                        :value="item.fecha_vigencia">
                    </td>
                    <td class="td2" v-text="'$'+ $root.formatNumber(item.precio_venta)"></td>
                    <td class="td2" v-text="'$'+ $root.formatNumber(item.valor_escrituras)"></td>
                    <td class="td2" v-text="item.credito_puente"></td>
                    <td class="td2" v-text="this.moment(item.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                    <td class="td2" v-text="'$'+ $root.formatNumber(item.saldo)"></td>
                    <template v-if="item.fecha_infonavit">
                        <td v-if="item.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(item.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                        <td v-if="item.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                    </template>
                    <template v-else>
                        <td class="td2">
                            <Button v-if="item.detenido == 0" btnClass="btn-success btn-sm" title="Inscribir Infonavit"
                                @click="$emit('abrirModal', { opcion: 'autorizado', data: item })"
                                icon="fa fa-calendar-check-o">
                            </Button>
                            <Button v-if="item.detenido == 0" @click="noAplicaInfonavit(item.folio)" btnClass="btn-danger btn-sm" title="No aplica"
                                icon="fa fa-times-circle">
                            </Button>
                            <label v-else> DETENIDO </label>
                        </td>
                    </template>
                    <td class="td2">
                        <Button title="Ver todas las observaciones" btnClass="btn-info pull-right"
                            @click="$emit('verObs', item.folio)">
                            Ver Observaciones
                        </Button>
                    </td>
                </tr>
            </template>
        </TableComponent>
    </div>
</template>

<script>
import TableComponent from '../Componentes/TableComponent.vue';
import Button from '../Componentes/ButtonComponent.vue';

export default {
    props:{
        rolId: {type: String},
        arrayData: {type: Array}
    },
    components: {
        TableComponent,
        Button
    },
    data() {
        return {
            search: {
                buscar: '',
                b_etapa:'',
                b_manzana: '',
                b_lote: '',
                b_empresa: '',
                criterio: '',
            },
            band: 0
        }
    },
    methods: {
        noAplicaInfonavit(id){
            swal({
            title: '¿Esta seguro de que la inscripción de Infonavit no aplica para este registro?',
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
                let me = this;
                axios.put('/expediente/InfonavitNoAplica',{
                    'folio': id
                }).then(function (response) {
                    me.$emit('listarData', me.search)
                    swal(
                        'Hecho!',
                        'No aplica.',
                        'success'
                    )
                }).catch(function (error) {
                    console.log(error);
                });

            } else if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.cancel
            ) {

            }
            })
        },
    },
}

</script>

<style>
</style>
