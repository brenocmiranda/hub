jQuery( function( $ ){
    let $galerias = [];
    let init = {
        validations: {
            /**
             * Validade is email
             */
            isEmail: function( email ){
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test( email );
            },

            /**
             * Validade is phone
             */
            isPhone: function( phone ) {
                var pattern = /^(\()?\d{2}(\))?\s?\d{4,5}(\-|\.)?\d{4}$/i;
                return pattern.test( phone );
            },

            /**
             * Disabled inputs on click submit
             */
            setDisabled: function( form, state ) {
                $( form ).find( 'input, select, textarea, button' ).each( function(){
                    $( this ).prop( "disabled", state );
                    $( this ).attr( "disabled", state );
                });
            },
        },

        masks: {
            /**
             * Rules for input phone
             */
            phone: function( v ) {
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
            },

            /**
             * Rules for caracter special
             */
            removeNonLetters: function(input) {
                var r = input.replace(/[^a-zA-Z]/g, "");
                return r;
            },

            /**
             * Rules spaces
             */
            removeSpaces: function(input) {
            if( input.which == 32 ) return false;
            },
        },

        hub: {
            /**
             * Send data for integration
             */
            integration: function( data ){
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
        },

        loading: {
            /**
             * Loading scripts
             */
            script: function( $url, $params = {}, callback = null ){
                let d = document, s = d.createElement('script');
                s.type = 'text/javascript';
                for( let $p in $params ) s[ $p ] = $params[ $p ];
                s.src = $url;
                if( callback ) s.onload = callback;
                let a = d.getElementsByTagName("body")[0].appendChild( s );
                return a;
            },

            /**
             * Leitura de section por viewport (Adicionando class is-view quando estiver disponível a section)
            */
            isInViewport: function( item ) {
                let $t = item,
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
            },
            checkInView: function() {
                let $inView = $('section, .inview');
                $inView.each(function () {
                    if (init.loading.isInViewport($(this))) {
                        $(this).addClass('is-inview');
                    }
                });
            },

            /**
             * Loading Patrimar and Novolar
            */
            patrimar: function(){
                $('.loading').fadeOut();
            },

            /**
             * Loading lib in GTM
            */
            initGTMOnEvent: function( event ) {
                init.loading.initGTM();
                event.currentTarget.removeEventListener(event.type, init.loading.initGTMOnEvent); // remove the event listener that got triggered
            },
            initGTM: function() {
                if (window.gtmDidInit) {
                    return false;
                }
                window.gtmDidInit = true; // flag to ensure script does not get added to DOM more than once.
                $.getScript('https://www.googletagmanager.com/gtm.js?id=' + window.ID_GTM, function(){
                    dataLayer.push({ event: 'gtm.js', 'gtm.start': new Date().getTime(), 'gtm.uniqueEventId': 0 });
                });
            },

            /**
             * Loading lib in Chat
            */
            chatPatrimar: function(){
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
            },

            /**
             * Loading lib in recaptcha
             */
            recaptcha: function($recaptcha_loaded){
                let input = this,
                    $form = $(this).parents('form'),
                    $submit = $form.find('.submit-btn');

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
            },

            /**
             * Loading in video Colorbox
             */
            initColorboxVideo: function() {
                $( 'a[href*="vimeo.com"]' ).on( 'click', function( e ){
                    e.preventDefault();
                    let match = /vimeo.*\/(\d+)/i.exec( this.href );
                    if( match ){
                        showColorboxVideo({ href: '//player.vimeo.com/video/' + match[ 1 ] + '?autoplay=1', innerHeight: 402 });
                    }
                });
                $( 'a[href*="youtube.com"]' ).on( 'click', function( e ){
                    e.preventDefault();
                    let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/,
                            match = this.href.match( regExp );
                    if( match && match[ 7 ].length == 11 ){
                        showColorboxVideo({ href: '//www.youtube.com/embed/' + match[ 7 ] + '?autoplay=1' });
                    }
                });
            },
            showColorboxVideo: function(params) {
                let $params = $.extend({
                    iframe: true,
                    innerWidth: 960,
                    innerHeight: 540,
                    maxWidth: '90%',
                    maxHeight: '90%',
                    fixed: true
                }, params);
                $.colorbox($params);
            },

            /**
             * Loading lib in Onetrust
             */
            onetrust: function(){
                let url = 'https://cdn.cookielaw.org/consent/' + window.oneTrust + '/OtAutoBlock.js';
                $.getScript(url, function(){
                    var script = document.createElement('script'); 
                    script.type = 'text/javascript';
                    script.setAttribute('data-domain-script', window.oneTrust);
                    script.async = true;
                    script.src = 'https://cdn.cookielaw.org/scripttemplates/otSDKStub.js';
                    var s = document.getElementsByTagName("body")[0].appendChild(script, s);
                    function OptanonWrapper(){}
                });
            },

            /**
             * Loading lib in Gallery
             */
            initGallery: function( galleryJson ) {
                $.getJSON( galleryJson, function( data ){
                    $galerias = data;

                    let $nav = $( '#gallery-nav' ),
                            $li = [];

                    $.each( data.galerias, function( i, gal ){
                        $li.push( `<a href="#" class="btn" data-gallery="${gal.slug}">${gal.name}</a>` );
                    } );

                    $nav.prepend( $li.join( "\n" ) );

                    $nav.find( 'a:first-child' ).addClass( 'active' );

                    init.loading.renderGallery( data.galerias[0].slug );
                } );
                $( document ).on( 'click', '[data-gallery]', function( e ){
                    e.preventDefault();
                    init.loading.renderGallery( $( this ).data( 'gallery' ) );
                    $( '#gallery-nav' ).find( 'a.active' ).removeClass( 'active' );
                    $( this ).addClass( 'active' );
                } );
            },
            renderGallery: function( $name ){
                let $gallery = $galerias.galerias.find( gal => gal.slug === $name );

                if( !$gallery ) return;

                let $itens = $gallery.images;
                if( !$itens.length ) return;

                let $gal = $( '#gallery-full' ), $path = $galerias.path + $gallery.slug +'/' ;

                if( $gal.hasClass( 'slick-initialized' ) ) $gal.slick( 'unslick' );

                $gal.html( '' );

                $render = [];

                $itens.map( function( item ){
                    let $itemBig = `<a href="${ $path +'1920/'+ item.src }" class="slide-item" title="${item.alt}"> <picture> <source srcset="${ $path +'320/'+ item.src }" media="(max-width: 320px)" /> <source srcset="${ $path +'640/'+ item.src }" media="(max-width: 640px)" /> <source srcset="${ $path +'960/'+ item.src }" media="(max-width: 1200px)" /> <img src="${ $path +'1280/'+ item.src }" alt="${item.alt}" loading="lazy" width="1280" height="720" /> </picture> <p>${item.alt}</p> </a>`;
                    $render.push($itemBig)
                });

                $gal.append( $render.join( "\n" ) );

                $gal.slick({ slidesToShow: 1, slidesToScroll: 1, arrows: true, lazyLoad: 'ondemand' });

                let $cbCfg = { maxWidth: '90%', maxHeight: '90%', fixed: true, rel:'slide' };
            
                $( 'a[href$=".jpg"], a[href$=".png"], a[href$=".gif"], a[href$=".webp"]' ).colorbox( $cbCfg );
            }
        }
    }
    
    /**
     * Disabled inputs on click submit
     */
    window.formSubmit = function( token ){
		setTimeout( function(){
			let $form = $("form.sending-form");
			$form.find( '.submit-btn' ).prop( "disabled", true );
		}, 10 );
	}

    /**
	 * Apply mask phone
	 */
    $( '.is-phone' ).on( 'keyup', function( e ){
        var v = init.masks.mphone( this.value );
        if( v != this.value ){
            this.value = v;
        }
    });

    /**
	 * Remove caracter special
	 */
    $( '.is-text' ).on( 'keyup', function( e ){
        var v = init.masks.removeNonLetters( this.value );
        if( v != this.value ){
            this.value = v;
        }
    });

    /**
	 * Remove space in input
	 */
    $( '.no-space' ).on( 'keypress', function( e ){
        init.masks.removeSpaces();
    });

    /**
	 * Alternate política de privacidade
	 */
    $( '[name="pp"]' ).on( 'change', function(){
        let $t = $( this ),
            $f = $t.parents( 'form' ),
            $b = $f.find( '.submit-btn' );
        $b.prop( 'disabled', !this.checked );
    }); 

    /**
	 * Send data for function Hub
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

		if (!init.validations.isEmail(email)) {
			alert('O e-mail fornecido não parece válido.');
			return;
		}

		if (!init.validations.isPhone(telefone)) {
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
		init.validations.setDisabled($form, true);

		let data = { nome, sobrenome, email, telefone, mensagem, empreendimento, url_params, url, origin, com };
		console.log( 'form data', data );

		init.hub.integration( data ).then(function (em_data) {
			console.log('sendmail', em_data);

			if (em_data.status) {
				$submit.removeClass('sending');
				$form[0].reset();
				dataLayer.push({ 'event': 'conversao_sucesso' });
				$(document).trigger('form-sended', [$form]);
			}  
            
		    init.validations.setDisabled($form, false);
			$("form.sending-form .submit-btn").attr("disabled", true);
			$form.removeClass('sending-form');

			$output.html(`<p>${em_data.status ? 'Dados enviados com sucesso.' : 'Erro ao enviar seus dados. Verifique e tente novamente.'}</p>`).addClass(em_data.status ? 'is-success' : 'is-error');
		});
	});

    /**
	 * Send data for function Hub
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

		if (!init.validations.isEmail(email)) {
			alert('O e-mail fornecido não parece válido.');
			return;
		}

		if (!init.validations.isPhone(telefone)) {
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
		init.validations.setDisabled($form, true);

		let data = { nome, sobrenome, email, telefone, mensagem, empreendimento, url_params, url, origin, com };
		console.log( 'form data', data );

		init.hub.integration( data ).then(function (em_data) {
			console.log('sendmail', em_data);

			if (em_data.status) {
				$submit.removeClass('sending');
				$form[0].reset();
				dataLayer.push({ 'event': 'conversao_sucesso' });
				$(document).trigger('form-sended', [$form]);
			}

            init.validations.setDisabled($form, false);
			$("form.sending-form .submit-btn").attr("disabled", true);
			$form.removeClass('sending-form');

			$output.html(`<p>${em_data.status ? 'Dados enviados com sucesso.' : 'Erro ao enviar seus dados. Verifique e tente novamente.'}</p>`).addClass(em_data.status ? 'is-success' : 'is-error');
		});
	});

    /**
	 * Leitura de section por viewport (Adicionando class is-view quando estiver disponível a section)
	*/
	init.loading.checkInView();
	$(window).on('resize scroll', init.loading.checkInView);

    /**
	 * Loading Patrimar and Novolar (3.5s)
	*/
	if ( $('.loading').length ){
        setTimeout(() => {
		    init.loading.patrimar();
        }, 3500);
	}

    /**
	 * Loading lib in GTM (3.5s)
	*/
	if ( window.ID_GTM ) {
		document.addEventListener('DOMContentLoaded', () => {
			setTimeout(() => {
                init.loading.initGTM();
            }, 3500);
		});
		$(document).on('scroll mousemove touchstart click', init.loading.initGTMOnEvent);
	}

    /**
	 * Loading lib in Chat (Click)
	*/
	if( window.building && $('.chat').length ){
		$('.chat').on('click', function(){
			init.loading.chatPatrimar();
		});
	}

    /**
	 * Loading lib in recaptcha (Click)
	 */
    let $recaptcha_loaded = false;
    $('input').on('focus.rcp', function () { 
        init.loading.recaptcha( $recaptcha_loaded );
    });

    /**
	 * Loading in video Colorbox (3.5s)
	 */
    if( window.useColorboxVideo ) { 
        setTimeout(() => {
			if( !$().colorbox ){
				init.loading.script( 'https://hub.komuh.com/js/external/colorbox.min.js', { async: true, defer: true }, function(){
					console.log( 'Colorbox loaded' );
					init.loading.initColorboxVideo();
				});
			} else {
				init.loading.initColorboxVideo();
			}
		}, 3500);
    }

    /**
	 * Loading lib in Onetrust (3.5s)
	 */
    if( window.oneTrust ){
		setTimeout(() => {
			init.loading.onetrust();
		}, 3500);
	}

	/**
	 * Loading lib in Gallery (3.5s)
	 */
    if( window.galleryJson ) {
		setTimeout(() => {
			// Init slick and colorbox
			if( !$().slick && !!$().colorbox ){
				init.loading.script( 'https://hub.komuh.com/js/external/slick.min.js', { async: true, defer: true }, function(){
					console.log( 'Slick loaded' );
					init.loading.script( 'colorbox.min.js', { async: true, defer: true }, function(){
						console.log( 'Colorbox loaded');
						init.loading.initGallery( window.galleryJson );
					});
				});
			} else if( !$().slick ) {
				init.loading.script( 'https://hub.komuh.com/js/external/slick.min.js', { async: true, defer: true }, function(){
					console.log( 'Slick loaded' );
					init.loading.initGallery( window.galleryJson );
				});
			} else if( !$().colorbox ) {
				init.loading.script( 'https://hub.komuh.com/js/external/colorbox.min.js', { async: true, defer: true }, function(){
					console.log( 'Colorbox loaded' );
					init.loading.initGallery( window.galleryJson );
				});
			} else {
				init.loading.initGallery();
			}
		}, 3500);
	}

    /**
	 * Loading lib in GoogleMaps (in scrolling)
	 */


	/**
	 * Loading video background ()
	 */
});
