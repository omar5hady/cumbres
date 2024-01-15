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
                     <select class="form-control col-md-4" v-model="search.criterio">
                         <option value="lotes.fraccionamiento_id">Proyecto</option>
                         <option value="c.nombre">Cliente</option>
                         <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                         <option value="contratos.id"># Folio</option>
                     </select>

                     <select class="form-control" v-if="search.criterio=='lotes.fraccionamiento_id'" v-model="search.buscar"
                         @change="$root.selectEtapa(search.buscar), search.b_etapa = ''">
                         <option value="">Seleccione</option>
                         <option v-for="proyecto in $root.$data.proyectos"
                             :key="proyecto.id" :value="proyecto.id">
                             {{ proyecto.nombre }}
                         </option>
                     </select>

                     <select class="form-control" v-else-if="search.criterio=='expedientes.gestor_id'" v-model="search.buscar">
                         <option value="">Gestor</option>
                         <option v-for="gestor in $root.$data.arrayGestores"
                             :key="gestor.id" :value="gestor.id">
                             {{ gestor.nombre_gestor }}
                         </option>
                     </select>

                     <input v-else type="text" v-model="search.buscar" @keyup.enter="$emit('listarData', search)" class="form-control" placeholder="Texto a buscar">

                 </div>
                 <div class="input-group" v-if="search.criterio=='lotes.fraccionamiento_id'">
                     <select class="form-control col-md-6"  v-model="search.b_etapa">
                         <option value="">Etapa</option>
                         <option v-for="e in $root.$data.etapas" :key="e.id" :value="e.id">
                             {{ e.num_etapa }}
                         </option>
                     </select>
                 </div>
                 <div class="input-group" v-if="search.criterio=='lotes.fraccionamiento_id'">
                     <!--Criterios para el listado de busqueda -->
                     <input type="text" v-model="search.b_manzana" class="form-control" placeholder="Manzana a buscar">
                     <input type="text" v-model="search.b_lote" class="form-control" placeholder="Lote a buscar">
                 </div>
             </div>
         </div>
         <div class="form-group row">
             <div class="col-md-10">
                 <div class="input-group">
                     <Button icon="fa fa-search" @click="$emit('listarData', search)">Buscar</Button>
                     <a :href="'/expediente/excelIngresarExp?buscar=' + search.buscar + '&b_etapa=' + search.b_etapa + '&b_manzana=' + search.b_manzana + '&b_lote=' +
                         search.b_lote +  '&criterio=' + search.criterio+'&b_empresa='+search.b_empresa" class="btn btn-success">
                         <i class="fa fa-file-text"></i> Excel
                     </a>
                 </div>
             </div>
         </div>
         <TableComponent :cabecera="[
                 '','Detener','Celular','Email','# Ref','Cliente','Asesor','Proyecto','Etapa','Manzana',
                 '# Lote','Modelo','Dirección','Avance obra','Firma Contrato','Resultado avaluo','Aviso preventivo',
                 'Ingresar expediente','Tipo de Crédito','Institución de Fin.','Valor de la vivienda','Crédito Puente',
                 'Fecha ultimo pagare','Saldo','Observaciones'
             ]"
         >
             <template v-slot:tbody>
                 <tr v-for="ingresar in arrayPorIngresar" :key="ingresar.id" :style="{ backgroundColor : !ingresar.detenido ? '#FFFFFF' : '#FF5F5F'}">
                     <td class="td2">
                         <Button v-if="ingresar.detenido == 0"
                             btnClass="btn-danger" size="btn-sm" title="Regresar expediente" icon="fa fa-exclamation-triangle"
                             @click="$emit('regresarExpediente', ingresar.folio)"></Button>
                         <label v-else> DETENIDO </label>
                     </td>
                     <td class="td2">
                         <Button v-if="ingresar.detenido == 0" btnClass="btn-danger" size="btn-sm"
                             @click="$emit('cambiarProceso', { folio: ingresar.folio, opc:1 })"
                             title="Detener solicitud" icon="fa fa-hand-paper-o">
                         </Button>
                         <Button v-if="ingresar.detenido == 1"
                             @click="$emit('continuarContrato', {folio: ingresar.folio, opc:0})"
                             btnClass="btn-success" size="btn-sm"
                             title="Reanudar solicitud" icon="fa fa-play">
                         </Button>
                     </td>
                     <td class="td2" >
                         <a title="Llamar" class="btn btn-dark" :href="'tel:'+ingresar.celular"><i class="fa fa-phone fa-lg"></i></a>
                         <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+ingresar.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                     </td>
                     <td class="td2">
                         <a title="Enviar correo" class="btn btn-secondary"
                             :href="`mailto:${ingresar.email};${ingresar.email_institucional ? ingresar.email_institucional : ''}`">
                             <i class="fa fa-envelope-o fa-lg"></i>
                         </a>
                     </td>
                     <td class="td2">
                         <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{ingresar.folio}}</a>
                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                             <a v-if="ingresar.sit_fg" class="dropdown-item" @click="$emit('abrirModal',{ opcion:'sit_fg', data: ingresar })">Doc Sit. Geologica</a>
                             <a class="dropdown-item" v-if="ingresar.pdf != '' && ingresar.pdf != null"  :href="'/downloadAvaluo/'+ingresar.pdf">Avaluo</a>
                             <a class="dropdown-item" @click="$emit('abrirPDF', ingresar)">Estado de cuenta</a>
                             <a class="dropdown-item" target="_blank" :href="'/contratoCompraVenta/pdf/'+ ingresar.folio">Contrato de compra venta</a>
                             <a class="dropdown-item" target="_blank" :href="'/cartaServicios/pdf/'+ ingresar.folio">Carta de servicios</a>
                             <a class="dropdown-item" target="_blank" :href="'/serviciosTelecom/pdf/'+ ingresar.folio">Servicios de telecomunición</a>
                             <a class="dropdown-item" :href="'/descargarReglamento/contrato/'+ ingresar.folio">Reglamento de la etapa</a>
                             <a class="dropdown-item" @click="$emit('catEspecificaciones', ingresar.folio)">Catalogo de especificaciones</a>
                             <a v-if="ingresar.foto_predial" class="dropdown-item" :href="'/downloadPredial/'+ ingresar.foto_predial">Predial</a>
                             <a v-if="ingresar.num_licencia" class="dropdown-item"  v-text="'Licencia: '+ingresar.num_licencia" :href="'/downloadLicencias/'+ingresar.foto_lic"></a>
                         </div>
                     </td>
                     <td class="td2" :style="{ color : ingresar.emp_constructora == 'Grupo Constructor Cumbres' ? '#2C36C2' : '#000000'}" v-text="ingresar.nombre_cliente"></td>
                     <td class="td2" v-text="ingresar.nombre_vendedor"></td>
                     <td class="td2" v-text="ingresar.proyecto"></td>
                     <td class="td2" v-text="ingresar.etapa"></td>
                     <td class="td2" v-text="ingresar.manzana"></td>
                     <td class="td2" >
                         <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                             {{ingresar.num_lote}} {{ ingresar.sublote ? ingresar.sublote : ''}}
                         </a>
                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                             <a v-if ="ingresar.foto_predial" class="dropdown-item" :href="'/downloadPredial/'+ingresar.foto_predial">Descargar predial</a>
                             <a v-if ="ingresar.foto_lic" class="dropdown-item" :href="'/downloadLicencias/'+ingresar.foto_lic">Descargar licencia</a>
                             <a v-if ="ingresar.foto_acta" class="dropdown-item" :href="'/downloadActa/'+ingresar.foto_acta">Descargar Acta de termino</a>
                         </div>
                     </td>
                     <td class="td2" v-text="ingresar.modelo"></td>
                     <td class="td2" v-text="`${ingresar.calle} ${ingresar.numero} ${ingresar.interior ? ingresar.interior : ''}`"></td>
                     <td class="td2" v-text="ingresar.avance_lote+ '%'"></td>
                     <td class="td2" v-text="ingresar.fecha_status"></td>

                     <td class="td2">
                         <span v-if="ingresar.avaluo_preventivo!='0000-01-01'"
                             v-text="'$'+ $root.formatNumber(ingresar.resultado)"></span>
                         <label v-else>No aplica</label>
                     </td>

                     <td @dblclick="$emit('abrirModal',{ opcion: 'fecha_recibido', data: ingresar})"
                         v-if="ingresar.aviso_prev!='0000-01-01' && !ingresar.aviso_prev_venc && ingresar.aviso_prev!=null" class="td2" title="Doble click">
                         <a href="#" v-text="'Fecha solicitud: ' + this.moment(ingresar.aviso_prev).locale('es').format('DD/MMM/YYYY')"></a>
                     </td>

                     <td @dblclick="$emit('abrirModal', { opcion: 'fecha_recibido', data: ingresar })" v-else-if="ingresar.aviso_prev!='0000-01-01' && ingresar.aviso_prev_venc" class="td2">

                         <span v-if = "ingresar.diferencia > 0" class="badge badge-danger" v-text="'Fecha vencimiento: '
                         + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                         <span v-if = "ingresar.diferencia < 0 && ingresar.diferencia >= -15 " class="badge badge-warning" v-text="'Fecha vencimiento: '
                         + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                         <span v-if = "ingresar.diferencia < -15 " class="badge badge-success" v-text="'Fecha vencimiento: '
                         + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                     </td>

                     <td v-else-if="ingresar.aviso_prev=='0000-01-01' || ingresar.aviso_prev==null" class="td2" v-text="'No aplica'"></td>
                     <td>
                         <Button v-if="ingresar.detenido == 0" @click="$emit('abrirModal', {opcion:'ingresar', data: ingresar})"
                             title="Ingresar" icon="fa fa-send-o" size="btn-sm">
                         </Button>
                         <label v-else> DETENIDO </label>
                     </td>
                     <td class="td2" v-text="ingresar.tipo_credito"></td>
                     <td class="td2" v-text="ingresar.institucion"></td>
                     <td class="td2" v-text="'$'+ $root.formatNumber(ingresar.precio_venta)"></td>
                     <td class="td2" v-text="ingresar.credito_puente"></td>
                     <td class="td2" v-text="this.moment(ingresar.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                     <td class="td2" v-text="'$'+ $root.formatNumber(ingresar.saldo)"></td>
                     <td class="td2">
                         <Button title="Ver todas las observaciones" btnClass="btn-info"
                             @click="$emit('verObs', ingresar.folio)">
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
        arrayPorIngresar: {type: Array}
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
        }
    },
    methods: {

    },
}

</script>

<style>
</style>
