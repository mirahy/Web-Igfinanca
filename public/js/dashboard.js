$(function () {

    var entrie = 0;
    var exit = 0;
    var entrie_o = 0;
    var exit_o = 0;
    var result = 0;
    var result_o = 0;


    //retorna valor para a div entries do card entradas dos dízimos
    $.ajax({
        type: "GET",
        url: "sum?status=1&operation=1&caixa=1",
        dataType: "json",
        success: function (response) {
            entrie = response;
            $("#entries").html('R$' + number_format(entrie,2,',','.'));
        }
    })

    //retorna valor para a div exits do card saídas e retorna o valor do carda saldo dos dízímos
    $.ajax({
        type: "GET",
        url: "sum?status=1&operation=2&caixa=1",
        dataType: "json",
        success: function (response) {
            exit = response;
            $("#exits").html('R$' + number_format(exit,2,',','.'));
            result = entrie - exit;
            $("#balance").html('R$' + number_format(result,2,',','.'));
        }
    })

    //retorna valor para a div entries do card entradas das ofertas
    $.ajax({
        type: "GET",
        url: "sum?status=1&operation=1&caixa=2",
        dataType: "json",
        success: function (response) {
            entrie_o = response;
            $("#entries_o").html('R$' + number_format(entrie_o,2,',','.'));
        }
    })

    //retorna valor para a div exits do card saídas e retorna o valor do carda saldo das ofertas
    $.ajax({
        type: "GET",
        url: "sum?status=1&operation=2&caixa=2",
        dataType: "json",
        success: function (response) {
            exit_o = response;
            $("#exits_o").html('R$' + number_format(exit_o,2,',','.'));
            result_o = entrie_o - exit_o;
            $("#balance_o").html('R$' + number_format(result_o,2,',','.'));
        }
    })


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