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
                        <i class="fa fa-align-justify"></i> Reporte Recepción Digital  &nbsp;&nbsp;
                    </div>
                    <div class="card-body">
                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" v-text="'Fecha de alta'"></a>
                            </li>
                        </ul>

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
                                    <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>


                        <!-- Tabla Resumen -->
                        <TableComponent :cabecera="[
                            'Leads', 'En Seguimiento',
                            'Potenciales', 'Env. Prospectos', 'Descartados', 'Hibernando'
                        ]">
                            <template v-slot:tbody>
                                <tr>
                                    <td><a href="#" @click="mostrarDetalle('total')">{{ leads.leads }}</a></td>
                                    <td><a href="#" @click="mostrarDetalle('seguimiento')">{{ leads.seguimiento }}</a></td>
                                    <td><a href="#" @click="mostrarDetalle('potenciales')">{{ leads.potenciales }}</a></td>
                                    <td><a href="#" @click="mostrarDetalle('prospectos')">{{ leads.env_prosp }}</a></td>
                                    <td><a href="#" @click="mostrarDetalle('descartados')">{{ leads.descartados }}</a></td>
                                    <td><a href="#" @click="mostrarDetalle('hibernando')">{{ leads.hibernando }}</a></td>
                                </tr>
                            </template>
                        </TableComponent>

                        <hr>

                        <h6 style="text-align: center; padding-bottom: 5px;"> Semaforo de Atención Digital</h6>

                        <!-- Tabla Resumen -->
                        <TableComponent :cabecera="[
                            '< 7 dias', '< 15 dias', '+ 16 dias', '', 'Auditoria'
                        ]">
                            <template v-slot:tbody>
                                <tr>
                                    <td class="table-success"><a href="#" @click="mostrarDetalle('verde')">{{ leads.verde }}</a></td>
                                    <td class="table-warning"><a href="#" @click="mostrarDetalle('amarillo')">{{ leads.amarillo }}</a></td>
                                    <td class="table-danger"><a href="#" @click="mostrarDetalle('rojo')">{{ leads.rojo }}</a></td>
                                    <td></td>
                                    <td><a href="#" @click="mostrarDetalle('auditoria')">{{ leads.auditoria }}</a></td>
                                </tr>
                            </template>
                        </TableComponent>

                        <template v-if="listado.mostrar">
                            <hr>
                            <h5 style="text-align: center; padding-bottom: 10px;">{{ listado.titulo }}</h5>

                            <TableComponent
                                :cabecera="['Lead', 'Campaña', 'Proyecto', 'Fecha de alta']"
                            >
                                <template v-slot:tbody>
                                    <tr v-for="lead in listado.data.data" :key="lead.id">
                                        <td>{{ lead.nombre }} {{ lead.apellidos }}</td>
                                        <td>{{(lead.nombre_campania) ? lead.nombre_campania : 'Organico'}}</td>
                                        <td>{{ lead.proyecto }}</td>
                                        <td>{{ lead.created_at }}</td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <div style="display:flex; flex-direction: column; align-items: center;">
                                <NavComponent
                                    :current="listado.data.current_page"
                                    :last="listado.data.last_page"
                                    @changePage="getData"
                                />
                            </div>

                        </template>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import NavComponent from '../Componentes/NavComponent.vue';
    import TableComponent from '../Componentes/TableComponent.vue';
    export default {
        components:{
            TableComponent,
            NavComponent
        },
        data(){
            return{
                leads : [],
                b_fecha1:'',
                b_fecha2:'',
                opcion: '',
                listado: {
                    mostrar: false,
                    titulo: "",
                    data: []
                },
            }
        },
        computed:{
        },
        methods : {
            async listarReporte(){
                let me = this;
                try {
                    const url = `reportes/reporteRecepcionDigital?fecha1=${me.b_fecha1}&fecha2=${me.b_fecha2}`
                    const response = await axios.get(url)
                    if(response)
                        me.leads = response.data;

                } catch(error){
                }
            },
            async getData(page){
                let me = this;
                me.listado.data = [];
                try {
                    const url = `reportes/getDataReporte?page=${page}&fecha1=${me.b_fecha1}&fecha2=${me.b_fecha2}&opcion=${me.opcion}`
                    const response = await axios.get(url)
                    if(response)
                        me.listado.data = response.data;

                } catch(error){
                }
            },
            async mostrarDetalle(opcion){
                let me = this;
                me.opcion = opcion

                switch(opcion){
                    case 'total':{
                        me.listado.titulo = 'Leads Totales';
                        break;
                    }
                    case 'seguimiento':{
                        me.listado.titulo = 'Leads en seguimiento';
                        break;
                    }
                    case 'potenciales':{
                        me.listado.titulo = 'Leads potenciales';
                        break;
                    }
                    case 'prospectos':{
                        me.listado.titulo = 'Leads enviados a Prospectos';
                        break;
                    }
                    case 'descartados':{
                        me.listado.titulo = 'Leads descartados';
                        break;
                    }
                    case 'hibernando':{
                        me.listado.titulo = 'Leads hibernando';
                        break;
                    }
                    case 'auditoria':{
                        me.listado.titulo = 'Leads Auditados';
                        break;
                    }
                    default:{
                        me.listado.titulo = `Leads en ${opcion}`
                        break;
                    }
                }
                await me.getData(1)
                me.listado.mostrar = true;
            }
        },
        mounted() {
            this.listarReporte();
        }
    }
</script>
<style scoped>
    td {
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
        text-align: center;
    }
</style>
