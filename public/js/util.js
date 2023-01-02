// Base url do site
baseUrl = getBaseUrl();

//Parâmetros para foramatação de datas
const option = {
  day: 'numeric',
  month: 'numeric',
  year: 'numeric',
  hour: 'numeric',
  minute: 'numeric',
  second: 'numeric'
}


// Traduçõa para portugues do DataTable
const DATATABLE_PTBR = {
    "sEmptyTable": "Nenhum registro encontrado",
    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_ resultados por página",
    "sLoadingRecords": "Carregando...",
    "sProcessing": "Processando...",
    "sZeroRecords": "Nenhum registro encontrado",
    "sSearch": "Pesquisar",
    "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
    },
    "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
    }
}

//Limpar class e tag span com mensagem de erro
function clearErrors(){
    $(".is-invalid").removeClass("is-invalid");
    $(".danger-feedback").removeClass("danger-feedback");
    $(".help-block").html("");
   }
//Limpar class e tag span com mensagem de erro tela login
   function clearErrorsLogin(){
    $(".d-none").removeClass("d-none");
    $(".is-invalid").removeClass("is-invalid");
    $(".danger-feedback").removeClass("danger-feedback");
    $(".help-block").html("");
   }

   
//Exibir erros
function showErrors(error_list){
    clearErrors();

    $.each(error_list, function(id, message){
        $(id).addClass("is-invalid");
        $(id).parent().siblings(".help-block").addClass("danger-feedback");
        $(id).parent().siblings(".help-block").html(message);
    })

}
//Exibir mensagens dinamicas
function loadingImg(message=""){
    return "<i class='fa fa-circle-notch fa-spin'></i>&nbsp;" + message
}

// formatar data/hora de "mm-dd-aaaa 00:00:00" para "dd-mm-aaaa 00:00:00"
function FormatData(data){
    var dateSplit = data.split('-');
    var dateSplit2 = dateSplit[2].split('T');
    data = new Date(data);
    return dateSplit2[1] == null ? dateSplit2[0] +'-'+ dateSplit[1] +'-'+ dateSplit[0] :
    data.toLocaleDateString('pt-br', option);

}

//formatar numeros
function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }
  




