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
                    <i class="fa fa-align-justify"></i> Opciones
                    &nbsp;
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Porcentaje</th>
                                    <th>Descripcion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in arrayListA" :key="item.id">
                                    <td v-text="item.clave">Concepto</td>
                                    <td style="padding:0px;">
                                        <input v-model="item.valor" v-on:keyup.enter="editaPorcentajes(item)" type="number" min="0" step=".01" class="form-control" style="height: 45px;">
                                    </td>
                                    <td v-text="item.descripcion"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>

                    <div class="row">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th># Lote</th>
                                    <th>m²</th>
                                    <th>costo m²</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in arrayLotes" :key="item.id">
                                    <td v-text="item.num_lote">Concepto</td>
                                    <td style="padding:0px;">
                                        <input v-model="item.terrenom2" v-on:keyup.enter="editaLote(item)" type="number" min="0" step=".01" class="form-control" style="height: 45px;">
                                    </td>
                                    <td style="padding:0px;">
                                        <input v-model="item.costom2" v-on:keyup.enter="editaLote(item)" type="number" min="0" step=".01" class="form-control" style="height: 45px;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
export default {
    
    data() {
        return{
            arrayListA:[],
            arrayLotes:[],
            myAlerts:{
                popAlert : function(title = 'Alert',type = "success", description =''){
                    swal({
                        title: title,
                        type: type,
                        text: description,
                        showConfirmButton:false,
                        timer:1500,
                    })
                }
            },
        }
    },
    computed:{

    },
    methods: {
        listarPorcentajes(){
            axios.get('/calc/descuentos').then(
                response => this.arrayListA = response.data
            ).catch(error => console.log(error));
        },
        editaPorcentajes(datos){
            axios.put('/calc/descuentos/edita',{
                'id':datos.id,
                'valor':datos.valor
            }).then(
                this.myAlerts.popAlert('Guardado correctamente')
            ).catch(error => console.log(error));
        },
        editaLote(datos){
            axios.put('/calc/lote/edita',{
                'id':datos.id,
                'terrenom2':datos.terrenom2,
                'costom2':datos.costom2,
            }).then(
                this.myAlerts.popAlert('Guardado correctamente')
            ).catch(error => console.log(error));
        },
        buscaLotes(){
            axios.get('/calc/lotes').then(
                response => this.arrayLotes = response.data
            ).catch(error => console.log(error));
        }
    },
    mounted() {
        this.listarPorcentajes();
        this.buscaLotes();
    }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: fixed !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
.card-user2 .avatar.border-white {
    border: 5px solid #fff;
}
.card-user2 .avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    position: relative;
    margin-bottom: 15px;
}
.card2 .avatar {
    width: 200px;
    height: 200px;
    overflow: hidden;
    border-radius: 50%;
    margin-right: 5px;
}
.border-white {
    border-color: #fff!important;
}
.card-user2 .author {
    text-align: center;
    text-transform: none;
    margin-top: -65px;
}
.card2 .author {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.card2 {
    border-radius: 6px;
    box-shadow: 0 2px 2px hsla(38,16%,76%,.5);
    background-color: #fff;
    color: #252422;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
    border: none;
}
.card2 .card-image2 img  {
    width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
.bg-gradient-primary {
    background: #00ADEF!important;
    background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
    border-color: #00ADEF!important;
}
.p-3 {
    padding: 1rem!important;
}
</style>

