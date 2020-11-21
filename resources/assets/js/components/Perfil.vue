<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <strong>
          <a style="color:#FFFFFF;" href="/">Home</a>
        </strong>
      </li>
    </ol>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-4">
                <div v-for="rem in arrayRemindersLead" :key="rem.id" class="col-sm-12 alert alert-dark alert-dismissible fade show" role="alert">
                    <strong>¡Atención! : </strong> {{rem.comentario}} 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br>
                    Nombre de cliente: {{rem.nombre+" "+rem.apellidos}}<br>
                    Email <a :href="'mailto:'+rem.email" v-text="rem.email"></a>
                    Tel <a :href="'tel:+'+rem.celular" v-text="rem.celular"></a>
                    <br>
                    <div class="px-3 py-2 text-right">
                        <button type="button" class="btn btn-dark rounded" @click="enterado(rem.id)">
                            <span aria-hidden="true">Enterado</span>
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div v-for="rem in arrayReminders" :key="rem.id" class="col-sm-12 alert alert-warning alert-dismissible fade show" role="alert">
                <strong>¡No lo olvides! : </strong> {{rem.comentario}} 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <br>
                Nombre de cliente: {{rem.apellidos+" "+rem.nombre}}<br>
                Email <a :href="'mailto:'+rem.email" v-text="rem.email"></a>
                Tel <a :href="'tel:+'+rem.celular" v-text="rem.celular"></a>
            </div>
        </div>

        <div class="text-center" v-if="arrayCumple.length >= 1 && arrayCumple.length > 1"> <h6>Deséales un Feliz Cumpleaños a tus Clientes</h6> </div>
        <div v-if="arrayCumple.length >= 1 && arrayCumple.length == 1"> <h6>Deséales un Feliz Cumpleaños a tus Clientes</h6> </div>
        
        <div class="row" v-if="arrayCumple.length >= 1 " >
            <div v-for="cumple in arrayCumple" :key="cumple.id" class="col-xl-4 col-lg-5 col-md-4">
                <div class="card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-gradient-primary p-3 mfe-3">
                            
                                <i style="font-size:1.5rem; color:white;" class="fa fa-birthday-cake"></i>
                            
                        </div>
                        <div>
                            <div class="text-value text-primary">&nbsp;&nbsp;&nbsp;{{cumple.nombre.toUpperCase() + ' ' + cumple.apellidos.toUpperCase()}}</div>
                            <div class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; {{this.moment(cumple.f_nacimiento).locale('es').format('DD/MMM/YYYY')}}</div>
                            <div v-if="cumple.clasificacion == 2" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; Tipo A</div>
                            <div v-if="cumple.clasificacion == 3" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; Tipo B</div>
                            <div v-if="cumple.clasificacion == 4" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; Tipo C</div>
                            <div v-if="cumple.clasificacion == 5" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; Venta</div>
                            <div v-if="cumple.clasificacion == 6" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; Cancelado</div>
                            <div v-if="cumple.clasificacion == 1" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; No Viable</div>
                            <div v-if="cumple.clasificacion == 7" class="text-muted text-uppercase font-weight-bold small"> &nbsp;&nbsp;&nbsp; Coacreditado</div>
                        </div>
                    </div>
                    <div>
                        <div class="card-footer px-3 py-2 text-right">
                            <a title="Llamar" class="btn btn-info rounded" :href="'tel:'+cumple.celular"><i class="fa fa-phone fa-lg"></i></a>
                            <a title="Enviar whatsapp" class="btn btn-success rounded" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+cumple.celular+'&text=Feliz cumpleaños'"><i class="fa fa-whatsapp fa-lg"></i></a>
                        </div>
                        <div v-if="rolId != 2 && rolId != 8" class="card-footer px-3 py-2 text-left">
                            <div class="text-muted text-uppercase font-weight-bold small" v-text="'Vendedor ' + cumple.vendedor + ' '+ cumple.vendedor_ap"></div>
                        </div>
                    </div>

                    
                        
                    
                    
                </div>
                
            </div>

           
            
        </div>



        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card2 card-user2">
                    <div class="card-image2">
                        <div><img src="/img/avatars/chuchito.png" alt="..."></div>
                    </div>
                    <!---->
                    <div class="card-body">
                        <div>
                            <div class="author"><img
                                    v-if="url"
                                    :src="url"
                                    class="avatar border-white"
                                    
                                    
                                    >
                                    <img
                                    v-else
                                    :src="'/img/avatars/'+foto_user"
                                    class="avatar border-white"
                                    
                                    
                                    >
                                <h4 class="title">
                                    
                                    <font style="vertical-align: inherit;">
                                        <font v-text="nombre_completo" style="vertical-align: inherit;">
                                        </font>
                                    </font><br><small>
                                            <font style="vertical-align: inherit;">
                                                <font v-text="usuario" style="vertical-align: inherit;"></font>
                                            </font>
                                        </small>
                                </h4>
                            </div>
                            <p class="description text-center">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">
                                        "Soñamos casas, construimos sueños..."
                                    </font>
                                </font>
                            </p>
                        </div>
                        
                        
                    </div>
                    <!---->
                </div>
                
            </div>
            <div class="col-xl-8 col-lg-7 col-md-6">
                <div class="card2 card2">
                    <!---->
                    <div class="card-header">
                        <h4 class="card-title">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Editar perfil</font>
                            </font>
                        </h4>
                        <!---->
                    </div>
                    <div class="card-body" style="padding-bottom: 0px;">
                        <div>
                            <form method="post" @submit="formSubmit" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Empresa
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                  class="form-control" placeholder="Grupo Constructor Cumbres" readonly>
                                            <!---->
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Usuario
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                placeholder="Usuario" class="form-control" v-model="usuario" readonly>
                                            <!---->
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group"><label>
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">Contraseña</font>
                                                </font>
                                            </label><!----><input aria-describedby="addon-right addon-left" type="password"
                                                placeholder="Contraseña" class="form-control" v-model="password">
                                            <!----></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Nombre(s)
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                placeholder="Nombre(s)" class="form-control" v-model="nombre">
                                            <!---->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Apellidos
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                placeholder="Dirección" class="form-control" v-model="apellidos">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-4">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Fecha de nacimiento
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="date"
                                                placeholder="Dirección" class="form-control" v-model="f_nacimiento">
                                            <!---->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label>
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">Celular</font>
                                                </font>
                                            </label><!----><input aria-describedby="addon-right addon-left" maxlength="10" type="text"
                                                placeholder="Celular" class="form-control" v-model="celular">
                                            <!----></div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Dirección
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                placeholder="Dirección" class="form-control" v-model="direccion">
                                            <!---->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Colonia
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                placeholder="Colonia" class="form-control" v-model="colonia">
                                            <!---->
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Código postal
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="text"
                                                placeholder="Código postal" class="form-control" v-model="cp">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Correo Electronico
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input aria-describedby="addon-right addon-left" type="email"
                                                placeholder="Correo Electronico" class="form-control" v-model="email">
                                            <!---->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="control-label">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">
                                                        Cambiar foto de perfil
                                                    </font>
                                                </font>
                                            </label>
                                            <!----><input type="file" class="form-control" v-on:change="onImageChange">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center"><button @click="passwordChange()" type="submit" class="btn btn-primary">
                                        <i class="icon-save"></i>&nbsp;Aplicar cambios
                                        </button></div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body text-right" style="padding:0px;">
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                    <!---->
                </div>
            </div>
        </div>

        <!-- Manual -->
        <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Dentro de la vista principal los usuarios podrán encontrar información relevante a su 
                        perfil e información personal y medio de contacto, los usuarios podrán editar la información, 
                        así como subir una imagen de perfil.
                    </p>
                    <p>
                        En caso de que el usuario sea un asesor en la parte superior de la vista principal además 
                        podrá encontrar tarjetas con el o los nombres de clientes que cumplan años ese día.
                    </p>
                    <p>
                        Además, podrán encontrar en la parte superior derecha un submenú para acceder a la 
                        página principal, agenda de Google y botón de cerrar sesión. 
                    </p>
                    <p>
                        También podan encontrar un icono de campana el cual podrán ver las notificaciones nuevas y antiguas.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
  </main>
</template>

<script>
export default {
  props: {
    userId: { type: String },
    rolId: { type: String }
  },
  data() {
    return {
      id: 0,
      usuario: "",
      password: "",
      nombre_completo: "",
      nombre: "",
      apellidos: "",
      f_nacimiento: "",
      celular: "",
      colonia: "",
      direccion: "",
      cp: 0,

      email: "",
      foto_user: "",
      arrayUsuario: [],
      arrayCumple:[],
      url: null,
      arrayReminders:[],
      arrayRemindersLead:[]
    };
  },

  methods: {
    onImageChange(e) {
      console.log(e.target.files[0]);

      this.foto_user = e.target.files[0];
      this.url = URL.createObjectURL(this.foto_user);
    },

    formSubmit(e) {
      e.preventDefault();

      let formData = new FormData();

      formData.append("foto_user", this.foto_user);
      let me = this;
      axios.post("/users/foto/" + this.userId, formData);
    },

    enterado(id){
        axios
        .put("/comments/leadEnterado", {
          'id': id,
        })
        .then(function(response) {
          location.reload();
        })

        .catch(function(error) {
          currentObj.output = error;
        });
    },

    passwordChange() {
      axios
        .put("/users/update/password", {
          'password': this.password,
          'celular': this.celular,
          'email': this.email,
          'direccion': this.direccion,
          'apellidos': this.apellidos,
          'nombre': this.nombre,
          'colonia': this.colonia,
          'f_nacimiento': this.f_nacimiento,
          'cp': this.cp,
          'id': this.userId
        })
        .then(function(response) {
          location.reload();
          swal({
            position: "top-end",
            type: "success",
            title: "Cambios aplicados correctamente",
            showConfirmButton: false,
            timer: 2000
          });
        })

        .catch(function(error) {
          currentObj.output = error;
        });
    },

    getBirthdayPeople(){
        let me = this;
        me.arrayCumple = [];
        var url = "/getBirthdayPeople";
        axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCumple = respuesta.people;
        })
        .catch(function(error) {
          console.log(error);
        });

    },

    obtenerUsuario() {
      let me = this;
      me.arrayUsuario = [];
      var url = "/usuario/datos?id=" + this.userId;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayUsuario = respuesta.usuario;
          me.usuario = me.arrayUsuario[0].usuario;
          me.nombre_completo = me.arrayUsuario[0].n_completo;
          me.foto_user = me.arrayUsuario[0].foto_user;
          me.celular = me.arrayUsuario[0].celular;
          me.direccion = me.arrayUsuario[0].direccion;
          me.colonia = me.arrayUsuario[0].colonia;
          me.cp = me.arrayUsuario[0].cp;
          me.nombre = me.arrayUsuario[0].nombre;
          me.apellidos = me.arrayUsuario[0].apellidos;
          me.f_nacimiento = me.arrayUsuario[0].f_nacimiento;
          

          me.email = me.arrayUsuario[0].email;
          me.password = "";
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    getReminder(){
        axios.get('/comments/reminder/').then(
            response => {
                console.log(response);
                this.arrayReminders = response.data;
            }
        ).catch(error => console.log(error));
    },
    getReminderLeads(){
        axios.get('/comments/reminderCommentarioLead/').then(
            response => {
                console.log(response);
                this.arrayRemindersLead = response.data;
            }
        ).catch(error => console.log(error));
    }
  },
  mounted() {
    this.obtenerUsuario();
    this.getBirthdayPeople();
    this.getReminder();
    this.getReminderLeads();
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

