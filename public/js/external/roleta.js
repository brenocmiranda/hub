/* Lib Jquery Cookie (https://github.com/carhartl/jquery-cookie) */
!function(e){var n=/\+/g;function o(e){return t.raw?e:encodeURIComponent(e)}function i(e){return o(t.json?JSON.stringify(e):String(e))}function r(o,i){var r=t.raw?o:function(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return e=decodeURIComponent(e.replace(n," ")),t.json?JSON.parse(e):e}catch(e){}}(o);return e.isFunction(i)?i(r):r}var t=e.cookie=function(n,c,s){if(arguments.length>1&&!e.isFunction(c)){if("number"==typeof(s=e.extend({},t.defaults,s)).expires){var u=s.expires,a=s.expires=new Date;a.setMilliseconds(a.getMilliseconds()+864e5*u)}return document.cookie=[o(n),"=",i(c),s.expires?"; expires="+s.expires.toUTCString():"",s.path?"; path="+s.path:"",s.domain?"; domain="+s.domain:"",s.secure?"; secure":""].join("")}for(var d,p=n?void 0:{},f=document.cookie?document.cookie.split("; "):[],l=0,m=f.length;l<m;l++){var x=f[l].split("="),g=(d=x.shift(),t.raw?d:decodeURIComponent(d)),v=x.join("=");if(n===g){p=r(v,c);break}n||void 0===(v=r(v))||(p[g]=v)}return p};t.defaults={},e.removeCookie=function(n,o){return e.cookie(n,"",e.extend({},o,{expires:-1})),!e.cookie(n)}}(jQuery);


/* Definindo qual empreendimento vai aplicar no Sales Force */
if( location.hostname === 'lareserve.com.br' || location.hostname === 'www.lareserve.com.br' ) { 
	var storageDomain = 'lareserve.com.br';
	var PAGE = location.pathname.split('/').at(-2);
	switch (PAGE){
		case "etoile":
			var empreendimentoSalesForce = 'a0M4v00001SG1eAEAT';
			break;
		case "unique":
			var empreendimentoSalesForce = 'a0M6g00000CakAMEAZ';
			break;
		case "lessence":
			var empreendimentoSalesForce = 'a0M6g00000Caex4EAB';
			break;
		case "apogee":
			var empreendimentoSalesForce = 'a0M6g00000Caex2EAB';
			break;
			
	}
}
if( location.hostname === 'duoviladaserra.com.br' || location.hostname === 'www.duoviladaserra.com.br' ) { 
	var empreendimentoSalesForce = 'a0M6g00000CakANEAZ';
	var storageDomain = 'duoviladaserra.com.br';
}
if( location.hostname === 'perfeitoepronto.com.br' || location.hostname === 'www.perfeitoepronto.com.br' ) { 
	var empreendimentoSalesForce = 'a0M6g00000Caex2EAB';
	var storageDomain = 'perfeitoepronto.com.br';
}
if( location.hostname === 'aura.com.br' || location.hostname === 'www.aura.com.br' ) { 
	var empreendimentoSalesForce = 'a0M4v000029R79XEAS';
	var storageDomain = 'aura.com.br';
}


/* Função de sorteio do responsável */
function getSender( empresa ){
	const INTEGRACOES = [
	{
		empresa: 'patrimar',
		emails: { "larissa.castro@patrimar.com.br":  'Larissa Castro', "alexandre.souto@patrimar.com.br":  'Alexandre Souto', "dev@komuh.com":  'Dev Komuh' },
	},
	{
		empresa: 'somattos',
		emails: { "vendas@somattos.com.br":  'Vendas',  "carolina@somattos.com.br":  'Carolina',  "barbara.silva@somattos.com.br":  'Barbara', "dev@komuh.com":  'Dev Komuh' },
	},
	];
	if( empresa ){
		return INTEGRACOES.find( item => item.empresa === $.cookie( 'lareserve-lead-owner' ) );
	} else {
		return INTEGRACOES[ Math.floor( Math.random() * INTEGRACOES.length ) ];
	}
}


/* Criando cookie de acordo a empresa responsável */
const urlParams = new URLSearchParams(window.location.search);
const owner = urlParams.get('owner');
let data = new Date();
data.setHours( 20, 59, 59, 00 ); // Como o UTM é 0 precisamos colocar como -3hrs para expirar em meia noite

if ( owner ) {
	$.cookie( 'lareserve-lead-owner', owner, { expires: data } );
}
if( !$.cookie( 'lareserve-lead-owner' ) ){
	var $SENDER = getSender();
	$.cookie( 'lareserve-lead-owner', $SENDER.empresa, { expires: data } );
} else {
	var $SENDER = getSender( $.cookie( 'lareserve-lead-owner') );
}


/* Aplicando o chata nos botões */
if( $SENDER.empresa === 'patrimar') {
	$( '#button-consutor' ).attr( 'href', '#' ).attr( 'onclick', 'XRM_Chat.open()').addClass('botaoInicial');
	$( '.botaoInicial' ).attr( 'href', '#' ).attr( 'onclick', 'XRM_Chat.open()');
} else {
	$( '#button-consutor' ).on( 'click', function( e ){
		e.preventDefault();
		document.querySelector('.helpButtonEnabled').click();
	} );
	$( '.botaoInicial' ).on( 'click', function( e ){
		e.preventDefault();
		document.querySelector('.helpButtonEnabled').click();
	} );
}

console.log( { $SENDER } );