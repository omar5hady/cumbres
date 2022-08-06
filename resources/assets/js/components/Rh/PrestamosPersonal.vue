<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>

            <div class=" container-fluid ">
                <div class=" card scroll-box ">
                    <div class="card-header flex-md-column " >
                            
                           <i class="fa fa-align-justify"></i>Prestamos Personales
                    </div>
                    <div class=" card-body ">

                        {{userName}}

                        <div class=" form-group flex-column ">
                            <button class=" btn btn-info " @click="abrirModal('registrar')"> <i class="fa fa-plus-circle"></i> Nueva Solicitud.</button>    
                        </div>

                            <div class=" form-group ">
                                <input type="text">
                            </div>

                            <div class=" card-body ">

                <!-- inicio de tabla -->
                    <div class="table-responsive"> 
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Colaborador </th>
                                        <th>Monto solicitado</th>
                                        <th>Fecha de solic.</th>
                                        <th>Saldo</th>
                                        <th>Status</th>
                                        <th>Jefe inmediato</th>
                                        <th>RH</th>
                                        <th>Dirección</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="prestamo in arrayDataPrestamos" :key="prestamo.id">
                                        <td class="td2">
                                            <button type="button" @click="abrirModal('ver',prestamo)" class="btn btn-dark btn-sm">
                                                <i class="icon-eye"></i>
                                            </button>
                                            <button type="button" @click="abrirModal('editar',prestamo)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>
                                        </td>


                                        <td class="td2" v-text="prestamo.nombre + ' ' +prestamo.apellidos"></td>
                                        <td class="td2" v-text="'$' + formatNumber(prestamo.monto_solicitado)"></td>
                                        <td class="td2" v-text="this.moment(prestamo.fecha_ini).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="'$' + formatNumber(prestamo.saldo)"></td>
                                        <td class="td2">
                                            <!-- 0 Cancelado, 1 Pendiente, 2 Aprobado, 3 Liquidado -->
                                            <span v-if="prestamo.status == 0" class="badge badge-danger">Rechazado</span>
                                            <span v-if="prestamo.status == 1" class="badge badge-warning">Pendiente</span>
                                            <span v-if="prestamo.status == 2" class="badge badge-primary">Aprobado</span>
                                            <span v-if="prestamo.status == 3" class="badge badge-success">Liquidado</span>
                                        </td>
                                          <template v-if="isGerenteCurrent || isRHCurrent || isDireccionCurrent">

                                            <td class="td2" v-if="prestamo.jefe_band == 0">
                                                <button v-if="isGerenteCurrent"  class="btn btn-dark btn-sm"  type="button" @click="firmar('jefe',prestamo.id)"  >
                                                    <i class="icon-check"></i>
                                                </button>
                                               
                                            </td>
                                            <td class="td2" v-else >Firmado</td>

                                             <td class="td2" v-if="prestamo.rh_band == 0">
                                                <button v-if="isRHCurrent" class="btn btn-dark btn-sm"  type="button" @click="firmar('rh',prestamo.id)"  >
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else >Firmado</td>


                                             <td class="td2" v-if="prestamo.dir_band == 0">
                                                <button v-if="isDireccionCurrent" class="btn btn-dark btn-sm"   type="button" @click="firmar('dir',prestamo.id)" >
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else >Firmado</td>


                                        </template>

                                        <template v-else >
                                            <td class="td2" v-if="prestamo.jefe_band == 0">
                                                Sin Firma de Jefe
                                            </td>
                                            <td class="td2" v-else > Firmado</td>


                                            <td class="td2" v-if="prestamo.rh_band == 0">
                                                Sin Firma de RH
                                            </td>
                                            <td class="td2" v-else > Firmado</td>


                                            <td class="td2" v-if="prestamo.dir_band == 0">
                                                Sin Firma de Dirección
                                            </td>
                                             <td class="td2" v-else > Firmado</td>

                                        </template>
                                      

                                        <td>
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal('observaciones',prestamo)">Observaciones</button>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>

                        <!-- fin de tabla -->

                            </div>

                    </div>
                        

                </div>

            </div>

                <!--Inicio del modal agregar/actualizar-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label " for="text-input">Colaborador:
                                    </label>
                                   
                                        <input class=" col-md-6 " disabled="true" v-model="nom_colaborador" type="text" >
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label ">Gerente a cargo:
                                    </label>
                                   
                                      <select  class="col-md-6 form-control"  name="" id=""  v-model="idJefe">
                                            <option v-for="gerente in arrayIdGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre" ></option>
                                      </select>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" >Monto solicitado:
                                        <span style="color:red;" v-show="true">*</span>
                                    </label>
                                    
                                            <input  type="text" v-on:keypress="isNumber($event)" v-model="monto_solic" >
                                            
                                    
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" >fecha. </label>
                                    
                                        <input type="date" v-model="fecha_solic" >
                                    
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-descuento">Descuento por quincena.
                                        <span style="color:red;" v-show="true">*</span>
                                    </label>
                                        <div class=" flex-column justify-content-lg-around">
                                            <input type="text" v-on:keypress="isNumber($event)"  v-model="desc_quin" >
                                            <button type="button" v-show="desc_quin" class="btn btn-info " @click="generar_tab = 1 , generarTablaPagos()">Generar</button>
                                             <button type="button" v-show="desc_quin" class="btn btn-warning " @click="generar_tab = 1 , borrarTabla()">Borrar</button>

                                        </div>
                                    
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-4 form-control-label" >Motivo de prestamo.
                                        <span style="color:red;" v-show="true">*</span>
                                    </label>
                                    
                                        <textarea cols="30" rows="2"  type="text" class=" card-text " maxlength="50" v-model="motivo" ></textarea>
                                    
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-area-input">Observaciones
                                        <span style="color:red;" v-show="true">*</span>
                                    </label>
                                    
                                        <textarea cols="30" rows="2" type="text" class=" card-text " maxlength="50" v-model="obs_prestamo" ></textarea>
                                    
                                </div> -->

                                   <template v-if="generar_tab == 1" >

                                    <div class="form-group row">
                                        <div class=" col-md-10  justify-content-center">
                                            <div class="table-responsive"> 
                                            <table class="table2 table-bordered table-striped table-sm"> 
                                                <thead>
                                                    <tr>
                                                        <th>No. quincena</th>
                                                        <th>Pago Nomina</th>
                                                        <th>Pago Extraordinario</th>
                                                        <th>Saldo</th>
                                                        <th>Notas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr  v-for="pago ,index in arrayPagos" :key="pago.id"> 
                                                            <td  v-text="index"></td>
                                                            <td v-text="pago"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                </tbody>

                                            </table>

                                            </div>

                                        </div>
                                    </div>
                                    
                                </template>

                
                             

                             
                            
                         

                               
                                
                                <!-- Div para mostrar los errores que mande validerNotaria -->
                                <!-- <div v-show="errorprestamo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error"> 
                                        </div>
                                    </div>
                                </div> -->
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-success"   @click="enviarSolicitud()">Enviar</button>

                           


                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

           
       
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            userName:{type: String},
            userId:{type: String},
        },
        data(){
            return{
                arrayIdGerentes:[
                   {id:'10',user:'admin1' ,nombre:'Elizabeth'},
                    {id:'11',user:'admin2', nombre: 'Yazmin'},
                    {id:'2',user:'admin', nombre: 'miguelin'}
                ],

                objPagos:[],
                
                 arrayIdRH:[
                    '10','7','3',
                ],
                 arrayIdDir:[
                    '10','7','3','2',
                ],
                arrayPagos:[],
                arraySaldo:[],

                isGerenteCurrent:false,
                isRHCurrent:false,
                isDireccionCurrent:false,
                currentId:null,
                modal:null,
                tituloModal:null,
                arrayDataPrestamos:[],
                generar_tab:0,

               // variables colaborador //
                nom_colaborador:'',
                monto_solic:0,
                motivo:null,
                obs_prestamo:null,
                desc_quin:null,
                fecha_solic:null,
                idJefe:null,
                saldoFaltante:null,


                
            }
        },
        
        computed:{
     
          
        },
        methods : {

                // Math.ceil(11.123) devuelve el numero entero siguiente 
            generarTablaPagos(){

                     this.borrarTabla();

               var NpagoQ= parseFloat(this.monto_solic )/ parseFloat(this.desc_quin )
                    Math.ceil(NpagoQ) 
                    
                    
                    dataObj={};
                    var saldo = parseFloat(this.monto_solic );
                    var pagoQ = parseFloat(this.desc_quin )
                    for(var i=0; i<24; i++){
                        
                        dataObj['id']= i+1;

                        if(saldo <= 0 ){
                                dataObj['pago']=0
                                this.arrayPagos.push(dataObj)
                        }else{
                            if(saldo <= pagoQ ){
                                dataObj['pago']=saldo
                                this.arrayPagos.push(dataObj)
                                }
                            else{
                                dataObj['pago']=pagoQ
                                this.arrayPagos.push(dataObj)
                            }
                                 
                        }
                        if(saldo <= 0 ){
                            dataObj['saldo']=0
                            this.arrayPagos.push(dataObj)
                        }else{
                            if(saldo <= pagoQ ){
                                saldo -=saldo;
                            }
                            else{
                                saldo -=pagoQ;
                            }
                            dataObj['saldo']=saldo;
                            this.arrayPagos.push(dataObj)
                        }
                        

                    }
                     // terminar  
                    if(saldo > 0){
                        this.saldoFaltante = saldo;
                    }

                    console.log(this.saldoFaltante);
                    console.log(this.arrayPagos);
                    console.log(this.arraySaldo);
                    console.log(this.objPagos);


            },

            enviarSolicitud(){
                let me = this;
                var url = '/prestamos/registrarPrestamo?id=' + this.userId +
                                                        '&monto='+ this.monto_solic +
                                                        '&motivo='+ this.motivo +
                                                        '&desc_quin='+this.desc_quin +
                                                        '&fecha_solic='+ this.fecha_solic +
                                                        '&idJefe=' + this.idJefe;
                axios.post(url).then(function (response) {
                    me.cerrarModal();
                    
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Se ha enviado solicitud'
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
              firmar(firma_f,id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de firmar esta solicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/prestamos/firmar',{
                            'id' : id ,
                            'firma_de':firma_f
                        }).then(function (response){
                            me.dataPrestamos(); 
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Firmada',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
            },
            dataColaborador(){
            let me = this;
                var url = '/prestamos/getColaborador?id=' + this.userId;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                  
                     me.nom_colaborador = respuesta.nombre + ' ' +respuesta.apellidos ;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            dataPrestamos(){
            let me = this;
                var url = '/prestamos/getDataPrestamos?idUser='+this.userId+ 
                            '&isGerenteCurrent='+this.isGerenteCurrent +
                            '&isRHCurrent='+ this.isRHCurrent +
                            '&isDireccionCurrent='+ this.isDireccionCurrent;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                  
                     me.arrayDataPrestamos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
              isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },

            
             formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },

             abrirModal(accion){
               
                switch(accion){
                    case 'registrar':
                    {
                        const fecha = new Date();
                        const anio = fecha.getFullYear();
                        const mes  = fecha.getMonth()+1;
                        const dia  = fecha.getDate();
                        this.modal=1;
                        this.tituloModal='Nueva Solicitud';
                        this.fecha_solic=anio+'-'+mes+'-'+dia;

                        break;
                    }
                    case 'ver':
                    {
                      
                        break;
                    }
                    case 'editar':
                    {
                       
                        
                        break;
                    }
                    case 'observaciones':
                    {
                       
                        break;
                    }
                }
            },

            isGerenteCurrent_Id(){

                this.arrayIdGerentes.forEach(element => {
                    if(element.id == this.userId){
                        this.isGerenteCurrent = true;
                    }else{ 
                        if( element.user == this.userName){
                            this.isGerenteCurrent = true;
                        }
                    }

                });
                   console.log(this.isGerenteCurrent);
            },
              isRHCurrent_Id(){
             this.isRHCurrent = this.arrayIdRH.includes(this.userId)
                if(!this.isRHCurrent)
                   this.isRHCurrent = this.arrayIdRH.includes(this.userName)
            },
              isDirecCurrent_Id(){
             this.isDireccionCurrent = this.arrayIdDir.includes(this.userId)
                if(!this.isDireccionCurrent)
                   this.isDireccionCurrent = this.arrayIdDir.includes(this.userName)
            },


            cerrarModal(){
                
                this.modal=null;
                this.tituloModal=null;


            },
            borrarTabla(){
                
                this.arrayPagos=[];
                this.arraySaldo=[];


            }
           
        },
        mounted() {
            this.dataPrestamos();
            this.dataColaborador();
            this.isGerenteCurrent_Id();
            this.isRHCurrent_Id();
            this.isDirecCurrent_Id();

            
           
        }
    }
</script>
<style scoped>
   body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 0.875rem;
    font-weight: normal;
    line-height: 1.5;
    color: #151b1e;
    background-color: #e4e5e6;
}
  .td2 {
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
    }

button{
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
        overflow-y: auto;
    }
 
</style>
