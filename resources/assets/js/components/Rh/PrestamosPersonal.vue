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

                        <div class=" form-group flex-column ">
                            <button class=" btn btn-info " @click="abrirModal('registrar')"> <i class="fa fa-plus-circle"></i> Nueva Solicitud.</button>    
                        </div>

                           <div class="form-group row">
                            
                            <div class="col-md-8" v-if="isRHCurrent">
                                <div class="input-group">
                                    <input type="text"  class="form-control col-md-3" disabled placeholder="Colaborador: ">
                                    <input type="text"  v-model="b_colaborador" @keyup.enter="dataPrestamos()" class="form-control" placeholder="Nombre a buscar">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text"  class="form-control col-md-3" disabled placeholder="Fecha: ">
                                    <input type="date"  v-model="b_fecha1" @keyup.enter="dataPrestamos()" class="form-control">
                                    <input type="date"  v-model="b_fecha2" @keyup.enter="dataPrestamos()" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                     <select class="form-control" v-model="b_status" >
                                        <option value="">Estatus</option>
                                        <option value="0">Rechazado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Aprobado</option>
                                        <option value="3">Liquidado</option>
                                    </select>
                                    <button type="submit" @click="dataPrestamos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>



                            <div class=" card-body ">

                <!-- inicio de tabla -->
                    <div v-if="vista_tabla ==1 "  class="table-responsive"> 
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
                                            <template v-if="isRHCurrent" >
                                                <button type="button" @click="abrirModal('editar',prestamo)" class="btn btn-warning btn-sm">
                                                    <i class="icon-pencil"></i>
                                                </button>

                                            </template>
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
                          
                                <div class="form-group row">
                                    <label class="col-md-3 " for="text-input">Colaborador:
                                    </label>
                                   
                                         <label class="col-md-1 form-control" >fecha:</label>
                                            <label class=" col-md-2 form-control " v-text="fecha_solic"></label>
                                </div>
                                
                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label ">Gerente a cargo:
                                    </label>
                                        <template v-if="modalVista == '0' || modalVista == '2'">
                                            <select  class="col-md-6 form-control"  name="" id=""  v-model="idJefe">
                                                    <option v-for="gerente in arrayIdGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre" ></option>
                                        <label class=" col-md-6 form-control disabled "  type="text" >{{nom_colaborador}}</label>
                                            </select>

                                        </template>
                                           <template v-if="modalVista == '1'">
                                            <select disabled class="col-md-6 form-control"  name="" id=""  v-model="idJefe">
                                                    <option v-for="gerente in arrayIdGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre" ></option>
                                            </select>

                                        </template>

                                </div>

                                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" >Monto solicitado:
                                        <span style="color:red;" v-show="monto_solic == 0">*</span>
                                    </label>

                                        <template v-if="modalVista =='0' || modalVista == '2' ">
                                            <a  v-if="editAjusteMonto ==0"   class="form-control col-md-3 " style="cursor: pointer; color:deepskyblue; text-decoration: underline; text-shadow: slategrey;" title="Click para editar"  @click="editAjusteMonto=1" v-text="'$'+formatNumber(monto_solic)" ></a>

                                        </template>
                                    
                                        
                                        <template v-if="modalVista == '0' || modalVista == '2' ">
                                            <input v-if="editAjusteMonto ==1" autofocus aria-selected="true" class=" form-control col-md-3" title="Enter para guardar.."  pattern="\d*" type="text"  
                                                    @keyup.enter="editAjusteMonto=0"  
                                                    v-on:keypress="isNumber($event)"  v-model="monto_solic"> 

                                        </template>
                                        <template   v-if="modalVista =='1'">
                                            <input disabled class="col-md-3  form-control "  type="number" v-on:keypress="isNumber($event)" v-model="monto_solic" >
                                            
                                        </template>
                                        
                                    
                                            
                                    
                                </div>

                                <!-- <div class="form-group row">
                                    <label class="col-md-4 form-control-label" >fecha. </label>
                                    
                                        <input type="date" v-model="fecha_solic" >
                                    
                                </div> -->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-descuento">Descuento por quincena.
                                        <span style="color:red;" v-show="desc_quin ==0">*</span>
                                    </label>
                                        <div class="form-group row col-md-9  ">
                                                <template v-if="modalVista == '0' || modalVista == '2'">
                                                    <a v-if="editAjusteQuin ==0"  class="form-control col-md-2 " style="cursor: pointer; color:deepskyblue; text-decoration: underline; " title="Click para editar"  @click="editAjusteQuin=1" v-text="'$'+formatNumber(desc_quin)" ></a>

                                                </template>
                                                <template v-if="modalVista =='0' || modalVista == '2' ">
                                                    <input  v-if="editAjusteQuin ==1"  class=" form-control col-md-2" title="Enter para guardar.."  pattern="\d*" type="text"  
                                                    @keyup.enter="editAjusteQuin=0"  
                                                    v-on:keypress="isNumber($event)"  v-model="desc_quin"> 

                                                </template>

                                                 <template v-if="modalVista == '1'">
                                                    <input disabled class="form-control col-md-2" type="number" v-on:keypress="isNumber($event)"  v-model="desc_quin" >

                                                </template>
                                                    <template v-if="modalVista == '0' || modalVista == '2'">
                                                        <button class="form-control col-md-2 btn btn-info " type="button" v-show="desc_quin"  @click="generar_tab = 1 , generarTablaPagos()">Generar</button>
                                                        <button class=" form-control col-md-2 btn btn-warning" type="button" v-show="desc_quin"  @click="generar_tab = 1 , borrarTabla()">Borrar</button>

                                                    </template>




                                        </div>
                                    
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" >Motivo de prestamo.
                                        <span style="color:red;" v-show="motivo">*</span>
                                    </label>
                                    
                                        <textarea class="col-md-6 form-control" cols="10" rows="2"  type="text"  maxlength="50" v-model="motivo" ></textarea>
                                    
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-area-input">Observaciones
                                        <span style="color:red;" v-show="true">*</span>
                                    </label>
                                    
                                        <textarea cols="30" rows="2" type="text" class=" card-text " maxlength="50" v-model="obs_prestamo" ></textarea>
                                    
                                </div> -->

                                   <template v-if="generar_tab == 1" >

                                    <div class="form-group row">
                                        <div class=" col-md-12">
                                            <div class="table-responsive"> 
                                            <table class="table table-bordered table-striped table-sm"> 
                                                <thead>
                                                    <tr>
                                                        <th>No. quincena</th>
                                                        <th>Pago Nomina</th>
                                                        <!-- <th>Pago Extraordinario</th> -->
                                                        <th>Pago Extraordinario</th>
                                                        <th>Saldo</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr  v-for="(pago, index ) in arrayPagos" :key="pago.id"> 
                                                            <td  v-text="pago.id"></td>
                                                            <td v-text="pago.pago"></td>
                                                            <!-- <td class="td2" v-text="'$'+formatNumber(pago.pagoExtra)"></td> -->
                                                            <!--Se agrega un condicional para editar el precio ajuste y validar que solo sean valores numericos -->
                                                            <td class="td2" v-if="editAjuste ==0">
                                                                <a title="Click para editar" href="#" @click="editAjuste=1" v-text="'$'+formatNumber(pago.pagoExtra)" ></a>
                                                            </td>
                                                            <td class="td2"   v-if="editAjuste ==1">
                                                            <input title="Enter para guardar.. Nota: solo guarda el ultimo campo que modifico" class="form-control2" pattern="\d*" type="text"  
                                                                    @keyup.enter="validarMonto(pago.pago,index,$event.target.value),editAjuste=0" step="1"  
                                                                    v-on:keypress="isNumber($event)"  v-model="pago.pagoExtra"> 
                                                            </td>
                                                            <td v-text="pago.saldo"></td>
                                                            <td ></td>
                                                        </tr>
                                                        <tr >
                                                            <td></td>
                                                            <td class=" font-1xl bg-danger " v-text="total"></td>
                                                            <td class=" font-1xl bg-danger " v-text="totalExtra"></td>
                                                            <td class=" font-1xl bg-danger " v-text="saldoFaltante"></td>
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
                          
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                                <div v-if="isRHCurrent && modalVista == '0' && tituloModal !='Nueva Solicitud'">
                                    <button type="button" class="btn btn-success" @click="aprobar_rh(1)">Aprobar</button>
                                    <button type="button" class="btn btn-danger" @click="aprobar_rh(0)">Rechazar</button>
                                </div>
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <div v-if="modalVista == '0'">
                                    <button type="button" class="btn btn-success"   @click="enviarSolicitud()">enviar</button>
                            </div>
                            <div v-if="modalVista == '2'">
                                    <button type="button" class="btn btn-success"   @click="enviarSolicitud()">Guardar</button>
                            </div>

                           


                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!-- inicio modal observaciones  -->
             <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="obs_prestamo" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="guardaObs()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObservaciones" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.observacion" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
       
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
                   // {id:'2',user:'admin', nombre: 'miguelin'}
                ],

                 arrayIdRH:[
                    '10','7','3','2'
                ],
                 arrayIdDir:[
                    '10','7','3',
                ],
                arraySaldo:[],
                arrayObservaciones:[],
                editAjuste:0,
                editAjusteMonto:0,
                editAjusteQuin:0,
                vista_tabla:0,
                modalVista: '0',
                isGerenteCurrent:false,
                isRHCurrent:false,
                isDireccionCurrent:false,
                currentId:null,
                modal:null,
                tituloModal:null,
                arrayDataPrestamos:[],
                generar_tab:0,
                b_status:'',
                b_colaborador:'',
                b_fecha1:'',
                b_fecha2:'',
                e_nombre:'',
                e_id_user:'',

               // variables colaborador //
                id_prestamo:null,
                monto_solic:0,
                motivo:null,
                obs_prestamo:'',
                desc_quin:null,
                fecha_solic:null,
                idJefe:null,
                saldoFaltante:null,
                pago_extraOrd:null,
                total:0,
                totalExtra:0,
                nom_colaborador:'',



                
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
                    var saldo = parseFloat(this.monto_solic );
                    var pagoQ = parseFloat(this.desc_quin )
                    for(var i=0; i<24; i++){
                        
                         var dataObj={'pagoExtra':0};
                            dataObj['id'] =i+1;
                         
                        if(saldo <= 0 ){
                                dataObj['pago']=0
                        }else{
                            if(saldo <= pagoQ ){
                                dataObj['pago']=saldo
                               //this.total += saldo;
                                }
                            else{
                                dataObj['pago']=pagoQ
                               // this.total += pagoQ;
                            }
                        }
                        
                        if(saldo <= 0 ){
                            dataObj['saldo']=0
                          
                        }else{
                            if(saldo <= pagoQ ){
                                saldo -=saldo;
                            }
                            else{
                                saldo -=pagoQ;
                            }
                            dataObj['saldo']=saldo;
                           
                        }
                         
                         this.arrayPagos.push(dataObj)
                       
                    }
                     // terminar  
                    if(saldo > 0){
                        this.saldoFaltante = saldo;
                        

                    }

                    this.arrayPagos.forEach(element => {
                           this.total +=parseFloat(element.pago); 
                           
                        });
                    // console.log(this.saldoFaltante);
                    // console.log(this.arrayPagos);
                    // console.log(this.arraySaldo);
                   

            },

            validarMonto(pagoQ, index ,extra){
                        
                        console.log(pagoQ,index,extra);
                        
                        
                        var extraO = parseFloat(extra);
                        if(index == 0){
                        var saldoAnt = parseFloat(this.monto_solic)
                        }else {
                        var  saldoAnt = parseFloat(this.arrayPagos[index-1].saldo)
                        }
                        var monto=parseFloat(pagoQ) + extraO

                if(parseFloat(saldoAnt) > monto ){
                    this.arrayPagos[index].pagoExtra = extraO;
                    
                    this.arrayPagos[index].saldo = saldoAnt - monto;
                    this.totalExtra +=extraO;
                    this.total -=extraO   // pendinente 
                    this.actualiTabla(index+1,0)
                }else{
                    this.arrayPagos[index].pago = saldoAnt;
                    this.arrayPagos[index].pagoExtra = 0;
                    this.arrayPagos[index].saldo = 0;
                    this.actualiTabla(index+1,1)
                }
            },



            actualiTabla(index,band){
                     
                if(index >=  this.arrayPagos.length ){
                    return 
                }else{
                    
                        console.log('index '+index);
                        for ( index ; index < this.arrayPagos.length; index++) {
                            let saldoAnt = this.arrayPagos[index-1].saldo
                            if(band ==1){
                                this.arrayPagos[index].pago = 0;
                                this.arrayPagos[index].pagoExtra = 0;
                                this.arrayPagos[index].saldo = 0;

                            }else{
                                    if(saldoAnt < this.desc_quin ){
                                        this.arrayPagos[index].pago = saldoAnt;
                                        this.arrayPagos[index].pagoExtra = 0;
                                        this.arrayPagos[index].saldo = saldoAnt - saldoAnt;
                                    }else{
                                        this.arrayPagos[index].pago = this.desc_quin;
                                        this.arrayPagos[index].pagoExtra = 0;
                                        this.arrayPagos[index].saldo = saldoAnt - this.arrayPagos[index].pago  ;
                                    }
                            }
                            
                        }
                      
                    
                }

            },
            guardaObs(){
                let me = this;
                axios.post('/prestamos/post_obs',{
                    'id' :  me.id_prestamo,
                    'obs' : me.obs_prestamo,
                }).then(function (response){
                    me.getObservaciones(me.id_prestamo); 
                    me.obs_prestamo='';
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Comentario guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            getObservaciones(id){
                    let me = this;
                var url = '/prestamos/get_obs?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayObservaciones = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             editarSolicitud(){
                let me = this;
                var url = '/prestamos/editarPrestamo?id=' + this.e_id_user +
                                                        '&monto='+ this.monto_solic +
                                                        '&motivo='+ this.motivo +
                                                        '&desc_quin='+this.desc_quin +
                                                        '&fecha_solic='+ this.fecha_solic +
                                                        '&idJefe=' + this.idJefe;
                axios.put(url).then(function (response) {
                    me.cerrarModal();
                    me.dataPrestamos();
                    
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

            enviarSolicitud(){
                let me = this;
                var url = '/prestamos/registrarPrestamo?id=' + this.userId +
                                                        '&solicitud_id='+ this.id_prestamo +
                                                        '&monto='+ this.monto_solic +
                                                        '&motivo='+ this.motivo +
                                                        '&desc_quin='+this.desc_quin +
                                                        '&fecha_solic='+ this.fecha_solic +
                                                        '&idJefe=' + this.idJefe;
                axios.post(url).then(function (response) {
                    me.cerrarModal();
                    me.dataPrestamos();
                    
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
              aprobar_rh(band){
                let me = this;
                Swal.fire({
                    title: 'AprobarSolicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/prestamos/aprobar_rh',{
                             'band':band,
                            'id' : this.id_prestamo ,
                            'fecha_aprob':this.fecha_solic,

                            
                        }).then(function (response){
                                me.dataPrestamos();
                                me.cerrarModal() 
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Aprobada',
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
                  
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

             nom_col(id_user){
            let me = this;
                var url = '/prestamos/getColaborador?id=' + id_user;
                axios.get(url).then(function (response) {
                    me.nom_colaborador = respuesta.nombre + ' ' +respuesta.apellidos ;
                    var respuesta = response.data;
                     me.e_nombre=respuesta.nombre + ' ' +respuesta.apellidos ;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            dataPrestamos(){
            let me = this;
                var url = '/prestamos/getDataPrestamos?idUser='+this.userId+ 
                            '&isGerenteCurrent='+this.isGerenteCurrent +
                            '&b_colaborador='+ this.b_colaborador +
                            '&isRHCurrent='+ this.isRHCurrent +
                            '&b_fecha1='+ this.b_fecha1 +
                            '&b_fecha2='+ this.b_fecha2 +
                            '&b_status='+ this.b_status +
                            '&isDireccionCurrent='+ this.isDireccionCurrent;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                  
                     me.arrayDataPrestamos = respuesta.data;
                     if(me.arrayDataPrestamos.length > 0){ // Mod
                        me.vista_tabla =1;
                     }
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

             abrirModal(accion,data=[]){
               
                switch(accion){
                    case 'registrar':
                    {
                        const fecha = new Date();
                        const anio = fecha.getFullYear();
                        const mes  = fecha.getMonth()+1;
                        const dia  = fecha.getDate();
                        this.modal=1;
                        this.modalVista='0';
                        this.tituloModal='Nueva Solicitud';
                        this.fecha_solic=anio+'-'+mes+'-'+dia;

                        break;
                    }
                    case 'ver':
                    {
                        this.modal=1;
                        this.modalVista='1';
                        this.tituloModal='Solicitud';
                        this.fecha_solic=data['fecha_ini'];
                        this.monto_solic=data['monto_solicitado'];
                        this.idJefe=data['jefe_id'];
                        this.motivo=data['motivo'];
                        this.desc_quin=data['desc_quin'];
                        this.generarTablaPagos();
                        this.generar_tab=1;
                        break;
                    }
                    case 'editar':
                    {
                        this.modal=1;
                        this.modalVista='2';
                        this.id_prestamo=data['id'];
                        this.tituloModal='Editar Solicitud';
                        this.fecha_solic=data['fecha_ini'];
                        this.monto_solic=data['monto_solicitado'];
                        this.idJefe=data['jefe_id'];
                        this.motivo=data['motivo'];
                        this.desc_quin=data['desc_quin'];
                        this.nom_col(data['user_id']);
                        this.e_id_user=data['user_id'];
                        this.generarTablaPagos();
                        this.nom_colaborador=this.e_nombre;
                        this.generar_tab=1;
                        break;
                       
                      
                    }
                    case 'observaciones':
                    {
                        this.modal=2;
                        this.modalVista='1';
                        this.id_prestamo=data['id'];
                        this.getObservaciones(this.id_prestamo);
                        this.obs_prestamo=null;
                       
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
                this.id_prestamo=null;
                this.tituloModal=null;
                this.modalVista='0';
                this.tituloModal=null;
                this.fecha_solic=null;
                this.monto_solic=0;
                this.idJefe=null;
                this.motivo=null;
                this.desc_quin=null;
                this.e_id_user='';
                this.e_nombre='';
                this.borrarTabla();


            },
            borrarTabla(){
                
                this.arrayPagos=[];
                this.arraySaldo=[];
                this.saldoFaltante=0;
                this.pago_extraOrd=0;


            }
           
        },
        mounted() {
            this.dataColaborador();
            this.isGerenteCurrent_Id();
            this.isRHCurrent_Id();
            this.isDirecCurrent_Id();
            this.dataPrestamos();

            
           
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
