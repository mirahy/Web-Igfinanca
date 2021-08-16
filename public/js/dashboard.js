$(function () {

    var entrie = 0;
    var exit = 0;
    var entrie_o = 0;
    var init_value_o = 0;
    var exit_o = 0; 
    
    $.ajax(
        entrada_dizimo('entrada_dizimo'),
        saida_dizimo('saida_dizimo'),
        saldo_inicial('saldo_inicial'),
        entrada_ofertas('entrada_ofertas'),
        saida_ofertas('saida_ofertas'),
        set_values('set_values')
    )


    //retorna valor de entradas dos dízimos
    function entrada_dizimo(mensagem){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=1&caixa=1&closing_status=1",
            dataType: "json",
            success: function (response) {
                entrie = response;              
            }
        })
        console.log(mensagem);
        return parseFloat(entrie);
        
    }

    //retorna valor para de saidas dos dízimos
    function saida_dizimo(mensagem){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=2&caixa=1&closing_status=1",
            dataType: "json",
            success: function (response) {
                exit = response;            
            }
        })
        console.log(mensagem);
        return parseFloat(exit);  
    } 
    

    //retorna saldo dos meses anteriores para somar a entrada das ofertas
    function saldo_inicial(mensagem){
        $.ajax({
            type: "GET",
            url: "init?status=1&caixa=2&closing_status=0",
            dataType: "json",
            success: function (response) {
                init_value_o = response;
            }
        })
        console.log(mensagem);
        return parseFloat(init_value_o);
    }
    


    //retorna valor entradas das ofertas
    function entrada_ofertas(mensagem){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=1&caixa=2&closing_status=1",
            dataType: "json",
            success: function (response) {
                entrie_o = response;
            }
        })
        console.log(mensagem);
        return parseFloat(entrie_o);
    }
    

    //retorna valor saídas de ofertas
    function saida_ofertas(mensagem){
        $.ajax({
            type: "GET",
            url: "sum?status=1&operation=2&caixa=2&closing_status=1",
            dataType: "json",
            success: function (response) {
                exit_o = response;    
            }
        })
        console.log(mensagem);
        return parseFloat(exit_o);
    }

    // setar valores nos cards
    function set_values(mensagem){
        $.ajax({
            success: function (response) {
                $("#entries").html('R$' + number_format(entrada_dizimo('Olá 1'),2,',','.'));
                $("#exits").html('R$' + number_format(saida_dizimo('Olá 2'),2,',','.'));
                $("#balance").html('R$' + number_format(entrada_dizimo('Olá 3') - saida_dizimo('Olá 4'),2,',','.')); 
                $("#entries_o").html('R$' + number_format(entrada_ofertas('Olá 5') + saldo_inicial('Olá 6'),2,',','.'));
                $("#exits_o").html('R$' + number_format(saida_ofertas('Olá 7'),2,',','.'));
                $("#balance_o").html('R$' + number_format((entrada_ofertas('Olá 8') + saldo_inicial('Olá 9')) - saida_ofertas('Olá 10'),2,',','.'));
                console.log(mensagem);
                
            }
            
        }); 
    }

    

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