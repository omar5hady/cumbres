<template>
  <main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Calendario</a></strong></li>
    </ol>
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <form @submit.prevent>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="event_name">TIpo de evento</label>
                  <select class="form-control" @click="changeColor()" v-model="newEvent.event_name" >
                      <option value="Vacaciones">Vacaciones</option>
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
        initialView: 'dayGridMonth',
        //dateClick: this.handleDateClick,
        events: [{}],
        locale: 'es',
        selectable:true,
        
      },
      
      events: "",
      newEvent: {
        event_name: "",
        start_date: "",
        end_date: "",
        color :"",
        description:'',
        user_id:''
      },
      addingMode: true,
      indexToUpdate: "",
      arrayUsers:[],
      nombre:'',
      vista:1
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
          this.newEvent.color = '#fff'
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
      const { id, event_name, start, end, color, description, user_id,nombre } = this.calendarOptions.events.find(
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
      };
      this.nombre = nombre;
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
      axios
        .get("/api/calendar")
        .then(resp => (this.calendarOptions.events = resp.data.data))
        .catch(err => console.log(err.response.data));
    },
    resetForm() {
      this.nombre = '';
      Object.keys(this.newEvent).forEach(key => {
        return (this.newEvent[key] = "");
      });
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

