LANCAMENTO_PENDETE   = 0;
LANCAMENTO_APROVADO  = 1;
LANCAMENTO_REPROVADO = 2;

FECHAMENTO_FECHADO        = 0;
FECHAMENTO_ABERTO         = 1;
FECHAMENTO_PRE_FECHAMENTO = 2;

OPERATION_OUTPUT = 0;
OPERATION_INPUT  = 1;

edit = "";

$(function () {

    // botao laçamentos dizimos
    $("#btn_add_launch_d").on("click",function () {
        $.ajax({
            success: function (response) {
                clearErrors();
                $("#name").prop( "disabled", false );
                $("#value").prop( "disabled", false );
                $("#description").prop( "disabled", false );
                $("#idtb_caixa").prop( "disabled", false );
                $("#idtb_payment_type").prop( "disabled", false );
                $("#idtb_closing").prop( "disabled", false );
                $("#operation_date").prop( "disabled", false );
                $("#btn_save_launch").show();
                $("#launch_form")[0].reset();
                $("#idtb_operation").val(1);
                $("#idtb_type_launch").val(1);
                $("#idtb_base").val(1);
                $("#idtb_caixa").val(1);
                $("#id_user").val(0);
                $("#name").show();
                $("#name").parent().siblings(".control-label").show();
                $("#idtb_caixa").hide();
                $("#idtb_caixa").parent().siblings(".control-label").hide();
                //$("#img")[0].attr("src", "");
                $("#modal_launch").modal();
            }
        })
    });

    // botao laçamentos ofertas
    $("#btn_add_launch_o").on("click", function () {
        $.ajax({
            success: function (response) {
                clearErrors();
                $("#name").prop( "disabled", false );
                $("#value").prop( "disabled", false );
                $("#description").prop( "disabled", false );
                $("#idtb_caixa").prop( "disabled", false );
                $("#idtb_payment_type").prop( "disabled", false );
                $("#idtb_closing").prop( "disabled", false );
                $("#operation_date").prop( "disabled", false );
                $("#btn_save_launch").show();
                $("#launch_form")[0].reset();
                $("#idtb_operation").val(1);
                $("#idtb_type_launch").val(2);
                $("#idtb_base").val(1);
                $("#idtb_caixa").val(2);
                $("#id_user").val(0);
                $("#name").val('Oferta Local').hide();
                $("#name").parent().siblings(".control-label").hide();
                $("#idtb_caixa").hide();
                $("#idtb_caixa").parent().siblings(".control-label").hide();
                //$("#img")[0].attr("src", "");
                $("#modal_launch").modal();
            }
        })
    });

     // botao laçamentos gerais
     $("#btn_add_launch_others").on("click", function () {
        $.ajax({
            success: function (response) {
                clearErrors();
                $("#name").prop( "disabled", false );
                $("#value").prop( "disabled", false );
                $("#description").prop( "disabled", false );
                $("#idtb_caixa").prop( "disabled", false );
                $("#idtb_payment_type").prop( "disabled", false );
                $("#idtb_closing").prop( "disabled", false );
                $("#operation_date").prop( "disabled", false );
                $("#btn_save_launch").show();
                $("#launch_form")[0].reset();
                $("#idtb_operation").val(1);
                $("#idtb_type_launch").val(5);
                $("#idtb_base").val(1);
                $("#id_user").val(0);
                $("#name").show();
                $("#name").parent().siblings(".control-label").show();
                $("#idtb_caixa").show();
                $("#idtb_caixa").parent().siblings(".control-label").show();
                //$("#img")[0].attr("src", "");
                $("#modal_launch").modal();
            }
        })
    });

    // botao laçamentos compras
    $("#btn_add_launch_buy").on("click", function () {
        $.ajax({
            success: function (response) {
                clearErrors();
                $("#name").prop( "disabled", false );
                $("#value").prop( "disabled", false );
                $("#description").prop( "disabled", false );
                $("#idtb_caixa").prop( "disabled", false );
                $("#idtb_payment_type").prop( "disabled", false );
                $("#idtb_closing").prop( "disabled", false );
                $("#operation_date").prop( "disabled", false );
                $("#btn_save_launch").show();
                $("#launch_form")[0].reset();
                $("#idtb_operation").val(2);
                $("#idtb_type_launch").val(3);
                $("#idtb_base").val(1);
                $("#id_user").val(0);
                //$("#img")[0].attr("src", "");
                $("#modal_launch").modal();
            }
        })
    });


    // botao laçamentos serviços
    $("#btn_add_launch_service").on("click", function () {
        $.ajax({
            success: function (response) {
                clearErrors();
                $("#name").prop( "disabled", false );
                $("#value").prop( "disabled", false );
                $("#description").prop( "disabled", false );
                $("#idtb_caixa").prop( "disabled", false );
                $("#idtb_payment_type").prop( "disabled", false );
                $("#idtb_closing").prop( "disabled", false );
                $("#operation_date").prop( "disabled", false );
                $("#btn_save_launch").show();
                $("#launch_form")[0].reset();
                $("#idtb_operation").val(2);
                $("#idtb_type_launch").val(4);
                $("#idtb_base").val(1);
                $("#id_user").val(0);
                //$("#img")[0].attr("src", "");
                $("#modal_launch").modal();
            }
        })
    });

    function btn_aprov() {

        //Click aprovar Lançamentos
        $(".btn_apr").on("click", function () {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "aprov",
                dataType: "json",
                data: {
                    "id": $(this).attr("id_launch"),
                    "status": $(this).attr("status")
                },

                success: function (response) {
                    clearErrors();
                    if (response["status"]) {
                        $msg = "Lançamento " + response["success"] + "  com sucesso!";
                        Swal.fire("Sucesso!", $msg, "success");
                        dt_launch_apr.ajax.reload();

                    } else {
                        $msg = "Mensagens: " + response["success"];
                        Swal.fire("Atenção!", $msg, "error");
                        dt_launch_apr.ajax.reload();
                    }
                }


            })


        });

        //Click reprovar Lançamentos
        $(".btn_repr").on("click", function () {
            Swal.fire({
                title: "Atenção!",
                text: "Deseja reprovar este Lançamento?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
            }).then((result) => {
                if (result.value) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: "aprov",
                            dataType: "json",
                            data: {
                                "id": $(this).attr("id_launch"),
                                "status": $(this).attr("status")
                            },

                            success: function (response) {
                                clearErrors();
                                if (response["status"]) {
                                    $msg = "Lançamento " + response["success"] + "  com sucesso!";
                                    Swal.fire("Sucesso!", $msg, "success");
                                    dt_launch_apr.ajax.reload();

                                } else {
                                    $msg = "Mensagens: " + response["success"];
                                    Swal.fire("Atenção!", $msg, "error");
                                    dt_launch_apr.ajax.reload();
                                }
                            }

                        })
                    }
                })

        });

    }


    function btn_edit_launch() {

        // botao editar entradas
        $(".btn_edit_launch").on("click", function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-launch",
                dataType: "json",
                data: { "id": $(this).attr("idtb_launch") },
                success: function (response) {

                    clearErrors();
                    $("#launch_form")[0].reset();
                    $.each(response["imput"], function (id, value) {

                        $("#" + id).val(value)
                    });
                    if (response["imput"]['idtb_type_launch'] == 1) {
                        $("#name").prop( "disabled", false );
                        $("#value").prop( "disabled", false );
                        $("#description").prop( "disabled", false );
                        $("#idtb_caixa").prop( "disabled", false );
                        $("#idtb_payment_type").prop( "disabled", false );
                        $("#idtb_closing").prop( "disabled", false );
                        $("#operation_date").prop( "disabled", false );
                        $("#btn_save_launch").show();
                        $("#idtb_caixa").val(1);
                        $("#name").parent().siblings(".control-label").show();
                        $("#name").show();

                    } else if (response["imput"]['idtb_type_launch'] == 2) {
                        $("#name").prop( "disabled", false );
                        $("#value").prop( "disabled", false );
                        $("#description").prop( "disabled", false );
                        $("#idtb_caixa").prop( "disabled", false );
                        $("#idtb_payment_type").prop( "disabled", false );
                        $("#idtb_closing").prop( "disabled", false );
                        $("#operation_date").prop( "disabled", false );
                        $("#btn_save_launch").show();
                        $("#idtb_caixa").val(2);
                        $("#name").parent().siblings(".control-label").hide();
                        $("#name").val('Oferta Local').hide();

                    }else if (response["imput"]['idtb_type_launch'] == 5) {
                        $("#name").prop( "disabled", false );
                        $("#value").prop( "disabled", false );
                        $("#description").prop( "disabled", false );
                        $("#idtb_caixa").prop( "disabled", false );
                        $("#idtb_payment_type").prop( "disabled", false );
                        $("#idtb_closing").prop( "disabled", false );
                        $("#operation_date").prop( "disabled", false );
                        $("#btn_save_launch").show();
                        $("#name").show();
                        $("#name").parent().siblings(".control-label").show();
                        $("#idtb_caixa").show();
                        $("#idtb_caixa").parent().siblings(".control-label").show();

                    }


                    $("#modal_launch").modal();
                }

            })

        });



        // botao excluir lançamentos de entradas
        $(".btn_del_launch").on("click", function () {
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar este Lançamento?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "destroy-launch",
                        dataType: "json",
                        data: { "id": course_id.attr("idtb_launch") },
                        success: function (response) {
                            console.log(response);
                            $msg = "Lançamento " + response["success"] + "  com sucesso!";
                            Swal.fire("Sucesso!", $msg, "success");
                            dt_launch.ajax.reload();
                            dt_launch_o.ajax.reload();
                        }
                    })
                }
            })
        });


    }


    function btn_edit_launch_s() {

        // botao editar saídas
        $(".btn_edit_launch_exits").on("click", function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-launch",
                dataType: "json",
                data: { "id": $(this).attr("idtb_launch") },
                success: function (response) {

                    clearErrors();
                    $("#launch_form")[0].reset();
                    $.each(response["imput"], function (id, value) {
                        $("#" + id).val(value)

                    });
                    $("#name").prop( "disabled", false );
                    $("#value").prop( "disabled", false );
                    $("#description").prop( "disabled", false );
                    $("#idtb_caixa").prop( "disabled", false );
                    $("#idtb_payment_type").prop( "disabled", false );
                    $("#idtb_closing").prop( "disabled", false );
                    $("#operation_date").prop( "disabled", false );
                    $("#btn_save_launch").show();
                    $("#modal_launch").modal();

                }

            })

        });

        
        // botao excluir lançamentos de saídas
        $(".btn_del_launch_exits").on("click", function () {
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar este Lançamento?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "destroy-launch",
                        dataType: "json",
                        data: { "id": course_id.attr("idtb_launch") },
                        success: function (response) {
                            console.log(response);
                            $msg = "Lançamento " + response["success"] + "  com sucesso!";
                            Swal.fire("Sucesso!", $msg, "success");
                            dt_launch_buy.ajax.reload();
                            dt_launch_service.ajax.reload();
                        }
                    })
                }
            })
        });


    }

    function btn_vizualizar() {

        // botao editar saídas
        $(".btn_vizualizar").on("click", function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-launch",
                dataType: "json",
                data: { "id": $(this).attr("idtb_launch") },
                success: function (response) {

                    clearErrors();
                    $("#launch_form")[0].reset();
                    $.each(response["imput"], function (id, value) {
                        $("#" + id).val(value)

                    });
                    $("#name").prop( "disabled", true );
                    $("#value").prop( "disabled", true );
                    $("#description").prop( "disabled", true );
                    $("#idtb_caixa").prop( "disabled", true );
                    $("#idtb_payment_type").prop( "disabled", true );
                    $("#idtb_closing").prop( "disabled", true );
                    $("#operation_date").prop( "disabled", true );
                    $("#btn_save_launch").hide();
                    $("#modal_launch").modal();

                }

            })

        });
    }



    // função pesquisa autocomplete nome usuários
    $(function () {

        $("#name").autocomplete({

            source: baseUrl + 'autocomplete'
        });
    });






    /** 
    * tabela dizimos
    **/
    var dt_launch = $('#dt_launch').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query?launch=1&caixa=1&closing_status=1&closing_status1=2',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            {
                data: 'value', name: 'value',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'caixa.name', name: 'caixa.name' },
            { data: 'payment_type.name', name: 'payment_type.name' },
            {
                data: 'operation_date',

                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == LANCAMENTO_PENDETE ? "<a href='"+baseUrl+"apr-l'><span class='badge badge-warning'>Pendente</span></a>" 
                        : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            { data: 'closing.MonthYear', name: 'closing.MonthYear' },
            {
                "data": "closing.status",
                "render": function (data, type, row, meta) {
                    data == FECHAMENTO_FECHADO ? edit = 'disabled' :  edit = '';
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                        : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a idtb_launch="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a idtb_launch="' + row.id + '" class="btn btn-xs btn-primary btn_edit_launch ' + edit + '" id="btn_edit_launch" title="Editar laçamento">'
                    +'<i class="fa fa-edit"></i></a> <a idtb_launch="' + row.id + '" class="btn btn-xs btn-danger btn_del_launch ' + edit + '" id="btn_del_launch" >'
                    +'<i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_launch();
            btn_vizualizar();
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(10).footer()).html(
                'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
            );


        }
    });

    /** 
      * tabela ofertas
      **/
    var dt_launch_o = $('#dt_launch_o').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query?launch=2&caixa=2&closing_status=1&closing_status1=2',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            {
                data: 'value', name: 'value',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'caixa.name', name: 'caixa.name' },
            { data: 'payment_type.name', name: 'payment_type.name' },
            {
                data: 'operation_date',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == LANCAMENTO_PENDETE ? "<a href='"+baseUrl+"apr-l'><span class='badge badge-warning'>Pendente</span></a>" 
                        : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            { data: 'closing.MonthYear', name: 'closing.MonthYear' },
            {
                "data": "closing.status",
                "render": function (data, type, row, meta) {
                    data == FECHAMENTO_FECHADO ? edit = 'disabled' :  edit = '';
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                        : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a idtb_launch="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a idtb_launch="' + row.id + '" class="btn btn-xs btn-primary btn_edit_launch ' + edit + '" id="btn_edit_launch" title="Editar laçamento">'
                    +'<i class="fa fa-edit"></i></a> <a idtb_launch="' + row.id + '" class="btn btn-xs btn-danger btn_del_launch ' + edit + '" id="btn_del_launch" >'
                    +'<i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_launch();
            btn_vizualizar();
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(10).footer()).html(
                'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
            );
        }
    });

    /** 
    * tabela Geral
    **/
     var dt_launch_others = $('#dt_launch_others').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query?launch=5&closing_status=1&closing_status1=2',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            {
                data: 'value', name: 'value',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'caixa.name', name: 'caixa.name' },
            { data: 'payment_type.name', name: 'payment_type.name' },
            {
                data: 'operation_date',

                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == LANCAMENTO_PENDETE ? "<a href='"+baseUrl+"apr-l'><span class='badge badge-warning'>Pendente</span></a>" 
                        : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            { data: 'closing.MonthYear', name: 'closing.MonthYear' },
            {
                "data": "closing.status",
                "render": function (data, type, row, meta) {
                    data == FECHAMENTO_FECHADO ? edit = 'disabled' :  edit = '';
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                        : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return ' <a idtb_launch="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a idtb_launch="' + row.id + '" class="btn btn-xs btn-primary btn_edit_launch ' + edit + '" id="btn_edit_launch" title="Editar laçamento">'
                    +'<i class="fa fa-edit"></i></a> <a idtb_launch="' + row.id + '" class="btn btn-xs btn-danger btn_del_launch ' + edit + '" id="btn_del_launch" >'
                    +'<i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_launch();
            btn_vizualizar();
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(10).footer()).html(
                'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
            );


        }
    });



    /** 
    * tabela Saidas compras
    **/
    var dt_launch_buy = $('#dt_launch_buy').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query?launch=3&closing_status=1&closing_status1=2',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            {
                data: 'value', name: 'value',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'caixa.name', name: 'caixa.name' },
            { data: 'payment_type.name', name: 'payment_type.name' },
            {
                data: 'operation_date',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == LANCAMENTO_PENDETE ? "<a href='"+baseUrl+"apr-l'><span class='badge badge-warning'>Pendente</span></a>"
                     : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            { data: 'closing.MonthYear', name: 'closing.MonthYear' },
            {
                "data": "closing.status",
                "render": function (data, type, row, meta) {
                    data == FECHAMENTO_FECHADO ? edit = 'disabled' :  edit = '';
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                        : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a idtb_launch="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a idtb_launch="' + row.id + '" class="btn btn-xs btn-primary btn_edit_launch_exits ' + edit + '" id="btn_edit_launch_exits" title="Editar laçamento">'
                    +'<i class="fa fa-edit"></i></a> <a idtb_launch="' + row.id + '" class="btn btn-xs btn-danger btn_del_launch_exits ' + edit + '" id="btn_del_launch_exits" >'
                    +'<i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_launch_s();
            btn_vizualizar();
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(10).footer()).html(
                'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
            );
        }
    });



    /** 
    * tabela Saidas serviços
    **/
    var dt_launch_service = $('#dt_launch_service').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query?launch=4&closing_status=1&closing_status1=2',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            {
                data: 'value', name: 'value',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'caixa.name', name: 'caixa.name' },
            { data: 'payment_type.name', name: 'payment_type.name' },
            {
                data: 'operation_date',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == LANCAMENTO_PENDETE ? "<a href='"+baseUrl+"apr-l'><span class='badge badge-warning'>Pendente</span></a>" 
                        : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            { data: 'closing.MonthYear', name: 'closing.MonthYear' },
            {
                "data": "closing.status",
                "render": function (data, type, row, meta) {
                    data == FECHAMENTO_FECHADO ? edit = 'disabled' :  edit = '';
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                        : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a idtb_launch="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a idtb_launch="' + row.id + '" class="btn btn-xs btn-primary btn_edit_launch_exits ' + edit + '" id="btn_edit_launch_exits" title="Editar laçamento">'
                    +'<i class="fa fa-edit"></i></a> <a idtb_launch="' + row.id + '" class="btn btn-xs btn-danger btn_del_launch_exits ' + edit + '" id="btn_del_launch_exits" >'
                    +'<i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_launch_s();
            btn_vizualizar();
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(10).footer()).html(
                'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
            );
        }
    });




    /** 
   * tabela aprovações
   **/
    var dt_launch_apr = $('#dt_launch_apr').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query?status=0',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            {
                data: 'value', name: 'value',
                render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
            },
            { data: 'caixa.name', name: 'caixa.name' },
            { data: 'payment_type.name', name: 'payment_type.name' },
            {
                data: 'operation_date',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == LANCAMENTO_PENDETE ? "<span class='badge badge-warning'>Pendente</span>" 
                        : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" 
                        : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            { data: 'closing.MonthYear', name: 'closing.MonthYear' },
            {
                "data": "closing.status",
                "render": function (data, type, row, meta) {
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                        : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
            },
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a idtb_launch="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a id_launch="' + row.id + '" status="1" class="btn btn-xs btn-success btn_apr" id="btn_aprovar" title="Aprovar"> <i class="fa fa-check"></i></a>'
                    +' <a id_launch="' + row.id + '" status="2" class="btn btn-xs btn-danger btn_repr" id="btn_reprovar" title="Reprovar"> <i class="fa fa-times fa-lg"></i></a>';
                    /*'<form> <input name="id" id="id" value="'+row.id+'" hidden> <input name="status" id="status" value="1" hidden>
                     <button type="submit" class="btn btn-xs btn-success btn_aprovar" id="btn_aprovar" title="Aprovar"> <i class="fa fa-check"></i>
                      </button> <button type="submit" class="btn btn-xs btn-danger btn_reprovar" id="btn_reprovar" title="Reprovar"> 
                      <i class="fa fa-times fa-lg"></i> </button> </form> '*/
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_aprov();
            btn_vizualizar();
        },

        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(9).footer()).html(
                'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
            );


        }
    });



     // Setup - add a text input to each header cell
    $('#dt_consult thead th').each(function() {
        var title = $('#dt_consult thead th').eq($(this).index()).text();
        $(this).html(''+title+'<input type="text"  class="form-control mw-100" placeholder="'+title+'" />');
    });


     /** 
    * tabela consulta
    **/
   var dt_consult = $('#dt_consult').DataTable({
    "oLanguage": DATATABLE_PTBR,
    "autoWidth": false,
    "processing": true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    // "serverSide": true,
    "ajax": baseUrl + 'query',
    "columns": [
        { data: 'type_launch.name', name: 'launch.name' },
        { data: 'user.name', name: 'user.name' },
        {
            data: 'value', name: 'value',
            render: $.fn.dataTable.render.number('.', ',', 2, 'R$')
        },
        { data: 'description', name: 'description' },
        { data: 'caixa.name', name: 'caixa.name' },
        { data: 'payment_type.name', name: 'payment_type.name' },
        {
            data: 'operation_date',

            render: function (data, type, row) {
                return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                    data;
            }
        },
        {
            "data": "status",
            "render": function (data, type, row, meta) {
                return data == LANCAMENTO_PENDETE ? "<a href='"+baseUrl+"apr-l'><span class='badge badge-warning'>Pendente</span></a>" 
                    : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>" : "<span class='badge badge-danger'>Reprovado</span>";
            },
            columnDefs: [
                { targets: "no-sort", orderable: false },
                { targets: "dt-center", ClassName: "dt-center" }
            ]
        },
        { data: 'closing.MonthYear', name: 'closing.MonthYear' },
        {
            "data": "closing.status",
            "render": function (data, type, row, meta) {
                data == FECHAMENTO_FECHADO ? edit = 'disabled' :  edit = '';
                return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" 
                    : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
            },
            columnDefs: [
                { targets: "no-sort", orderable: false },
                { targets: "dt-center", ClassName: "dt-center" }
            ]
        },
        {
            data: 'created_at',
            render: function (data, type, row) {
                return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                    data;
            }
        },
        {
            data: 'updated_at',
            render: function (data, type, row) {
                return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                    data;
            }
        },
        
    ],
    
    
    // //função pesquisa por coluna select
    // initComplete: function () {
    //     this.api().columns([0,1,3,4,7]).every( function () {
    //         var column = this;
    //         var select = $('<select><option value=""></option></select>')
    //             .appendTo( $(column.header()) )
    //             .on( 'change', function () {
    //                 var val = $.fn.dataTable.util.escapeRegex(
    //                     $(this).val()
    //                 );

    //                 column
    //                     .search( val ? '^'+val+'$' : '', true, false )
    //                     .draw();
    //             } );

    //         column.data().unique().sort().each( function ( d, j ) {
    //             select.append( '<option value="'+d+'">'+d.substr(0,10)+'</option>' )
    //         } );

    //         $( select ).click( function(e) {
    //             e.stopPropagation();
    //       });
    //     } );
    // },

    //função pesquisa por coluna texto
    initComplete: function () {
        // Apply the search
        dt_consult.columns().eq(0).each(function(colIdx) {
            $('input', dt_consult.column(colIdx).header()).on('keyup change', function() {
                dt_consult
                    .column(colIdx)
                    .search(this.value.replace (/;/g, "|"), true, false)
                    .draw();
            });
         
            $('input', dt_consult.column(colIdx).header()).on('click', function(e) {
                e.stopPropagation();
            });

        });

    },

    //função retorna valor total no rodape da tabela
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                    i : 0;
        };

        // Total over all pages
        total = api
            .column(2)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

        // Total over this page
        pageTotal = api
            .column(2, { page: 'current' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

        // Update footer
        $(api.column(9).footer()).html(
            'R$' +  number_format(pageTotal, 2, ',', '.')  + ' ( R$' + number_format(total, 2, ',', '.') + ' total)'
        );


    }
});



    //Click lançar/editar modal Lançamentos
    $("#launch_form").on("submit", function () {

        $.ajax({
            type: "POST",
            url: "keep-lauch",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function () {
                clearErrors();
                $("#btn_save_launch").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },

            success: function (response) {
                clearErrors();
                if (response["status"]) {
                    $msg = "Lançamento " + response["success"] + "  com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_launch").modal('hide');
                    dt_launch.ajax.reload();
                    dt_launch_o.ajax.reload();
                    dt_launch_buy.ajax.reload();
                    dt_launch_service.ajax.reload();
                    dt_launch_others.ajax.reload();

                } else {
                    showErrors(response["error_list"]);
                }
            }
        })

        return false;

    });






});