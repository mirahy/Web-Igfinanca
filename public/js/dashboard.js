$(function () {

    var entrie = 0;
    var exit = 0;
    var entrie_o = 0;
    var init_value_o = 0;
    var exit_o = 0;    


    //retorna valor de entradas dos dízimos
    function entrada_dizimo(){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=1&caixa=1&closing_status=1",
            dataType: "json",
            success: function (response) {
                entrie = response;              
            }
        })
        return parseFloat(entrie);
    }

    //retorna valor para de saidas dos dízimos
    function saida_dizimo(){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=2&caixa=1&closing_status=1",
            dataType: "json",
            success: function (response) {
                exit = response;            
            }
        })
        return parseFloat(exit);  
    } 
    

    //retorna saldo dos meses anteriores para somar a entrada das ofertas
    function saldo_inicial(){
        $.ajax({
            type: "GET",
            url: "init?status=1&caixa=2&closing_status=0",
            dataType: "json",
            success: function (response) {
                init_value_o = response;
            }
        })
        return parseFloat(init_value_o);
    }
    


    //retorna valor entradas das ofertas
    function entrada_ofertas(){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=1&caixa=2&closing_status=1",
            dataType: "json",
            success: function (response) {
                entrie_o = response;
            }
        })
        return parseFloat(entrie_o);
    }
    

    //retorna valor saídas de ofertas
    function saida_ofertas(){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=2&caixa=2&closing_status=1",
            dataType: "json",
            success: function (response) {
                exit_o = response;    
            }
        })
        return parseFloat(exit_o);
    }

    // setar valores nos cards
    function set_values(){
        $.ajax({
            success: function (response) {
                $("#entries").html('R$' + number_format(entrada_dizimo(),2,',','.'));
                $("#exits").html('R$' + number_format(saida_dizimo(),2,',','.'));
                $("#balance").html('R$' + number_format(entrada_dizimo() - saida_dizimo(),2,',','.')); 
                $("#entries_o").html('R$' + number_format(entrada_ofertas() + saldo_inicial(),2,',','.'));
                $("#exits_o").html('R$' + number_format(saida_ofertas(),2,',','.'));
                $("#balance_o").html('R$' + number_format(entrada_ofertas() + saldo_inicial() - saida_ofertas(),2,',','.'));
                
            }
        }); 
    }

    $.ajax(
        entrada_dizimo(),
        saida_dizimo(),
        saldo_inicial(),
        entrada_ofertas(),
        saida_ofertas(),
        set_values()
    )

    //retorna lançamentos pendentes dos dízimos
    $.ajax({
        type: "GET",
        url: "pend?status=0&caixa=1",
        dataType: "json",
        success: function (response) {
            $("#pend").html(response);
        }
    })

    //retorna lançamentos pendentes das ofertas
    $.ajax({
        type: "GET",
        url: "pend?status=0&caixa=2",
        dataType: "json",
        success: function (response) {
            $("#pend_o").html(response);
        }
    })
})