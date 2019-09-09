// Base url do site
baseUrl = getBaseUrl();

$(function(){

    $("#login_form").submit(function(){
        
        $.ajax({
            type: "POST",
            url: "login",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function(){
                clearErrorsLogin();
                $("#btn_login").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function(json){
                if(json["status"] == 1){
                    clearErrorsLogin();
                    $("#btn_login").parent().siblings(".help-block").html(loadingImg("Logando..."));
                    window.location = baseUrl + "dashboard";
                }else{
                    showErrors(json["error_list"]);
                }
            },
            error: function(response){
                console.log(response);
            }
        })

        return false;
    })

})