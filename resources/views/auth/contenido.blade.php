<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema cumbres">
    <meta name="author" content="casascumbres.mx">
    <meta name="keyword" content="Sistema de venta de casas ">

    <title>Sistema cumbres</title>

    <!-- Hojas de estilo -->
    <link href="vendor/css/font-awesome.min.css" rel="stylesheet">
    <link href="vendor/css/simple-line-icons.min.css" rel="stylesheet">
    <!-- Estilo principal para la aplicacion -->
    <link href="css/plantilla.css" rel="stylesheet">
</head>
{{-- Backgroudn --}}
<img id="login" src="img/login.png" alt="">

<body class="app flex-row align-items-center">
    <div class="container">
        @yield('login')
    </div>

    <!-- Bootstrap and necessary plugins -->
    <script src="js/plantilla.js"></script>

</body>

</html>

<style>
    /* Estilo para background */
    #login {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
    }

</style>
