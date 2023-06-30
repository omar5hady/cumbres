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
                    <i class="fa fa-align-justify"></i> Registro
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input
                                    class="form-control col-md-3"
                                    type="text"
                                    disabled
                                    placeholder="Invitado a Buscar:"
                                />
                                <input
                                    type="text"
                                    v-model="b_invitado"
                                    class="form-control"
                                    @keyup.enter="findInvitado()"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <Button
                                @click="findInvitado()"
                                :btnClass="'form-control btn-primary'"
                                :icon="'fa fa-search'"
                            >
                                Buscar
                            </Button>
                        </div>
                    </div>

                    <div class="row" v-if="dataInvitado">
                        <div class="col-sm-6 col-lg-4">
                            <div
                                class="card mb-4"
                            >
                                <div
                                    class="card-header d-flex justify-content-center align-items-center"
                                    :style="`${dataInvitado.isCliente == 1 ? 'background-color: #f1b922;' : 'background-color: #5dc1b9;'}   color: white;`"
                                >
                                    <span class="material-icons md-48" style="font-size: 64px;">family_restroom</span>

                                </div>
                                <div
                                    class="card-header d-flex justify-content-center align-items-center"
                                    :style="`${dataInvitado.isCliente == 1 ? 'background-color: #f1b922;' : 'background-color: #5dc1b9;'}   color: white;`"
                                >
                                    <h4>Invitados confirmados</h4>

                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5">
                                            <h5 class="text-uppercase" style="font-weight: bold;">
                                                {{ dataInvitado.nombre }}
                                                {{ dataInvitado.ap_paterno }}
                                                {{ dataInvitado.ap_materno }}
                                            </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5">
                                            <button class="btn btn-sm btn-primary" title="Añadir"
                                            v-if="dataInvitado.status==0 && dataInvitado.asist_adult < 4"
                                            @click="changeAsistAdult(1)"
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 12px;">add</span>
                                            </button>
                                            <h4>{{ dataInvitado.asist_adult }}</h4>
                                            <button class="btn btn-sm btn-primary" title="Quitar"
                                                v-if="dataInvitado.status==0 && dataInvitado.asist_adult > 1"
                                                @click="changeAsistAdult(-1)"
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 12px;">remove</span>
                                            </button>
                                        </div>
                                        <div
                                            class="text-uppercase text-medium-emphasis small"
                                        >
                                            <strong>adultos</strong>
                                        </div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <div class="fs-5">
                                            <button class="btn btn-sm btn-primary" title="Añadir"
                                            v-if="dataInvitado.status==0 && dataInvitado.asist_kid < 4"
                                            @click="changeAsistKid( 1 )"
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 12px;">add</span>
                                            </button>
                                            <h4>{{ dataInvitado.asist_kid }}</h4>
                                            <button class="btn btn-sm btn-primary" title="Quitar"
                                                v-if="dataInvitado.status==0 && dataInvitado.asist_kid > 0"
                                                @click="changeAsistKid( -1 )"
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 12px;">remove</span>
                                            </button>
                                        </div>
                                        <div
                                            class="text-uppercase text-medium-emphasis small"
                                        >
                                            <strong>niños</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5">
                                            <button class="btn btn-success" title="Confirmar registro"
                                                v-if="dataInvitado.status == 0"
                                                @click="confirmAsist()"
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 24px;">done</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Inicio del modal observaciones-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import Button from "../Componentes/ButtonComponent.vue";

export default {
    components: {
        Button
    },
    props: {
        invitado:{
            type:String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            b_invitado:'',
            dataInvitado: null
        };
    },
    computed: {},
    methods: {
        setDatos(){
            if(this.invitado)
                this.dataInvitado = JSON.parse(this.invitado)
        },
        changeAsistAdult( cant ){
            this.dataInvitado.asist_adult += cant;
        },
        changeAsistKid( cant ){
            this.dataInvitado.asist_kid += cant;
        },
        async confirmAsist(){
            let me = this;

            const res = await axios.put(`/invitacion/confirm`,me.dataInvitado)

            me.dataInvitado.status = 1;
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            toast({
            type: 'success',
            title: 'Asistencia confirmada.'
            })
        },
        async findInvitado(){
            let me = this;
            me.dataInvitado = {}

            try{
                const res = await axios.get(`/evento/findInvitado?nombre=${me.b_invitado}`)
                me.dataInvitado = res.data
            }
            catch(e){
                console.log(e)
            }
        }
    },
    mounted() {
        this.setDatos();
    }
};
</script>
<style>
@import  "https://fonts.googleapis.com/icon?family=Material+Icons";
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
</style>
