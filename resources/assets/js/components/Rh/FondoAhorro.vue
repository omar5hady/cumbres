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
                    <i class="fa fa-align-justify"></i> Fondo de ahorro
                    <div class="button-header">
                        <button v-if="(adminMant == 'shady' || adminMant == 'marce.gaytan') && busqueda.user_id"
                        type="button" class="btn btn-success btn-sm"
                            @click="nuevoMovimiento()"
                        >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                </div>

                <div class="info-center" v-if="loading">
                    <LoadingComponentVue></LoadingComponentVue>
                </div>
                
                
                <div class="card-body" v-else>
                    <div class="info-center" style="margin-top:50px; margin-bottom:50px;" v-if="mostrar == 0">
                        <center><h4>Aun no se ha cargado información para este usuario</h4></center>
                    </div>
                    <div class="row" v-else> 
                        <div class="col-md-6">
                            <div class="card card-user">
                                <!---->
                                <div class="card-body">
                                    <div>
                                        <div class="author">
                                            <img
                                                :src="`/img/avatars/${datosAhorro.usuario.foto}`"
                                                class="avatar border-white"
                                            />
                                            <h4 class="title">
                                                <font style="vertical-align: inherit;">
                                                    <font v-text="datosAhorro.usuario.nombre"
                                                        style="vertical-align: inherit;"
                                                    >
                                                    </font>
                                                </font>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <!---->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;"
                                                >Saldo actual: </font
                                            >
                                        </font>
                                    </h4>
                                    <!---->
                                </div>
                                <div class="card-body" style="padding-bottom: 0px;">
                                    <div>
                                        <h5 style="color:#198754">$ {{$root.formatNumber(datosAhorro.saldo)}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6" v-if="(adminMant == 'shady' || adminMant == 'marce.gaytan')">
                            <div class="form-group row">
                                <input v-if="vista==2" disabled type="text" v-model="busqueda.nombre" class="form-control col-md-6">
                                <button v-if="vista == 2" class="form-control btn btn-sm btn-primary col-md-3" @click="vista = 1, busqueda.user_id = ''">Cambiar</button>
                                <input v-if="vista==1" type="text" name="user" list="usersName" @keyup="selectUsuario(busqueda.user_id)" @change="getNombre(busqueda.user_id)"  class="form-control col-md-8" v-model="busqueda.user_id">
                                <datalist v-if="vista==1" id="usersName">
                                    <option value="">Seleccione</option>
                                    <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                                </datalist>
                                <!-- <input
                                    type="text"
                                    v-model="b_solicitante"
                                    @keyup.enter="getFondo(1)"
                                    class="form-control"
                                    placeholder="Nombre a buscar"
                                /> -->
                                 
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="mostrar == 1">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <input
                                    type="text"
                                    class="form-control col-md-3 col-sm-12"
                                    disabled
                                    placeholder="Fecha: "
                                />
                                <input
                                    type="date"
                                    v-model="busqueda.fechaIni"
                                    @keyup.enter="getFondo(1)"
                                    class="form-control col-md-4 col-sm-12"
                                />
                                <input
                                    type="date"
                                    v-model="busqueda.fechaFin"
                                    @keyup.enter="getFondo(1)"
                                    class="form-control col-md-4 col-sm-12"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive"  v-if="mostrar == 1">
                        <table
                            class="table2 table-bordered table-striped table-sm"
                        >
                            <thead>
                                <tr>
                                    <th v-if="(adminMant == 'shady' || adminMant == 'marce.gaytan')">
                                        
                                    </th>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pago in pagos.data" :key="pago.id">
                                    <td class="td2" v-if="(adminMant == 'shady' || adminMant == 'marce.gaytan')">
                                        <template v-if="pago.status == 0">
                                             <button type="button" class="btn btn-danger btn-sm"
                                             @click="deletePago(pago)"
                                             >
                                                <i class="icon-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                @click="editarPago(pago)"
                                            >
                                                <i class="icon-pencil"></i>
                                            </button>
                                        </template>
                                       
                                    </td>
                                    <td
                                        class="td2"
                                        v-text="pago.fecha_movimiento"
                                    ></td>
                                    <td class="td2" v-text="pago.concepto"></td>
                                    <td class="td2" :style="(pago.status == 0 && pago.tipo_movimiento == 0) ? 'color:green;' : 
                                        (pago.tipo_movimiento == 1 && pago.status == 1) ? 'color:red;' : ''
                                    " 
                                        v-text="`$ ${$root.formatNumber(pago.monto)}`">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav v-if="mostrar == 1">
                        <!--Botones de paginacion -->
                        <!--Botones de paginacion -->
                        <ul class="pagination">
                            <li
                                class="page-item"
                                v-if="pagos.current_page > 5"
                                @click="getFondo(1)"
                            >
                                <a class="page-link" href="#">Inicio</a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.prev_page_url"
                                @click="getFondo(pagos.current_page - 1)"
                            >
                                <a class="page-link" href="#">Ant</a>
                            </li>

                            <li
                                class="page-item"
                                v-if="pagos.current_page - 3 >= 1"
                                @click="getFondo(pagos.current_page - 3)"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    v-text="pagos.current_page - 3"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.current_page - 2 >= 1"
                                @click="getFondo(pagos.current_page - 2)"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    v-text="pagos.current_page - 2"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.current_page - 1 >= 1"
                                @click="getFondo(pagos.current_page - 1)"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    v-text="pagos.current_page - 1"
                                ></a>
                            </li>
                            <li class="page-item active">
                                <a
                                    class="page-link"
                                    href="#"
                                    v-text="pagos.current_page"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.current_page + 1 <= pagos.last_page"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click="getFondo(pagos.current_page + 1)"
                                    v-text="pagos.current_page + 1"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.current_page + 2 <= pagos.last_page"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click="getFondo(pagos.current_page + 2)"
                                    v-text="pagos.current_page + 2"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.current_page + 3 <= pagos.last_page"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click="getFondo(pagos.current_page + 3)"
                                    v-text="pagos.current_page + 3"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="pagos.next_page_url"
                                @click="getFondo(pagos.current_page + 1)"
                            >
                                <a class="page-link" href="#">Sig</a>
                            </li>
                            <li
                                class="page-item"
                                v-if="
                                    pagos.current_page < 5 &&
                                        pagos.last_page > 5
                                "
                                @click="getFondo(pagos.last_page)"
                            >
                                <a class="page-link" href="#">Ultimo</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal observaciones-->
        <ModalComponent v-if="modal.mostrar"
            :titulo="modal.titulo"
            @closeModal="closeModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Fecha de movimiento</label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="" v-model="datosPago.fecha_movimiento">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Tipo de movimiento</label>
                    <div class="col-md-5">
                        <select :disabled="modal.accion == 'update'"
                            @change="actMovimiento" v-model="datosPago.tipo_movimiento" class="form-control">
                            <option value="0">Ingreso</option>
                            <option value="1">Salida</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" v-if="datosPago.tipo_movimiento == 1">
                    <label class="col-md-3 form-control-label" for="text-input">Concepto</label>
                    <div class="col-md-8">
                        <input type="text" placeholder="Descripción movimiento" v-model="datosPago.concepto" class="form-control">
                    </div>
                </div>
                <div class="form-group row" v-if="datosPago.tipo_movimiento == 1">
                    <label class="col-md-3 form-control-label" for="text-input">Periodo por pagar</label>
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="" v-model="datosPago.fechaIni">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="" v-model="datosPago.fechaFin">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                    <div class="col-md-4">
                        <input type="text" placeholder="Monto" @keypress="$root.isNumber($event)" v-model="datosPago.monto" class="form-control">
                    </div>
                    <div class="col-md-4" v-if="datosPago.monto">
                        <label for="">$ {{$root.formatNumber(datosPago.monto)}}</label>
                    </div>
                </div>
            </template>

            <template v-slot:buttons-footer>
                <button v-if="modal.accion == 'nuevo'" type="button" class="btn btn-success" @click="save()">Guardar</button>
                <button v-if="modal.accion == 'update'" type="button" class="btn btn-primary" @click="update()">Actualizar</button>
            </template>
            
        </ModalComponent>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import LoadingComponentVue from '../Componentes/LoadingComponent.vue'
import ModalComponent from '../Componentes/ModalComponent.vue'

export default {
    components:{
        LoadingComponentVue,
        ModalComponent
    },
    props: {
        adminMant: { type: String },
        userId: { type: String }
    },
    data() {
        return {
            busqueda: {
                fechaIni: "",
                fechaFin: "",
                user_id: this.userId,
                nombre:''
            },
            datosAhorro: {},
            pagos: [],
            datosPago:{},
            arrayUsers: [],
            loading: false,
            vista:2,
            mostrar: 0,
            modal:{
                mostrar: 0,
                titulo:'',
                accion:'nuevo'
            }
        };
    },
    computed: {},
    methods: {
        getFondo(page) {
            let me = this;
            me.datosAhorro = {};
            me.pagos = [];
            me.loading = true

            const url =
                "/fondo-ahorro?page=" +
                page +
                "&fechaIni=" +
                me.busqueda.fechaIni +
                "&fechaFin=" +
                me.busqueda.fechaFin +
                "&user_id=" +
                me.busqueda.user_id;

            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.datosAhorro = respuesta.data[0];
                    me.mostrar = 0;
                    if(me.datosAhorro){
                        me.pagos = me.datosAhorro.pagos;
                        me.busqueda.nombre = me.datosAhorro.usuario.nombre;
                        me.mostrar = 1    
                    }
                    
                    me.loading = false
                })
                .catch(function(error) {
                    console.log(error);
                    me.datosAhorro = {};
                    me.pagos = [];
                    me.loading = false
                });
        },
        selectUsuario(buscar){
            let me = this;
            
            me.arrayUsers=[];
            const url = '/usuarios/selectUser?buscar=' + buscar;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.arrayUsers = respuesta.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        editarPago(pago){
            this.modal.mostrar = 1
            this.modal.titulo = 'Editar movimiento'
            this.modal.accion = 'update'
            this.datosPago = pago
        },
        nuevoMovimiento(){
            this.modal.mostrar = 1;
            this.modal.titulo = 'Nuevo movimiento'
            this.modal.accion = 'nuevo'
            this.datosPago = {}
        },
        actMovimiento(){
            this.datosPago.fechaIni = ''
            this.datosPago.fechaFin = ''
            this.datosPago.concepto = ''
        },
        closeModal(){
            this.modal.mostrar = 0
            this.modal.titulo = ''
            this.datosPago = {}
            this.getFondo()
        },
        getNombre(id){
            let me = this;
            const url = '/usuarios/getNombre?id=' + id;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.busqueda.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
                me.vista = 2
                me.getFondo(1)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        save(){
            let me = this;
            let fondo_id = ''
            if(me.datosAhorro){
                 fondo_id = me.datosAhorro.id
            }

            axios.post('/fondo-ahorro',{
                    'fondo_id': fondo_id,
                    'monto': me.datosPago.monto,
                    'tipo_movimiento': me.datosPago.tipo_movimiento,
                    'fecha_movimiento': me.datosPago.fecha_movimiento,
                    'concepto': me.datosPago.concepto,
                    'fechaIni': me.datosPago.fechaIni,
                    'fechaFin': me.datosPago.fechaFin,
                    'user_id': me.busqueda.user_id
                }).then(function (response){
                    me.closeModal()
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Movimiento guardado correctamente.'
                    })
                }).catch(function (error){
                    console.log(error);
                    me.closeModal();
                });
        },
        update(){
            let me = this;
            
            axios.put(`/fondo-ahorro/${me.datosPago.id}`,{
                    'fondo_id': me.datosPago.fondo_id,
                    'monto': me.datosPago.monto,
                    'fecha_movimiento': me.datosPago.fecha_movimiento,
                    'concepto': me.datosPago.concepto,
                    'id': me.datosPago.id
                }).then(function (response){
                    me.closeModal()
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Movimiento actualizado correctamente.'
                    })
                }).catch(function (error){
                    console.log(error);
                    me.closeModal();
                });
        },
        deletePago(pago){
            swal({
                title: '¿Estas seguro de eliminar este movimiento?',
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
                axios.delete(`/fondo-ahorro/${pago.id}`, {
                    params: {'id': pago.id, 'fondo_id': pago.fondo_id}
                }).then(function (response){
                        swal(
                        'Borrado!',
                        'Movimiento eliminado correctamente.',
                        'success'
                        )
                        me.getFondo(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
        }
    },
    mounted() {
        this.getFondo(1);
    }
};
</script>
<style scoped>
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
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
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);
}

.td2,
.th2 {
    border: solid rgb(200, 200, 200) 1px;
    padding: 0.5rem;
}

.td2 {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
}

.td2:first-of-type,
th:first-of-type {
    border-left: none;
}

.td2:last-of-type,
th:last-of-type {
    border-right: none;
}
.card .avatar {
    width: 100px;
    height: 100px;
    overflow: hidden;
    border-radius: 80%;
    margin-right: 5px;
}
.card-user .author {
    text-align: center;
    text-transform: none;
    margin-top: -65px;
}
.info-center{
    display: flex;
    justify-content: center;
    width: 100% !important;
    
}
.button-header{
    display: flex;
    justify-content: flex-end;
    margin-top: -20px;
}
</style>
