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
                        <i class="fa fa-align-justify"></i> Asignación de servicios
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                        <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                        <option value="f_ini">Fecha de inicio</option>
                                        <option value="f_fin">Fecha de termino</option>
                                    </select>
                                    <input type="date" v-if="criterio=='f_ini'" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control col-md-6" placeholder="fecha inicio" >
                                    <input type="date" v-if="criterio=='f_ini'" v-model="buscar2"  @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control col-md-6" placeholder="fecha fin" >
                                    <input type="date" v-if="criterio=='f_fin'" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="fecha inicio" >
                                    <input type="date" v-if="criterio=='f_fin'" v-model="buscar2"  @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="fecha fin" >
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'"  v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <Button :btnClass="'btn-primary'" :icon="'fa fa-search'"
                                        @click="listarEtapa(1,buscar,buscar2,criterio)">
                                        Buscar
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Fraccionamiento','Etapa','Fecha de inicio','Fecha de termino']">
                            <template v-slot:tbody>
                                <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                    <td class="td2" style="width:10%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                            @click="abrirModal('etapa','actualizar',etapa)" title="Editar"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="etapa.fraccionamiento"></td>
                                    <td class="td2" v-text="etapa.num_etapa"></td>
                                    <td class="td2" v-text="etapa.f_ini"></td>
                                    <td class="td2" v-text="etapa.f_fin"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="pagination.current_page" :last="pagination.last_page"
                            @changePage="cambiarPagina"
                        ></Nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent  v-if="modal"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :label1="'Número de etapa'">
                        <input type="text" readonly v-model="num_etapa" class="form-control" placeholder="# de etapa">
                    </RowModal>
                    <RowModal :clsRow1="'col-md-8'" :label1="'Servicios'">
                        <select class="form-control" v-model="servicio_id" >
                            <option value="0">Seleccione</option>
                            <option v-for="servicio in arrayServicios" :key="servicio.id" :value="servicio.id" v-text="servicio.descripcion"></option>
                        </select>
                    </RowModal>

                    <div class="modal-body" v-if="arrayServicioEtapa.length">
                        <TableComponent :cabecera="['','Servicio']">
                            <template v-slot:tbody>
                                <tr v-for="servicio in arrayServicioEtapa" :key="servicio.id">
                                    <td class="td2" >
                                        <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'"
                                            @click="eliminarServicio(servicio.id)" title="Eliminar servicio"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="servicio.descripcion" ></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button :btnClass="'btn-primary'" @click="registrarServEtapa()" :disabled="servicio_id==0">Guardar</Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue'
    import TableComponent from '../Componentes/TableComponent.vue'
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
    import Nav from '../Componentes/NavComponent.vue'
    import Button from '../Componentes/ButtonComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent,
            RowModal,
            Nav,
            Button
        },
        data(){
            return{
                proceso : false,
                id:0,
                num_etapa : 0,
                servicio_id : 0,
                servEtapaId : 0,
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.nombre',
                buscar : '',
                buscar2: '',
                arrayFraccionamientos : [],
                arrayServicios : [],
                arrayServicioEtapa : [],
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
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarEtapa(page, buscar, buscar2, criterio){
                let me = this;
                var url = '/etapa?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapa = respuesta.etapas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectServEtapas(etapa_id){
                let me = this;
                me.arrayServicioEtapa=[];
                var url = '/servicios_etapas?etapa_id=' + etapa_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayServicioEtapa = respuesta.servicios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectServicios(){
                let me = this;
                me.arrayServicios=[];
                var url = '/select_servicios';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayServicios = respuesta.servicios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEtapa(page,me.buscar,me.buscar2,me.criterio);
            },
            /**Metodo para registrar  */
            registrarServEtapa(){
                if(this.proceso==true){
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/servicio_etapa/registrar',{
                    'etapa_id': this.id,
                    'servicio_id': this.servicio_id,
                }).then(function (response){
                    me.proceso=false;
                    me.servicio_id=0;
                    me. selectServEtapas(me.id); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Servicio asignado agregada correctamente',
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
                me.buscar2="";
            },
            eliminarServicio(id){
                this.servEtapaId = id;
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

                axios.delete('/servicio_etapa/eliminar',
                        {params: {'id': this.servEtapaId}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Servicio borrado correctamente.',
                        'success'
                        )
                        me.selectServEtapas(me.id)
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.num_etapa = '';
                this.servicio_id=0;
                this.contador=0;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "etapa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Etapa';
                                this.fraccionamiento_id = '0';
                                this.num_etapa = this.contador;
                                // this.f_ini = '';
                                this.f_fin = '';
                                this.personal_id = '0';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.num_etapa=data['num_etapa'];
                                this.tituloModal='Servicios de la Etapa: '+this.num_etapa;
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                break;
                            }
                        }
                    }
                }
                this.selectServEtapas(this.id);
                this.selectServicios();
            }
        },
        mounted() {
            this.listarEtapa(1,this.buscar,this.buscar2,this.criterio);
        }
    }
</script>
<style>
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
