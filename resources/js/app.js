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
