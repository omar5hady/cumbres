<template>
<div class="conte_principal">

      <div class="content_grid">
        
        <div class="top"> </div>

          <div  class="div_fanfar_izq" >
            <img  src="/img/ruleta/fanfarria1_Izq.png" width="100%" height="100%"  alt="">
         
        </div>
         <div   class="div_fanfar_der" >
            <img  src="/img/ruleta/fanfarria2_Der.png" width="100%" height="100%"  alt="">
         
        </div>
          <div class="grid_confeti" >
            <img class="content_confetti"  src="/img/ruleta/confetti.png" width="100%" height="100%"  alt="">
         
        </div>
       

      
        <div   class="grid_ruleta">
            <img v-if="checkPremio() ==3000" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/3mil.png" alt="">
            <img v-if="checkPremio() ==5000" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/5mil.png" alt="">
            <img v-if="checkPremio() ==8000" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/8mil.png" alt="">
            <img v-if="checkPremio() ==10000" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/10mil.png" alt="">
            <img v-if="checkPremio() ==15000" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/15mil.png" alt="">
            <img v-if="checkPremio() ==0" class="content_ruleta" style=" z-index: 5;" src="/img/ruleta/GraciasxParticipar.png" alt="">
            
         
        </div>
    
        

          
      </div>

</div>
</template>

<script>
import VueConfetti from 'vue-confetti';
Vue.use(VueConfetti)
export default {
   props:{
      dataCpremio:{type:String},
    },
  data(){
    return{
        arrPremio:this.dataCpremio,
        confeti:false,


    }
  },
 

  methods:{

    checkPremio(){
        let premio =JSON.parse(this.arrPremio)
        
        if (premio[0].premio !=0) {
            this.$confetti.start();
        }

        return premio[0].premio;
    }
  },
  mounted(){
    this.checkPremio();
  }
}


</script>

<style>
body{
  background-image: url('/img/ruleta/Background.png');
   background-position: center;
    background-size:cover;
    background-repeat: no-repeat;
}
.conte_principal{
    overflow: hidden;
    max-width: 1260px;
    min-width: 1260px;

    max-height: 1500px;
    max-height: 1500px;

}
.content_grid{
  display: grid;
   /* grid-template-rows: repeat(30,1fr);  */
   /* bloques de 50 px  halto */
  /* grid-template-columns: repeat(21,1fr); */
   /* loques de 60px  ancho */
  
  
  grid-template-columns: repeat(21, 60px);
  grid-template-rows: repeat(30, 50px);

  /* grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); */


  width: 100%;
  height: 100%;  
}

  .top{
    background-image: url('/img/ruleta/Participa_y_Gana.png');
    grid-column: 7/16;
    grid-row: 4/10;
    width: 540px;
    height: 350px;
    z-index: 2;
  }

.grid_ruleta{
  grid-column: 7/16;
  grid-row:8/19;
  width: 540px;
  height: 550px;
  z-index: 0;
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

}
.grid_confeti{
  grid-column: 5/16;
  grid-row:7/18;
  width: 840px;
  height: 850px;
  z-index: 0;
}

.content_confetti{
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  align-content: center;
  margin: auto;
  width: 840px;  /* todo el ancho de el componente padre */
  height: 850px; /* se ajusta a el ancho */

}







.div_fanfar_izq{
  grid-column: 1/6;
  grid-row:3/14;
  width: 540px;height: 550px; 
  z-index:0;
  
  
}
.div_fanfar_der{
  grid-column: 12/17;
  grid-row:3/14;
  width: 540px;height: 550px; 
  z-index: 0;
  
  
}








</style>