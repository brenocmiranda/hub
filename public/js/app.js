//import './bootstrap';

// Enable toltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Menu mobile
if(localStorage.getItem('menu') == "mobile" || $(window).width() < 769){
    $('aside').removeClass('desktop');
    $('aside').addClass('mobile');
} else {
    $('aside').addClass('desktop');
    $('aside').removeClass('mobile');
}
$('.menu').on('click', function (e) {
    e.preventDefault();
    if ($('aside').hasClass('desktop')) {
        $('aside').removeClass('desktop');
        $('aside').addClass('mobile');
        localStorage.setItem('menu', 'mobile');
    } else {
        $('aside').addClass('desktop');
        $('aside').removeClass('mobile');
        localStorage.setItem('menu', 'desktop');
    }
});

// Tables
var $table = $('#table');
$table.bootstrapTable({
    locale: "pt-BR",
    theadClasses: 'table-dark',
    buttonsAlign: 'left',
    pageSize: 5,
    search: true,
    showSearchButton: true,
    searchable: true,
    pagination: true,
    paginationPreText: '<',
    paginationNextText: '>',
    showColumns: true,
    showColumnsToggleAll: true,
    showExport: true,
    exportDataType: 'all',
    exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
});


// Function slugify
function slugify(str) {
  
    // Converte o texto para caixa baixa:
    str = str.toLowerCase();
    
    // Remove qualquer caractere em branco do final do texto:
    str = str.replace(/^\s+|\s+$/g, '');
  
    // Lista de caracteres especiais que serão substituídos:
    const from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
    
    // Lista de caracteres que serão adicionados em relação aos anteriores:
    const to   = "aaaaaeeeeeiiiiooooouuuunc------";
    
    // Substitui todos os caracteres especiais:
    for (let i = 0, l = from.length; i < l; i++) {
      str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }
  
    // Remove qualquer caractere inválido que possa ter sobrado no texto:
    str = str.replace(/[^a-z0-9 -]/g, '');
    
    // Substitui os espaços em branco por hífen:
    str = str.replace(/\s+/g, '-');
  
    return str;
};

// Mascara de telefone
function mphone( v ) {
    var r = v.replace(/\D/g, "");
    r = r.replace(/^0/, "");
    if (r.length > 12) {
        r = r.replace(/^(\d\d)(\d\d)(\d{5})(\d{4}).*/, "+$1 ($2) $3-$4");
    } else if (r.length > 11) {
        r = r.replace(/^(\d\d)(\d\d)(\d{4})(\d{4}).*/, "+$1 ($2) $3-$4");
    } else if (r.length > 10) {
        r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
    } else if (r.length > 5) {
        r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (r.length > 2) {
        r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
    } else {
        r = r.replace(/^(\d*)/, "($1");
    }
    return r;
}
$( '.is-phone' ).on( 'keyup', function( e ){
    var v = mphone( this.value );
    if( v != this.value ){
        this.value = v;
    }
});

// Função pré-visualização de imagem
function image(input){
	if(input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (oFREvent){
            var img = $('<img src="' + oFREvent.target.result + '" width="130" height="130" class="rounded-circle me-1 object-fit-cover">');
            $('.PreviewImage').html(img);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

// Search Lead in navbar
$('#leadSearch').on('submit', function(e){
    e.preventDefault();
    let search_term = $(this).find('#search').val().toLowerCase();
    let url = $(this).attr('action');

    if(search_term.length > 0){
        $.ajax({
            type: "GET",
            url: url,
            data: { search: search_term },
            datatype: "json",
            success: function(data){
                $('#leadSearch').find('.resultList').html('');
                if(data[0]){
                    $.each(data, function( index, item ){
                        $('#leadSearch').find('.resultList').append('<a href="' + item.url + '" class="text-decoration d-flex justify-content-between icon-link mb-2"><span>' + item.name + '</span><i class="bi bi-chevron-right"></i></a>');
                        $('#leadSearch').find('.result').show();
                    });
                } else {
                    $('#leadSearch').find('.resultList').append('<p>Nenhum registro encontrado.</span></p>');
                    $('#leadSearch').find('.result').show();
                }
            }
        });
    }
})
$('#leadSearch .close').on('click', function(e){
    e.preventDefault();
    $('#leadSearch').find('.resultList').html('');
    $('#leadSearch').find('#search').val('');
    $('#leadSearch').find('.result').hide();
});