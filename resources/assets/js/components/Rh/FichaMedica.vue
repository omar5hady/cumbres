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
                    <i class="fa fa-align-justify"></i> Ficha Medica
                    <div class="button-header">
                        <Button v-if="busqueda.user_id && (userName == 'shady' || userName == 'marce.gaytan')" @click="nuevoMovimiento()"
                            :btnClass="'btn-sucess'"
                            :icon="'icon-plus'"
                        >Nuevo</Button>
                        <!---->
                    </div>
                </div>

                <div class="info-center" v-if="loading">
                    <LoadingComponentVue></LoadingComponentVue>
                </div>


                <div class="card-body" v-else>

                    <div class="form-group row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row" v-if=" (userName == 'shady' || userName == 'marce.gaytan')">
                                <input v-if="vista==2" disabled type="text" v-model="busqueda.nombre" class="form-control col-md-6">
                                <button v-if="vista == 2" class="form-control btn btn-sm btn-primary col-md-3" @click="vista = 1, busqueda.user_id = ''">Cambiar</button>
                                <input v-if="vista==1" type="text" name="user" list="usersName" @keyup="selectUsuario(busqueda.user_id)" @change="getNombre(busqueda.user_id)"  class="form-control col-md-8" v-model="busqueda.user_id">
                                <datalist v-if="vista==1" id="usersName">
                                    <option value="">Seleccione</option>
                                    <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                        </div>
                    </div>

                    <div class="info-center" style="margin-top:50px; margin-bottom:50px;" v-if="mostrar == 0">
                        <center><h4>Aun no se ha cargado información para este usuario</h4></center>
                    </div>

                    <template v-else>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-user">
                                    <!---->
                                    <div class="card-body">
                                        <div class="author">
                                            <img
                                                :src="`/img/avatars/default-image.gif`"
                                                class="avatar border-white"
                                            />
                                            <h4 class="title">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;"
                                                    >
                                                        {{medicalRecord.usuario.nombre}}
                                                    </font>
                                                </font>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-8">
                                            <h5 style="vertical-align: inherit;">
                                                Grupo Sanguineo: <strong>{{medicalRecord.tipo_sangre}}</strong>
                                            </h5>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 style="vertical-align: inherit">Fecha: {{histMedico.fecha}}</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 style="vertical-align: inherit;">
                                                Peso: <strong>{{histMedico.peso}} kg</strong>
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 style="vertical-align: inherit;">
                                                Talla: <strong>{{medicalRecord.estatura}} m</strong>
                                            </h6>
                                        </div>
                                        <div class="col-md-12">
                                            <h6 style="vertical-align: inherit;">
                                                IMC: <strong>{{histMedico.imc}}</strong>
                                            </h6>
                                        </div>
                                        <div class="col-md-12">
                                            <h6 style="vertical-align: inherit;"
                                             v-if="medicalRecord.regimen_alimenticio"
                                            >
                                                Regimen de alimentación: <strong>{{medicalRecord.regimen_alimenticio}}</strong>
                                            </h6>
                                        </div>

                                        <div class="col-md-12">

                                            <Nav :current="medicalRecord.historial.current_page"
                                                :last="medicalRecord.historial.last_page"
                                                @changePage="getRecord"
                                            ></Nav>
                                        </div>
                                    </div>
                                    <!---->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"
                                                    >Afilaciones a servicios de salud </font
                                                >
                                            </font>
                                            &nbsp;
                                            <Button v-if=" (userName == 'shady' || userName == 'marce.gaytan')"
                                            @click="addAfiliacion()"
                                            title="Nuevo" :icon="'icon-plus'"
                                            ></Button>
                                        </h5>
                                        <!---->
                                    </div>
                                    <div class="card-body" style="padding-bottom: 0px;">
                                        <TableVue :cabecera="[
                                            'Proveedor', 'No. Poliza', 'Tipo'
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="afiliacion in medicalRecord.afiliaciones" :key="afiliacion.id">
                                                    <td class="td2">{{afiliacion.proveedor}}</td>
                                                    <td class="td2">{{afiliacion.poliza}}</td>
                                                    <td class="td2">{{afiliacion.tipo}}</td>
                                                </tr>
                                            </template>
                                        </TableVue>
                                    </div>
                                </div>
                            </div>

                            <CardTextVue
                                :title="'Alergias'"
                                :description="medicalRecord.alergias"
                            ></CardTextVue>

                            <CardTextVue
                                :title="'Tratamientos actuales'"
                                :description="histMedico.tratamiento_act"
                            ></CardTextVue>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"
                                                    >Contactos de Emergencia </font
                                                >
                                            </font>
                                            &nbsp;
                                            <Button v-if=" (userName == 'shady' || userName == 'marce.gaytan')"
                                            @click="addContact()"
                                            title="Nuevo contacto" :icon="'icon-plus'"
                                            ></Button>
                                        </h5>
                                        <!---->
                                    </div>
                                    <div class="card-body" style="padding-bottom: 0px;">
                                        <TableVue :cabecera="[
                                            'Nombre', 'Telefono','Telefono 2', 'Dirección', 'Parentesco'
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="contact in medicalRecord.contacts" :key="contact.id">
                                                    <td class="td2">{{contact.nombre}}</td>
                                                    <td class="td2">{{contact.telefono1}}</td>
                                                    <td class="td2">{{contact.telefono2}}</td>
                                                    <td class="td2">{{contact.direccion}}</td>
                                                    <td class="td2">{{contact.parentesco}}</td>
                                                </tr>
                                            </template>
                                        </TableVue>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"
                                                    >Vacunas </font
                                                >
                                            </font>
                                            &nbsp;
                                            <Button v-if=" (userName == 'shady' || userName == 'marce.gaytan')"
                                            @click="addVaccine()"
                                            title="Nuevo" :icon="'icon-plus'"
                                            ></Button>
                                        </h5>
                                        <!---->
                                    </div>
                                    <div class="card-body" style="padding-bottom: 0px;">
                                        <TableVue :cabecera="[
                                            'Vacuna', 'Lote', 'Fecha'
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="vaccine in medicalRecord.vaccines" :key="vaccine.id">
                                                    <td class="td2">{{vaccine.vacuna}}</td>
                                                    <td class="td2">{{vaccine.lote}}</td>
                                                    <td class="td2">{{vaccine.created_at}}</td>
                                                </tr>
                                            </template>
                                        </TableVue>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formularios para historial clinico -->
                        <div id="accordion" role="tablist">
                            <!-- Historial Medico -->
                            <div class="card mb-0">
                                <div class="card-header" id="headingOne" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false"
                                            aria-controls="collapseOne" class="collapsed"
                                            >Historial Médico
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseOne" role="tabpanel"
                                    aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                        <div class="col-md-12"></div>
                                        <CardListGroupVue :titulo="'Antecendentes'"
                                            :data="[
                                                {antecedente : 'Diabetes', especificacion: histMedico.diabetes_esp, estado: histMedico.diabetes},
                                                {antecedente : 'Hipertensión', especificacion: histMedico.hipertension_esp, estado: histMedico.hipertension},
                                                {antecedente : 'Evento epiléptico', especificacion: histMedico.epileptico_esp, estado: histMedico.epileptico},
                                                {antecedente : 'Problema cardíaco', especificacion: histMedico.cardiaco_esp, estado: histMedico.cardiaco},
                                                {antecedente : 'Desmayos y/o Mareos', especificacion: histMedico.mareos_esp, estado: histMedico.mareos},
                                                {antecedente : 'Asma', especificacion: histMedico.asma_esp, estado: histMedico.asma},
                                                {antecedente : 'Toxicomanías', especificacion: histMedico.toxicomanias_esp, estado: histMedico.toxicomanias},
                                                {antecedente : 'Cirugía reciente', especificacion: histMedico.cirugia_esp, estado: histMedico.cirugia},
                                                {antecedente : 'Embarazo y/o Puerperio', especificacion: histMedico.embarazo_esp, estado: histMedico.embarazo},
                                                {antecedente : 'Transfusión', especificacion: histMedico.transfusion_esp, estado: histMedico.transfusion},
                                                {antecedente : 'Lesión Músculo Esquelética', especificacion: histMedico.lesion_musculo_esp, estado: histMedico.lesion_musculo},
                                                {antecedente : 'Ortopédicos', especificacion: histMedico.ortopedicos_esp, estado: histMedico.ortopedicos},
                                            ]"
                                            :data2="[
                                                {antecedente : 'Respiratorios', especificacion: histMedico.respiratorios_esp, estado: histMedico.repiratorios},
                                                {antecedente : 'Oftálmicos', especificacion:histMedico.oftalmicos_esp, estado: histMedico.oftalmicos},
                                                {antecedente : 'Naríz y/u Oídos', especificacion:histMedico.nariz_esp, estado: histMedico.nariz},
                                                {antecedente : 'Neurológicos', especificacion:histMedico.neurologicos_esp, estado: histMedico.neurologicos},
                                                {antecedente : 'Hematológicos', especificacion:histMedico.hematologicos_esp, estado: histMedico.hematologicos},
                                                {antecedente : 'Hepáticos', especificacion:histMedico.hepaticos_esp, estado: histMedico.hepaticos},
                                                {antecedente : 'Aparato Digestivo', especificacion:histMedico.digestivo_esp, estado: histMedico.digestivo},
                                                {antecedente : 'Tiroideo', especificacion:histMedico.tiroideo_esp, estado: histMedico.tiroideo},
                                                {antecedente : 'Dermatológico', especificacion:histMedico.dermatologico_esp, estado: histMedico.dermatologico},
                                                {antecedente : 'Inmunológico', especificacion:histMedico.inmunologico_esp, estado: histMedico.inmunologico},
                                                {antecedente : 'Urinarios', especificacion:histMedico.urinario_esp, estado: histMedico.urinario},
                                                {antecedente : 'Covid-19', especificacion:histMedico.covid_esp, estado: histMedico.covid},
                                            ]"
                                        ></CardListGroupVue>
                                        <CardListGroupVue :titulo="'Psicologicos/Psquiátricos'"
                                            :data="[
                                                {antecedente : 'Cambios en alimentación', especificacion: histMedico.cambio_alimentacion_esp, estado: histMedico.cambio_alimentacion},
                                                {antecedente : 'Aislamiento personal', especificacion: histMedico.aislamiento_esp, estado: histMedico.aislamiento},
                                                {antecedente : 'Sensación de vacío o sin importancia', especificacion: histMedico.sensacion_vacio_esp, estado: histMedico.sensacion_vacio},
                                                {antecedente : 'Impotencia o desesperanza', especificacion: histMedico.desesperanza_esp, estado: histMedico.desesperanza},
                                                {antecedente : 'Confusión, Distracción o Irritabilidad', especificacion: histMedico.irritabilidad_esp, estado: histMedico.irritabilidad},
                                                {antecedente : 'Pensamientos y/o Recuerdos que no salgan de su cabeza', especificacion: histMedico.pensamientos_cabeza_esp, estado: histMedico.pensamientos_cabeza},
                                                {antecedente : 'Pensar Lastimarse a Sí Mismo u Otros', especificacion: histMedico.lastimarse_esp, estado: histMedico.lastimarse},
                                            ]"
                                            :data2="[
                                                {antecedente : 'Alteraciones del Sueño', especificacion: histMedico.alt_sueno_esp, estado: histMedico.alt_sueno},
                                                {antecedente : 'Agotamiento Excesivo', especificacion: histMedico.agotamiento_esp, estado: histMedico.agotamiento},
                                                {antecedente : 'Dolores Inexplicables', especificacion: histMedico.dolores_esp, estado: histMedico.dolores},
                                                {antecedente : 'Aumento en Toxicomanias', especificacion: histMedico.aumento_toxic_esp, estado: histMedico.aumento_toxic},
                                                {antecedente : 'Cambios de humor', especificacion: histMedico.humor_esp, estado: histMedico.humor},
                                                {antecedente : 'Escuchar voces o creer cosas que no son ciertas', especificacion: histMedico.voces_esp, estado: histMedico.voces},
                                                {antecedente : 'Dificultad para realizar tareas diarias', especificacion: histMedico.dif_tareas_esp, estado: histMedico.dif_tareas},
                                            ]"
                                        ></CardListGroupVue>

                                        <CardTextVue
                                            :title="'Tratamiento médico controlado'"
                                            :description="histMedico.medic_controlado"
                                        ></CardTextVue>

                                        <CardTextVue
                                            :title="'Observaciones'"
                                            :description="histMedico.observacion"
                                        ></CardTextVue>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>


                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal nuevo registro-->
        <ModalComponent v-if="modal.mostrar"
            :titulo="modal.titulo"
            @closeModal="closeModal()"
        >
            <template v-slot:body>
                <div class="card-body" v-if="modal.accion == 'nuevo'">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Datos Médicos Generales</a></li>
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Antecedentes</a></li>
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Psicológicos/Psiquiátricos</a></li>
                    </ul>
                    <template v-if="paso == 1"> <!--Datos Generales -->
                    <br>
                        <RowModal :label1="'Grupo y R.H.'">
                            <select class="form-control" v-model="medicalRecord.tipo_sangre">
                                <option value="">Seleccione</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </RowModal>
                        <RowModal :label1="'Peso (kg)'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Talla (m)'">
                            <input v-model="histMedico.peso" type="text" class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)">
                            <template v-slot:input2>
                                <input v-model="medicalRecord.estatura" type="text" class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Alerta'" :clsRow1="'col-md-8'">
                            <textarea v-model="medicalRecord.alerta" class="form-control" cols="50" rows="4"></textarea>
                        </RowModal>
                        <RowModal :label1="'Alergias'" :clsRow1="'col-md-8'">
                            <textarea v-model="medicalRecord.alergias" class="form-control" cols="50" rows="4"
                                placeholder="Especificar agente alérgico y reacción que provoca"
                            ></textarea>
                        </RowModal>
                        <RowModal :label1="'Tratamientos actuales'" :clsRow1="'col-md-8'">
                            <textarea v-model="histMedico.tratamiento_act" class="form-control" cols="50" rows="4"
                                placeholder="Especificar medicamento, dosis y periocidad de toma"
                            ></textarea>
                        </RowModal>
                    </template>

                    <template v-if="paso == 2"> <!--Datos Generales -->
                    <br>
                        <RowModal :label1="'Diabetes'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Respiratorios'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.diabetes" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.diabetes" v-model="histMedico.diabetes_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.respiratorios" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.respiratorios" v-model="histMedico.respiratorios_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Hipertensión'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Oftálmicos'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.hipertension" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.hipertension" v-model="histMedico.hipertension_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.oftalmicos" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.oftalmicos" v-model="histMedico.oftalmicos_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Evento Epiléptico'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Naríz y/u Oídos'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.epileptico" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.epileptico" v-model="histMedico.epileptico_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.nariz" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.nariz" v-model="histMedico.nariz_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Problema Cardíaco'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Neurológicos'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.cardiaco" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.cardiaco" v-model="histMedico.cardiaco_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.neurologicos" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.neurologicos" v-model="histMedico.neurologicos_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Desmayos y/o mareos'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Hematológicos'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.mareos" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.mareos" v-model="histMedico.mareos_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.hematologicos" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.hematologicos" v-model="histMedico.hematologicos_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Asma'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Hepáticos'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.asma" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.asma" v-model="histMedico.asma_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.hepaticos" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.hepaticos" v-model="histMedico.hepaticos_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Toxicomanías'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Aparato digestivo'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.toxicomanias" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.toxicomanias" v-model="histMedico.toxicomanias_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.digestivo" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.digestivo" v-model="histMedico.digestivo_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Cirugía Reciente'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Tiroideo'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.cirugia" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.cirugia" v-model="histMedico.cirugia_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.tiroideo" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.tiroideo" v-model="histMedico.tiroideo_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Embarazo y/o Puerperio'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Dermatológico'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.embarazo" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.embarazo" v-model="histMedico.embarazo_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.dermatologico" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.dermatologico" v-model="histMedico.dermatologico_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Transfusión'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Inmunológicos'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.transfusion" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.transfusion"  v-model="histMedico.transfusion_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.inmunologico" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.inmunologico" v-model="histMedico.inmunologico_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Lesión músculo esquelética'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Urinarios'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.lesion_musculo" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.lesion_musculo"  v-model="histMedico.lesion_musculo_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.urinario" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.urinario" v-model="histMedico.urinario_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="'Ortopédicos'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'COVID-19'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.ortopedicos" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.ortopedicos"  v-model="histMedico.ortopedicos_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.covid" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.covid" v-model="histMedico.covid_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                    </template>

                    <template v-if="paso == 3"> <!--Datos Generales -->
                    <br>
                        <RowModal :label1="'Cambios en alimentación'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Alteraciones del sueño'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.cambio_alimentacion" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.cambio_alimentacion" v-model="histMedico.cambio_alimentacion_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.alt_sueno" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.alt_sueno" v-model="histMedico.alt_sueno_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Aislamiento Personal'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Agotamiento excesivo'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.aislamiento" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.aislamiento" v-model="histMedico.aislamiento_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.agotamiento" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.agotamiento" v-model="histMedico.agotamiento_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Sensación de vacío o sin importancia'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Dolores inexplicables'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.sensacion_vacio" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.sensacion_vacio" v-model="histMedico.sensacion_vacio_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.dolores" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.dolores" v-model="histMedico.dolores_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Impotencia o Desesperanza'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Aumento en Toxicomanias'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.desesperanza" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.desesperanza" v-model="histMedico.desesperanza_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.aumento_toxic" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.aumento_toxic" v-model="histMedico.aumento_toxic_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Confusión, Distracción o Irritabilidad'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Cambios de humor'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.irritabilidad" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.irritabilidad" v-model="histMedico.irritabilidad_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.humor" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.humor" v-model="histMedico.humor_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Pensamientos y/o Recuerdos que no salgan de su cabeza'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Escuchar voces o creer cosas que no son ciertas'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.pensamientos_cabeza" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.pensamientos_cabeza" v-model="histMedico.pensamientos_cabeza_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.voces" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.voces" v-model="histMedico.voces_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :label1="'Pensar lastimarse a si mismo u otros'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'" :label2="'Dificultad para realizar tareas diarias'">
                            <label class="switch switch-default switch-pill switch-success">
                                <input type="checkbox" class="switch-input" v-model="histMedico.lastimarse" checked>
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <input v-if="histMedico.lastimarse" v-model="histMedico.lastimarse_esp"
                                placeholder="Especifique" type="text" class="form-control">
                            <template v-slot:input2>
                                    <label class="switch switch-default switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" v-model="histMedico.dif_tareas" checked>
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <input v-if="histMedico.dif_tareas" v-model="histMedico.dif_tareas_esp"
                                    placeholder="Especifique" type="text" class="form-control">
                            </template>
                        </RowModal>

                        <RowModal :label1="''" :clsRow1="'col-md-12'">
                            <center><h6>En caso de encontrarse bajo tratamiento médico controlado describa las dosis y procedimientos indicados.</h6></center>
                            <textarea v-model="histMedico.medic_controlado" class="form-control" cols="50" rows="2"
                                placeholder="Especificar"
                            ></textarea>
                        </RowModal>

                        <RowModal :label1="'Observaciones'" :clsRow1="'col-md-10'">
                            <textarea v-model="histMedico.observacion" class="form-control" cols="50" rows="2"
                            ></textarea>
                        </RowModal>
                    </template>
                </div>

                <div class="card-body" v-if="modal.accion == 'afiliacion'">
                    <RowModal :label1="'Proveedor'" :clsRow1="'col-md-8'">
                        <input type="text" v-model="afiliacion.proveedor" class="form-control">
                    </RowModal>
                    <RowModal :label1="'No. Poliza'" :clsRow1="'col-md-8'">
                        <input type="text" v-model="afiliacion.poliza" class="form-control" pattern="\d*" maxlength="15" v-on:keypress="$root.isNumber($event)">
                    </RowModal>
                    <RowModal :label1="'Tipo'" :clsRow1="'col-md-8'">
                        <input type="text" v-model="afiliacion.tipo" class="form-control">
                    </RowModal>
                </div>

                <div class="card-body" v-if="modal.accion == 'vaccine'">
                    <RowModal :label1="'Vacuna'" :clsRow1="'col-md-8'">
                        <input type="text" v-model="vaccines.vacuna" class="form-control">
                    </RowModal>
                    <RowModal :label1="'No. Lote'" :clsRow1="'col-md-5'">
                        <input type="text" v-model="vaccines.lote" class="form-control" maxlength="15">
                    </RowModal>
                </div>

                <div class="card-body" v-if="modal.accion == 'contact'">
                    <RowModal :label1="'Contacto'" :clsRow1="'col-md-4'" :label2="'Parentesco'" :clsRow2="'col-md-4'">
                        <input type="text" v-model="contact.nombre" class="form-control">
                        <template v-slot:input2>
                            <input type="text" v-model="contact.parentesco" class="form-control">
                        </template>
                    </RowModal>
                    <RowModal :label1="'Telefono'" :clsRow1="'col-md-4'" :label2="'Telefono 2'" :clsRow2="'col-md-4'">
                        <input type="text" v-model="contact.telefono1" class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)">
                        <template v-slot:input2>
                            <input type="text" v-model="contact.telefono2" class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)">
                        </template>
                    </RowModal>
                    <RowModal :label1="'Email'" :clsRow1="'col-md-6'">
                        <input type="email" v-model="contact.email" class="form-control">
                    </RowModal>
                    <RowModal :label1="'Dirección'" :clsRow1="'col-md-6'">
                        <input type="text" v-model="contact.direccion" class="form-control">
                    </RowModal>
                </div>
            </template>

            <template v-slot:buttons-footer>
                <Button v-if="modal.accion == 'nuevo'" :btnClass="'btn-success'" :icon="'icon-check'" @click="save()">Guardar Registro</Button>
                <Button v-if="modal.accion == 'afiliacion'" :btnClass="'btn-success'" :icon="'icon-check'" @click="storeAfiliacion()">Guardar</Button>
                <Button v-if="modal.accion == 'vaccine'" :btnClass="'btn-success'" :icon="'icon-check'" @click="storeVaccine()">Guardar</Button>
                <Button v-if="modal.accion == 'contact'" :btnClass="'btn-success'" :icon="'icon-check'" @click="storeContact()">Guardar</Button>
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
import TableVue from '../Componentes/TableComponent.vue'
import Nav from '../Componentes/NavComponent.vue'
import CardListGroupVue from './components/CardListGroup.vue'
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
import CardTextVue from './components/CardText.vue'
import Button from '../Componentes/ButtonComponent.vue'

export default {
    components:{
        LoadingComponentVue,
        ModalComponent,
        TableVue,
        Button,
        Nav,
        CardListGroupVue,
        RowModal,
        CardTextVue
    },
    props: {
        userName: { type: String },
        userId: { type: String }
    },
    data() {
        return {
            busqueda: {
                user_id: this.userId,
                nombre:''
            },
            proceso : false,
            histMedico: {
                peso : 50,
                imc: 0,
                tratamiento_act : '',
                medic_controlado : '',
                observacion : '',
                //HISTORIAL MEDICO
                diabetes: false,
                diabetes_esp: '',
                hipertension: false,
                hipertension_esp: '',
                epileptico: false,
                epileptico_esp: '',
                cardiaco: false,
                cardiaco_esp: '',
                mareos: false,
                mareos_esp: '',
                asma: false,
                asma_esp: '',
                toxicomanias: false,
                toxicomanias_esp: '',
                cirugia: false,
                cirugia_esp: '',
                embarazo: false,
                embarazo_esp: '',
                transfusion: false,
                transfusion_esp: '',
                lesion_musculo: false,
                lesion_musculo_esp: '',
                ortopedicos: false,
                ortopedicos_esp: '',
                respiratorios: false,
                respiratorios_esp: '',
                oftalmicos: false,
                oftalmicos_esp: '',
                nariz: false,
                nariz_esp: '',
                neurologicos: false,
                neurologicos_esp: '',
                hematologicos: false,
                hematologicos_esp: '',
                hepaticos: false,
                hepaticos_esp: '',
                digestivo: false,
                digestivo_esp: '',
                tiroideo: false,
                tiroideo_esp: '',
                dermatologico: false,
                dermatologico_esp: '',
                inmunologico: false,
                inmunologico_esp: '',
                urinario: false,
                urinario_esp: '',
                covid: false,
                covid_esp: '',
                //PSICOLOGICOS/PSIQUIATRICOS
                cambio_alimentacion: false,
                cambio_alimentacion_esp: '',
                aislamiento: false,
                aislamiento_esp: '',
                sensacion_vacio: false,
                sensacion_vacio_esp: '',
                desesperanza: false,
                desesperanza_esp: '',
                irritabilidad: false,
                irritabilidad_esp: '',
                pensamientos_cabeza: false,
                pensamientos_cabeza_esp: '',
                lastimarse: false,
                lastimarse_esp: '',
                alt_sueno: false,
                alt_sueno_esp: '',
                agotamiento: false,
                agotamiento_esp: '',
                dolores: false,
                dolores_esp: '',
                aumento_toxic: false,
                aumento_toxic_esp: '',
                humor: false,
                humor_esp: '',
                voces: false,
                voces_esp: '',
                dif_tareas: false,
                dif_tareas_esp : ''
            },
            afiliacion:{
                record_id : '',
                proveedor : '',
                poliza : '',
                tipo : ''
            },
            vaccines:{
                record_id : '',
                vacuna : '',
                lote : ''
            },
            contact :{
                record_id : '',
                nombre : '',
                telefono1 : '',
                telefono2 : '',
                email : '',
                direccion : '',
                parentesco : ''
            },
            medicalRecord:{},
            arrayUsers: [],
            loading: false,
            vista:2,
            mostrar: 0,
            paso: 1,
            modal:{
                mostrar: 0,
                titulo:'',
                accion:'nuevo'
            }
        };
    },
    computed: {},
    methods: {

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
        getRecord(page) {
            let me = this;
            me.medicalRecord = {};
            me.histMedico = {};
            me.loading = true

            const url =
                "/medical-record?page=" +
                page +
                "&user_id=" +
                me.busqueda.user_id;

            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;

                    me.medicalRecord = respuesta.data[0];
                    me.mostrar = 0;
                    if(me.medicalRecord){
                        me.histMedico = me.medicalRecord.historial.data[0];
                        me.busqueda.nombre = me.medicalRecord.usuario.nombre;
                        me.mostrar = 1
                    }

                    me.loading = false
                })
                .catch(function(error) {
                    console.log(error);
                    me.medicalRecord = {};
                    me.histMedico = {};
                    me.loading = false
                });
        },

        save(){
            let me = this;
            if(me.proceso)
                return;
            me.proceso = true;

            axios.post('/medical-record',{
                    'medicalRecord': me.medicalRecord,
                    'histMedico': me.histMedico
                }).then(function (response){
                    me.proceso = false;
                    me.closeModal()

                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Registro guardado correctamente.'
                    })
                }).catch(function (error){
                    console.log(error);
                    me.proceso = false;
                    // me.closeModal();
                });
        },

        storeAfiliacion(){
            let me = this;
            if(me.proceso)
                return;
            me.proceso = true;

            axios.post('/medical/storeAfiliacion',{
                    'record_id': me.afiliacion.record_id,
                    'proveedor': me.afiliacion.proveedor,
                    'poliza': me.afiliacion.poliza,
                    'tipo': me.afiliacion.tipo,
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
                    title: 'Registro guardado correctamente.'
                    })
                }).catch(function (error){
                    console.log(error);
                    me.proceso = false;
                    // me.closeModal();
                });
        },

        storeContact(){
            let me = this;
            if(me.proceso)
                return;
            me.proceso = true;

            axios.post('/medical/storeContact',{
                    'record_id': me.contact.record_id,
                    'nombre' : me.contact.nombre,
                    'telefono1' : me.contact.telefono1,
                    'telefono2' : me.contact.telefono2,
                    'email' : me.contact.email,
                    'direccion' : me.contact.direccion,
                    'parentesco' : me.contact.parentesco
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
                    title: 'Registro guardado correctamente.'
                    })
                }).catch(function (error){
                    console.log(error);
                    me.proceso = false;
                    // me.closeModal();
                });
        },

        storeVaccine(){
            let me = this;
            if(me.proceso)
                return;
            me.proceso = true;

            axios.post('/medical/storeVaccine',{
                    'record_id': me.vaccines.record_id,
                    'vacuna' : me.vaccines.vacuna,
                    'lote' : me.vaccines.lote,

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
                    title: 'Registro guardado correctamente.'
                    })
                }).catch(function (error){
                    console.log(error);
                    me.proceso = false;
                    // me.closeModal();
                });
        },

        addAfiliacion(){
            this.modal.mostrar = 1;
            this.modal.titulo = `Nuevo registro para: ${this.busqueda.nombre}`;
            this.modal.accion = 'afiliacion';
            this.afiliacion = {
                record_id : this.medicalRecord.id,
                proveedor : '',
                poliza : '',
                tipo : ''
            };
        },

        addContact(){
            this.modal.mostrar = 1;
            this.modal.titulo = `Nuevo registro para: ${this.busqueda.nombre}`;
            this.modal.accion = 'contact';
            this.contact = {
                record_id : this.medicalRecord.id,
                nombre : '',
                telefono1 : '',
                telefono2 : '',
                email : '',
                direccion : '',
                parentesco : ''
            }
        },

        addVaccine(){
            this.modal.mostrar = 1;
            this.modal.titulo = `Nuevo registro para: ${this.busqueda.nombre}`;
            this.modal.accion = 'vaccine';
            this.vaccines = {
                record_id : this.medicalRecord.id,
                vacuna : '',
                lote : ''
            }
        },

        nuevoMovimiento(){
            this.modal.mostrar = 1;
            this.modal.titulo = `Nuevo registro para: ${this.busqueda.nombre}`;
            this.modal.accion = 'nuevo'
            this.paso = 1;
            this.medicalRecord = {
                id : null,
                user_id : this.busqueda.user_id,
                alerta : '',
                tipo_sangre : '',
                estatura : 1.50,
                alergias : '',
                regimen_alimenticio : null,
                ...this.medicalRecord
            }
            this.histMedico = {
                peso : this.histMedico.peso ? this.histMedico.peso : 50,
                imc: this.histMedico.paso/(this.medicalRecord.estatura*this.medicalRecord.estatura),
                tratamiento_act : '',
                medic_controlado : '',
                observacion : '',
                //HISTORIAL MEDICO
                diabetes: false,
                diabetes_esp: '',
                hipertension: false,
                hipertension_esp: '',
                epileptico: false,
                epileptico_esp: '',
                cardiaco: false,
                cardiaco_esp: '',
                mareos: false,
                mareos_esp: '',
                asma: false,
                asma_esp: '',
                toxicomanias: false,
                toxicomanias_esp: '',
                cirugia: false,
                cirugia_esp: '',
                embarazo: false,
                embarazo_esp: '',
                transfusion: false,
                transfusion_esp: '',
                lesion_musculo: false,
                lesion_musculo_esp: '',
                ortopedicos: false,
                ortopedicos_esp: '',
                respiratorios: false,
                respiratorios_esp: '',
                oftalmicos: false,
                oftalmicos_esp: '',
                nariz: false,
                nariz_esp: '',
                neurologicos: false,
                neurologicos_esp: '',
                hematologicos: false,
                hematologicos_esp: '',
                hepaticos: false,
                hepaticos_esp: '',
                digestivo: false,
                digestivo_esp: '',
                tiroideo: false,
                tiroideo_esp: '',
                dermatologico: false,
                dermatologico_esp: '',
                inmunologico: false,
                inmunologico_esp: '',
                urinario: false,
                urinario_esp: '',
                covid: false,
                covid_esp: '',
                //PSICOLOGICOS/PSIQUIATRICOS
                cambio_alimentacion: false,
                cambio_alimentacion_esp: '',
                aislamiento: false,
                aislamiento_esp: '',
                sensacion_vacio: false,
                sensacion_vacio_esp: '',
                desesperanza: false,
                desesperanza_esp: '',
                irritabilidad: false,
                irritabilidad_esp: '',
                pensamientos_cabeza: false,
                pensamientos_cabeza_esp: '',
                lastimarse: false,
                lastimarse_esp: '',
                alt_sueno: false,
                alt_sueno_esp: '',
                agotamiento: false,
                agotamiento_esp: '',
                dolores: false,
                dolores_esp: '',
                aumento_toxic: false,
                aumento_toxic_esp: '',
                humor: false,
                humor_esp: '',
                voces: false,
                voces_esp: '',
                dif_tareas: false,
                dif_tareas_esp : ''
            }

        },

        closeModal(){
            this.modal.mostrar = 0
            this.modal.titulo = ''
            this.getNombre(this.busqueda.user_id);
        },

        getNombre(id){
            console.log(id)
            let me = this;
            const url = '/usuarios/getNombre?id=' + id;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.busqueda.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
                me.vista = 2
                me.getRecord(1);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

    },
    created() {
        this.getNombre(this.userId);
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
    width: 120px;
    height: 120px;
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
