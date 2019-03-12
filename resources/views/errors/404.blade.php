<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.0.0
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema cumbres">
    <meta name="author" content="casascumbres.mx">
    <meta name="keyword" content="Sistema de administracion cumbres">
    <link rel="shortcut icon" href="img/favicon.png">
    <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
    <title>Sistema Cumbres</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">

</head>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="clearfix">
            <h1 class="float-left display-3 mr-4">404</h1>
            <h4 class="pt-3">Oops! You're lost.</h4>
            <p class="text-muted">La pagina que estas buscando no fue encontrada.</p>
          </div>
          
        </div>
      </div>
    </div>

    <!-- Bootstrap and necessary plugins-->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
  </body>
</html>