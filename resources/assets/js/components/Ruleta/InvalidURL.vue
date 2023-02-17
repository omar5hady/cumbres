<template>

<div class="main">

     <h1>Error 404</h1>
     <h2>
        Pagina no encontrada
     </h2>
	  <p>Lo siento, parece que el enlace no es valido, verifica que este correcto.</p>
	
	

 


</div>







    
            

 


</template>



<script>


export default {

    props:{
      dataCliente:{type: String}

    },
    data(){
        return{

     
        }
    },

    computed:{



    },


    methods:{




  },

  mounted(){
  }
}
</script>

<style scoped>
  .main{
    display: flex;
    flex-direction: column;
    justify-content:space-between;
    align-items: center;
    margin-top: 200px;
  
  }

    h1{
      font-size: 100px;
      color: azure;
    }
    h2{
      font-size: 80px;
    }
    p{
      font-size: 30px;
    }



</style>