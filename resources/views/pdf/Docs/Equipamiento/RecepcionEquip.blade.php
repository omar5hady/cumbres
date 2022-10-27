<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recepción de Equipamiento</title>
</head>
<style type="text/css">
.contenedor {
clear:both;
float: left;
}

body {
    font-family: sans-serif;
}

.table { display: table; width:100%; border-collapse: collapse; table-layout: fixed; }
.table-cell { display: table-cell; padding: 0.4em; font-size: 9pt; }
.table-row { display: table-row; }

.table2 { display: table; width:100%; border-collapse: collapse; table-layout: fixed; border: ridge #0B173B 1px; color:black; }
.table-cell2 { display: table-cell; padding: 0em; font-size: 8.5pt; border: ridge #0B173B 1px; color:black; }
.table-row { display: table-row; }
</style>
<body>

    <div class="contenedor">
        <div style="width: 720px;">
            <div style="display: inline-block; float: left;" >
                @if($revision->emp_constructora == 'CONCRETANIA' && $revision->emp_terreno == 'CONCRETANIA' )
                    <IMG SRC="img/logo2C.png" width="250" height="50">
                @else
                    <IMG SRC="img/logo2.png" width="250" height="50">
                @endif
            </div>
            <div style="display: inline-block; float: right;" >
                @if($revision->tipoRecepcion == 2)
                    <IMG SRC="img/recepcionCloset.png" width="200" height="50">
                @elseif($revision->tipoRecepcion == 1)
                    <IMG SRC="img/recepcionCocina.png" width="200" height="50">
                @else
                    <IMG SRC="img/recepcionGeneral.png" width="200" height="50">
                @endif

            </div>

            <div class="table" style="margin-top:60px; border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                    <div colspan="2" class="table-cell"><b>Fraccionamiento: <u>{{$revision->proyecto}}</u></b></div>
                    <div class="table-cell"><b> Fecha de revision: <u>{{$revision->fecha_revision}}</u></b></div>
                </div>
                <div class="table-row">
                    <div class="table-cell"><b>Manzana: <u>{{$revision->manzana}}</u></b></div>
                    <div class="table-cell"><b>Lote: <u>{{$revision->num_lote}}</u> </b></div>
                    <div class="table-cell"><b>Prototipo: <u>{{$revision->modelo}}</u></b></div>
                </div>
                <div class="table-row">
                    <div colspan="2" class="table-cell"><b>Proveedor: <u>{{$revision->proveedor}}</u></b></div>
                    <div class="table-cell"></div>
                </div>
                <div class="table-row">
                    @if($revision->casa_muestra == 1)
                        <div class="table-cell"><b>Casa Muestra: <u>Si</u></b></div>
                    @else
                        <div class="table-cell"><b>Casa Muestra: <u>No</u></b></div>
                    @endif
                    <div colspan="2" class="table-cell"></div>
                </div>
            </div>

            <div class="table2">
                @if($revision->tipoRecepcion == 2)
                    @foreach($revision->revision as $cat)
                        <div class="table-row">
                            <div colspan="4" class="table-cell2" style="text-align: center; background-color: black; color: white;">
                                <b>{{$cat->categoria}}</b>
                            </div>
                        </div>
                        <div class="table-row">
                            <div class="table-cell2"></div>
                            <div class="table-cell2"><center><b> Rec. Dr:</b></center></div>
                            <div class="table-cell2"><center><b> Rec. Iz:</b></center></div>
                            <div class="table-cell2"><center><b> Rec. Ppal:</b></center></div>
                        </div>
                        @foreach($cat->subcategoria as $sub)
                            <div class="table-row">
                                <div class="table-cell2"><b>+ {{$sub->subcategoria}}:</b></div>
                                <div class="table-cell2"></div>
                                <div class="table-cell2"></div>
                                <div class="table-cell2"></div>
                            </div>
                            @foreach($sub->concepto as $det)
                                <div class="table-row">
                                    <div class="table-cell2">&nbsp;- {{$det->concepto}}:</div>
                                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">
                                        @if($det->check1 == '1') &#10004; @endif
                                    </div>
                                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">
                                        @if($det->check2 == '1') &#10004; @endif
                                    </div>
                                    <div class="table-cell2" style="text-align:center; font-family:DejaVu Sans;">
                                        @if($det->check3 == '1') &#10004; @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach

                @elseif($revision->tipoRecepcion == 1)

                    @foreach($revision->revision as $cat)
                        <div class="table-row">
                            <div colspan="5" class="table-cell2" style="text-align: center; background-color: black; color: white;">
                                <b>{{$cat->categoria}}</b>
                            </div>
                        </div>
                        @foreach($cat->subcategoria as $sub)
                            <div class="table-row">
                                <div class="table-cell2"><b>+ {{$sub->subcategoria}}:</b></div>
                                <div class="table-cell2" colspan="4"></div>
                            </div>
                            @foreach($sub->concepto as $det)
                                <div class="table-row">
                                    <div class="table-cell2">&nbsp;- {{$det->concepto}}:</div>
                                    <div class="table-cell2" colspan="4" style="text-align:center; font-family:DejaVu Sans;">
                                        @if($det->check1 == '1') &#10004; @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach

                @endif

            </div>

        <p><b>Observaciones: </b>{{$revision->obs_recep}}</p>

        <div class="table" style="border: ridge #0B173B 1px; color:black;">
                <div class="table-row">
                    <div colspan="2" class="table-cell"><b>Supervisor: {{$revision->supervisor}}</b></div>
                    <div class="table-cell"><b>Firma:</b></div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
