<template>
<div class="conte_principal">

      <div class="content_grid">
        
        <div class="top">
          
        </div>

        <div v-if="!muestraPremio"  class="div_tr">
            <img class="img_tr" src="/img/ruleta/flecha.png" alt="">
        </div>

        <div v-if="!muestraPremio" class="grid_ruleta">
          <div class="content_ruleta">
          <img class="anim" src="/img/ruleta/Ruleta.png" alt="">
          </div>
        </div>
        <div  v-if="muestraPremio" class="div_fanfar_izq" >
            <img  src="/img/ruleta/fanfarria1_Izq.png" width="100%" height="100%"  alt="">
         
        </div>
        <div v-if="muestraPremio"  class="div_fanfar_der" >
          <img  src="/img/ruleta/fanfarria2_Der.png" width="100%" height="100%"  alt="">
        
        </div>
         
        <div  v-if="muestraPremio" class="grid_ruleta">
            <img v-if="vP =='3'" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/3mil.png" alt="">
            <img v-if="vP =='5'" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/5mil.png" alt="">
            <img v-if="vP =='8'" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/8mil.png" alt="">
            <img v-if="vP =='10'" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/10mil.png" alt="">
            <img v-if="vP =='15'" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/15mil.png" alt="">
            <img v-if="vP =='0'" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/GraciasxParticipar.png" alt="">
         
        </div>
       
      
        <div v-if="div_registra" class="div_registra_d">
          <div class="form_registra">
              <label for="nom"> Nombre(s) </label>
              <input v-model="no" class="form-control" style="font-size: x-large; font-family: sans-serif; border-radius: 10px;" type="text" name="" id="nom">
              <label  for="ape"> Apellido(s) </label>
              <div  style=" display: flex; flex-direction: row;">
                  <input v-model="app" class="form-control col-md-6" placeholder="Apellido Paterno" style="font-size: x-large; font-family: sans-serif; border-top-left-radius: 10px;border-bottom-left-radius: 10px;" type="text" name="" id="ape">
                  <input v-model="apm" class="form-control col-md-6" placeholder="Apellido Materno" style="font-size: x-large; font-family: sans-serif; border-top-right-radius: 10px;border-bottom-right-radius: 10px;" type="text" name="" id="ape">

              </div>
              <label  for="f_nac"> Fecha de nacimiento </label>
              <input v-model="fn" class="form-control" style="font-size: x-large; border-radius: 10px;" type="date" name="" id="f_nac">
              
               <div v-if="validDatos" class="mensaje-error" style="font-size: 16px; padding-top: 30px; margin-left: 100px;">Llene todos los campos requeridos.</div>

          </div>
         
          <a class="aviso_priv" style=" margin-left:220px;" target="_blank"  href="https://www.casascumbres.mx/wp-content/uploads/2022/09/AvisoPrivacidad2022Nom-1.pdf">Aviso de privacidad</a>
        </div>
          <div v-if="div_registra" class="div_boton_gl">
              <img style="width: 240px; height: 50px;" src="/img/ruleta/BuenaSuerte_boton.png" @click="btn_gl()" alt="">
          </div>  
          

          <div v-if="btn_girar" class="div_girar">
              <img class="img_girar" src="/img/ruleta/boton_girar.png" @click="registraD()">
          </div> 

          <div v-if="form_input_cw" class="div_legenda">
               <img  src="/img/ruleta/donde_recibir.png">
          </div>
          <div v-if="form_input_cw" class="div_enviar">
                <img @click="btn_email = true" class="img_enviar" src="/img/ruleta/mail_icon.png">
                <img @click="btn_waths = true" class="img_enviar" src="/img/ruleta/whats_App_icon.png">
          </div>

          <div v-if="btn_email || btn_waths" class="div_corr_wts">
             
             <div v-if="btn_email" class="form_corr_wts">
              <!-- <label  for="correo">Correo</label> -->
              <input v-model="reg_correo" placeholder="Correo electronico" class="form-control" style="font-size: x-large; font-family: sans-serif; border-radius: 10px;" type="text" name="" id="correo" >
              <a class="aviso_priv"  target="_blank"  href="https://www.casascumbres.mx/wp-content/uploads/2022/09/AvisoPrivacidad2022Nom-1.pdf">Aviso de privacidad</a>
            </div> 

            <div v-if="btn_waths" class="form_corr_wts">
              <!-- <label  for="cw">WhatsApp</label> -->
              <input v-model="reg_whts" placeholder="Whats app" class="form-control" maxlength="10" style="font-size: x-large; font-family: sans-serif; border-radius: 10px;" type="text" name="" id="cws" v-on:keypress="isNumber($event)"> 
              <a class="aviso_priv"  target="_blank"  href="https://www.casascumbres.mx/wp-content/uploads/2022/09/AvisoPrivacidad2022Nom-1.pdf">Aviso de privacidad</a>
            </div>

          </div>

            <div v-if="btn_email || btn_waths" class="div_boton_cw">
                  <img @click="putCorreoWhats()" style="width: 140px; height: 50px;" src="/img/ruleta/enviar_boton.png" alt="">
                  <div v-if="validCorreo" class="mensaje-error" style="font-size: 16px;  margin-left: -20px; width: 200px;">
                    Ingrese un correo válido.</div>
            </div> 

          
      </div>

</div>
</template>

<script>
import VueConfetti from 'vue-confetti';
Vue.use(VueConfetti)
export default {
   props:{
      dataLead:{type:String},
      dataPremio:{type:String},

    },
  data(){
    return{
      premio:'',
      descripcion:'',
      muestraPremio:false,  // false inicio
      vP:'3',
      btn_girar:true,
      no:'',
      app:'',
      apm:'',
      fn:'',
      div_registra:false,
      folio:'',
      leadRegistrado:'',
      premioCliente:'',
      lead_id:this.dataLead,
      con_premio:this.dataPremio,
      form_input_cw:false,
      btn_email:false,
      btn_waths:false,
      reg_correo:'',
      reg_whts:'',
      formatoCorreo:/^[a-zA-Z0-9_-]+[a-zA-Z0-9._-]*@[a-zA-Z0-9._-]+\.[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?$/,
      validCorreo:false,
      validDatos:false,

      

    }
  },
 

  methods:{

    registraD(){
       if(this.no !='' && this.app !='' && this.apm !='' && this.fn !=''){
          this.postLead()
          
      }else{
         this.div_registra=true;
      }



    },

    btn_gl(){
      

      if(this.no !='' && this.app !='' && this.apm !='' && this.fn !=''){
        this.div_registra=false;
        this.validDatos=false;

      }else{
        this.validDatos=true;
      }

    },

    play(vlp){

      this.btn_girar=false

      let sound = new Audio('/sound/ruleta/ruleta_audio.mp3')
                  sound.play();

                  var img_tr =$('.img_tr');
                  img_tr[0].style.animationDuration="70ms"
                  img_tr[0].style.animationIterationCount="135"

                var element = $('.anim'); // acceso a las propiedades de CCS de la clase .anim
              
                  switch (vlp) {
                    case '8':
                        element[0].style.animation="carousel8"
                        element[0].style.transform="rotate(7230deg)"
                      break;
                  
                    case '5':
                        element[0].style.animation="carousel5"
                        element[0].style.transform="rotate(7260deg)"
                      
                      break;
                    case '3':
                        element[0].style.animation="carousel3"
                        element[0].style.transform="rotate(7290deg)"
                      
                      break;
                    case '0':
                        element[0].style.animation="carousel0"
                        element[0].style.transform="rotate(7320deg)"
                      
                      break;
                    case '15':
                        element[0].style.animation="carousel15"
                        element[0].style.transform="rotate(7350deg)"
                      
                      break;
                    case '10':
                        element[0].style.animation="carousel10"
                        element[0].style.transform="rotate(7380deg)"
                      
                      break;
                  
                  
                    default:
                      break;
                  }

                  element[0].style.animationDuration="6500ms"
                  element[0].style.animationTimingFunction="cubic-bezier(0.150, 0.000, 0.000, 1.000);"
                  element[0].style.animationIterationCount="1"
                  element[0].style.transformOrigin="center"


                  // const color = element.css('color');

                  // element.css('color', 'red');
              if(vlp !='0'){
                setTimeout(() => {
                this.$confetti.start();
              }, "8000")

              }

              setTimeout(()=>{
                  this.muestraPremio=true
                  this.vP=vlp  
                  this.form_input_cw=true;
              },"8500")



    },
    CreateRFC(nombre,apPat,apMat,fechaN){

                var special = ['!','"','#','$','%','&','(',')','*','+',',','-','.','/','^',
                                '[',']','{','}','~',"'"];
                var prepo = ["DA", "DAS", "DE", "DEL", "DER", "DI", "DIE", "DD", "EL", "LA",
                            "LOS", "LAS", "LE", "LES", "MAC", "MC", "VAN", "VON", "Y"];
                var i;

                //{{{{{{{{{{{{{{{{{{paterno}}}}}}}}}}}}}}}}}}
                var paterno = apPat.toUpperCase();
                var paterno2 = paterno.split(" ");
                var paternoL ="";

                if(paterno2.length > 1){
                    var paterno1 ="";
                    if(prepo.indexOf(paterno2[0]) >= 0){
                        for(i = 1; i < paterno2.length; i++){
                            paterno1 = paterno1+paterno2[i]+" ";
                        }
                    }else{
                        for(i = 0; i < paterno2.length; i++){
                            paterno1 = paterno1+paterno2[i]+" ";
                        }
                    }
                    paterno = paterno1.split("");
                }else{paterno = paterno2[0].split("");}

                for (i = 1; i < 100; i++) {
                    if (paterno[i] == "A") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "E") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "I") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "O") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "U") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                }

                if(paternoL == ""){ paternoL = "X" }
                if(paterno[0] == "\u00d1"){paterno[0]="X"; }//para la Ñ
                if(special.indexOf(paterno[0]) > 0){ paterno[0] = "X";}
                if(special.indexOf(paternoL) > 0){ paternoL = "X";}

                //{{{{{{{{{{{{{{{{{{materno}}}}}}}}}}}}}}}}}}
                var materno;
                materno = apMat.toUpperCase();
                var materno2 = materno.split(" ");

                if(materno2.length > 1){
                    var materno1 = "";
                    if(prepo.indexOf(materno2[0]) >= 0){
                        for(i = 1; i < materno2.length; i++){
                            materno1 = materno1+materno2[i]+" ";
                        }
                    }else{
                        for(i = 0; i < materno2.length; i++){
                            materno1 = materno1+materno2[i]+" ";
                        }
                    }
                    materno = materno1.split("");
                }else{materno = materno2[0].split("");}

                if(materno == ""){materno[0]="X"; }
                if(materno[0] == "\u00d1"){materno[0]="X"; }//para la Ñ
                if(special.indexOf(materno[0]) > 0){ materno[0] = "X";}

                //{{{{{{{{{{{{{{{{{{nombre}}}}}}}}}}}}}}}}}}
                var names = ["MARIA","MA.","MA","JOSE","J","J.","DA","DAS", "DE", "DEL", "DER",
                            "DI", "DIE", "DD", "EL", "LA", "LOS", "LAS", "LE", "LES", "MAC",
                            "MC", "VAN", "VON", "Y"];
                var name;
                name = nombre.toUpperCase();
                var name2 = name.split(' ');

                if(name2.length > 1){
                    var name1 = "";
                    if(names.indexOf(name2[0]) >= 0){
                        for(i = 1; i < name2.length; i++){
                            name1 = name1+name2[i]+" ";
                        }
                    }else{
                        for(i = 0; i < name2.length; i++){
                            name1 = name1+name2[i]+" ";
                        }
                    }
                    name = name1.split("");
                }else{name = name2[0].split("");}

                if(name[0] == "\u00d1"){name[0]="X"; }//para la Ñ
                if(special.indexOf(name[0]) > 0){ name[0] = "X";}

                //{{{{{{{{{{{{{{{{{{part 1}}}}}}}}}}}}}}}}}}
                var ants = ["BACA","BAKA","BUEI","BUEY","CACA","CACO","CAGA","CAGO","CAKA",
                            "CAKO","COGE","COGI","COJA","COJE","COJI","COJO","COLA","CULO",
                            "FALO","FETO","GETA","GUEI","GUEY","JETA","JOTO","KACA","KACO",
                            "KAGA","KAGO","KAKA","KAKO","KOGE","KOGI","KOJA","KOJE","KOJI",
                            "KOJO","KOLA","KULO","LILO","LOCA","LOCO","LOKA","LOKO","MAME",
                            "MAMO","MEAR","MEAS","MEON","MIAR","MION","MOCO","MOKO","MULA",
                            "MULO","NACA","NACO","PEDA","PEDO","PENE","PIPI","PITO","POPO",
                            "PUTA","PUTO","QULO","RATA","ROBA","ROBE","ROBO","RUIN","SENO",
                            "TETA","VACA","VAGA","VAGO","VAKA","VUEI","VUEY","WUEI","WUEY"];

                var p1 = paterno[0] + paternoL + materno[0] + name[0];

                if(ants.indexOf(p1) >= 0){
                    var x = p1.split("");
                    x[1] = "X";
                    p1="";
                    for(i=0; i < 4; i++){
                        p1=p1+x[i];
                    }
                }

                //{{{{{{{{{{{{{{{{{{date}}}}}}}}}}}}}}}}}}
                var date = fechaN.split('');
                var p2 = date[2]+date[3]+date[5]+date[6]+date[8]+date[9];

              
                return  p1 + p2;
            },


    
    checkPremioFolio(folio,registrado) { // verifica el premio segun su folio y si ya esta registrado
                // 3mil  1-9...  5mil 10-20 ,!=50 ... 8mil 50-150-250...  10mil 100, 200, !=1000 ...  15mil -1000 , 2000

            if (registrado != '1') {
                  var select_premio;
                  var premDiezMil=[];     
                  var premOchoMil=[];     
                  var premCincoMil=[];     
                  var premTresMil = [];    
                  var premQuinceMil=[];    
                  
                  for (let index = 1; index <= 100; index++) { 
                      premQuinceMil.push(index*1000)
                  }
                  for (let index = 1; index <= 100; index++) { 
                    if (((index*100)%1000) !=0){
                      premDiezMil.push(index*100)
                      
                    }
                  }

                  for (let index = 1; index <= 100 ; index++) {
                    if ((index % 2) != 0) {
                      premOchoMil.push(index *50)   
                    }
                  }
                  
                  for (var i = 0; i < 1000; i++) {
                    if ((i*10) % 50 !== 0 && (i*10) % 100 !== 0) {
                    premCincoMil.push(i*10);
                    }
                  }
                    for (var i = 0; i < 8000; i++) {
                      if (i % 10 !== 0) {
                      premTresMil.push(i);
                    }
                  }
      
                  select_premio = premTresMil.includes(folio);
                  if(!select_premio){
                  select_premio = premCincoMil.includes(folio);
                      if (!select_premio){
                        select_premio = premOchoMil.includes(folio);
                          if(!select_premio){
                            select_premio = premDiezMil.includes(folio);
                              if(!select_premio){
                                select_premio = premQuinceMil.includes(folio);
                                  if (select_premio) {
                                    this.premioCliente='15';
                                    this.premio='15000';
                                    this.descripcion='Premio de 15000 pesos ';
                                  }
                              }
                              else{
                                this.premioCliente='10';
                                    this.premio='10000';
                                    this.descripcion='Premio de 10000 pesos ';
                              }
                          }
                          else{
                            this.premioCliente='8';
                            this.premio='8000';
                            this.descripcion='Premio de 8000 pesos ';
                          }  
                      }else{
                        this.premioCliente='5';
                        this.premio='5000';
                        this.descripcion='Premio de 5000 pesos ';
                      }  
                  }else {
                    this.premioCliente='3';
                    this.premio='3000';
                    this.descripcion='Premio de 3000 pesos ';
                  } 


              
            }else{
              this.premioCliente='0';  // por defecto si el cliente esta registrado se le asigna el de sigue participando
              this.premio='0';
              this.descripcion='Cliente ya registrado en prospectos';
            }
              
              this.completaPremio(folio);

    },
    postLead(){ // guarda los datos registrados en la tabla leads  Creando su RFC sin homoclave
        let me = this
       const rfc = this.CreateRFC(this.no ,this.app,this.apm,this.fn )

          axios.post('premios/store',{
                  'rfc':rfc,
                  'lead_id':me.lead_id,
                  'nombres':me.no,
                  'apep':me.app,
                  'apem':me.apm,
                  'fenac':me.fn,                  
              }).then(function(response){
                  var respuesta=response.data;
                  me.folio=respuesta.folio;
                  me.leadRegistrado=respuesta.registrado
                  console.log(me.folio,me.leadRegistrado);
                  me.checkPremioFolio(me.folio,me.leadRegistrado); // verifica el premio segun su folio y si ya esta registrado
                  me.play(me.premioCliente); // por ultimo hace la animacon de la ruleta
              }).catch(function(error){

              });
    },
    completaPremio(folio){ // completa los campos faltantes de el premio segun su folio asignado 
            let me = this;
                axios.put('datosPremio/completa', {
                  'id':folio,
                  'premio': me.premio,
                  'descripcion':me.descripcion,
                }).then(function (response){


                }).catch(function(error){
                    console.log(error);

                });
    },
     putCorreoWhats(){ // guarda el correo o el whatasapp a la tabla de LEADS
        if (!this.checkForm()) {
          return
        }
      let me = this;
                axios.put('/premios/reg_corr_whats', {
                  'lead_id':me.lead_id,
                   'reg_correo':me.reg_correo,
                    'reg_whts':me.reg_whts,
                }).then(function (response){
                   // me.form_input_cw=false;
                    me.btn_email=false;
                    me.btn_waths=false;
                    me.reg_correo='';
                    me.reg_whts='';
                    me.validCorreo=false;

                }).catch(function(error){
                    console.log(error);

                });

    },
    isNumber: function(evt) { // valida que solo sean numero en el input de whatsapp
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
    },
    checkForm(){  // valida el formato de correo que sea valido
            if (this.formatoCorreo.test(this.reg_correo) || this.btn_waths){
              
              return true;
            }else{
              this.validCorreo=true;
              return false;
        }
    },

  },
   
    

}


</script>

<style>
body{
  background-image: url('/img/ruleta/Background.png');
   background-position: center;
    background-size:cover;
    background-repeat: no-repeat;
    z-index: -4;
}
.conte_principal{
    overflow: hidden;
    max-width: 1260px;
    min-width: 1260px;

    max-height: 1500px;
    z-index: -3;

}
.content_grid{
  display: grid;
   /* grid-template-rows: repeat(30,1fr);  */
   /* bloques de 50 px  halto */
  /* grid-template-columns: repeat(21,1fr);  */
  /* loques de 60px  ancho */
  /* grid-auto-rows: minmax(100px, auto);
  grid-auto-columns:minmax(100px, auto); */
   grid-template-columns: repeat(21, 60px);
  grid-template-rows: repeat(30, 50px);

  background-image: url('/img/ruleta/Background.png');
  background-position: center;
  width: 100%;
  height: 100%; 
  z-index: -2; 
}

  .top{
    background-image: url('/img/ruleta/Participa_y_Gana.png');
    grid-column: 7/16;
    grid-row: 2/9;
    width: 540px;
    height: 350px;
    z-index: 1;
  }

.grid_ruleta{
  grid-column: 7/16;
  grid-row:8/19;
  width: 540px;
  height: 550px;
  z-index: 0;
}
.div_premios{
  grid-column: 7/16;
  grid-row:8/19;
  width: 540px;
  height: 550px;
}

.div_fanfar_izq{
  grid-column: 1/6;
  grid-row:2/13;
  width: 540px;height: 550px; 
  z-index:0;
  
  
}
.div_fanfar_der{
  grid-column: 12/17;
  grid-row:2/13;
  width: 540px;height: 550px; 
  z-index: 0;
  
  
}


.div_tr{   
  grid-column: 11;
  grid-row:9;
  z-index: 2;
  width: 60px;
  height: 50px;
  /* margin-top: 20px; */
    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

}
.img_tr{
  width: 60px;
  height: 70px;
  margin-top: -20px;

  animation: flecha;
  animation-duration:800ms;
  animation-timing-function:linear;
  animation-iteration-count:infinite;
  animation-play-state:running;
  

}

.content_ruleta{
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  align-content: center;
  margin: auto;
  width: 100%;  /* todo el ancho de el componente padre */
  height: 540px; /* se ajusta a el ancho */
  z-index: 0;

}

.div_girar{
  grid-column: 10/13;
  grid-row:19/21;
  width: 180px;
  height: 100px;
    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  
}
.img_girar{
    width: 180px;
    height: 80px;
}
.img_girar:hover{
    cursor: pointer;
    transform: scale(120%);
}
.div_legenda{
  grid-column: 8/15;
  grid-row:19;
  width: 420px;
  height: 50px;
    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.div_enviar{
  grid-column: 10/14;
  grid-row:20;
  width: 240px;
  height: 50px;
    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: row;
  justify-content:space-between;
  align-items: center;
}
.img_enviar{
    margin-top: 40px;
    
    width: 70px;
    height: 60px;

}
.img_enviar:hover{
  cursor: pointer;
  transform: scale(115%);
}
.div_registra_d{
  grid-column: 6/17;
  grid-row:4/21;
  width: 630px;
  height: 850px;

  background-image: url('/img/ruleta/formulario1.png');

    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content:center;
  align-items:flex-start;
  z-index: 5;
  border-radius: 20px;
}
.form_registra{
    display: flex;
    flex-direction: column;
    align-items:flex-start;
    width: 600px;
    height: 450px;
    margin-top: 150px;
   padding: 60px;

    z-index: 6;
    color: aliceblue;
    font-size: x-large;
    font-family: sans-serif;
    
    
}

.div_boton_gl{
  grid-column: 9/14;
  grid-row: 19;
  width: 240px;
  height: 50px;

    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content:center;
  align-items:flex-start;
  z-index: 6;
}
.div_boton_gl:hover{
  cursor: pointer;
  transform: scale(115%);
}

.div_corr_wts{
   grid-column: 7/17;
  grid-row: 13/23;
  width: 600px;
  height: 450px;

  background-image: url('/img/ruleta/formulario2.png');
  background-repeat: no-repeat;

    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content:center;
  align-items:flex-start;
  z-index: 3;

}
.form_corr_wts{
    display: flex;
    flex-direction: column;
    align-items:center;
    align-content: space-between;
    width: 420px;
    height: 250px;
    margin: 60px;
    padding-top: 60px;
    z-index: 4;



    color: aliceblue;
    font-size: x-large;
    font-family: sans-serif;
}
.aviso_priv{

  font-size: 20px; 
  color: tan;
  margin-top: 180px;
  margin-left: -10px;
  
}

.aviso_priv:hover{
  cursor: pointer;
  color: rgb(240, 232, 221);
  text-decoration: none;
}

.div_boton_cw{
  grid-column: 10/13;
  grid-row: 19;
  width: 140px;
  height: 50px;

    margin-left: 10px;

    /* si se desea alineacion dentro de este contenedor se añaden reglas de flexbox*/    
  display: flex;
  flex-direction: column;
  justify-content:center;
  align-items:flex-start;
  z-index: 3;
}
.div_boton_cw:hover{
  cursor: pointer;
  transform: scale(115%);
}



   .anim {
		width: 100%;
		height: 100%;
    transform-origin: center;
    animation: carousel;
    animation-duration:8000ms; /* sonido*/
    animation-timing-function:linear;
    animation-iteration-count:infinite;
    animation-play-state:running;

    

  }
  .mensaje-error{
      color:rgb(237, 33, 10);
      font-size: 14px;
      left: 300px;
      padding-top: 30px;
      margin-left: 100px;
      
  }



     @keyframes flecha {  
      from{
           -moz-transform: rotate(0deg);
           -webkit-transform: rotate(0deg);
           -ms-transform: rotate(0deg);
           
      }
      to { 
          -moz-transform: rotate(-15deg); 
          -webkit-transform: rotate(-15deg); 
          -ms-transform: rotate(-15deg); 
        
        }

	
}
     @keyframes carousel {  
      from{
        -moz-transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg); 
        
        }
      to {
        -moz-transform: rotate(360deg); 
        -webkit-transform: rotate(360deg); 
        -ms-transform: rotate(360deg); 
        
        }

	
}
     @keyframes carousel8 {  
    from { 
      -moz-transform: rotate(0deg); 
      -webkit-transform: rotate(0deg); 
      -ms-transform: rotate(0deg); 
      
      }
    to { 
      -moz-transform: rotate(7230deg); 
      -webkit-transform: rotate(7230deg); 
      -ms-transform: rotate(7230deg); 
      
      }

	
}
     @keyframes carousel5 {  
    from { 
      -moz-transform: rotate(0deg); 
      -webkit-transform: rotate(0deg); 
      -ms-transform: rotate(0deg); 
      
      }
    to { 
      -moz-transform: rotate(7260deg);
      -webkit-transform: rotate(7260deg);
      -ms-transform: rotate(7260deg);
      
      }

	
}
     @keyframes carousel3 {  
    from { 
      -moz-transform: rotate(0deg);
      -webkit-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      }
    to { 
      -moz-transform: rotate(7290deg);
      -webkit-transform: rotate(7290deg);
      -ms-transform: rotate(7290deg);
      }
	
}
     @keyframes carousel0 {  
    from { 
      -moz-transform: rotate(0deg); 
      -webkit-transform: rotate(0deg); 
      -ms-transform: rotate(0deg); 
      
      }
    to { 
      -moz-transform: rotate(7320deg);
      -webkit-transform: rotate(7320deg);
      -ms-transform: rotate(7320deg);
      }

	
}
     @keyframes carousel15 {  
    from { 
      -moz-transform: rotate(0deg);
      -webkit-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      
      }
    to { 
      -moz-transform: rotate(7350deg);
      -webkit-transform: rotate(7350deg);
      -ms-transform: rotate(7350deg);
      }

	
}
     @keyframes carousel10 {  
    from { 
      -moz-transform: rotate(0deg); 
      --webkit-transform: rotate(0deg); 
      --ms-transform: rotate(0deg); 
      }
    to { 
      -moz-transform: rotate(7380deg);
      --webkit-transform: rotate(7380deg);
      --ms-transform: rotate(7380deg);
       }

	
}




</style>