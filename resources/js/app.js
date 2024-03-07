//import './bootstrap';

// Enable toltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Menu mobile
if(localStorage.getItem('menu') == "mobile"){
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