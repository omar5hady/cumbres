<template>
   <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header flex-md-column" >
                    <i class="fa fa-align-justify"></i>Donaciones
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control col-md-3" disabled placeholder="Item:">
                                <input type="text" @keyup.enter="index(1)"
                                    v-model="b_item" class="form-control" placeholder="Item a buscar">
                                <Button @click="index(1)" icon="fa fa-search"> Buscar</Button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container">
                    <LoadingComponent v-if="loading"></LoadingComponent>
                    <template v-else>


                        <ul class="nav nav-tabs" id="myTab1" role="tablist" v-if="items">
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = ITEM_STATUS.ACTIVO, b_finalizado = '', b_myitems = '',
                                    index(1)"
                                v-bind:class="{ 'text-primary active': b_status === ITEM_STATUS.ACTIVO}"
                                role="tab">{{ (b_status === ITEM_STATUS.ACTIVO)
                                    ? `Disponibles: ${items.meta.total ? items.meta.total : 0}` : 'Disponibles' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_finalizado = 1, b_status = '', b_myitems = '',
                                    index(1)"
                                v-bind:class="{ 'text-primary active': b_finalizado == 1}"
                                role="tab">{{ (b_finalizado == 1)
                                    ? `Finalizados: ${items.meta.total ? items.meta.total : 0}` : 'Finalizados' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = ITEM_STATUS.ENTREGADO, b_finalizado = '', b_myitems = 1,
                                    index(1)"
                                v-bind:class="{ 'text-primary active': b_status === ITEM_STATUS.ENTREGADO}"
                                role="tab">{{ (b_status === ITEM_STATUS.ENTREGADO)
                                    ? `Mis Items: ${items.meta.total ? items.meta.total : 0}` : 'Mis Items' }}</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === ITEM_STATUS.ACTIVO }" v-if="b_status ===  ITEM_STATUS.ACTIVO">
                                <div class="row" v-if="items">
                                    <div class="col-sm" v-for="item in items.data" :key="item.id">
                                        <div class="card" style="width: 18rem;">
                                            <img class="bd-placeholder-img card-img-top"
                                                loading="lazy"
                                                :style="item.status != ITEM_STATUS.ACTIVO ?
                                                    'filter: brightness(0.5);' : ''"
                                                :src="item.picture ? `/files/rh/items/${item.picture}` :
                                                    'https://placehold.co/600x400?text=Cumbres'"
                                                    width="auto" height="200">
                                            <div class="top-left">
                                                <h6>
                                                {{
                                                    item.status == ITEM_STATUS.APARTADO ? 'Apartado'
                                                    : item.status == ITEM_STATUS.ENTREGADO ? 'Entregadp'
                                                    : ''
                                                }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{item.titulo}}</h5>
                                                <p class="card-text">{{ item.descripcion }}</p>
                                                <hr>
                                                <p class="card-subtitle" v-if="userName == 'shady' || userName == 'marce.gaytan' || rolId == 11">
                                                    Colaborador: {{ item.usuario.nombre }}
                                                </p>
                                            </div>
                                            <div class="card-body">
                                                <template v-if="item.user_id != userId">
                                                    <Button title="Solicitar articulo"
                                                        :disabled="item.reservation"
                                                        :btnClass="'btn-success'"
                                                        :icon="'fa fa-hand-paper-o'"
                                                        @click="solicitarItem(item.id)"
                                                    >
                                                        {{ item.reservation ? 'Solicitado' : 'Solicitar articulo' }}
                                                    </Button>
                                                </template>
                                                <template v-if="item.historial.length > 0">
                                                    <Button v-if="rolId == 1 || userName == 'marce.gaytan' || rolId == 11"
                                                        btnClass="btn-dark"
                                                        title="Ver solicitudes"
                                                        icon="icon-eye"
                                                        @click="abrirModal('solicitudes',item)"
                                                    >
                                                        Ver solicitudes
                                                    </Button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_finalizado == 1 }" v-if="b_finalizado == 1">
                                <div class="row" v-if="items">
                                    <div class="col-sm" v-for="item in items.data" :key="item.id">
                                        <div class="card" style="width: 18rem;">
                                            <img class="bd-placeholder-img card-img-top"
                                                loading="lazy"
                                                style="filter: brightness(0.5);"
                                                :src="item.picture ? `/files/rh/items/${item.picture}` :
                                                    'https://placehold.co/600x400?text=Cumbres'"
                                                    width="auto" height="200">
                                            <div class="top-left">
                                                <h6>
                                                {{
                                                    item.status == ITEM_STATUS.APARTADO ? 'Apartado'
                                                    : item.status == ITEM_STATUS.ENTREGADO ? 'Entregado'
                                                    : ''
                                                }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{item.titulo}}</h5>
                                                <p class="card-text">{{ item.descripcion }}</p>
                                                <hr>
                                                <template v-if="userName == 'shady' || userName == 'marce.gaytan' || rolId == 11">
                                                    <p class="card-subtitle">
                                                        Donante: {{ item.usuario.nombre }}
                                                    </p>
                                                    <hr>
                                                    <p class="card-subtitle" v-if="userName == 'shady' || userName == 'marce.gaytan' || rolId == 11">
                                                        Colaborador Elegido: {{ item.elegido.nombre }} {{ item.elegido.apellidos }}
                                                    </p>
                                                </template>
                                            </div>
                                            <div class="card-body">
                                                <template v-if="item.status == ITEM_STATUS.APARTADO">
                                                    <Button title="Entregar articulo"
                                                        v-if="rolId == 1"
                                                        btnClass="btn-success"
                                                        icon="fa fa-handshake-o"
                                                        @click="setEntrega(item.id)"
                                                    >
                                                        Entregar
                                                    </Button>
                                                </template>
                                                <h6 v-else>
                                                    Entregado el dia: {{  item.f_entrega }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === ITEM_STATUS.ENTREGADO }" v-if="b_status === ITEM_STATUS.ENTREGADO">
                                <div class="row" v-if="items">
                                    <div class="col-sm" v-for="item in items.data" :key="item.id">
                                        <div class="card" style="width: 18rem;">
                                            <img class="bd-placeholder-img card-img-top"
                                                loading="lazy"
                                                style="filter: brightness(0.5);"
                                                :src="item.picture ? `/files/rh/items/${item.picture}` :
                                                    'https://placehold.co/600x400?text=Cumbres'"
                                                    width="auto" height="200">
                                            <div class="top-left">
                                                <h6>
                                                {{
                                                    item.status == ITEM_STATUS.APARTADO ? 'Apartado'
                                                    : item.status == ITEM_STATUS.ENTREGADO ? 'Entregado'
                                                    : ''
                                                }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{item.titulo}}</h5>
                                                <p class="card-text">{{ item.descripcion }}</p>
                                                <hr>
                                                <template v-if="userName == 'shady' || userName == 'marce.gaytan' || roldId == 11">
                                                    <p class="card-subtitle">
                                                        Donante: {{ item.usuario.nombre }}
                                                    </p>
                                                    <hr>
                                                    <p class="card-subtitle" v-if="userName == 'shady' || userName == 'marce.gaytan' || rolId == 11">
                                                        Colaborador Elegido: {{ item.elegido.nombre }} {{ item.elegido.apellidos }}
                                                    </p>
                                                </template>
                                            </div>
                                            <div class="card-body">
                                                <h6>
                                                    Entregado el dia: {{  item.f_entrega }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="items">
                                <Nav v-if="items"
                                    :current="items.meta.current_page"
                                    :last="items.meta.last_page"
                                    @changePage="index"
                                ></Nav>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </div>

        <ModalComponent
            v-if="modal.mostrar"
            :titulo="modal.titulo"
            @closeModal="closeModal()"
        >
            <template v-slot:body>

                <RowModal label1="" clsRow1="col-md-12">
                    <TableComponent :cabecera="[
                        'Colaborador', 'Fecha', ''
                    ]">
                        <template v-slot:tbody>
                            <tr  v-for="solic in item.historial" :key="solic.id">
                                <td class="td2">{{ solic.nombre }} {{ solic.apellidos }}</td>
                                <td class="td2">{{ solic.created_at }}</td>
                                <td class="td2">
                                    <Button v-if="item.status == ITEM_STATUS.ACTIVO"
                                        icon="icon-check" title="Elegir colaborador"
                                        @click="setColaborador(solic.id)"
                                    >Elegir</Button>
                                    <template v-else>
                                        {{ solic.status == 2 ? 'Elegido' : '' }}
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                </RowModal>

            </template>
            <template v-slot:buttons-footer>

            </template>
        </ModalComponent>


    </main>
</template>

<script>
import Button from "../../Componentes/ButtonComponent.vue";
import Nav from "../../Componentes/NavComponent.vue";
import LoadingComponent from "../../Componentes/LoadingComponent.vue";
import ModalComponent from "../../Componentes/ModalComponent.vue";
import RowModal from "../../Componentes/ComponentesModal/RowModalComponent.vue";
import TableComponent from "../../Componentes/TableComponent.vue"


export default{

        components:{
            Button,
            Nav,
            LoadingComponent,
            RowModal,
            ModalComponent,
            TableComponent

        },
        props:{
            rolId: { type: String },
            userName: { type: String },
            userId: { type: String },
        },
        data(){
            return{
                ITEM_STATUS : Object.freeze({
                    ACTIVO : 1,
                    APARTADO : 2,
                    ENTREGADO : 3,
                    FINALIZADO : 4
                }),

                b_status : 1,
                b_item : '',
                b_finalizado : '',
                b_myitems : '',

                loading : false,
                modal: {
                    mostrar : false,
                    titulo : '',
                },
                items: null,
                item:{}

            }
        },
        computed:{
        },
        methods : {
            async index(page){
                let me = this;
                me.items = null;
                me.loading = true;

                let user_id = '';

                if(!me.b_finalizado && me.rolId != 1 && me.b_status != me.ITEM_STATUS.ACTIVO && me.b_myitems == '')
                    user_id = 1

                try{
                    const url   = `/donativos-items?page=${page}&item=${me.b_item}&user_id=${user_id}&finalizado=${me.b_finalizado}&status=${me.b_status}&myitem=${me.b_myitems}`;
                    const res   = await axios.get(url);
                    me.items    = await res.data
                }catch(e){
                    console.log(e);
                }
                finally{
                    me.loading = false
                }
            },
            async solicitarItem(id){
                let me = this;

                try{
                    const res = await axios.post('/donativos-items/solicitarItem',{
                        'id': id,
                    })
                    swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Solicitud creada correctamente',
                            showConfirmButton: false,
                            timer: 2000
                        })
                }
                catch(e){
                    alert('Error al crear la solicitud')
                }
                finally{
                    me.index(me.items.meta.current_page)
                }
            },
            async setColaborador(id){
                let me = this;

                try{
                    const res = await axios.put(`/donativos-items/setColaborador/${id}`,{
                        'id': id,
                    })
                    swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Solicitud elegida correctamente',
                            showConfirmButton: false,
                            timer: 2000
                        })
                }
                catch(e){
                    alert('Error al elegir la solicitud')
                }
                finally{
                    me.index(me.items.meta.current_page)
                    me.closeModal()
                }
            },
            async setEntrega(id){
                let me = this;

                try{
                    const res = await axios.put(`/donativos-items/setEntrega/${id}`,{
                        'id': id,
                    })
                    swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Item entregado correctamente',
                            showConfirmButton: false,
                            timer: 2000
                        })
                }
                catch(e){
                    alert('Error al entregar el item')
                }
                finally{
                    me.index(me.items.meta.current_page)
                }
            },
            abrirModal(accion, data={}){
                let me = this;

                me.modal.mostrar = true;
                me.modal.titulo  = 'Ver solicitudes'

                me.item = {...data}


            },
            closeModal(){
                let me = this;
                me.modal.mostrar = false;
            }
        },
        mounted() {
            this.index(1)
        }
    }


</script>

<style scoped>
    .top-left {
        position: absolute;
        top: 8px;
        left: 16px;
        color: white;
    }

    .td2{
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
    }
</style>
