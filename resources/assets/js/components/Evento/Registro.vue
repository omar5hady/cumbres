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
                                    placeholder="Folio a Buscar:"
                                />
                                <input
                                    type="number"
                                    v-model="b_folio"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <Button
                                :btnClass="'form-control btn-primary'"
                                :icon="'fa fa-search'"
                            >
                                Buscar
                            </Button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div
                                class="card mb-4"
                            >
                                <div
                                    class="card-header d-flex justify-content-center align-items-center"
                                    style="background-color: navy; color: white;"
                                >
                                    <span class="material-icons md-48" style="font-size: 64px;">family_restroom</span>

                                </div>
                                <div
                                    class="card-header d-flex justify-content-center align-items-center"
                                    style="background-color: navy; color: white;"
                                >
                                    <h4>Invitados confirmados</h4>

                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5">
                                            <h5 style="font-weight: bold;">
                                                Juanito Perez
                                            </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5">
                                            <button class="btn btn-sm btn-primary" title="Añadir"
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 12px;">add</span>
                                            </button>
                                            <h4>2</h4>
                                            <button class="btn btn-sm btn-primary" title="Quitar"
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
                                                style="border-radius: 100%;">
                                                    <span class="material-icons" style="font-size: 12px;">add</span>
                                            </button>
                                            <h4>4</h4>
                                            <button class="btn btn-sm btn-primary" title="Quitar"
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
    props: {},
    data() {
        return {
            b_folio:''
        };
    },
    computed: {},
    methods: {
    },
    mounted() {}
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
