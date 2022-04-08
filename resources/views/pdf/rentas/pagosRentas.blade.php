<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagares</title>
</head>
<style type="text/css">
    @page{
        margin-right: 30px;
        margin-left: 30px;
        margin-top: 40px;
    }
    body {
        font-size: 9.5pt;
        font-family: sans-serif;
    }

    .table-row {
        display: table-row;
    }

    .table-cell1 {
        display: table-cell;
        padding: 0em;
        font-size:9.5pt;
    }

    .table {
        display: table;
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
   

</style>

<body>
    @foreach ($contrato->pagares as $pago)

        <div style="border-style: groove;">
            <div style="float: left; margin-left: 40px;">
                <table>
                    <tr style="height: .5px">
                        <td width=100 bgcolor="black"
                            style="padding-top: -0.2in; padding-bottom: -0.2in; ">
                            <center><h3 style="color: white">P A G A R É</h4></center>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="float: right;">
                <table>
                    <tr>
                        <td>No.</td>
                        <td bgcolor="#c2c2c2" 
                            style="border: 1px solid #00000a;">
                                <strong><center>{{$pago->num_pago}} de {{$contrato->num_meses}}</center></strong>
                        </td>
                        <td></td>
                        <td>&nbsp;BUENO POR: </td>
                        <td bgcolor="#c2c2c2" 
                            style="border: 1px solid #00000a;">
                                <strong><center>$ {{$pago->importe}}</center></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>EN SAN LUIS POTOSI A: </td>
                        <td>{{mb_strtoupper($pago->fecha_pago)}}</td>
                    </tr>
                </table>
            </div>

            <br>

            

            <div style="text-align: justify; margin:50px">

                <div>
                    <p>
                        DEBE(MOS) Y PAGARE(MOS) INCONDICIONALMENTE POR ESTE PAGARÉ A LA ORDEN DE: 
                        <strong><u>{{mb_strtoupper($contrato->nombre_arrendador)}}</u></strong> &nbsp;
                        EN &nbsp; <strong><u>{{mb_strtoupper($contrato->direccion_arrendador)}}</u></strong> &nbsp;
                        EL DIA &nbsp; <strong><u>{{mb_strtoupper($pago->fecha_pago)}}</u></strong>.
                    </p>
                    
                    <table>
                        <tr>
                            <td>LA CANTIDAD DE: </td>
                            <td width=250 bgcolor="#c2c2c2" 
                                style="border: 1px solid #00000a;
                                padding-top: 0.05in; padding-bottom: 0.05in;
                                padding-left: 0.2in; padding-right: 0.2in;">
                                <strong><center>$ {{$pago->monto}}</center></strong>
                            </td>
                        </tr>
                    </table>

                    <p>
                        Valor recibido  a  mi  (nuestra)  entera   satisfacción.   Este  pagaré forma parte de   una   
                        serie numerado del    UNO    al   {{$contrato->cant_meses}}  y  todos están sujetos a la condición de que, al no 
                        pagarse cualesquiera de  ellos  a  su  vencimiento,  serán exigibles todos los que le sigan en, número además de los ya  
                        vencidos,  desde la fecha de vencimiento de este documento hasta el día de su liquidación causará intereses moratorios al 
                        tipo de 5.00 %  mensual,  pagadero en esta Ciudad juntamente con el principal.
                    </p>

                </div>
                <br>


                <div class="table" style="text-align:left;">
                    <div class="table-row">
                        <div style="text-align:center;" colspan="2" class="table-cell1"> <b>NOMBRE Y DATOS DEL DEUDOR </div>
                        <div colspan="" class="table-cell1"> <b></div>
                        <div style="text-align:center;" colspan="2" class="table-cell1"> <b>DATOS PERSONALES Y FIRMA DEL AVAL</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"><strong>Nombre:</strong>: {{$contrato->nombre_arrendatario}}</div>
                        <div colspan="" class="table-cell1"> <b></div>
                        <div colspan="2" class="table-cell1"><strong>Nombre:</strong>: {{$contrato->nombre_aval}}</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"><strong>Dirección:</strong>: {{$contrato->dir_arrendatario}}</div>
                        <div colspan="" class="table-cell1"> <b></div>
                        <div colspan="2" class="table-cell1"><strong>Dirección:</strong>: {{$contrato->dir_aval}}</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"><strong>Fracc. y Mpio:</strong>: {{$contrato->municipio_arrendatario}} CP {{$contrato->cp_arrendatario}}}</div>
                        <div colspan="" class="table-cell1"> <b></div>
                        <div colspan="2" class="table-cell1"><strong>Fracc. y Mpio:</strong>: {{$contrato->municipio_aval}} CP {{$contrato->cp_aval}}</div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1"><br><br></div>
                        <div colspan="" class="table-cell1"> <b></div>
                        <div colspan="2" class="table-cell1"><br><br></div>
                    </div>
                    <div class="table-row">
                        <div colspan="2" class="table-cell1">Acepto:___________________</div>
                        <div colspan="" class="table-cell1"> <b></div>
                        <div colspan="2" class="table-cell1">Acepto:___________________</div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
    @endforeach
</body>

</html>
