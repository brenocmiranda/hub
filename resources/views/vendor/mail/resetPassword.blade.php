<html>
<body>
	<div>
		<table width="650" cellpadding="0" border="0" cellspacing="0" align="center" bgcolor="#FFFFFF" style="border-radius:5px; border:1px solid #dddddd; text-align: left">
			<tbody>
				<tr bgcolor="#f4f4f4">
					<td>
						<table>
							<tbody>
								<tr>
									<td style="padding:15px 15px 15px 30px">
										<img src="{{ asset('images/logo-black.png') }}" alt="Hub Integrações" height="50">
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding: 20px 40px;">
						<font face="Helvetica,Arial,sans-serif" color="#222222" style="font-size:15px; line-height:25px;">
							<p>
								<b>Obaaa, {{explode(" ", ucfirst(strtolower($user->name)))[0]}}, sua senha foi reiniciada com sucesso!</b>
							</p>
							<p>
								Você teve os seus dados de acessos atualizados na plataforma <b>{{env('APP_NAME')}}</b>. Caso você desconheça essa solicitação, entre em contato com administrador.
							<p>
								<a href="{{route('login')}}" target="_blank">Acesse a plataforma e faça login.</a>
							</p>
						</font>
					</td>
				</tr>
				<tr>
					<td>
						<p style="border-top:1px solid #dddddd; margin: 0px 15px 0px 15px"></p>
					</td>
				</tr>
				<tr>
					<td style="padding: 20px 40px;">
						<font face="Helvetica,Arial,sans-serif" color="#222222" style="font-size:15px; line-height:25px;">
							<b>Equipe do {{env('APP_NAME')}}</b><br>
							<a href="{{env('APP_URL')}}" target="_blank">{{env('APP_URL')}}</a><br>
						</font>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>