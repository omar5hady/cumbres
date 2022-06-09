<template>
  <main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Calendario</a></strong></li>
    </ol>
    <div class="container-fluid">
      <div class="header">
          <button type="button" @click="nuevo = 1" v-if="nuevo == 0 && rolId != 2" class="btn btn-primary">
              <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="nuevo = 0" v-if="nuevo == 1" class="btn btn-secondary">
              <i class="fa fa-mail-reply"></i>&nbsp;Filtrar
          </button>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-10" v-if="nuevo == 0">
          <div class="row"> 
            <div class="col-md-6">
              <div class="form-group">
                <label >Proyecto de guardia: </label>
                <select class="form-control" v-model="b_proyecto" >
                    <option value="">Fraccionamiento</option>
                    <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                </select>
              </div>
            </div>            
          </div>
          <div class="row"> 
            <div class="col-md-6">
              <div class="form-group">
                <label >Tipo de Evento: </label>
                <select class="form-control" v-model="b_evento" >
                    <option value="">Evento</option>
                    <option value="Vacaciones">Vacaciones</option>
                    <option value="Descanso">Descanso</option>
                    <option value="Guardia">Guardia</option>
                    <option value="Pendientes">Pendientes</option>
                    <option value="Propaganda">Propaganda</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-6">
              <div class="form-group">
                <button type="button" @click="getEvents()" class="btn btn-secondary">
                <i class="fa fa-search"></i>&nbsp;Buscar
                </button>
              </div>
            </div>

          </div>
        </div>

        <div class="col-md-10" v-if="nuevo == 1">
          <form @submit.prevent>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="event_name">TIpo de evento</label>
                  <select class="form-control" @click="changeColor(), newEvent.proyecto_id = 0" v-model="newEvent.event_name" >
                      <option value="Vacaciones">Vacaciones</option>
                      <option value="Descanso">Descanso</option>
                      <option value="Guardia">Guardia</option>
                      <option value="Pendientes">Pendientes</option>
                      <option value="Propaganda">Propaganda</option>
                  </select>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="description">Descripción</label>
                  <input type="text" id="description" class="form-control" v-model="newEvent.description">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="usersName">Usuario: </label>
                  <input v-if="vista==2" disabled type="text" v-model="nombre" class="form-control col-md-4">
                  <button v-if="vista == 2" class="form-control btn btn-sm btn-secondary col-md-1" @click="vista = 1, newEvent.user_id = ''">Cambiar</button>
                  <input v-if="vista==1" type="text" name="user" list="usersName" @keyup="selectUsuario(newEvent.user_id)" @keyup.enter="getNombre(newEvent.user_id)"  class="form-control col-md-4" v-model="newEvent.user_id">
                  <datalist v-if="vista==1" id="usersName">
                      <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                  </datalist>
                  
                </div>
              </div>
            </div>
            <div class="row" v-if="newEvent.event_name == 'Guardia'">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="usersName">Proyecto de guardia: </label>
                  <select class="form-control" v-model="newEvent.proyecto_id" >
                      <option value="0">Fraccionamiento</option>
                      <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                  </select>
                  
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label for="start_date">Dia inicial</label>
                  <input
                    type="date"
                    id="start_date"
                    class="form-control"
                    v-model="newEvent.start_date"
                  >
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="end_date">Dia final</label>
                  <input type="date" id="end_date" class="form-control" v-model="newEvent.end_date">
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="color">Color de etiqueta </label>
                  <input type="color" id="color" class="control"  v-model="newEvent.color">
                </div>
              </div>
              
              
            </div>
            <div class="row">
              
              <template v-if="rolId != 2">
                <div class="col-md-6 mb-4" v-if="addingMode && rolId">
                  <button class="btn btn-sm btn-primary" @click="addNewEvent">Guardar Evento</button>
                </div>
                <template v-else>
                  <div class="col-md-6 mb-4">
                    <button class="btn btn-sm btn-success" @click="updateEvent">Actualizar</button>
                    <button class="btn btn-sm btn-danger" @click="deleteEvent">Borrar</button>
                    <button class="btn btn-sm btn-secondary" @click="addingMode = !addingMode, vista = 1, resetForm()">Cancelar</button>
                  </div>
                </template>
                  
              </template>
              
            </div>
          </form>
        </div>
        <div class="col-md-10">
          <button v-if="calendarOptions.weekends== false" @click="toggleWeekends">Ver Fin de Semana</button>
          <button v-else @click="toggleWeekends">Ocultar Fin de Semana</button>
          <Fullcalendar :options="calendarOptions" :events="events"/>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import Fullcalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from '@fullcalendar/core/locales/es';
import axios from "axios";

export default {
  props:{
    rolId:{type: String}
  },
  components: {
    Fullcalendar
  },
  data() {
    return {
      calendarOptions: {
        eventClick: this.showEvent,
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridWeek',
        duration: { days: 3 },
        //initialView: 'dayGridDay',
        //initialView: 'dayGridMonth',
        //dateClick: this.handleDateClick,
        events: [{}],
        locale: 'es',
        selectable:true,
        weekends: true,

        
      },
      
      events: "",
      newEvent: {
        event_name: "",
        start_date: "",
        end_date: "",
        color :"",
        description:'',
        user_id:'',
        proyecto_id:0,
      },
      addingMode: true,
      indexToUpdate: "",
      arrayUsers:[],
      arrayFraccionamientos:[],
      nombre:'',
      vista:1,
      nuevo:0,
      b_proyecto:'',
      b_evento:'',
    };
  },
  created() {
    this.getEvents();
  },
  methods: {
    getNombre(id){
      
      let me = this;
      var url = '/usuarios/getNombre?id=' + id;
      axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
          me.vista = 2
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
    selectFraccionamientos(){
        let me = this;
        me.arrayFraccionamientos=[];
        var url = '/select_fraccionamiento';
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayFraccionamientos = respuesta.fraccionamientos;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    changeColor(){
      switch(this.newEvent.event_name){
        case 'Vacaciones':{
          this.newEvent.color = '#35974A'
          break;
        }
        case 'Guardia':{
          this.newEvent.color = '#215074'
          break;
        }
        case 'Pendientes':{
          this.newEvent.color = '#AF4716'
          break;
        }
        case 'Propaganda':{
          this.newEvent.color = '#414141'
          break;
        }
        default:{
          this.newEvent.color = '#000000'
          break;
        }
          
      }
    },
    addNewEvent() {
      axios
        .post("/api/calendar", {
          ...this.newEvent
        })
        .then(data => {
          this.getEvents(); // update our list of events
          this.resetForm(); // clear newEvent properties (e.g. title, start_date and end_date)
          this.vista = 1;
        })
        .catch(err =>
          console.log("Unable to add new event!", err.response.data)
        );
    },
    showEvent(arg) {
      this.addingMode = false;
      this.vista = 2;
      const { id, event_name, start, end, color, description, user_id,nombre, proyecto_id } = this.calendarOptions.events.find(
        event => event.id === +arg.event.id
      );
      this.indexToUpdate = id;
      this.newEvent = {
        event_name: event_name,
        start_date: start,
        end_date: end,
        color: color,
        description: description,
        user_id : user_id,
        proyecto_id : proyecto_id,
      };
      this.nombre = nombre;
      this.nuevo = 1;
    },
    toggleWeekends: function() {
      this.calendarOptions.weekends = !this.calendarOptions.weekends // toggle the boolean!
    },
    updateEvent() {
      axios
        .put("/api/calendar/" + this.indexToUpdate, {
          ...this.newEvent
        })
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.vista = 1;
          this.addingMode = !this.addingMode;
        })
        .catch(err =>
          console.log("Unable to update event!", err.response.data)
        );
    },
    deleteEvent() {
      axios
        .delete("/api/calendar/" + this.indexToUpdate)
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.vista = 1;
          this.addingMode = !this.addingMode;
        })
        .catch(err =>
          console.log("Unable to delete event!", err.response.data)
        );
    },
    getEvents() {
      this.selectFraccionamientos();
      axios
        .get("/api/calendar?proyecto="+this.b_proyecto+'&evento='+this.b_evento)
        .then(resp => (this.calendarOptions.events = resp.data.data))
        .catch(err => console.log(err.response.data));
    },
    resetForm() {
      this.nombre = '';
      Object.keys(this.newEvent).forEach(key => {
        return (this.newEvent[key] = "");
      });
      this.newEvent.proyecto_id = 0;
    }
  },
  watch: {
    indexToUpdate() {
      return this.indexToUpdate;
    }
  }
};
</script>

<style lang="css">
@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
.fc-title {
  color: rgb(255, 255, 255);
}
.fc-title:hover {
  cursor: pointer;
}
</style>

