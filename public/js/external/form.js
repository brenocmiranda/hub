jQuery( function( $ ){

	/**
	 * Validations inputs
	 */
	function isEmail( email ){
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test( email );
	}

	function isPhone( phone ){
		var pattern = /^(\()?\d{2}(\))?\s?\d{4,5}(\-|\.)?\d{4}$/i;
		return pattern.test( phone );
	}

	/**
	 * Send data for integration
	 */
	function sendEmail( data ) {
		return new Promise((resolve, reject) => {
			let $mail_data = data;
			$.ajax({
				url: 'https://hub.komuh.com/api/leads/lps',
				type: 'POST',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', 'Bearer 4|GAoYty9d37SKV2EMUhRWG2jKQ3erJFjqP5vWkd6u2d82e33a');
				},
				data: $mail_data,
				success: function ( $data ) { resolve( $data ); },
				error: function ( xhr, textStatus, errorThrown ) { reject( console.log( xhr ) );  },
			});
		});
	}

	/**
	 * Disabled inputs on click submit
	 */
	function setDisabled( form, state ){
		$( form ).find( 'input, select, textarea, button' ).each( function(){
			$( this ).prop( "disabled", state );
			$( this ).attr( "disabled", state );
		});
	}
	
	window.formSubmit = function( token ){
		setTimeout( function(){
			let $form = $("form.sending-form");
			$form.find( '.submit-btn' ).prop( "disabled", true );
		}, 10 );
	}

	/**
	 * Rules for input data
	 */
	function mphone( v ) {
		var r = v.replace(/\D/g, "");
		r = r.replace(/^0/, "");
		if( r.length > 10 ){
			r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
		}
		else if( r.length > 5 ){
			r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
		}
		else if( r.length > 2 ){
			r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
		}
		else {
			r = r.replace(/^(\d*)/, "($1");
		}
		return r;
	}

	function removeNonLetters(input) {
		var r = input.replace(/[^a-zA-Z]/g, "");
		return r;
	}

	$( '.no-space' ).on( 'keypress', function( e ){
		if( e.which == 32 ) return false;
	});

	$( '.is-phone' ).on( 'keyup', function( e ){
		var v = mphone( this.value );
		if( v != this.value ){
			this.value = v;
		}
	});

	$( '.is-text' ).on( 'keyup', function( e ){
		var v = removeNonLetters( this.value );
		if( v != this.value ){
			this.value = v;
		}
	});

	$( '[name="pp"]' ).on( 'change', function(){
		let $t = $( this ),
			$f = $t.parents( 'form' ),
			$b = $f.find( '.submit-btn' );
		$b.prop( 'disabled', !this.checked );
	});

	/**
	 * Send data for function sendEmail
	 */
	window.dataLayer = window.dataLayer || [];
	$( '.submit-btn' ).prop( 'disabled', true ).on( 'click', function( e ){
		e.preventDefault();

		let $submit = $(this),
			$form = $submit.parents('form'),
			$output = $form.find('.form-output'),
			nome = $form.find('[name="nome"]').val().trim(),
			email = $form.find('[name="email"]').val().trim(),
			telefone = $form.find('[name="telefone"]').val().trim(),
			empreendimento = $form.find('[name="empreendimento"]').val().trim(),
			sobrenome = $form.find('[name="sobrenome"]').length > 0 ? $form.find('[name="sobrenome"]').val().trim() : "",
			mensagem = $form.find('[name="messagem"]').length > 0 ? $form.find('[name="messagem"]').val().trim() : "",
			origin = $form.find('[name="origin"]').length > 0 ? $form.find('[name="origin"]').val().trim() : "",
			com = $form.find('[name="com"]').length > 0 ? $form.find('[name="com"]').val().trim() : "",
			pp = $form.find( '[name="pp"]'),
			url_params = location.search ? location.search.replace('?', '') : '',
			url = location.href;

		if ( !pp.is( ':checked' )) {
			alert( 'Aceite da Política de Privacidade é obrigatório.' );
			return;
		}

		if (nome == '' || email == '' || telefone == '' || empreendimento == '') {
			alert('Preencha todos os campos.');
			return;
		}

		if (!isEmail(email)) {
			alert('O e-mail fornecido não parece válido.');
			return;
		}

		if (!isPhone(telefone)) {
			alert('O telefone fornecido não parece válido.');
			return;
		}

		if (!$output.length) {
			$form.prepend('<div class="form-output"></div>');
			$output = $form.find('.form-output');
		}

		$output.html('').removeClass('is-error is-success');
		$submit.addClass('sending');
		$form.addClass('sending-form');
		setDisabled($form, true);

		let data = { nome, sobrenome, email, telefone, mensagem, empreendimento, url_params, url, origin, com };
		console.log( 'form data', data );

		sendEmail( data ).then(function (em_data) {
			console.log('sendmail', em_data);

			if (em_data.status) {
				$submit.removeClass('sending');
				$form[0].reset();
				dataLayer.push({ 'event': 'conversao_sucesso' });
				$(document).trigger('form-sended', [$form]);
			}

			setDisabled($form, false);
			$("form.sending-form .submit-btn").attr("disabled", true);
			$form.removeClass('sending-form');

			$output.html(`<p>${em_data.status ? 'Dados enviados com sucesso.' : 'Erro ao enviar seus dados. Verifique e tente novamente.'}</p>`).addClass(em_data.status ? 'is-success' : 'is-error');
		});
	});

	/**
	 * Send data in modal whatsapp for function sendEmail
	 */
	$('.submit-whatsapp').on('click', function (e) {
		e.preventDefault();

		let $submit = $(this),
			$form = $submit.parents('form'),
			$output = $form.find('.form-output-modal'),
			nome = $form.find('[name="name"]').val().trim(),
			email = $form.find('[name="email"]').val().trim(),
			telefone = $form.find('[name="phone"]').val().trim(),
			url_params = location.search ? location.search.replace('?', '') : '',
			url = location.href,
			empreendimento = $form.find('[name="empreendimento"]').val().trim(),
			tel_whatsapp = $form.find('[name="tel-whatsapp"]').val().trim(),
			msg_whatsapp = $form.find('[name="msg-whatsapp"]').val().trim();

		if (nome == '' || email == '' || telefone == '' || empreendimento == '') {
			alert('Preencha todos os campos.');
			return;
		}

		if (!isEmail(email)) {
			alert('O e-mail fornecido não parece válido.');
			return;
		}

		if (!isPhone(telefone)) {
			alert('O telefone fornecido não parece válido.');
			return;
		}

		if (!$output.length) {
			$form.prepend('<div class="form-output-modal"></div>');
			$output = $form.find('.form-output-modal');
		}

		$output.html('').removeClass('is-error is-success');
		$submit.addClass('sending');
		$form.addClass('sending-form');
		setDisabled($form, true);

		let data = { nome, email, telefone, url_params, empreendimento, url };
		console.log('form data', data);

		sendEmail(data).then(function (em_data) {
			console.log('sendmail', em_data);

			if (em_data.status) {
				$submit.removeClass('sending');
				$form[0].reset();
				dataLayer.push({ 'event': 'whatsapp_sucesso' });
				$(document).trigger('form-sended', [$form]);
			}

			setDisabled($form, false);
			$('#myModal').fadeOut();

			window.location.href = 'https://api.whatsapp.com/send/?phone=' + tel_whatsapp + '&text=' + window.encodeURIComponent(msg_whatsapp) + '&app_absent=0';
		});

	});

	/**
	 * Leitura de section por viewport (Adicionando class is-view quando estiver disponível a section)
	*/
	$.fn.isInViewport = function () {
		let $t = $(this),
			elTop = $t.offset().top,
			elBottom = elTop + $t.outerHeight(),
			$w = $(window),
			wHeight = $w.height(),
			scTop = $w.scrollTop(),
			scBottom = scTop + wHeight,
			middle = scTop + (wHeight / 2);
		//console.log( { el: $t.attr( 'id' ), middle, elTop, elBottom, scTop, scBottom, wHeight } );
		return elBottom > scTop && elTop < scBottom;
		//	return middle > elTop;
	};
	let $inView = $('section, .inview');
	function checkInView() {
		$inView.each(function () {
			if ($(this).isInViewport()) {
				$(this).addClass('is-inview');
			}
		});
	}
	$(window).on('resize scroll', checkInView);
	checkInView();

	/**
	 * Loading Patrimar and Novolar (1s)
	*/
	if( $('.loading').length ){
		setTimeout(() => {
			$('.loading').fadeOut();
		}, 1000);
	}

	/**
	 * Loading lib in GTM (1s)
	*/
	if (window.ID_GTM) {
		document.addEventListener('DOMContentLoaded', () => {
			/** init gtm after 1000 seconds - this could be adjusted */
			setTimeout(initGTM, 1000);
		});
		$(document).on('scroll mousemove touchstart click', initGTMOnEvent);
	}
	function initGTMOnEvent(event) {
		initGTM();
		event.currentTarget.removeEventListener(event.type, initGTMOnEvent); // remove the event listener that got triggered
	}
	function initGTM() {
		if (window.gtmDidInit) {
			return false;
		}
		window.gtmDidInit = true; // flag to ensure script does not get added to DOM more than once.
		$.getScript('https://www.googletagmanager.com/gtm.js?id=' + window.ID_GTM, function(){
			dataLayer.push({ event: 'gtm.js', 'gtm.start': new Date().getTime(), 'gtm.uniqueEventId': 0 });
		});
	}

	/**
	 * Loading lib in Chat (Click)
	*/
	if(window.building && $('.chat').length){
		$('.chat').on('click', function(){
			var self = window.location.toString();
			var querystring = self.split("?");
			if (querystring.length > 1) {
				var pairs = querystring[1].split("&");
				for (i in pairs) {
					var keyval = pairs[i].split("=");
					if (sessionStorage.getItem(keyval[0]) === null) {
						sessionStorage.setItem(keyval[0], decodeURIComponent(keyval[1]));
					}
				}
			}
			var utm_source = sessionStorage.getItem('utm_source') || "";
			var utm_campaign = sessionStorage.getItem('utm_campaign') || "";
			var utm_medium = sessionStorage.getItem('utm_medium') || "";

			$.getScript('https://www.patrimar.com.br/hotsites/integracoes/chat.php?empreendimento=' + window.building + '&url=' + window.location.pathname + '&utm_source=' + utm_source + '&campanha=' + utm_campaign + '&midia=' + utm_medium, function(){
				XRM_Chat.open();
			});
		});
	}

	/**
	 * Loading lib in recaptcha (Click)
	 */
	let $recaptcha_loaded = false;
	$('input').on('focus.rcp', function () {
		let input = this,
			$form = $(this).parents('form'),
			$submit = $form.find('.submit-btn');

		//console.log({ el: this, name: this.name, $form, $submit })
		if (!$recaptcha_loaded) {
			$.getScript('//www.google.com/recaptcha/api.js', function () {
				$recaptcha_loaded = true;
				$form.find('input').each((i, el) => $(el).off('.rcp'));
				//console.log('recaptcha loaded');
				setTimeout(() => {
					$('.submit-btn').prop('disabled', true);
					// $submit.attr("disabled", true);
					if (input.name === 'pp' && input.checked ) {
						//console.log('pp => libera submit', input.name);
						$submit.removeProp( 'disabled' );
						// $submit.attr( "disabled", false);
					}
				}, 100);
			});
		}
	});

	/**
	 * Loading in iframe (Click)
	 */
	function abrirVideoColorbox(params) {
		let $params = $.extend({
			iframe: true,
			innerWidth: 960,
			innerHeight: 540,
			maxWidth: '90%',
			maxHeight: '90%',
			fixed: true
		}, params);

		$.colorbox($params);
	}
	if( !!$().colorbox ){
		$( 'a[href*="vimeo.com"]' ).on( 'click', function( e ){
			e.preventDefault();
			let match = /vimeo.*\/(\d+)/i.exec( this.href );
			if( match ){
				abrirVideoColorbox({ href: '//player.vimeo.com/video/' + match[ 1 ] + '?autoplay=1', innerHeight: 402 });
			}
		});
		$( 'a[href*="youtube.com"]' ).on( 'click', function( e ){
			e.preventDefault();
			let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/,
					match = this.href.match( regExp );
			if( match && match[ 7 ].length == 11 ){
				abrirVideoColorbox({ href: '//www.youtube.com/embed/' + match[ 7 ] + '?autoplay=1' });
			}
		});
	}
	
	/**
	 * Loading lib in Onetrust (1s)
	 */
	if(window.onetrust){
		setTimeout(() => {
			$.getScript('https://cdn.cookielaw.org/consent/' + window.onetrust + '/OtAutoBlock.js', function(){
				$.getScript('https://cdn.cookielaw.org/scripttemplates/otSDKStub.js', function(){
					function OptanonWrapper(){}
				});
			});
		}, 1000);
	}

	/**
	 * Loading lib in GoogleMaps (Click)
	 */
	
});
