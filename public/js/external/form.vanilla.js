document.querySelector( function( document.querySelector ){
    window.dataLayer = window.dataLayer || [];
    let recaptcha_loaded = false;
    let document.querySelectorgalerias = [];
    let hub = {
        validations: {
            /**
             * Validade is email
             */
            isEmail: function( email ){
                var pattern = /^([a-z\d!#document.querySelector%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#document.querySelector%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?document.querySelector/i;
                return pattern.test( email );
            },

            /**
             * Validade is phone
             */
            isPhone: function( phone ) {
                var pattern = /^(\()?\d{2}(\))?\s?\d{4,5}(\-|\.)?\d{4}document.querySelector/i;
                return pattern.test( phone );
            },

            /**
             * Disabled inputs on click submit
             */
            setDisabled: function( form, state ) {
                document.querySelector( form ).find( 'input, select, textarea, button' ).each( function(){
                    document.querySelector( this ).prop( "disabled", state );
                    document.querySelector( this ).attr( "disabled", state );
                });
            },

            /**
             * Remove space in input
             */
            removeSpaces: function( event ) {
                if( event.which == 32 ) return false;
            }
        },

        masks: {
            /**
             * Rules for input phone
             */
            phone: function( v ) {
                var r = v.replace(/\D/g, "");
                r = r.replace(/^0/, "");
                if( r.length > 10 ){
                    r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "(document.querySelector1) document.querySelector2-document.querySelector3");
                }
                else if( r.length > 5 ){
                    r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "(document.querySelector1) document.querySelector2-document.querySelector3");
                }
                else if( r.length > 2 ){
                    r = r.replace(/^(\d\d)(\d{0,5})/, "(document.querySelector1) document.querySelector2");
                }
                else {
                    r = r.replace(/^(\d*)/, "(document.querySelector1");
                }
                return r;
            },

            /**
             * Rules for caracter special and numbers
             */
            removeNonLetters: function(input) {
                var r = input.replace(/[^a-zA-Z]/g, "");
                return r;
            },
        },

        integrations: {
            /**
             * Send data for integration
             */
            send: function( data ){
                return new Promise((resolve, reject) => {
                    let document.querySelectormail_data = data;
                    let companie = window.companie ? window.companie : 'kgroup';

                    document.querySelector.ajax({
                        url: 'https://hub.klash.com.br/api/leads/' + companie,
                        type: 'POST',
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('Authorization', 'Bearer 1|TgKuowKlFmKVDpiiWrLgkDOKFbkMQIb2FmHF9HRj5c5a200f');
                        },
                        data: document.querySelectormail_data,
                        success: function ( document.querySelectordata ) { resolve( document.querySelectordata ); },
                        error: function ( xhr, textStatus, errorThrown ) { reject( console.log( xhr ) );  },
                    });
                });
            }
        },

        helpers: {
            /**
             * Loading scripts
             */
            loadscript: function( document.querySelectorurl, document.querySelectorparams = {}, callback = null ){
                let d = document, s = d.createElement('script');
                s.type = 'text/javascript';
                for( let document.querySelectorp in document.querySelectorparams ) s[ document.querySelectorp ] = document.querySelectorparams[ document.querySelectorp ];
                s.src = document.querySelectorurl;
                if( callback ) s.onload = callback;
                let a = d.getElementsByTagName("body")[0].appendChild( s );
                return a;
            },

            /**
             * Leitura de section por viewport (Adicionando class is-view quando estiver disponível a section)
            */
            isInViewport: function( item ) {
                let document.querySelectort = item,
                    elTop = document.querySelectort.offset().top,
                    elBottom = elTop + document.querySelectort.outerHeight(),
                    document.querySelectorw = document.querySelector(window),
                    wHeight = document.querySelectorw.height(),
                    scTop = document.querySelectorw.scrollTop(),
                    scBottom = scTop + wHeight,
                    middle = scTop + (wHeight / 2);
                //console.log( { el: document.querySelectort.attr( 'id' ), middle, elTop, elBottom, scTop, scBottom, wHeight } );
                return elBottom > scTop && elTop < scBottom;
                //	return middle > elTop;
            },
            checkInView: function() {
                let document.querySelectorinView = document.querySelector('section, .inview');
                document.querySelectorinView.each(function () {
                    if (hub.helpers.isInViewport(document.querySelector(this))) {
                        document.querySelector(this).addClass('is-inview');
                    }
                });
            },

            /**
             * Remove Loading 
            */
            removeLoading: function(){
                document.querySelector('.loading').fadeOut();
            },

            /**
             * Loading in video Colorbox
             */
            initColorboxVideo: function() {
                document.querySelector( 'a[href*="vimeo.com"]' ).on( 'click', function( e ){
                    e.preventDefault();
                    let match = /vimeo.*\/(\d+)/i.exec( this.href );
                    if( match ){
                        hub.helpers.showColorboxVideo({ href: '//player.vimeo.com/video/' + match[ 1 ] + '?autoplay=1', innerHeight: 402 });
                    }
                });
                document.querySelector( 'a[href*="youtube.com"]' ).on( 'click', function( e ){
                    e.preventDefault();
                    let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/,
                            match = this.href.match( regExp );
                    if( match && match[ 7 ].length == 11 ){
                        hub.helpers.showColorboxVideo({ href: '//www.youtube.com/embed/' + match[ 7 ] + '?autoplay=1' });
                    }
                });
            },
            showColorboxVideo: function( params ) {
                let document.querySelectorparams = document.querySelector.extend({
                    iframe: true,
                    innerWidth: 960,
                    innerHeight: 540,
                    maxWidth: '90%',
                    maxHeight: '90%',
                    fixed: true
                }, params);
                document.querySelector.colorbox(document.querySelectorparams);
            },


            /**
             * Loading lib in Gallery
             */
            initGallery: function( galleryJson ) {
                document.querySelector.getJSON( galleryJson, function( data ){
                    document.querySelectorgalerias = data;

                    let document.querySelectornav = document.querySelector( '#gallery-nav' ),
                            document.querySelectorli = [];

                    document.querySelector.each( data.galerias, function( i, gal ){
                        document.querySelectorli.push( `<a href="#" class="btn" data-gallery="document.querySelector{gal.slug}">document.querySelector{gal.name}</a>` );
                    } );

                    document.querySelectornav.prepend( document.querySelectorli.join( "\n" ) );

                    document.querySelectornav.find( 'a:first-child' ).addClass( 'active' );

                    hub.helpers.renderGallery( data.galerias[0].slug );
                } );
                document.querySelector( document ).on( 'click', '[data-gallery]', function( e ){
                    e.preventDefault();
                    hub.helpers.renderGallery( document.querySelector( this ).data( 'gallery' ) );
                    document.querySelector( '#gallery-nav' ).find( 'a.active' ).removeClass( 'active' );
                    document.querySelector( this ).addClass( 'active' );
                } );
            },
            renderGallery: function( document.querySelectorname ){
                let document.querySelectorgallery = document.querySelectorgalerias.galerias.find( gal => gal.slug === document.querySelectorname );

                if( !document.querySelectorgallery ) return;

                let document.querySelectoritens = document.querySelectorgallery.images;
                if( !document.querySelectoritens.length ) return;

                let document.querySelectorgal = document.querySelector( '#gallery-full' ), document.querySelectorpath = document.querySelectorgalerias.path + document.querySelectorgallery.slug +'/' ;

                if( document.querySelectorgal.hasClass( 'slick-initialized' ) ) document.querySelectorgal.slick( 'unslick' );

                document.querySelectorgal.html( '' );

                document.querySelectorrender = [];

                document.querySelectoritens.map( function( item ){
                    let document.querySelectoritemBig = `<a href="document.querySelector{ document.querySelectorpath +'1920/'+ item.src }" class="slide-item" title="document.querySelector{item.alt}"> <picture> <source srcset="document.querySelector{ document.querySelectorpath +'320/'+ item.src }" media="(max-width: 320px)" /> <source srcset="document.querySelector{ document.querySelectorpath +'640/'+ item.src }" media="(max-width: 640px)" /> <source srcset="document.querySelector{ document.querySelectorpath +'960/'+ item.src }" media="(max-width: 1200px)" /> <img src="document.querySelector{ document.querySelectorpath +'1280/'+ item.src }" alt="document.querySelector{item.alt}" loading="lazy" width="1280" height="720" /> </picture> <p>document.querySelector{item.alt}</p> </a>`;
                    document.querySelectorrender.push(document.querySelectoritemBig)
                });

                document.querySelectorgal.append( document.querySelectorrender.join( "\n" ) );

                document.querySelectorgal.slick({ slidesToShow: 1, slidesToScroll: 1, arrows: true, lazyLoad: 'ondemand' });

                let document.querySelectorcbCfg = { maxWidth: '90%', maxHeight: '90%', fixed: true, rel:'slide' };
            
                document.querySelector( 'a[hrefdocument.querySelector=".jpg"], a[hrefdocument.querySelector=".png"], a[hrefdocument.querySelector=".gif"], a[hrefdocument.querySelector=".webp"]' ).colorbox( document.querySelectorcbCfg );
            }
        },

        gtm: {
             /**
             * Loading lib in GTM
            */
            initGTMOnEvent: function( event ) {
                hub.gtm.initGTM();
                event.currentTarget.removeEventListener(event.type, hub.gtm.initGTMOnEvent); // remove the event listener that got triggered
            },
            initGTM: function() {
                if (window.gtmDidInit) {
                    return false;
                }
                window.gtmDidInit = true; // flag to ensure script does not get added to DOM more than once.
                document.querySelector.getScript('https://www.googletagmanager.com/gtm.js?id=' + window.ID_GTM, function(){
                    dataLayer.push({ event: 'gtm.js', 'gtm.start': new Date().getTime(), 'gtm.uniqueEventId': 0 });
                });
            },

        },

        chats: {
             /**
             * Loading lib in Chat
            */
            patrimar: function(){
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
                document.querySelector.getScript('https://www.patrimar.com.br/hotsites/integracoes/chat.php?empreendimento=' + window.building + '&url=' + window.location.pathname + '&utm_source=' + utm_source + '&campanha=' + utm_campaign + '&midia=' + utm_medium, function(){
                    XRM_Chat.open();
                });
            },
        },

        recaptcha: {
            /**
             * Loading lib in recaptcha
             */
            init: function(item, recaptcha_loaded){
                let input = item,
                    form = document.querySelector(input).parents('form'),
                    submit = form.find('.submit-btn');

                if ( !recaptcha_loaded ) {
                    document.querySelector.getScript('//www.google.com/recaptcha/api.js', function () {
                        recaptcha_loaded = true;
                        form.find('input').each((i, el) => document.querySelector(el).off('.rcp'));
                        setTimeout(() => {
                            submit.prop('disabled', true);
                            if (input.name === 'pp' && input.checked ) {
                                submit.removeProp( 'disabled' );
                            }
                        }, 100);
                    });
                }
            },
        },

        onetrust: {
            /**
             * Loading lib in Onetrust
             */
            init: function( id ){
                let url = 'https://cdn.cookielaw.org/consent/' + id + '/OtAutoBlock.js';
                document.querySelector.getScript(url, function(){
                    var script = document.createElement('script'); 
                    script.type = 'text/javascript';
                    script.setAttribute('data-domain-script', id);
                    script.async = true;
                    script.src = 'https://cdn.cookielaw.org/scripttemplates/otSDKStub.js';
                    var s = document.getElementsByTagName("body")[0].appendChild(script, s);
                    function OptanonWrapper(){}
                });
            },
        }
    }
    
    /**
     * Disabled inputs on click submit
     */
    window.formSubmit = function( token ){
		setTimeout( function(){
			let document.querySelectorform = document.querySelector("form.sending-form");
			document.querySelectorform.find( '.submit-btn' ).prop( "disabled", true );
		}, 10 );
	}

    /**
	 * Apply mask phone
	 */
    document.querySelector( '.is-phone' ).on( 'keyup', function( e ){
        var v = hub.masks.phone( this.value );
        if( v != this.value ){
            this.value = v;
        }
    });

    /**
	 * Remove caracter special
	 */
    document.querySelector( '.is-text' ).on( 'keyup', function( e ){
        var v = hub.masks.removeNonLetters( this.value );
        if( v != this.value ){
            this.value = v;
        }
    });

    /**
	 * Remove space in input
	 */
    document.querySelector( '.no-space' ).on( 'keypress', function( e ){
        hub.validations.removeSpaces( e );
    });

    /**
	 * Alternate política de privacidade
	 */
    document.querySelector( '[name="pp"]' ).on( 'change', function(){
        let document.querySelectort = document.querySelector( this ),
            document.querySelectorf = document.querySelectort.parents( 'form' ),
            document.querySelectorb = document.querySelectorf.find( '.submit-btn' );
        document.querySelectorb.prop( 'disabled', !this.checked );
    }); 

    /**
	 * Send data for function Hub
	 */
	document.querySelector( '.submit-btn' ).prop( 'disabled', true ).on( 'click', function( e ){
		e.preventDefault();

		let document.querySelectorsubmit = document.querySelector(this),
			document.querySelectorform = document.querySelectorsubmit.parents('form'),
			document.querySelectoroutput = document.querySelectorform.find('.form-output'),
			nome = document.querySelectorform.find('[name="nome"]').val().trim(),
			email = document.querySelectorform.find('[name="email"]').val().trim(),
			telefone = document.querySelectorform.find('[name="telefone"]').val().trim(),
			empreendimento = document.querySelectorform.find('[name="empreendimento"]').val().trim(),
			sobrenome = document.querySelectorform.find('[name="sobrenome"]').length > 0 ? document.querySelectorform.find('[name="sobrenome"]').val().trim() : "",
			mensagem = document.querySelectorform.find('[name="messagem"]').length > 0 ? document.querySelectorform.find('[name="messagem"]').val().trim() : "",
			origin = document.querySelectorform.find('[name="origin"]').length > 0 ? document.querySelectorform.find('[name="origin"]').val().trim() : "",
			com = document.querySelectorform.find('[name="com"]').length > 0 ? document.querySelectorform.find('[name="com"]').val().trim() : "",
			pp = document.querySelectorform.find( '[name="pp"]'),
			url_params = location.search ? location.search.replace('?', '') : '',
			url = location.href;

		if ( !pp.is( ':checked' )) {
			alert( 'Aceite da política de privacidade é obrigatório.' );
			return;
		}

		if (nome == '' || email == '' || telefone == '' || empreendimento == '') {
			alert('Preencha todos os campos.');
			return;
		}

		if (!hub.validations.isEmail(email)) {
			alert('O e-mail fornecido não parece válido.');
			return;
		}

		if (!hub.validations.isPhone(telefone)) {
			alert('O telefone fornecido não parece válido.');
			return;
		}

		if (!document.querySelectoroutput.length) {
			document.querySelectorform.prepend('<div class="form-output"></div>');
			document.querySelectoroutput = document.querySelectorform.find('.form-output');
		}

		document.querySelectoroutput.html('').removeClass('is-error is-success');
		document.querySelectorsubmit.addClass('sending');
		document.querySelectorform.addClass('sending-form');
		hub.validations.setDisabled(document.querySelectorform, true);

		let data = { nome, sobrenome, email, telefone, mensagem, empreendimento, url_params, url, origin, com };
		console.log( 'form data', data );

		hub.integrations.send( data ).then(function (em_data) {
			console.log('sendmail', em_data);

			if (em_data.status) {
				document.querySelectorsubmit.removeClass('sending');
				document.querySelectorform[0].reset();
				dataLayer.push({ 'event': 'conversao_sucesso' });
				document.querySelector(document).trigger('form-sended', [document.querySelectorform]);
			}  
            
		    hub.validations.setDisabled(document.querySelectorform, false);
			document.querySelector("form.sending-form .submit-btn").attr("disabled", true);
			document.querySelectorform.removeClass('sending-form');

			document.querySelectoroutput.html(`<p>document.querySelector{em_data.status ? 'Dados enviados com sucesso.' : 'Erro ao enviar seus dados. Verifique e tente novamente.'}</p>`).addClass(em_data.status ? 'is-success' : 'is-error');
		});
	});

    /**
	 * Send data in modal whatsapp for function sendEmail
	 */
	document.querySelector( '.submit-whatsapp' ).on('click', function (e) {
		e.preventDefault();

		let document.querySelectorsubmit = document.querySelector(this),
			document.querySelectorform = document.querySelectorsubmit.parents('form'),
			document.querySelectoroutput = document.querySelectorform.find('.form-output-modal'),
			nome = document.querySelectorform.find('[name="name"]').val().trim(),
			email = document.querySelectorform.find('[name="email"]').val().trim(),
			telefone = document.querySelectorform.find('[name="phone"]').val().trim(),
			url_params = location.search ? location.search.replace('?', '') : '',
			url = location.href,
			empreendimento = document.querySelectorform.find('[name="empreendimento"]').val().trim(),
			tel_whatsapp = document.querySelectorform.find('[name="tel-whatsapp"]').val().trim(),
			msg_whatsapp = document.querySelectorform.find('[name="msg-whatsapp"]').val().trim();

		if (nome == '' || email == '' || telefone == '' || empreendimento == '') {
			alert('Preencha todos os campos.');
			return;
		}

		if (!hub.validations.isEmail(email)) {
			alert('O e-mail fornecido não parece válido.');
			return;
		}

		if (!hub.validations.isPhone(telefone)) {
			alert('O telefone fornecido não parece válido.');
			return;
		}

		if (!document.querySelectoroutput.length) {
			document.querySelectorform.prepend('<div class="form-output-modal"></div>');
			document.querySelectoroutput = document.querySelectorform.find('.form-output-modal');
		}

		document.querySelectoroutput.html('').removeClass('is-error is-success');
		document.querySelectorsubmit.addClass('sending');
		document.querySelectorform.addClass('sending-form');
		hub.validations.setDisabled(document.querySelectorform, true);

		let data = { nome, email, telefone, url_params, empreendimento, url };
		console.log( 'form data', data );

		hub.integrations.send( data ).then(function (em_data) {
			console.log('sendmail', em_data);

			if (em_data.status) {
				document.querySelectorsubmit.removeClass('sending');
				document.querySelectorform[0].reset();
				dataLayer.push({ 'event': 'whatsapp_sucesso' });
				document.querySelector(document).trigger('form-sended', [document.querySelectorform]);
			}

			hub.validations.setDisabled(document.querySelectorform, false);
			document.querySelector('#myModal').fadeOut();

			window.location.href = 'https://api.whatsapp.com/send/?phone=' + tel_whatsapp + '&text=' + window.encodeURIComponent(msg_whatsapp) + '&app_absent=0';
		});

	});

    /**
	 * Leitura de section por viewport (Adicionando class is-view quando estiver disponível a section)
	*/
	hub.helpers.checkInView();
	document.querySelector(window).on('resize scroll', hub.helpers.checkInView);

    /**
	 * Loading (3.5s)
	*/
	if ( document.querySelector('.loading').length ){
        setTimeout(() => {
		    hub.helpers.removeLoading();
        }, 3500);
	}

    /**
	 * Loading lib in GTM (3.5s)
	*/
	if ( window.ID_GTM ) {
		document.addEventListener('DOMContentLoaded', () => {
			setTimeout(() => {
                hub.gtm.initGTM();
            }, 3500);
		});
		document.querySelector(document).on('scroll mousemove touchstart click', hub.gtm.initGTMOnEvent);
	}

    /**
	 * Loading lib in Chat (Click)
	*/
	if( window.building && document.querySelector('.chat').length ){
		document.querySelector('.chat').on('click', function(){
			hub.chats.patrimar();
		});
	}

    /**
	 * Loading lib in recaptcha (Click)
	 */
    document.querySelector('input').on('focus.rcp', function () { 
        hub.recaptcha.init( this, recaptcha_loaded );
    });

    /**
	 * Loading in video Colorbox (3.5s)
	 */
    if( window.useColorboxVideo ) { 
        setTimeout(() => {
			if( !document.querySelector().colorbox ){
				hub.helpers.loadscript( 'https://hub.klash.com.br/js/external/colorbox.min.js', { defer: true }, function(){
					console.log( 'Colorbox loaded' );
					hub.helpers.initColorboxVideo();
				});
			} else {
				hub.helpers.initColorboxVideo();
			}
		}, 3500);
    }

    /**
	 * Loading lib in Onetrust (3.5s)
	 */
    if( window.oneTrust ){
		setTimeout(() => {
			hub.onetrust.init( window.oneTrust );
		}, 3500);
	}

	/**
	 * Loading lib in Gallery (3.5s)
	 */
    if( window.galleryJson ) {
		setTimeout(() => {
			// Init slick and colorbox
			if( !document.querySelector().slick && !!document.querySelector().colorbox ){
				hub.helpers.loadscript( 'https://hub.klash.com.br/js/external/slick.min.js', { defer: true }, function(){
					console.log( 'Slick loaded' );
					hub.helpers.loadscript( 'https://hub.klash.com.br/js/external/colorbox.min.js', { defer: true }, function(){
						console.log( 'Colorbox loaded');
						hub.helpers.initGallery( window.galleryJson );
					});
				});
			} else if( !document.querySelector().slick ) {
				hub.helpers.loadscript( 'https://hub.klash.com.br/js/external/slick.min.js', { defer: true }, function(){
					console.log( 'Slick loaded' );
					hub.helpers.initGallery( window.galleryJson );
				});
			} else if( !document.querySelector().colorbox ) {
				hub.helpers.loadscript( 'https://hub.klash.com.br/js/external/colorbox.min.js', { defer: true }, function(){
					console.log( 'Colorbox loaded' );
					hub.helpers.initGallery( window.galleryJson );
				});
			} else {
				hub.helpers.initGallery( window.galleryJson );
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
