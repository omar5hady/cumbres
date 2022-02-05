<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Listado general de productos -->
                <div class="card scroll-box" v-if="vista == 0">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Inventario
                        <!--   Boton Nuevo    -->
                        <button type="button" v-if="userName == 'zaira.valt' || userName == 'shady'" @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarProducto(1)" class="form-control" placeholder="Producto">
                                    <button type="submit" @click="listarProducto(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive"> 
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Producto</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="inventario in arrayInventario.data" :key="inventario.id">
                                        <td class="td2">
                                            <button 
                                            v-if="userName == 'zaira.valt' || userName == 'shady'"
                                            type="button" @click="abrirModal('actualizar',inventario)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>
                                            <button 
                                                type="button" @click="verDetalle(inventario)" class="btn btn-primary btn-sm"
                                                title="Ver detalle"
                                            >
                                                <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="inventario.producto"></td>
                                        <td class="td2" v-text="inventario.unidad"></td>
                                        <td class="td2" v-text="inventario.stock"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayInventario.current_page > 5" @click="listarProducto(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page > 1"
                                    @click="listarProducto(arrayInventario.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayInventario.current_page-3 >= 1"
                                    @click="listarProducto(arrayInventario.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page-2 >= 1"
                                    @click="listarProducto(arrayInventario.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page-1 >= 1"
                                    @click="listarProducto(arrayInventario.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayInventario.current_page+1 <= arrayInventario.last_page">
                                    <a class="page-link" href="#" @click="listarProducto(arrayInventario.current_page+1)" 
                                    v-text="arrayInventario.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayInventario.current_page+2 <= arrayInventario.last_page">
                                    <a class="page-link" href="#" @click="listarProducto(arrayInventario.current_page+2)"
                                     v-text="arrayInventario.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayInventario.current_page+3 <= arrayInventario.last_page">
                                    <a class="page-link" href="#" @click="listarProducto(arrayInventario.current_page+3)"
                                    v-text="arrayInventario.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayInventario.current_page < arrayInventario.last_page"
                                    @click="listarProducto(arrayInventario.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page < 5 && arrayInventario.last_page > 5" @click="listarProducto(arrayInventario.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Listado detalle producto -->
                <div class="card scroll-box" v-if="vista == 1">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Detalle del producto
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="vista = 0, listarProducto(1)" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <h5 class="text-primary" for="">Producto </h5>
                                <h6 v-text="detalle.producto"></h6>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-primary" for="">Stock actual </h5>
                                <h6 v-text="detalle.stock"></h6>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <button v-if="userName == 'zaira.valt' || userName == 'shady'"
                                    type="button" @click="abrirModal('nuevoMovimiento')" class="btn btn-dark">
                                    <i class="fa fa-list-alt"></i>&nbsp;Nuevo movimiento
                                </button>
                            </div>
                        </div>

                        <hr>

                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item" 
                                v-if="userName == 'zaira.valt' 
                                    || userName == 'shady'
                                    || userName == 'alejandro.pe'
                                    || userName == 'ing_david'
                                    || userName == 'eli_hdz'"
                            >
                                <a @click="tab = 1" class="nav-link" v-bind:class="{ 'text-info active': tab==1 }" v-text="'Compras de insumos'"></a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 2" class="nav-link" v-bind:class="{ 'text-info active': tab==2 }" v-text="'Salida de insumos'"></a>
                            </li>
                        </ul>


                        <div class="tab-content" id="myTab1Content">
                            
                            <!-- Compras -->
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==1 }" v-if="tab == 1">
                                <div class="table-responsive"> 
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Concepto</th>
                                                <th>Proveedor</th>
                                                <th>Factura</th>
                                                <th>Cant.</th>
                                                <th>Unidad</th>
                                                <th>P. Unit</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="compra in arrayCompras.data" :key="compra.id">
                                                <td class="td2" v-text="compra.fecha"></td>
                                                <td class="td2" v-text="compra.concepto"></td>
                                                <td class="td2" v-text="compra.nombre"></td>
                                                <td class="td2" v-text="compra.num_factura"></td>
                                                <td class="td2" v-text="compra.cantidad"></td>
                                                <td class="td2" v-text="compra.unidad"></td>
                                                <td class="td2" v-text="'$'+formatNumber(compra.p_unit)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(compra.total)"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                </div>
                                <nav>
                                    <!--Botones de paginacion -->
                                <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item" v-if="arrayCompras.current_page > 5" @click="getCompras(1)">
                                            <a class="page-link" href="#" >Inicio</a>
                                        </li>
                                        <li class="page-item" v-if="arrayCompras.current_page > 1"
                                            @click="getCompras(arrayCompras.current_page-1)">
                                            <a class="page-link" href="#" >Ant</a>
                                        </li>
                                        <li class="page-item" v-if="arrayCompras.current_page-3 >= 1"
                                            @click="getCompras(arrayCompras.current_page-3)">
                                            <a class="page-link" href="#" v-text="arrayCompras.current_page-3"></a>
                                        </li>
                                        <li class="page-item" v-if="arrayCompras.current_page-2 >= 1"
                                            @click="getCompras(arrayCompras.current_page-2)">
                                            <a class="page-link" href="#" v-text="arrayCompras.current_page-2"></a>
                                        </li>
                                        <li class="page-item" v-if="arrayCompras.current_page-1 >= 1"
                                            @click="getCompras(arrayCompras.current_page-1)">
                                            <a class="page-link" href="#" v-text="arrayCompras.current_page-1"></a>
                                        </li>
                                        <li class="page-item active" >
                                            <a class="page-link" href="#" v-text="arrayCompras.current_page"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayCompras.current_page+1 <= arrayCompras.last_page">
                                            <a class="page-link" href="#" @click="getCompras(arrayCompras.current_page+1)" 
                                            v-text="arrayCompras.current_page+1"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayCompras.current_page+2 <= arrayCompras.last_page">
                                            <a class="page-link" href="#" @click="getCompras(arrayCompras.current_page+2)"
                                            v-text="arrayCompras.current_page+2"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayCompras.current_page+3 <= arrayCompras.last_page">
                                            <a class="page-link" href="#" @click="getCompras(arrayCompras.current_page+3)"
                                            v-text="arrayCompras.current_page+3"></a>
                                        </li>

                                        <li class="page-item" v-if="arrayCompras.current_page < arrayCompras.last_page"
                                            @click="getCompras(arrayCompras.current_page+1)">
                                            <a class="page-link" href="#" >Sig</a>
                                        </li>
                                        <li class="page-item" v-if="arrayCompras.current_page < 5 && arrayCompras.last_page > 5" @click="getCompras(arrayCompras.last_page)">
                                            <a class="page-link" href="#" >Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==2 }" v-if="tab == 2">
                                <div class="table-responsive"> 
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Concepto</th>
                                                <th>Oficina</th>
                                                <th>Solicitante</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="salida in arraySalidas.data" :key="salida.id">
                                                <td class="td2" v-text="salida.fecha"></td>
                                                <td class="td2" v-text="salida.concepto"></td>
                                                <td class="td2" v-text="salida.oficina"></td>
                                                <td class="td2" v-text="salida.nombre+ ' '+ salida.apellidos"></td>
                                                <td class="td2" v-text="salida.cantidad"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                </div>
                                <nav>
                                    <!--Botones de paginacion -->
                                <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item" v-if="arraySalidas.current_page > 5" @click="getSalidas(1)">
                                            <a class="page-link" href="#" >Inicio</a>
                                        </li>
                                        <li class="page-item" v-if="arraySalidas.current_page > 1"
                                            @click="getSalidas(arraySalidas.current_page-1)">
                                            <a class="page-link" href="#" >Ant</a>
                                        </li>

                                        <li class="page-item" v-if="arraySalidas.current_page-3 >= 1"
                                            @click="getSalidas(arraySalidas.current_page-3)">
                                            <a class="page-link" href="#" v-text="arraySalidas.current_page-3"></a>
                                        </li>
                                        <li class="page-item" v-if="arraySalidas.current_page-2 >= 1"
                                            @click="getSalidas(arraySalidas.current_page-2)">
                                            <a class="page-link" href="#" v-text="arraySalidas.current_page-2"></a>
                                        </li>
                                        <li class="page-item" v-if="arraySalidas.current_page-1 >= 1"
                                            @click="getSalidas(arraySalidas.current_page-1)">
                                            <a class="page-link" href="#" v-text="arraySalidas.current_page-1"></a>
                                        </li>
                                        <li class="page-item active" >
                                            <a class="page-link" href="#" v-text="arraySalidas.current_page"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arraySalidas.current_page+1 <= arraySalidas.last_page">
                                            <a class="page-link" href="#" @click="getSalidas(arraySalidas.current_page+1)" 
                                            v-text="arraySalidas.current_page+1"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arraySalidas.current_page+2 <= arraySalidas.last_page">
                                            <a class="page-link" href="#" @click="getSalidas(arraySalidas.current_page+2)"
                                            v-text="arraySalidas.current_page+2"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arraySalidas.current_page+3 <= arraySalidas.last_page">
                                            <a class="page-link" href="#" @click="getSalidas(arraySalidas.current_page+3)"
                                            v-text="arraySalidas.current_page+3"></a>
                                        </li>

                                        <li class="page-item" v-if="arraySalidas.current_page < arraySalidas.last_page"
                                            @click="getSalidas(arraySalidas.current_page+1)">
                                            <a class="page-link" href="#" >Sig</a>
                                        </li>
                                        <li class="page-item" v-if="arraySalidas.current_page < 5 && arraySalidas.last_page > 5" @click="getSalidas(arraySalidas.last_page)">
                                            <a class="page-link" href="#" >Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- Fin Listados -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Producto <span style="color:red;" v-show="producto==''">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="producto" class="form-control" placeholder="Nombre del producto">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Unidad <span style="color:red;" v-show="unidad==''">*</span>
                                    </label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="unidad" class="form-control" placeholder="Unidad">
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal nuevo Movimiento-->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de movimiento</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="tipo_mov">
                                            <option value="">Seleccione</option>
                                            <option value="0">Compra</option>
                                            <option value="1">Salida</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Compra -->
                                <template v-if="tipo_mov == '0'">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Fecha <span style="color:red;" v-show="fecha==''">*</span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="fecha" class="form-control" placeholder="Fecha">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Concepto <span style="color:red;" v-show="concepto==''">*</span>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="concepto" class="form-control" placeholder="Concepto">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Proveedor <span style="color:red;" v-show="proveedor==''">*</span>
                                        </label>
                                        <div class="col-md-5">
                                            <select class="form-control" v-model="proveedor">
                                                <option value="">Seleccione</option>
                                                <option v-for="proveedor in arrayProveedores" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Factura <span style="color:red;" v-show="num_factura==''">*</span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="num_factura" maxlength="11" 
                                            pattern="\d*"
                                             v-on:keypress="isNumber($event)"
                                            class="form-control" placeholder="Factura">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Cantidad <span style="color:red;" v-show="cantidad==''">*</span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="number" v-model="cantidad" class="form-control" placeholder="Cantidad">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Precio unitario <span style="color:red;" v-show="p_unit==0 || p_unit == ''">*</span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="p_unit" maxlength="11" 
                                            pattern="\d*"
                                             v-on:keypress="isNumber($event)"
                                            class="form-control" placeholder="Precio unitario">
                                        </div>
                                        <div class="col-md-3">
                                            <label v-text="'$'+formatNumber(p_unit)"></label>
                                        </div>
                                    </div>
                                    
                                    <hr>

                                    <div class="form-group row">
                                        <div class="col-md-3 form-control-label">
                                            <h6 class="text-primary">Total</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 v-text="'$'+formatNumber(total=(p_unit*cantidad))"></h6>
                                        </div>
                                    </div>
                                    
                                </template>
                                <!-- Salida -->
                                <template v-if="tipo_mov == '1'">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Fecha <span style="color:red;" v-show="fecha==''">*</span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="fecha" class="form-control" placeholder="Fecha">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Concepto <span style="color:red;" v-show="concepto==''">*</span>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="concepto" class="form-control" placeholder="Concepto">
                                        </div>
                                    </div>

                                     <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Cantidad <span style="color:red;" v-show="cantidad==''">*</span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="number" @change="validar()" v-model="cantidad" class="form-control" placeholder="Cantidad">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Solicitante <span style="color:red;" v-show="user_id==''">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <input v-if="vista_solic==2" disabled type="text" v-model="nombre" class="form-control col-md-8">
                                            <button v-if="vista_solic == 2" class="form-control btn btn-sm btn-secondary col-md-4" @click="vista_solic = 1, user_id = ''">Cambiar</button>
                                            <input v-if="vista_solic==1" type="text" name="user" list="usersName" @keyup="selectUsuario(user_id)" @change="getNombre(user_id)"  class="form-control col-md-8" v-model="user_id">
                                            <datalist v-if="vista_solic==1" id="usersName">
                                                <option value="">Seleccione</option>
                                                <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">
                                            Oficina <span style="color:red;" v-show="oficina==''">*</span>
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="oficina" class="form-control" placeholder="Oficina">
                                        </div>
                                    </div>
                                    
                                </template>

                                
                                
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipo_mov==0" class="btn btn-primary" @click="registrarCompra()">Guardar</button>
                            <button type="button" v-if="tipo_mov==1" class="btn btn-primary" @click="registrarSalida()">Guardar</button>
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
        },
        data(){
            return{
                arrayInventario : [],
                arrayProveedores : [],

                arrayCompras : [],
                arraySalidas : [],
                arrayUsers : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                buscar : '',
                producto : '',
                unidad : '',
                id : '',
                vista:0,
                detalle : {
                    'id' : '',
                    'producto' : '',
                    'stock' : 0
                },

                tipo_mov:'',
                //Compra
                fecha : '',
                concepto : '',
                proveedor : '',
                num_factura : '',
                cantidad : '',
                p_unit : 0,
                total : '',
                tipo_producto : '',
                user_id : '',
                vista_solic : 1,
                nombre : '',
                //Salida
                oficina : '',
                tab : 1,
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarProducto(page){
                let me = this;
                var url = '/inventarios/getInventario?page=' + page+'&buscar='
                    +this.buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInventario = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getCompras(page){
                let me = this;
                var url = '/inventarios/getCompras?page=' + page + '&id=' + me.detalle.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCompras = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getSalidas(page){
                let me = this;
                var url = '/inventarios/getSalidas?page=' + page + '&id=' + me.detalle.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySalidas = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getProveedor(){
                let me = this;
                var url = '/inventarios/returnProveedor';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProveedores = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectUsuario(buscar){
                let me = this;
                
                me.arrayUsers=[];
                var url = '/usuarios/selectUser?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsers = respuesta.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getNombre(id){
                let me = this;
                var url = '/usuarios/getNombre?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
                    me.vista_solic = 2
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46 ) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            /**Metodo para registrar  */
            registrar(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/inventarios/storeCompra',{
                    'producto' : this.producto,
                    'unidad' : this.unidad
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProducto(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Producto agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            validar(){
                if(this.cantidad > this.detalle.stock)
                    this.cantidad = this.detalle.stock;
            },
            registrarCompra(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/inventarios/storeCompra',{
                    'fecha' : this.fecha,
                    'concepto' : this.concepto,
                    'proveedor' : this.proveedor,
                    'num_factura' : this.num_factura,
                    'cantidad' : this.cantidad,
                    'unidad' : this.unidad,
                    'p_unit' : this.p_unit,
                    'total' : this.total,
                    'stock' : this.unidad,
                    'tipo_producto' : this.tipo_producto,
                    'tipo_mov' : this.tipo_mov
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProducto(1); //se enlistan nuevamente los registros
                    me.getCompras(1);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Compra registrada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            registrarSalida(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/inventarios/storeSalida',{
                    'fecha' : this.fecha,
                    'concepto' : this.concepto,
                    'cantidad' : this.cantidad,
                    'tipo_producto' : this.tipo_producto,
                    'tipo_mov' : this.tipo_mov,
                    'user_id' : this.user_id,
                    'oficina' : this.oficina
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProducto(1); //se enlistan nuevamente los registros
                    me.getSalidas(1);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Compra registrada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizar(){       
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/inventarios/updateInventario',{
                    'id' : this.id,
                    'producto' : this.producto,
                    'unidad' : this.unidad
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProducto(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Producto actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            verDetalle(producto){
                this.detalle = producto;
                this.vista = 1;
                this.tab = 2;
                this.getCompras(1);
                this.getSalidas(1);
            },
            cerrarModal(){
               this.modal = 0;
            },
            
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Producto';
                        this.tipoAccion = 1;
                        this.producto = '';
                        this.unidad = '';
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Vehiculo';
                        this.id = data['id'];
                        this.producto = data['producto'];
                        this.unidad = data['unidad'];
                        this.tipoAccion = 2;
                        break;
                    }
                    case 'nuevoMovimiento':
                    {
                        this.getProveedor();
                        this.modal = 2;
                        this.tituloModal='Nuevo movimiento';
                        this.tipo_mov = '';
                        this.fecha = '';
                        this.concepto = '';
                        this.proveedor = '';
                        this.num_factura = '';
                        this.cantidad = '';
                        this.p_unit = 0;
                        this.total = '';
                        this.tipo_producto = this.detalle.id;

                        this.vista_solic = 1;
                        this.oficina = '';
                        this.user_id = '';
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarProducto(1);
        }
    }
</script>
<style>
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
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
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
        box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
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
