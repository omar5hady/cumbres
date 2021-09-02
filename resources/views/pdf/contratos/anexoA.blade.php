<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ANEXO A</title>
</head>

<style type="text/css">
div {
  page-break-inside: avoid;
}
body {
    font-size: 12.5pt;
    font-family: Arial;
}

.li-2 {font-family:"DejaVu Sans";}
</style>
    <body>
        <div style="display: inline-block; float: center;">
            <div margin: 10px; style="display: inline-block; float: left;" >
                <IMG SRC="img/logosFraccionamientos/{{ $contrato->logo_fracc }}" width="120" height="120">
            </div>
            <div style="display: inline-block; float: right;" >
                <IMG SRC="img/contratos/logoContrato.jpg" width="120" height="120">
            </div>
        </div>
       
        <div style="margin: 10px; margin-top: 120px;"> 
            <br>
            <hr>
            <p align="right">ANEXO A</p>
           
        </div>
        <div style="margin: 60px; margin-top: 120px;"> 
            <br>
            <center>
                <IMG SRC="files/lotes/colindancias/{{ $contrato->colindancias }}" >
            </center>
                
            <br>
        </div>
    </body>
</html>