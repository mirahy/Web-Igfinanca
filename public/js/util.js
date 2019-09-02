// Base url do site
baseUrl = getBaseUrl();

function clearErrors(){
    $(".d-none").removeClass("d-none");
    $(".is-invalid").removeClass("is-invalid");
    $(".danger-feedback").removeClass("danger-feedback");
    $(".help-block").html("");
   }

function showErrors(error_list){
    clearErrors();

    $.each(error_list, function(id, message){
        $(id).addClass("is-invalid");
        $(id).parent().siblings(".help-block").addClass("danger-feedback");
        $(id).parent().siblings(".help-block").html(message);
    })

}

function loadingImg(message=""){
    return "<i class='fa fa-circle-notch fa-spin'></i>&nbsp;" + message
}

