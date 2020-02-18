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
                    <div class="card-body">
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
                    <!---->
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
    userId: { type: String }
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
      url: null
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
    }
  },
  mounted() {
    this.obtenerUsuario();
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
</style>

