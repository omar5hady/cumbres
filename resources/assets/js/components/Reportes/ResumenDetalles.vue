<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Reporte de Detalles
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" @click="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_etapa" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_contratista" >
                                        <option value="">Contratista</option>
                                        <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" @click="listarResumen(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="mostrar == 1">
                            <div class="col-md-4">
                                <div class="input-group">
                                   <table class="table table-bordered table-striped table-sm">
                                       <tr v-for="detalle in arrayDetalles" :key="detalle.id">
                                           <template v-if="detalle.cont != 0">
                                                <th v-text="detalle.detalles"></th>
                                                <th v-text="detalle.cont"></th>
                                           </template>
                                       </tr>
                                       
                                   </table>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-4">
                                <div class="input-group">
                                   <table class="table table-bordered table-striped table-sm">
                                       <tr v-for="contratista in arrayContratistaDet" :key="contratista.id">
                                           <template v-if="contratista.cont != 0">
                                                <th v-text="contratista.nombre"></th>
                                                <th v-text="contratista.cont"></th>
                                           </template>
                                           
                                       </tr>
                                   </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="mostrar == 1">
                            <div class="col-md-3">
                                <a class="btn btn-success" v-bind:href="'/reportes/reporteDetallesExcel?proyecto='+ b_proyecto + '&etapa='+ b_etapa + '&contratista='+ b_contratista">
                                    <i class="fa fa-file-text"></i>&nbsp; Descargar excel
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive" v-if="mostrar == 1">
                        <table class="table2 table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Cliente</th>
                                    <th>Fecha de solicitud</th>
                                    <th>Descripción del detalle</th>
                                    <th>Contratista</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contrato in arrayResProyecto" :key="contrato.id">
                                    <td class="td2" v-text="contrato.proyecto"></td>
                                    <td class="td2" v-text="contrato.num_etapa"></td>
                                    <td class="td2" v-text="contrato.manzana"></td>
                                    <td class="td2" v-text="contrato.num_lote"></td>
                                    <td class="td2" v-text="contrato.cliente"></td>
                                    <td class="td2" v-text="this.moment(contrato.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                    <td class="td2" v-text="contrato.detalles + '/ '+ contrato.detalle"></td>
                                    <td class="td2" v-text="contrato.nombre"></td>
                                    
                                </tr>                                
                            </tbody>
                        </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

           


        </main>
</template>

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data (){
            return {
                proceso:false,
                arrayResProyecto : [],
                arrayFraccionamientos: [],
                arrayAllEtapas:[],
                arrayDetalles:[],
                arrayContratistaDet:[],
                arrayContratistas:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                b_proyecto : '',
                b_etapa : '',
                b_contratista:'',
                precio_venta:0,
                enganche:0,
                directo:0,
                credito:0,
                saldo:0,
                monto_cobrado:0,
                lotes:0,
                disponibles:0,
                vendidas:0,
                individualizadas:0,
                habilitados:0,
                mostrar : 0,
                fecha_inicio:'',
                excedente:0,
                modal:0,
                comentario:'',
                id:'',
                tipoAccion : 0,
                titulo : '',
                bAudit: 0,
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarResumen (page){
                let me=this;
                var url= '/reportes/reporteDetalles?page=' + page + '&proyecto='+ me.b_proyecto + '&etapa='+ me.b_etapa +
                        '&contratista='+this.b_contratista;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayResProyecto = respuesta.resumen.data;
                    me.arrayDetalles = respuesta.detalles;
                    me.arrayContratistaDet = respuesta.contratistas;
                    me.pagination= respuesta.pagination;

                    me.arrayDetalles.sort((b, a) => a.cont - b.cont);
                    me.arrayContratistaDet.sort((b, a) => a.cont - b.cont);
                   
                    me.mostrar = 1;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
            selectContratistas(){
                let me = this;
                me.arrayContratistas = [];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/auditar/registrarObservacion',{
                    'id': this.id,
                    'comentario': this.comentario
                }).then(function (response){
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            selectEtapas(buscar){
                let me = this;
                
                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            cambiarPagina(page){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarResumen(page);
            }
        },
        mounted() {
            this.selectFraccionamientos();
            this.selectContratistas();
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
        display: flex;
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
