document.addEventListener('DOMContentLoaded', function() {
    window.dataLayer = window.dataLayer || [];
    let recaptcha_loaded = false;
    let galerias = [];
    let hub = {
        validations: {
            /**
             * Validate if email
             */
            isEmail: function(email) {
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                return pattern.test(email);
            },

            /**
             * Validate if phone
             */
            isPhone: function(phone) {
                var pattern = /^(\()?\d{2}(\))?\s?\d{4,5}(\-|\.)?\d{4}$/i;
                return pattern.test(phone);
            },

            /**
             * Disable inputs on click submit
             */
            setDisabled: function(form, state) {
                Array.from(form.querySelectorAll('input, select, textarea, button')).forEach(function(el) {
                    el.disabled = state;
                });
            },

            /**
             * Remove space in input
             */
            removeSpaces: function(event) {
                if (event.which === 32) return false;
            }
        },

        masks: {
            /**
             * Rules for input phone
             */
            phone: function(v) {
                var r = v.replace(/\D/g, "");
                r = r.replace(/^0/, "");
                if (r.length > 10) {
                    r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
                } else if (r.length > 5) {
                    r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
                } else if (r.length > 2) {
                    r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
                } else {
                    r = r.replace(/^(\d*)/, "($1");
                }
                return r;
            },

            /**
             * Rules for special characters and numbers
             */
            removeNonLetters: function(input) {
                return input.replace(/[^a-zA-Z]/g, "");
            },
        },

        integrations: {
            /**
             * Send data for integration
             */
            send: function(data) {
                return new Promise(function(resolve, reject) {
                    let mail_data = data;
                    let company = window.company || '9cc20c19-db84-4e9e-b56e-d93e188a6372';

                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'https://hub.klash.com.br/api/leads/' + company, true);
                    xhr.setRequestHeader('Authorization', 'Bearer 1|kcafoWFwa7FwBruRkG4UP24D03jkMHzCaCU1O5e6c8d74391');

                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            resolve(xhr.response);
                        } else {
                            reject(xhr.statusText);
                        }
                    };

                    xhr.onerror = function() {
                        reject(xhr.statusText);
                    };

                    xhr.send(JSON.stringify(mail_data));
                });
            }
        },

        helpers: {
            /**
             * Load script dynamically
             */
            loadscript: function(url, params = {}, callback = null) {
                let s = document.createElement('script');
                s.type = 'text/javascript';
                for (let p in params) s[p] = params[p];
                s.src = url;
                if (callback) s.onload = callback;
                document.body.appendChild(s);
                return s;
            },

            /**
             * Check if element is in viewport
             */
            isInViewport: function(item) {
                let elTop = item.getBoundingClientRect().top;
                let elBottom = elTop + item.offsetHeight;
                let wHeight = window.innerHeight;
                let scTop = window.scrollY;
                let scBottom = scTop + wHeight;

                return elBottom > scTop && elTop < scBottom;
            },
            checkInView: function() {
                let inView = document.querySelectorAll('section, .inview');
                inView.forEach(function(el) {
                    if (hub.helpers.isInViewport(el)) {
                        el.classList.add('is-inview');
                    }
                });
            },

            /**
             * Remove loading
             */
            removeLoading: function() {
                let loading = document.querySelector('.loading');
                if (loading) loading.style.display = 'none';
            },

            /**
             * Init Colorbox Video
             */
            initColorboxVideo: function() {
                document.querySelectorAll('a[href*="vimeo.com"]').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        let match = /vimeo.*\/(\d+)/i.exec(this.href);
                        if (match) {
                            hub.helpers.showColorboxVideo({ href: '//player.vimeo.com/video/' + match[1] + '?autoplay=1', innerHeight: 402 });
                        }
                    });
                });

                document.querySelectorAll('a[href*="youtube.com"]').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
                        let match = this.href.match(regExp);
                        if (match && match[7].length === 11) {
                            hub.helpers.showColorboxVideo({ href: '//www.youtube.com/embed/' + match[7] + '?autoplay=1' });
                        }
                    });
                });
            },
            showColorboxVideo: function(params) {
                let mergedParams = Object.assign({
                    iframe: true,
                    innerWidth: 960,
                    innerHeight: 540,
                    maxWidth: '90%',
                    maxHeight: '90%',
                    fixed: true
                }, params);
                // Assuming a Colorbox-like functionality
                $.colorbox(mergedParams);
            },

            /**
             * Init Gallery
             */
            initGallery: function(galleryJson) {
                fetch(galleryJson)
                    .then(response => response.json())
                    .then(data => {
                        galerias = data;

                        let nav = document.getElementById('gallery-nav');
                        let li = [];

                        data.galerias.forEach(function(gal) {
                            li.push(`<a href="#" class="btn" data-gallery="${gal.slug}">${gal.name}</a>`);
                        });

                        nav.insertAdjacentHTML('afterbegin', li.join("\n"));

                        nav.querySelector('a:first-child').classList.add('active');

                        hub.helpers.renderGallery(data.galerias[0].slug);
                    });

                document.addEventListener('click', function(e) {
                    if (e.target && e.target.dataset.gallery) {
                        e.preventDefault();
                        hub.helpers.renderGallery(e.target.dataset.gallery);
                        document.querySelector('#gallery-nav a.active').classList.remove('active');
                        e.target.classList.add('active');
                    }
                });
            },
            renderGallery: function(name) {
                let gallery = galerias.galerias.find(gal => gal.slug === name);

                if (!gallery) return;

                let items = gallery.images;
                if (!items.length) return;

                let gal = document.getElementById('gallery-full');
                let path = galerias.path + gallery.slug + '/';

                let html = [];

                items.forEach(function(image) {
                    html.push(`
                        <div class="gallery-item">
                            <a href="${path}${image.full}" class="gallery-image">
                                <img src="${path}${image.thumb}" alt="${image.caption}">
                            </a>
                        </div>
                    `);
                });

                gal.innerHTML = html.join("\n");

                hub.helpers.initColorboxImages(gal.querySelectorAll('a.gallery-image'));
            },
            initColorboxImages: function(images) {
                images.forEach(function(image) {
                    image.addEventListener('click', function(e) {
                        e.preventDefault();
                        // Assuming a Colorbox-like functionality
                        $.colorbox({ href: image.href, maxWidth: '90%', maxHeight: '90%', fixed: true });
                    });
                });
            }
        },

        chats: {
            /**
             * Loading lib in Chat
            */
            patrimar: function() {
                var self = window.location.toString();
                var querystring = self.split("?");
                if (querystring.length > 1) {
                    var pairs = querystring[1].split("&");
                    for (var i in pairs) {
                        var keyval = pairs[i].split("=");
                        if (sessionStorage.getItem(keyval[0]) === null) {
                            sessionStorage.setItem(keyval[0], decodeURIComponent(keyval[1]));
                        }
                    }
                }
                var utm_source = sessionStorage.getItem('utm_source') || "";
                var utm_campaign = sessionStorage.getItem('utm_campaign') || "";
                var utm_medium = sessionStorage.getItem('utm_medium') || "";
                
                var script = document.createElement('script');
                script.src = 'https://www.patrimar.com.br/hotsites/integracoes/chat.php?empreendimento=' + window.building + '&url=' + window.location.pathname + '&utm_source=' + utm_source + '&campanha=' + utm_campaign + '&midia=' + utm_medium;
                script.onload = function() {
                    XRM_Chat.open();
                };
                document.head.appendChild(script);
            },
        },

        recaptcha: {
            /**
             * Loading lib in recaptcha
             */
            init: function(input, recaptcha_loaded) {
                var form = input.closest('form');
                var submit = form.querySelector('.submit-btn');

                if (!recaptcha_loaded) {
                    var script = document.createElement('script');
                    script.src = '//www.google.com/recaptcha/api.js';
                    script.onload = function() {
                        recaptcha_loaded = true;
                        var inputs = form.querySelectorAll('input');
                        inputs.forEach(function(el) {
                            el.removeEventListener('input', function() {});
                        });

                        setTimeout(function() {
                            submit.disabled = true;
                            if (input.name === 'pp' && input.checked) {
                                submit.disabled = false;
                            }
                        }, 100);
                    };
                    document.head.appendChild(script);
                }
            },
        },

        onetrust: {
            /**
             * Loading lib in Onetrust
             */
            init: function(id) {
                var url = 'https://cdn.cookielaw.org/consent/' + id + '/OtAutoBlock.js';

                var script1 = document.createElement('script');
                script1.src = url;
                script1.onload = function() {
                    var script2 = document.createElement('script');
                    script2.type = 'text/javascript';
                    script2.setAttribute('data-domain-script', id);
                    script2.async = true;
                    script2.src = 'https://cdn.cookielaw.org/scripttemplates/otSDKStub.js';
                    document.body.appendChild(script2);
                    function OptanonWrapper() {}
                };
                document.head.appendChild(script1);
            },
        },
    };

    /**
     * Disabled inputs on click submit
     */
    window.formSubmit = function(token) {
        setTimeout(function() {
            let form = document.querySelector("form.sending-form");
            form.querySelector('.submit-btn').disabled = true;
        }, 10);
    }

    /**
     * Apply mask phone
     */
    document.querySelectorAll('.is-phone').forEach(function(input) {
        input.addEventListener('keyup', function(e) {
            var v = hub.masks.phone(this.value);
            if (v != this.value) {
                this.value = v;
            }
        });
    });

    /**
     * Remove special characters
     */
    document.querySelectorAll('.is-text').forEach(function(input) {
        input.addEventListener('keyup', function(e) {
            var v = hub.masks.removeNonLetters(this.value);
            if (v != this.value) {
                this.value = v;
            }
        });
    });

    /**
     * Remove space in input
     */
    document.querySelectorAll('.no-space').forEach(function(input) {
        input.addEventListener('keypress', function(e) {
            hub.validations.removeSpaces(e);
        });
    });

    /**
     * Alternate privacy policy
     */
    document.querySelectorAll('[name="pp"]').forEach(function(input) {
        input.addEventListener('change', function() {
            let form = input.closest('form');
            let submitButton = form.querySelector('.submit-btn');
            submitButton.disabled = !input.checked;
        });
    });

    /**
     * Send data for function Hub
     */
    document.querySelectorAll('.submit-btn').forEach(function(button) {
        button.disabled = true;
        button.addEventListener('click', function(e) {
            e.preventDefault();

            let form = button.closest('form');
            let output = form.querySelector('.form-output');
            let nome = form.querySelector('[name="nome"]').value.trim();
            let email = form.querySelector('[name="email"]').value.trim();
            let telefone = form.querySelector('[name="telefone"]').value.trim();
            let empreendimento = form.querySelector('[name="empreendimento"]').value.trim();
            let sobrenome = form.querySelector('[name="sobrenome"]') ? form.querySelector('[name="sobrenome"]').value.trim() : "";
            let mensagem = form.querySelector('[name="messagem"]') ? form.querySelector('[name="messagem"]').value.trim() : "";
            let origin = form.querySelector('[name="origin"]') ? form.querySelector('[name="origin"]').value.trim() : "";
            let com = form.querySelector('[name="com"]') ? form.querySelector('[name="com"]').value.trim() : "";
            let pp = form.querySelector('[name="pp"]');
            let url_params = location.search ? location.search.replace('?', '') : '';
            let url = location.href;

            if (!pp.checked) {
                alert('Aceite da política de privacidade é obrigatório.');
                return;
            }

            if (nome === '' || email === '' || telefone === '' || empreendimento === '') {
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

            if (!output) {
                form.insertAdjacentHTML('afterbegin', '<div class="form-output"></div>');
                output = form.querySelector('.form-output');
            }

            output.innerHTML = '';
            output.classList.remove('is-error', 'is-success');
            button.classList.add('sending');
            form.classList.add('sending-form');
            hub.validations.setDisabled(form, true);

            let data = { nome, sobrenome, email, telefone, mensagem, empreendimento, url_params, url, origin, com };
            console.log('form data', data);

            hub.integrations.send(data).then(function(em_data) {
                console.log('sendmail', em_data);

                if (em_data.status) {
                    button.classList.remove('sending');
                    form.reset();
                    dataLayer.push({ 'event': 'conversao_sucesso' });
                    document.dispatchEvent(new CustomEvent('form-sended', { detail: form }));
                }

                hub.validations.setDisabled(form, false);
                document.querySelector("form.sending-form .submit-btn").disabled = true;
                form.classList.remove('sending-form');

                output.innerHTML = `<p>${em_data.status ? 'Dados enviados com sucesso.' : 'Erro ao enviar seus dados. Verifique e tente novamente.'}</p>`;
                output.classList.add(em_data.status ? 'is-success' : 'is-error');
            });
        });
    });

    /**
     * Send data in modal WhatsApp for function sendEmail
     */
    document.querySelectorAll('.submit-whatsapp').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            let form = button.closest('form');
            let output = form.querySelector('.form-output-modal');
            let nome = form.querySelector('[name="name"]').value.trim();
            let email = form.querySelector('[name="email"]').value.trim();
            let telefone = form.querySelector('[name="phone"]').value.trim();
            let url_params = location.search ? location.search.replace('?', '') : '';
            let url = location.href;
            let empreendimento = form.querySelector('[name="empreendimento"]').value.trim();
            let tel_whatsapp = form.querySelector('[name="tel-whatsapp"]').value.trim();
            let msg_whatsapp = form.querySelector('[name="msg-whatsapp"]').value.trim();

            if (nome === '' || email === '' || telefone === '' || empreendimento === '') {
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

            if (!output) {
                form.insertAdjacentHTML('afterbegin', '<div class="form-output-modal"></div>');
                output = form.querySelector('.form-output-modal');
            }

            output.innerHTML = '';
            output.classList.remove('is-error', 'is-success');
            button.classList.add('sending');
            form.classList.add('sending-form');
            hub.validations.setDisabled(form, true);

            let data = { nome, email, telefone, url_params, empreendimento, url };
            console.log('form data', data);

            hub.integrations.send(data).then(function(em_data) {
                console.log('sendmail', em_data);

                if (em_data.status) {
                    button.classList.remove('sending');
                    form.reset();
                    dataLayer.push({ 'event': 'whatsapp_sucesso' });
                    document.dispatchEvent(new CustomEvent('form-sended', { detail: form }));
                }

                hub.validations.setDisabled(form, false);
                document.querySelector('#myModal').style.display = 'none';

                window.location.href = 'https://api.whatsapp.com/send/?phone=' + tel_whatsapp + '&text=' + encodeURIComponent(msg_whatsapp) + '&app_absent=0';
            });
        });
    });

    /**
     * Viewport section reading (Add class is-view when section is available)
     */
    hub.helpers.checkInView();
    window.addEventListener('resize', hub.helpers.checkInView);
    window.addEventListener('scroll', hub.helpers.checkInView);

    /**
     * Loading (3.5s)
     */
    if (document.querySelector('.loading')) {
        setTimeout(() => {
            hub.helpers.removeLoading();
        }, 3500);
    }

    /**
     * Loading lib in GTM (3.5s)
     */
    if (window.ID_GTM) {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                hub.gtm.initGTM();
            }, 3500);
        });
        document.addEventListener('scroll', hub.gtm.initGTMOnEvent);
        document.addEventListener('mousemove', hub.gtm.initGTMOnEvent);
        document.addEventListener('touchstart', hub.gtm.initGTMOnEvent);
        document.addEventListener('click', hub.gtm.initGTMOnEvent);
    }

    /**
     * Loading lib in Chat (Click)
     */
    if (window.building && document.querySelector('.chat')) {
        document.querySelector('.chat').addEventListener('click', function() {
            hub.chats.patrimar();
        });
    }

    /**
     * Loading lib in recaptcha (Click)
     */
    document.querySelectorAll('input').forEach(function(input) {
        input.addEventListener('focus', function() {
            hub.recaptcha.init(this, recaptcha_loaded);
        });
    });

    /**
     * Loading in video Colorbox (3.5s)
     */
    if (window.useColorboxVideo) {
        setTimeout(() => {
            if (!$.fn.colorbox) {
                hub.helpers.loadscript('https://hub.klash.com.br/js/external/colorbox.min.js', { defer: true }, function() {
                    console.log('Colorbox loaded');
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
    if (window.oneTrust) {
        setTimeout(() => {
            hub.onetrust.init(window.oneTrust);
        }, 3500);
    }

    /**
     * Loading lib in Gallery (3.5s)
     */
    if (window.galleryJson) {
        setTimeout(() => {
            if (!$.fn.slick && $.fn.colorbox) {
                hub.helpers.loadscript('https://hub.klash.com.br/js/external/slick.min.js', { defer: true }, function() {
                    console.log('Slick loaded');
                    hub.helpers.loadscript('https://hub.klash.com.br/js/external/colorbox.min.js', { defer: true }, function() {
                        console.log('Colorbox loaded');
                        hub.helpers.initGallery(window.galleryJson);
                    });
                });
            } else if (!$.fn.slick) {
                hub.helpers.loadscript('https://hub.klash.com.br/js/external/slick.min.js', { defer: true }, function() {
                    console.log('Slick loaded');
                    hub.helpers.initGallery(window.galleryJson);
                });
            } else if (!$.fn.colorbox) {
                hub.helpers.loadscript('https://hub.klash.com.br/js/external/colorbox.min.js', { defer: true }, function() {
                    console.log('Colorbox loaded');
                    hub.helpers.initGallery(window.galleryJson);
                });
            } else {
                hub.helpers.initGallery(window.galleryJson);
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
