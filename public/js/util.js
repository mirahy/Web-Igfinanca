// Base url do site
baseUrl = getBaseUrl();


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



