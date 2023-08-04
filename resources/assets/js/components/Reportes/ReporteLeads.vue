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
                        <i class="fa fa-align-justify"></i> Reporte Digital Leads  &nbsp;&nbsp;
                        <a :href="'/reportes/digitalLeads?fecha1=' +b_fecha1 + '&fecha2=' + b_fecha2 +
                                '&proyecto=' + b_proyecto + '&excel=1'"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ingresos-tab" data-toggle="tab" href="#ingresos" role="tab" aria-controls="ingresos" v-text="'Fecha de alta'"></a>
                            </li>
                        </ul>

                        <div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" v-model="b_fecha1" @keyup.enter="listarReporte()" class="form-control" >
                                        <input type="date" v-model="b_fecha2" @keyup.enter="listarReporte()" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_proyecto">
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id"
                                                :value="proyecto.id" v-text="proyecto.nombre">
                                            </option>
                                            <option value="0">Otro...</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <!-- Listado por ingresos -->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="6" class="text-center">REPORTE POR CAMPAÑA</th>
                                            </tr>
                                            <tr>
                                                <th> Campaña</th>
                                                <th> Fecha de campaña</th>
                                                <th> # Leads</th>
                                                <th> Descartados sin canalizar</th>
                                                <th> Canalizados a asesor</th>
                                                <th> Descartados por asesor</th>
                                                <th> Venta </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">Tráfico organico</td>
                                                <td v-text="camp_org"></td>
                                                <td v-text="cont_desc"></td>
                                                <td v-text="asesor_org"></td>
                                                <td v-text="desc_ase"></td>
                                                <td >
                                                    <a @click="verLeads(ventasOrg, 'venta')" href="#">{{n_ventasOrg}}</a>
                                                </td>
                                            </tr>
                                            <tr v-for="leads in arrayLeads" :key="leads.id">
                                                <template  v-if="leads.conteo > 0">
                                                    <td class="td2" v-text="leads.nombre_campania+' ('+leads.medio_digital+')'"></td>
                                                    <td class="td2"
                                                            v-text="this.moment(leads.fecha_ini).locale('es').format('DD/MMM/YYYY')
                                                            +' al '+this.moment(leads.fecha_fin).locale('es').format('DD/MMM/YYYY')">
                                                    </td>
                                                    <td class="td2" v-text="leads.conteo"></td>
                                                    <td class="td2" v-text="leads.descartado"></td>
                                                    <td class="td2" v-text="leads.asesor"></td>
                                                    <td class="td2" v-text="leads.descAsesor"></td>
                                                    <td >
                                                        <a @click="verLeads(leads.ventas, 'venta')" href="#">{{leads.n_ventas}}</a>
                                                    </td>
                                                </template>
                                            </tr>
                                            <!-- <tr>
                                                <td class="td2" colspan="2">Total:</td>
                                                <td class="td2" v-text="campanias.t_leads "></td>
                                                <td class="td2" v-text="campanias.t_descartados"></td>
                                                <td class="td2" v-text="campanias.t_canalizados"></td>
                                                <td class="td2" v-text="campanias.t_desc_asesor"></td>
                                                <td class="td2" v-text="campanias.t_venta"></td>
                                            </tr> -->

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <!-- Listado por ingresos -->

                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="7" class="text-center">REPORTE POR ASESOR</th>
                                            </tr>
                                            <tr>
                                                <th> Asesor</th>
                                                <th> # Leads asignados</th>
                                                <th> Descartados</th>
                                                <th></th>
                                                <th colspan="3"></th>
                                                <th> Removidos </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="asesor in arrayAsesores" :key="asesor.id">
                                                <td class="td2" v-text="asesor.nombre+' '+asesor.apellidos"></td>
                                                <td class="td2" v-text="asesor.conteo"></td>
                                                <td class="td2" v-text="asesor.descartados"></td>
                                                <td></td>
                                                <td class="table-success">
                                                    <a @click="verLeads(asesor.verde,'Verde')" href="#">{{asesor.nVerde}}</a>
                                                </td>
                                                <td class="table-warning">
                                                    <a @click="verLeads(asesor.amarillo,'Amarillo')" href="#">{{asesor.nAmarillo}}</a>
                                                </td>
                                                <td class="table-danger">
                                                    <a @click="verLeads(asesor.rojo, 'Rojo')" href="#">{{asesor.nRojo}}</a>
                                                </td>
                                                <td class="td2">
                                                    <a @click="verLeads(asesor.removidos, 'Removidos')" href="#">{{asesor.n_removidos}}</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-2">
                            </div>

                        </div>

                        <div class="tab-content" id="myTab1Content">
                            <!-- Listado por ingresos -->
                            <div class="tab-pane fade active show" id="ingresos" role="tabpanel" aria-labelledby="ingresos-tab">


                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <ModalComponent v-if="modal"
                @closeModal="modal=0"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <TableComponent v-if="modal == 1" :cabecera="['Lead','Campaña','Proyecto']">
                                <template v-slot:tbody>
                                    <tr v-for="c in leads" :key="c.id">
                                        <td class="td2" v-text="c.nombre+' '+c.apellidos"></td>
                                        <td>{{(c.nombre_campania) ? c.nombre_campania : 'Organico'}}</td>
                                        <td class="td2" v-text="c.proyecto"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <TableComponent v-if="modal == 2" :cabecera="['Lead','Fecha removido','FIn de castigo']">
                                <template v-slot:tbody>
                                    <tr v-for="c in leads" :key="c.id">
                                        <td class="td2" v-text="c.l_nombre+' '+c.l_apellidos"></td>
                                        <td>{{c.f_ini}}</td>
                                        <td class="td2" v-text="c.f_fin"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <TableComponent v-if="modal == 3" :cabecera="['Cliente','Fraccionamiento',
                                'Etapa','Manzana','Lote','Precio','Fecha']">
                                <template v-slot:tbody>
                                    <tr v-for="c in leads" :key="c.id">
                                        <td class="td2" v-text="c.nombre+' '+c.apellidos"></td>
                                        <td class="td2" v-text="c.fraccionamiento"></td>
                                        <td class="td2" v-text="c.etapa"></td>
                                        <td class="td2" v-text="c.manzana"></td>
                                        <td class="td2">
                                            {{c.num_lote}} {{c.sublote? c.sublote : ''}}
                                        </td>
                                        <td class="td2" v-text="'$'+$root.formatNumber(c.precio_venta)"></td>
                                        <td class="td2" v-text="c.fecha"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </div>
                </template>
            </ModalComponent>

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import TableComponent from '../Componentes/TableComponent.vue';
    export default {
        components:{
            ModalComponent,
            TableComponent
        },
        data(){
            return{

                arrayLeads : [],
                arrayFraccionamientos : [],
                arrayAsesores : [],
                arrayVendedores : [],
                camp_org:0,
                asesor_org:0,
                cont_desc:0,
                desc_ase:0,
                b_fecha1:'',
                b_fecha2:'',
                b_proyecto:'',
                emp_constructora:'',
                modal: 0,
                tituloModal: '',
                leads:[],
                ventasOrg : [],
                n_ventasOrg : 0,
                campanias:{
                    t_leads : 0,
                    t_descartados: 0,
                    t_canalizados: 0,
                    t_desc_asesor: 0,
                    t_venta: 0
                },
                asesor:{
                    t_leads: 0,
                    t_descartados: 0,
                    t_verde: 0,
                    t_rojo: 0,
                    t_removidos: 0
                }

            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                me.arrayLeads = [];
                var url = '/reportes/digitalLeads?fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 +
                    '&proyecto=' + me.b_proyecto + '&excel=0';

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLeads = respuesta.campanias;
                    me.asesor_org = respuesta.asesor_org;
                    me.camp_org = respuesta.camp_org;
                    me.arrayAsesores = respuesta.asesores;
                    me.desc_ase = respuesta.desc_ase;
                    me.cont_desc = respuesta.cont_desc;
                    me.ventasOrg = respuesta.ventasOrg;
                    me.n_ventasOrg = respuesta.n_ventasOrg;

                    me.arrayLeads.sort((b, a) => a.conteo - b.conteo);
                    me.arrayAsesores.sort((b, a) => a.conteo - b.conteo);
                    //me.arrayVendedores.sort((b, a) => a.dif15 - b.dif15);

                    me.setTotales();

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            setTotales(){
                let me = this;
                me.resetTotales();

                me.arrayLeads.forEach(e => {
                    me.campanias.t_leads+= e.conteo;
                    me.campanias.t_descartados+= e.descartado;
                    me.campanias.t_canalizados+= e.asesor;
                    me.campanias.t_desc_asesor+= e.descAsesor;
                    me.campanias.t_venta+= e.n_ventas;
                });

                me.arrayAsesores.forEach(e => {
                    me.asesor.t_leads+= e.conteo;
                    me.asesor.t_descartados+= e.descartado;
                    me.asesor.t_verde+= e.amarillo;
                    me.asesor.t_rojo+= e.rojo;
                    me.asesor.t_removidos+= e.removidos;
                });

            },
            resetTotales(){
                let me = this;

                me.campanias = {
                    t_leads : 0,
                    t_descartados: 0,
                    t_canalizados: 0,
                    t_desc_asesor: 0,
                    t_venta: 0
                };
                me.asesor = {
                    t_leads: 0,
                    t_descartados: 0,
                    t_verde: 0,
                    t_rojo: 0,
                    t_removidos: 0
                };
            },

            selectFraccionamientos(){
                let me = this;
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

            verLeads(data,tipo){
                switch(tipo){
                    case 'venta':
                        this.modal = 3
                        this.tituloModal = 'Ventas'
                        break;
                    case 'Removidos':
                        this.modal = 2
                        this.tituloModal = 'Leads Removidos'
                    break;
                    default:
                        this.modal = 1;
                        this.tituloModal = 'Leads en '+tipo;
                    break
                }
                this.leads = data;
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

        },
        mounted() {

            this.selectFraccionamientos();
            this.listarReporte();
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
