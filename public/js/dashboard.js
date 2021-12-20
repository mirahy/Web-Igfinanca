$(function () {
    $.when(

        //retorna valor de entradas dos dízimos
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=1&caixa=1&closing_status=1",
            dataType: "json",
            success: function (response) {
                $("#entries").html('R$' + number_format(response,2,',','.'));              
            }
        }),

        //retorna valor para de saidas dos dízimos
            $.ajax({
                type: "GET",
                url: "sum?status=1&operation=2&caixa=1&closing_status=1",
                dataType: "json",
                success: function (response) {
                    $("#exits").html('R$' + number_format(response,2,',','.'));            
                }
            }),

        //retorna saldo dos meses anteriores para somar a entrada das ofertas
            $.ajax({
                type: "GET",
                url: "init?status=1&caixa=2&closing_status=0",
                dataType: "json",
                success: function (response) {
                    
                },
                complete: function (response2) {
                    
                    $.ajax({
                        type: "GET",
                        url: "sum?status=1&operation=1&caixa=2&closing_status=1",
                        dataType: "json",
                        success: function (response) { 
                            value = response2.responseJSON;
                            $("#entries_o").html('R$' + number_format(value + response,2,',','.'));
                            
                        }
                    })      
                }
            }),
    
        //retorna valor saídas de ofertas
            $.ajax({
                type: "GET",
                url: "sum?status=1&operation=2&caixa=2&closing_status=1",
                dataType: "json",
                success: function (response) {
                    $("#exits_o").html('R$' + number_format(response,2,',','.'));   
                }
            })

    ).done(

        // setar valor de saldo dos dízimos
            $.ajax({
                type: "GET",
                url: "balance?status=1&caixa=1&closing_status=1&initDate=1&finalDate=1&closing_id=1",
                dataType: "json",
                success: function (response) {  
                    $("#balance").html('R$' + number_format(response,2,',','.'));   
                }
                
            }),

        // setar valor de saldo dos ofertas
            $.ajax({
                type: "GET",
                url: "init?status=1&caixa=2&closing_status=0",
                dataType: "json",
                success: function (response) {  
    
                },
                complete: function (response2) {
                    
                    $.ajax({
                        type: "GET",
                        url: "balance?status=1&caixa=2&closing_status=1&initDate=1&finalDate=1&closing_id=1",
                        dataType: "json",
                        success: function (response) { 
                            value = response2.responseJSON;
                            $("#balance_o").html('R$' + number_format(value + response,2,',','.'));
                            
                        }
                    })      
                }
                
            }),

        //retorna lançamentos pendentes dos dízimos
            $.ajax({
                type: "GET",
                url: "pend?status=0&caixa=1",
                dataType: "json",
                success: function (response) {
                    $("#pend").html(response);
                }
            }),

        //retorna lançamentos pendentes das ofertas
            $.ajax({
                type: "GET",
                url: "pend?status=0&caixa=2",
                dataType: "json",
                success: function (response) {
                    $("#pend_o").html(response);
                }
            })

    );

    
})