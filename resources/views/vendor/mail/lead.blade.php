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
                            
                            <h4 style="margin-bottom:0">Dados do cliente</h4>
                            <p style="margin-top: 0">
                                @if($lead->name)
                                    Nome: {{ $lead->name }} <br>
                                @endif
                                @if($lead->email)
                                    E-mail: {{ $lead->email }} <br>
                                @endif
                                @if($lead->phone)
                                    Telefone: {{ $lead->phone }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'message')->last())
                                    Mensagem: {{ $lead->RelationFields->where('name', 'message')->last() }}
                                @endif
                            </p>

                            <h4 style="margin-bottom:0">Dados do empreendimento</h4>
                            <p style="margin-top: 0">
                                @if($lead->RelationBuildings->name)
                                    Nome do empreendimento: {{ $lead->RelationBuildings->name }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'SrNumber')->last())
                                    Nº ticket: {{ $lead->RelationFields->where('name', 'SrNumber')->last()->value }}<br>
                                @endif
                                @if($lead->RelationFields->where('name', 'idCaso')->last())
                                    Nº lead: {{ $lead->RelationFields->where('name', 'idCaso')->last()->value }}<br>
                                @endif
                                @if($lead->RelationFields->where('name', 'PartyNumber')->last())
                                    Nº Contato: {{ $lead->RelationFields->where('name', 'PartyNumber')->last()->value }} 
                                @endif
                            </p>

                            <h4 style="margin-bottom:0">Dados de campanha</h4>
                            <p style="margin-top: 0">
                                @if($lead->RelationFields->where('name', 'utm_source')->last())
                                    utm_source: {{ $lead->RelationFields->where('name', 'utm_source')->last()->value }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'utm_campaign')->last())
                                    utm_campaign: {{ $lead->RelationFields->where('name', 'utm_campaign')->last()->value }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'utm_medium')->last())
                                    utm_medium: {{ $lead->RelationFields->where('name', 'utm_medium')->last()->value }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'utm_content')->last())
                                    utm_content: {{ $lead->RelationFields->where('name', 'utm_content')->last()->value }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'utm_term')->last())
                                    utm_term: {{ $lead->RelationFields->where('name', 'utm_term')->last()->value }}
                                @endif
                            </p>

                            <h4 style="margin-bottom:0">Dados da entrada</h4>
                            <p style="margin-top: 0">
                                @if($lead->RelationOrigins->name)
                                    Origem do Lead: {{ $lead->RelationOrigins->name }} <br>
                                @endif
                                @if($lead->RelationFields->where('name', 'url')->last())
                                    URL: {{ $lead->RelationFields->where('name', 'url')->last()->value }} <br>
                                @endif
                                @if($lead->created_at)
                                    Data: {{ $lead->created_at->format("d/m/Y H:i:s") }}
                                @endif
                            </p>
                            
							<p>
								<b>Visualize mais informações sobre esse lead acessando <a href="{{ route('leads.show', $lead->id ) }}" target="_blank">aqui</a>.</b>
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