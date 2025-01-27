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
								<b>Olá, {{explode(" ", ucfirst(strtolower($user->name)))[0]}}!</b>
							</p>
							<p>
								Recebemos uma solicitação de redefinição de senha através da nossa plataforma, para prosseguir com esse procedimento siga o passo a passo abaixo:
							</p>
							<p>
								<ul>
									<li>A sua nova senha deve seguir as seguintes políticas de segurança:
									<ul>
									 	<li>Deve conter no mínimo 8 carácteres;</li>
									 	<li>Possuir carácteres especiais;</li>
									 	<li>Possuir números.</li>
									 </ul>
									</li>
									<li>
										<a href="{{route('verify.password', $user->remember_token)}}" target="_blank">Acesse aqui para redefinir sua senha.</a>
									</li>
									<li>Faça o login com seus novos dados de acesso.</li>
								</ul>	
							</p>
							<p>
								<b>Caso não consiga efetuar essa alteração entre em contato com o administrador.</b>
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