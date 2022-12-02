<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body style="margin: 0; padding: 0;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td style="padding: 10px 0 30px 0;">
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
					<tr style="background-color: #003058;">
						<td align="center">
							<img src="https://siicumbres.com/img/mail/Head_Mesa1.png" width="400"  style="display: block;" />
						</td>
					</tr>
					<tr>
						<td style="padding: 40px 30px 40px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="center">
										<img src="https://siicumbres.com/img/mail/Body1.png" width="500"  style="display: block;" />
									</td>
								</tr>
								<tr>
									<td align="center" style="color: #00203a; font-size: 20px; font-family: Helvetica;">
                                        <br><br>
                                        <p>
                                            Bienvenido (a) a la familia Cumbres!! Agradecemos tu confianza y te hacemos llegar el <b>PLANO</b>
                                            de tu Nueva Casa ubicada en el lote <b>{{$contrato->num_lote}} {{($contrato->sublote) ? $contrato->sublote : ''}}</b>
                                            del proyecto <b>{{mb_strtoupper($contrato->fraccionamiento)}}</b> etapa
                                            <b>{{mb_strtoupper($contrato->etapa)}}</b> a
                                            nombre de <b>{{mb_strtoupper($contrato->nombre)}} {{mb_strtoupper($contrato->apellidos)}}</b>
                                        </p>
                                        <br>
                                        <p>
                                            Da clic en este enlace para obtener tus documentos
                                        </p>
									</td>
								</tr>
                                <tr>
									<td align="center" style="padding: 10px 0 10px 0; color: #fff; font-size: 20px; font-weight: bold; font-family: Helvetica;">
                                        <a href="{{$contrato->plano->public_url}}" target="_blank">
                                            <img src="https://siicumbres.com/img/mail/Button_1.png" alt="" width="150">
                                        </a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#fff">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="color: #ffffff;">
										<img src="https://siicumbres.com/img/mail/Food_Mesa1.png" alt="" width="750">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


</body>
</html>
