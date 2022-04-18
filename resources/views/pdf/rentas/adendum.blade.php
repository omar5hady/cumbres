<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato</title>
</head>

<style>

body{
 font-size: 11 pt;
 text-align: justify;
 font-family: Arial, sans-serif;
}

@page{
    margin: 85px;
    margin-right: 105px;
    margin-left: 105px;
}

.table-cell3 { display: table-cell; padding: 0em; font-size: 10 pt; }
.table3 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-row { display: table-row; }
</style>

<body>
    <div>
        
        <p align="justify">
            <strong>
                ADENDUM AL CONTRATO DE ARRENDAMIENTO CELEBRADO ENTRE 
                @if($contrato->tipo_arrendador == 1)
                    GRUPO CONSTRUCTOR CUMBRES, S. A. DE C.V. 
                @else
                    EL SR. {{mb_strtoupper($contrato->nombre_arrendador)}}</strong> 
                @endif 
                Y 
                @if($contrato->tipo_arrendatario == 1)
                    {{mb_strtoupper($contrato->nombre_arrendatario)}} 
                @else
                    EL SR. {{mb_strtoupper($contrato->nombre_arrendatario)}} 
                @endif
                DE LA CASA UBICADA {{mb_strtoupper($contrato->calle)}} #{{$contrato->numero}} 
                @if($contrato->interior != NULL)
                    Int. {{$contrato->interior}}
                @endif
                {{mb_strtoupper($contrato->etapa)}} FRACCIONAMIENTO {{mb_strtoupper($contrato->proyecto)}} C.P. {{$contrato->cp_proyecto}}, 
                @if($contrato->delegacion != NULL)
                    {{mb_strtoupper($contrato->delegacion)}}, 
                @endif
                {{mb_strtoupper($contrato->ciudad_proyecto)}}, {{mb_strtoupper($contrato->estado_proyecto)}}.
            </strong>
        </p>
        <br>

        <p align="justify">
            Con las facultades que se describen al Arrendador y Arrendatario en el mismo contrato de arrendamiento se manifiesta lo siguiente:
        </p>

        <p align="justify">
            1.- EL contrato de arrendamiento se firmó por mutuo acuerdo y aceptación de las partes, sin manifestar 
            el IVA (impuesto al valor agregado) como arrendamiento de una casa habitación sin muebles. Siendo en realidad 
            que la casa se encuentra arrendada con muebles, las cuales se describen en el anexo “A”
        </p>

        <p align="justify">
            2.- Así mismo, estos muebles serán destinados al uso del arrendamiento y serán devuelto al término del contrato 
            de arrendamiento en las mejores condiciones de servicio que se encuentren, considerando que tendrán un desgaste 
            normal por el uso. Cualquier daño fuera de lo normal será restituido, pagado o tomado del depósito en garantía 
            como lo determinen y acuerden las partes.
        </p>
        <br>

        <p align="justify">
            Leído que fue el presente ADENDUM y enteradas las partes de su contenido, lo firman por duplicado en la 
            ciudad de San Luis Potosí, S.L.P. a los {{$contrato->fecha_firma}}.
        </p>

        

        
        <br><br>

        <div class="table3">
            <div class="table-row">
                <div colspan="2" class="table-cell3"><b> </div>
                <div class="table-cell3"></div>
                <div colspan="2" class="table-cell3"><b></div>
            </div>
            <div class="table-row"> 
                <div colspan="5" class="table-cell3"></div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell3">
                    <p align="center">
                        EL ARRENDADOR<br><br><br><br><br><br>
                    </p>
                </div>
                <div style="width: 10%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">
                    <p align="center">
                        EL ARRENDATARIO<br><br><br><br><br><br>
                    </p>
                </div>
            </div>
            <div class="table-row">
                <div colspan="2" class="table-cell3">
                    <center>
                        @if($contrato->tipo_arrendador == 1)
                            GRUPO CONSTRUCTOR CUMBRES, S.A. de C.V.
                            <br> Representada en este acto por 
                            <br> {{mb_strtoupper($representante)}}
                        @else
                            {{mb_strtoupper($contrato->nombre_arrendador)}}
                        @endif
                    </center>
                    
                </div>
                <div style="width: 10%;" class="table-cell3"></div>
                <div colspan="2" class="table-cell3">
                    <center>
                        @if($contrato->tipo_arrendatario == 1)
                            {{mb_strtoupper($contrato->nombre_arrendatario)}}
                            <br> Representada en este acto por
                            <br> {{mb_strtoupper($contrato->representante_arrendatario)}}
                        @else
                            {{mb_strtoupper($contrato->nombre_arrendatario)}}
                        @endif
                    </center>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>

        <div>
            <div class="table3">
                    
                <div class="table-row">
                    <div class="table-cell3"><b> </div>
                    <div colspan="2" class="table-cell3"></div>
                    <div class="table-cell3"><b></div>
                </div>
                <div class="table-row"> 
                    <div colspan="5" class="table-cell3"> <br> <br> </div>
                </div>
                <div class="table-row">
                    <div class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">_________________________________________</div>
                    <div class="table-cell3"></div>
                </div>
                <div class="table-row">
                    <div class="table-cell3"></div>
                    <div colspan="2" class="table-cell3">
                        <center>
                            @if($contrato->tipo_aval == 1)
                                {{mb_strtoupper($contrato->nombre_aval)}}
                                <br> Representada en este acto por
                                <br> {{mb_strtoupper($contrato->representante_aval)}}
                            @else
                                {{mb_strtoupper($contrato->nombre_aval)}}
                            @endif
                            <br>FIADOR
                        </center>
                    </div>
                    <div class="table-cell3"></div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>

        
    </div>
</body>
</html>