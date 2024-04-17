/* Lib Sommattos */
console.log("Web2Lead loaded! V20200914.1"),window.onload=()=>{storageURLParameters()},urlencodeFormData=e=>{var $=new URLSearchParams;for(var a of e.entries())"string"==typeof a[1]&&$.append(a[0],a[1]);return $.toString()},storageURLParameters=()=>{let e=window.location.search;e.length>0&&sessionStorage.setItem("urlParams",e),""!==document.referrer&&sessionStorage.setItem("referrer",document.referrer)},web2lead=async(e,$)=>{let a=[{parameter:"utm_source",value:"lead_source"},{parameter:"utm_medium",value:"00N6g00000UDm13"},{parameter:"utm_campaign",value:"Campaign_ID"},{parameter:"utm_term",value:"00N6g00000UDiQd"}],r=[{parameter:"canal",value:"lead_source"},{parameter:"veiculo",value:"00N6g00000UDm13"},{parameter:"campanha",value:"Campaign_ID"},{parameter:"empreendimento",value:"00N6g00000UDiQd"}];r.forEach(a=>{$.hasOwnProperty(a.parameter)&&e.set(a.value,$[a.parameter])});let t=sessionStorage.getItem("urlParams"),o=new URLSearchParams(t);a.forEach($=>{o.has($.parameter)&&e.set($.value,o.get($.parameter))});let s=sessionStorage.getItem("referrer");if(null!==s)switch(e.set("lead_source","Web to Lead"),!0){case/google/.test(s):e.set("00N6g00000UDiRP","Google");break;case/yahoo/.test(s):e.set("00N6g00000UDiRP","Yahoo");break;case/bing/.test(s):e.set("00N6g00000UDiRP","Bing");break;default:e.set("00N6g00000UDiRP","Outros Sites")}return e.has("Campaign_ID")&&e.set("00N6g00000UDiQH",e.get("Campaign_ID")),e.set("oid","00D6g0000050n0k"),e.set("00N6g00000UDiQm","0"),e.set("encoding","UTF-8"),e.set("recordType","0126g000000iPzhAAE"),fetch("https://webto.salesforce.com/servlet/servlet.WebToLead",{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},mode:"no-cors",body:urlencodeFormData(e)})};

/* Função de integração com o SalesForce */
function send2SalesForce( data ) {
	let number_phone = data.user.telefone.replace( /\D/g, '' ),
	type = number_phone.length < 11 ? 'phone' : 'mobile'

	let formData = new FormData();
	formData.append('last_name', data.user.name)
	formData.append('email', data.user.email)
	formData.append( type, number_phone )

	let api = web2lead( formData, { canal: 'Web to Lead', veiculo: 'Outros Sites', empreendimento: empreendimentoSalesForce });

	return api;
}	

jQuery( function( $ ){

	var initESW = function( gslbBaseURL ){
		embedded_svc.settings.extraPrechatFormDetails = [
		{
			"label":"Empreendimento",
			"transcriptFields": ["Empreendimento__c"],
			"value": empreendimentoSalesForce
		},
		{
			"label":"Empresa",
			"transcriptFields": ["Empresa__c"],
			"value": "Somattos Engenharia"
		}
		];

		// Habilita o botão de chat padrão na parte de baixo das páginas
		embedded_svc.settings.displayHelpButton = true;

		embedded_svc.settings.language = 'pt-BR';

		// Assume como padrão o Chat com um especialista
		embedded_svc.settings.defaultMinimizedText = 'Chat';

		// Assume como padrão Agente off-line
		// embedded_svc.settings.disabledMinimizedText = '...';

		// Assume como padrão Carregando
		embedded_svc.settings.loadingText = 'Carregando';

		// Define o domínio de sua implantação para que seus visitantes possam navegar em subdomínios durante uma sessão de chat
		embedded_svc.settings.storageDomain = storageDomain;

		embedded_svc.settings.enabledFeatures = ['LiveAgent'];
		embedded_svc.settings.entryFeature = 'LiveAgent';
		embedded_svc.init(
			'https://somattos.my.salesforce.com',
			'https://somattoschat.secure.force.com/liveAgentSetupFlow',
			gslbBaseURL,
			'00D6g0000050n0k',
			'ChatLeads',
			{
				baseLiveAgentContentURL: 'https://c.la1-c2-ia5.salesforceliveagent.com/content',
				deploymentId: '5726g000000Hjka',
				buttonId: '5736g000000HlJr',
				baseLiveAgentURL: 'https://d.la1-c2-ia5.salesforceliveagent.com/chat',
				eswLiveAgentDevName: 'EmbeddedServiceLiveAgent_Parent04I6g000000HS01EAG_17445e9293b',
				isOfflineSupportEnabled: false
			}
		);
	}

	if( !window.embedded_svc ){
		var s = document.createElement('script');
		s.setAttribute('src', 'https://somattos.my.salesforce.com/embeddedservice/5.0/esw.min.js');
		s.onload = function(){ initESW(null); };
		document.body.appendChild(s);
	}
	else {
		initESW( 'https://service.force.com' );
	}

});