<!DOCTYPE html>
<html lang="es">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema cumbres">
    <meta name="author" content="casascumbres.mx">
    <meta name="keyword" content="Sistema de venta de casas ">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Premios</title>

    <!-- Icons -->

    <!-- Main styles for this application -->
    <link href="../css/plantilla.css" rel="stylesheet">

  </head>
  {{-- <img id="login" src="img/login.png" alt=""> --}}
  <body class="principal">

      <div id="helper" >
        <premios-ruleta 
        data-cpremio="{{$cpremio}}" 
        ></premios-ruleta>
      </div>



      <!-- Bootstrap and necessary plugins -->
      <script src="../js/helper.js "></script>
    <script src="../js/plantilla.js"></script>


  </body>
</html>

<style>
   body{
    /* overflow: hidden; */
    /* background-image: url('/img/ruleta/Background.png');
    background-attachment: fixed;
    background-repeat: no-repeat; */
    


    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    max-width: 1920px;
    min-width: 1260px;

   
  
   
  }


  @media screen and (max-width: 1080px){

body{
  background-image: url('/img/ruleta/Background.png');
  background-attachment: fixed;
  background-repeat: no-repeat;


display: flex;
flex-direction: column;
justify-content: center;
align-items: center;

max-width: 1080px;

  
    }




</style>