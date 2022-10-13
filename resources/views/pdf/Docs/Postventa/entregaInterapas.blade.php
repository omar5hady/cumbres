<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carta de Notificación a Interapas</title>
</head>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css?family=Muli&display=swap'); */

    @page{
        margin: 0cm 0cm 0cm 0cm;
    }

    body {
        font-family: 'AvenirNextLTPro','Muli', Arial, Helvetica, sans-serif
    }

    .h1{
        font-size: 14pt;
        font-style: bold;

    }

    .backblue{
        text-align: center;
        background-color: #003058;
        color: white;
        width: 100%;
        position: fixed;
    }


    .header{
        top: 0px;
    }

    .footer{
        bottom: 0px;
        height: 20.4092px;

    }

    .subheader{
        top: 136px;
        height: 4.89px;
    }

    .cover-container{
        top: 35%;
        background-color: #003058;
        opacity: 0.1;
        text-align: center;
        align-items: center;
        width: 100%;
        height: 210px;
        position: fixed;
    }

    .contenido{
        top: 150px;
        position: fixed;
        color: #003058;
        width: 100%;
        margin: 40px;
    }

    .fecha{
        text-align: right;
    }

    .info{
        top: 33% !important;
        margin-left: 80px;
        margin-right: 80px;
    }

    .firma{
        top: 65%;
        text-align: center;
    }

    .firma p{
        line-height: 5%;
    }



    .table { display: table; width: 98%; border-collapse: collapse; table-layout: fixed;}
    .table-row { display: table-row;  }
    .table-cell1 { display: table-cell; text-align:center; vertical-align: middle;}

</style>

<body>
    <div class="backblue header">
        <div  class="table" >
            <div class="table-row">
                <div  class="table-cell1">
                    <IMG SRC="img/contratos/Logos-01-White.png" height="130" >
                </div>
                <div colspan="4" class="table-cell1">
                    <p class="h1">CARTA DE NOTIFICACIÓN A INTERAPAS</p>
                </div>
                <div  class="table-cell1">
                    <IMG SRC="img/contratos/Logos-02-White.png" height="130" >
                </div>
            </div>
        </div>
    </div>
    <div class="backblue subheader"></div>

    <div class="contenido fecha">
        <p>San Luis Potosí, S.L.P. a _____ de  _________ del 20____.</p>
    </div>

    <div class="contenido info">
        <p>Recibí de entera conformidad por parte de <strong>GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.</strong>
            la vivienda ubicada en el LOTE {{$contrato->num_lote}}{{($contrato->sublote)?'-'.$contrato->sublote:''}}
            de la MANZANA {{$contrato->manzana}}
            de la calle {{$contrato->calle}}
            No. {{$contrato->numero}}{{($contrato->interior)?' Int.'.$contrato->interior:''}}
            del FRACCIONAMIENTO "{{$contrato->proyecto}}" ubicado
            en {{$contrato->delegacion}} en {{$contrato->estado}}.
        </p>
    </div>

    <div class="contenido firma">
        <p>Recibí</p>
        <br><br><br>
        <p>_______________________________________</p>
        <p>NOMBRE Y FIRMA</p>
    </div>

    <div class="cover-container"></div>
    <div class="backblue footer"></div>
</body>

</html>
