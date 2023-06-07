<template>
   <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header flex-md-column" >
                    <i class="fa fa-align-justify"></i>Mis Items
                    <Button btnClass="btn-info" icon="fa fa-plus-circle" @click="abrirModal('crear')"> Nuevo Item.</Button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control col-md-3" disabled placeholder="Item:">
                                <input type="text" @keyup.enter="index(1)"
                                    v-model="b_item" class="form-control" placeholder="Item a buscar">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group">
                                    <select class="form-control" v-model="b_status" @change="index(1)">
                                    <option value="">Estatus</option>
                                    <option :value="ITEM_STATUS.ACTIVO">Activo</option>
                                    <option :value="ITEM_STATUS.APARTADO">Apartado</option>
                                    <option :value="ITEM_STATUS.ENTREGADO">Entregado</option>
                                </select>
                                <Button @click="index(1)" icon="fa fa-search"> Buscar</Button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container">
                    <LoadingComponent v-if="loading"></LoadingComponent>
                    <template v-else>
                        <div class="row">
                            <Nav v-if="items"
                                :current="items.meta.current_page"
                                :last="items.meta.last_page"
                                @changePage="index()"
                            ></Nav>
                        </div>
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
                                            : item.status == ITEM_STATUS.ENTREGADO ? 'Entregado'
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
                                    <div class="card-body" v-if="item.status == ITEM_STATUS.ACTIVO">
                                        <Button title="Eliminar"
                                            :btnClass="'btn-danger'"
                                            :icon="'icon-trash'"
                                            @click="deleteItem(item.id)"
                                        >
                                            Eliminar
                                        </Button>
                                        <Button title="Editar"
                                            :btnClass="'btn-warning'"
                                            :icon="'icon-pencil'"
                                            @click="abrirModal('editar',item)"
                                        >
                                            Editar
                                        </Button>
                                    </div>
                                </div>
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
                <form method="post" @submit.prevent="saveForm" enctype="multipart/form-data">
                    <RowModal label1='' clsRow1="col-md-4" label2="" clsRow2="col-md-6">
                        <img :src="`${item.picture ? item.picture : 'https://placehold.co/600x400?text=Cumbres'}`"
                            width="auto" height="100">
                        <template v-slot:input2>
                            <div class="form-archivo">
                                <input ref="fileSelector"
                                    v-show="false"
                                    type="file" accept="image/*"
                                    v-on:change="onChangeFile"
                                />

                                <label class="label-button" @click="onSelectFile">
                                    Selecciona la imagen
                                    <i class="fa fa-upload"></i>
                                </label>
                                <div :class="(item.nom_archivo == 'Seleccione Archivo')
                                    ? 'text-file-hide' : 'text-file'"
                                    v-text="item.nom_archivo"
                                ></div>
                            </div>
                            <!-- <input type="file" accept="image/*"
                                ref="file"
                                @change="selectImage"> -->
                        </template>
                    </RowModal>
                    <RowModal label1="Titulo" clsRow1="col-md-5">
                        <input type="text" class="form-control" v-model="item.titulo" />
                    </RowModal>
                    <RowModal label1="Descripción" clsRow1="col-md-7" >
                        <textarea class="form-control" v-model="item.descripcion" cols="40" rows="4"></textarea>
                    </RowModal>
                    <RowModal label1="" clsRow1="col-md-12">
                        <center>
                            <button v-if="!item.loading"
                                type="submit"
                                class="btn btn-success"
                            >Guardar
                            </button>
                        </center>
                    </RowModal>
                </form>
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


export default {

        components:{
            Button,
            Nav,
            LoadingComponent,
            RowModal,
            ModalComponent

        },
        props:{
            rolId: { type: String },
            userName: { type: String }
        },
        data(){
            return{
                ITEM_STATUS : Object.freeze({
                    ACTIVO : 1,
                    APARTADO : 2,
                    ENTREGADO : 3
                }),

                b_status : '',
                b_item : '',

                items: null,
                loading : false,
                modal: {
                    mostrar : false,
                    titulo : '',
                },


                item:{
                    id : '',
                    status : '',
                    titulo :'',
                    descripcion: '',
                    picture: '',
                    nom_archivo: '',
                    file: null,
                    loading : false,
                }
            }
        },
        computed:{
        },
        methods : {
            saveForm(e) {
                if(this.validarForm())
                    return

                e.preventDefault();
                let currentObj = this;
                this.item.loading = true;

                let formData = new FormData();
                formData.append('file', this.item.file);
                formData.append('id', this.item.id);
                formData.append('titulo', this.item.titulo);
                formData.append('nom_archivo', this.item.nom_archivo);
                formData.append('descripcion', this.item.descripcion);

                let me = this;

                axios.post('/donativos-items', formData)
                .then(function (response) {
                    me.item.loading = false;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Item guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    me.closeModal();
                    me.index(me.items.meta.current_page)

                }).catch(function (error) {
                    currentObj.output = error;
                    me.item.loading = false;
                });
            },
            async deleteItem(id){
                try{
                    await axios.delete(`/donativos-items/${id}`,{
                        params: {'id': id}
                    });

                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Item Eliminado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
                catch(e){
                    alert('Error al tratar de eliminar el item')
                }
                finally{
                    this.index(this.items.meta.current_page)
                }

            },
            validarForm(){
                return (
                    this.item.titulo == '' || this.item.descripcion == ''
                )
            },
            onChangeFile(e){
                this.item.file = e.target.files[0];
                this.item.nom_archivo = e.target.files[0].name;
                this.item.picture = URL.createObjectURL(this.item.file)
            },
            onSelectFile(){
                this.$refs.fileSelector.click()
            },
            async index(page){
                let me = this;
                me.items = null;
                me.loading = true;

                let user = '1';
                if(me.rolId == 1 || me.rolId == 11 || me.userName == 'marce.gaytan')
                user = '';

                try{
                    const url   = `/donativos-items?page=${page}&item=${me.b_item}&user_id=${user}&status=${me.b_status}`;
                    const res   = await axios.get(url);
                    me.items    = await res.data
                }catch(e){
                    console.log(e);
                }
                finally{
                    me.loading = false
                }
            },
            limpiarItem(){
                this.item = {
                    id : '',
                    status : '',
                    titulo :'',
                    descripcion: '',
                    picture: '',
                    nom_archivo : 'Seleccione Archivo',
                    file: null,
                    loading : false,
                }
            },
            abrirModal(accion, data={}){
                let me = this;

                me.modal.mostrar = true;
                me.modal.titulo  = accion == 'crear' ? 'Crear Item' : 'Editar Item';

                me.item = {
                    id : '',
                    status : '',
                    titulo :'',
                    descripcion: '',
                    picture: '',
                    nom_archivo : 'Seleccione Archivo',
                    file: null,
                    loading : false,
                    ...data
                };

                if(me.item.picture != '' && me.item.picture != null){
                    me.item.picture = `/files/rh/items/${me.item.picture}`
                }
            },
            closeModal(){
                let me = this;
                me.limpiarItem()
                me.modal.mostrar = false;
            }
        },
        mounted() {
            this.index(1)
        }
    }


</script>

<style scoped>
    .form-archivo{
        display: flex;
        flex-direction: row;

        width: 100%;
    }
    .top-left {
        position: absolute;
        top: 8px;
        left: 16px;
        color: white;
    }
    .label-button{
        border-style: solid;
        cursor:pointer;
        color: #fff;
        background-color: #00ADEF;
        border-color: #00ADEF;
        padding: 10px;
        margin: 15px;
    }
    .label-button:hover {
        color: #fff;
        background-color: #1b8eb7;
        border-color: #00b0bb;
    }
    .text-file{
        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .text-file-hide{
        display: none;
        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
</style>
