<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Campañas publicitarias
                    <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('registrar')">
                        Nuevo
                    </Button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarCampania(1)" class="form-control" placeholder="Texto a buscar">
                                <Button   :icon="'fa fa-search'" @click="listarCampania(1)">
                                    Buscar
                                </Button>
                            </div>
                        </div>
                    </div>
                    <TableComponent :cabecera="['Opciones','Campaña','Medio Digital','Inicio','Termino','Presupuesto']">
                        <template v-slot:tbody>
                            <tr v-for="campania in arrayCampanias.data" :key="campania.id">
                                <td class="td2" style="width:15%">
                                    <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'" title="Editar"
                                        @click="abrirModal('actualizar',campania)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'" title="Eliminar"
                                        @click="eliminarCampania(campania)"
                                    ></Button>
                                </td>
                                <td class="td2" v-text="campania.nombre_campania"></td>
                                <td class="td2" v-text="campania.medio_digital"></td>
                                <td class="td2" v-text="this.moment(campania.fecha_ini).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2" v-text="this.moment(campania.fecha_fin).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2" v-text="'$'+$root.formatNumber(campania.presupuesto)"></td>
                            </tr>
                        </template>
                    </TableComponent>
                    <Nav :current="arrayCampanias.current_page"
                        :last="arrayCampanias.last_page"
                        @changePage="listarCampania"
                    ></Nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal agregar/actualizar-->
        <ModalComponent v-if="modal"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <RowModal :clsRow1="'col-md-9'" :label1="'Nombre de la campaña'">
                    <input type="text" id="nombre" v-model="nombre" class="form-control" placeholder="Campaña">
                </RowModal>
                <RowModal :label1="'Presupuesto'" :clsRow2="'col-md-4'">
                    <input type="text" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                id="presupuesto"
                                v-model="presupuesto" class="form-control" placeholder="Presupuesto">
                    <template v-slot:input2>
                        <p class="form-control"> ${{ $root.formatNumber(presupuesto) }}</p>
                    </template>
                </RowModal>
                <RowModal :clsRow1="'col-md-3'" :label1="'Fecha de inicio'" :clsRow2="'col-md-3'" :label2="'Fin'">
                    <input type="date" v-model="fecha_ini" class="form-control" placeholder="Fecha de inicio">
                    <template v-slot:input2>
                        <input type="date" v-model="fecha_fin" class="form-control" placeholder="Fecha de finalización">
                    </template>
                </RowModal>

                <template v-if="tipoAccion==1">
                    <RowModal :clsRow1="'col-md-6'" :label1="'Medio publicitario'" :clsRow2="'col-md-3'">
                        <input type="text" name="city" list="cityname" class="form-control" v-model="medio_digital" @keyup.enter="addMedio(medio_digital)" placeholder="Medio de publicidad">
                        <datalist id="cityname">
                            <option value="">Seleccione</option>
                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.nombre" v-text="medios.nombre"></option>
                        </datalist>
                        <template v-slot:input2>
                            <Button :btnClass="'btn-success'" :icon="'icon-plus'" title="Añadir"
                                @click="addMedio(medio_digital)"
                            ></Button>
                        </template>
                    </RowModal>

                    <div>
                        <div class="modal-header" v-if="medios">
                            <h5 class="modal-title"> Medios elegidos </h5>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <tbody>
                                <tr v-for="(medioD,index) in medios" :key="medioD">
                                    <td style="width:25%">
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'"
                                            @click="quitarMedio(index)" title="Quitar medio publicitario"
                                        ></Button>
                                    </td>
                                    <td v-text="medioD" ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>

                <template v-if="tipoAccion==2">
                    <RowModal :clsRow1="'col-md-6'" :label1="'Medio publicitario'">
                        <input type="text" name="city" list="cityname" class="form-control" v-model="medio_digital" laceholder="Medio de publicidad">
                        <datalist id="cityname">
                            <option value="">Seleccione</option>
                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.nombre" v-text="medios.nombre"></option>
                        </datalist>
                    </RowModal>
                </template>

                <div v-show="errorCampania" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error"></div>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <Button   v-if="tipoAccion==1" :icon="'icon-check'" @click="registrarCampania()">Guardar</Button>
                <Button   v-if="tipoAccion==2" :icon="'icon-check'" @click="actualizarCampania()">Guardar cambios</Button>
            </template>
        </ModalComponent>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue'
    import TableComponent from '../Componentes/TableComponent.vue'
    import Button from '../Componentes/ButtonComponent.vue'
    import Nav from '../Componentes/NavComponent.vue'
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent,
            Button, Nav, RowModal
        },
        data (){
            return {
                proceso:false,
                id: 0,
                nombre : '',
                medio_digital:'',
                fecha_ini:'',
                fecha_fin:'',
                presupuesto:0,
                arrayCampanias : [],
                medios:[],
                arrayMediosPublicidad:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCampania : 0,
                errorMostrarMsj : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{

        },
        methods : {
            listarCampania (page){
                axios.get('/campanias/index'+
                    '?buscar='+this.buscar+
                    '&page='+page

                ).then(
                    response => this.arrayCampanias = response.data
                ).catch(error => console.log(error));
            },

            addMedio(medio){
                this.medios.push(medio);
            },
            quitarMedio(index){
                this.medios.splice(index,1);
            },
            /**Metodo para registrar  */
            registrarCampania(){
                if(this.validarMedio() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/campanias/store',{
                    'nombre': this.nombre,
                    'medio_digital':this.medios,
                    'fecha_ini':this.fecha_ini,
                    'fecha_fin':this.fecha_fin,
                    'presupuesto':this.presupuesto
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarCampania(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Campaña guardada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            selectMedioPublicidad(){
                let me = this;
                me.arrayMediosPublicidad=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCampania(){


                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/campanias/update',{
                    'nombre': this.nombre,
                    'medio_digital':this.medio_digital,
                    'fecha_ini':this.fecha_ini,
                    'fecha_fin':this.fecha_fin,
                    'presupuesto':this.presupuesto,
                    'id' : this.id
                }).then(function (response){

                    me.cerrarModal();
                    me.listarCampania(1); //se enlistan nuevamente los registros
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);

                });
            },
            eliminarCampania(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
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

                axios.delete('/campanias/delete',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Campaña eliminada correctamente.',
                        'success'
                        )
                        me.listarCampania(1); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarMedio(){
                this.errorCampania=0;
                this.errorMostrarMsj=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("El nombre de la campaña no puede ir vacio.");

                if(this.medios.length == 0) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("Elegir un medio digital.");

                if(!this.fecha_ini) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("Registrar la fecha de inico de la campaña.");

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("Registrar la fecha de finalizacion de la campaña.");

                if(this.errorMostrarMsj.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCampania = 1;

                return this.errorCampania;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.errorCampania = 0;
                this.errorMostrarMsj = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.selectMedioPublicidad();
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Campaña';
                        this.fecha_ini ='';
                        this.fecha_fin ='';
                        this.nombre = '';
                        this.medio_digital = '';
                        this.tipoAccion = 1;
                        this.presupuesto = 0;
                        this.medios = [];
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Campaña';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.fecha_ini =data['fecha_ini'];
                        this.fecha_fin =data['fecha_fin'];
                        this.nombre = data['nombre_campania'];
                        this.medio_digital = data['medio_digital'];
                        this.presupuesto = data['presupuesto'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarCampania(1);
        }
    }
</script>
<style>
    .div-error{
        display: flex;
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
