LANCAMENTO_PENDETE   = 0;
LANCAMENTO_APROVADO  = 1;
LANCAMENTO_REPROVADO = 2;

CONSTANT_MES = [
                'MES', 
                'JANEIRO', 
                'FEVEREIRO', 
                'MARÇO', 
                'ABRIL',
                'MAIO',
                'JUNHO',
                'JULHO',
                'AGOSTO',
                'SETEMBRO',
                'OUTUBRO',
                'NOVEMBRO',
                'DEZENBRO'
                ];

OPERATION_OUTPUT = 0;
OPERATION_INPUT  = 1;

$(function(){

    // botao laçamentos dizimos
    $("#btn_add_launch_d").click(function(){
        $.ajax({
                success: function(response){
                        clearErrors();
                        $("#launch_form")[0].reset();
                        $("#idtb_operation").val(1);
                        $("#idtb_type_launch").val(1);
                        $("#idtb_base").val(1);
                        $("#idtb_closing").val(1);
                        $("#id_user").val(0);
                        $("#name").show();
                        $("#name").parent().siblings(".control-label").show();
                        //$("#img")[0].attr("src", "");
                        $("#modal_launch").modal();
                }   
        })
    });

    // botao laçamentos ofertas
    $("#btn_add_launch_o").click(function(){
        $.ajax({
                success: function(response){
                        clearErrors();
                        $("#launch_form")[0].reset();
                        $("#idtb_operation").val(1);
                        $("#idtb_type_launch").val(2);
                        $("#idtb_base").val(1);
                        $("#idtb_closing").val(1);
                        $("#id_user").val(0);
                        $("#name").val('Oferta').hide();
                        $("#name").parent().siblings(".control-label").hide();
                        //$("#img")[0].attr("src", "");
                        $("#modal_launch").modal();
                }   
        })
    });

    // botao laçamentos cmpras
    $("#btn_add_launch_buy").click(function(){
        $.ajax({
                success: function(response){
                        clearErrors();
                        $("#launch_form")[0].reset();
                        $("#idtb_operation").val(2);
                        $("#idtb_type_launch").val(3);
                        $("#idtb_base").val(1);
                        $("#idtb_closing").val(2);
                        $("#id_user").val(0);
                        //$("#img")[0].attr("src", "");
                        $("#modal_launch").modal();
                }   
        })
    });


    // botao laçamentos cmpras
    $("#btn_add_launch_service").click(function(){
        $.ajax({
                success: function(response){
                        clearErrors();
                        $("#launch_form")[0].reset();
                        $("#idtb_operation").val(2);
                        $("#idtb_type_launch").val(4);
                        $("#idtb_base").val(1);
                        $("#idtb_closing").val(2);
                        $("#id_user").val(0);
                        //$("#img")[0].attr("src", "");
                        $("#modal_launch").modal();
                }   
        })
    });

    

    
    function btn_edit_launch(){

        // botao editar entradas
        $(".btn_edit_launch").click(function(){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "show-launch",
                    dataType: "json",
                    data: {"id":$(this).attr("idtb_launch")},
                    success: function(response){
                        
                        clearErrors();
                        $("#launch_form")[0].reset();
                            $.each(response["imput"], function(id, value){

                                $("#"+id).val(value)
                            });
                            if(response["imput"]['idtb_type_launch'] == 1){
                                $("#name").parent().siblings(".control-label").show();
                                $("#name").show();

                            }else{
                                $("#name").parent().siblings(".control-label").hide();
                                $("#name").val('Oferta').hide();

                            }
                            
                            
                            $("#modal_launch").modal();
                    }
                   
                })
    
        });

           

        // botao excluir lançamentos de entradas
        $(".btn_del_launch").click(function(){
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar este Lançamento?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
            }).then((result)=>{
                if(result.value){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "destroy-launch",
                        dataType: "json",
                        data: {"id": course_id.attr("idtb_launch")},
                        success: function(response){
                            console.log(response);
                                $msg = "Lançamento "+ response["success"] +"  com sucesso!";
                                Swal.fire("Sucesso!", $msg, "success");
                                dt_launch.ajax.reload();
                                dt_launch_o.ajax.reload();
                        }
                    })
                }
            })
        });

         
    }


    function btn_edit_launch_s(){

                // botao editar saídas
                $(".btn_edit_launch_exits").click(function(){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "show-launch",
                        dataType: "json",
                        data: {"id":$(this).attr("idtb_launch")},
                        success: function(response){
                            
                            clearErrors();
                            $("#launch_form")[0].reset();
                                    $.each(response["imput"], function(id, value){
                                    $("#"+id).val(value)

                                });
                                $("#modal_launch").modal();

                        }
                    
                    })

            });

                // botao excluir lançamentos de saídas
        $(".btn_del_launch_exits").click(function(){
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar este Lançamento?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
            }).then((result)=>{
                if(result.value){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "destroy-launch",
                        dataType: "json",
                        data: {"id": course_id.attr("idtb_launch")},
                        success: function(response){
                            console.log(response);
                                $msg = "Lançamento "+ response["success"] +"  com sucesso!";
                                Swal.fire("Sucesso!", $msg, "success");
                                dt_launch_buy.ajax.reload();
                                dt_launch_service.ajax.reload();
                        }
                    })
                }
            })
        });


    }



    // função pesquisa autocomplete nome usuários
    $(function(){

        $("#name").autocomplete({

            source : baseUrl + 'autocomplete'
        });
    });
    

   



    /** 
    * tabela dizimos
    **/
   var dt_launch = $('#dt_launch').DataTable({
                    "oLanguage": DATATABLE_PTBR,
                    "autoWidth":  false,
                    "processing": true,
                    // "serverSide": true,
                    "ajax": baseUrl + 'query-dizimos',
                    "columns": [
                        { data: 'type_launch.name', name: 'launch.name' },
                        { data: 'user.name', name: 'user.name' },
                        { data: 'value', name: 'value' },
                        { data: 'operation_date', name: 'operation_date' },
                        {"data": "reference_month",
                            "render": function(data, type, row, meta){
                                return CONSTANT_MES[data];
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                        },
                        { data: 'reference_year', name: 'reference_year' },
                        {"data": "status",
                            "render": function(data, type, row, meta){
                                return data == LANCAMENTO_PENDETE ? "<span class='badge badge-warning'>Pendente</span>" : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>"  : "<span class='badge badge-danger'>Reprovado</span>";
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                        },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'updated_at', name: 'updated_at' },
                        {
                            "data": "action",
                            "render": function(data, type, row, meta){
                                return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                    }
                    ],
                    "drawCallback": function(){
                        btn_edit_launch();
                    }
   });

  /** 
    * tabela ofertas
    **/
   var dt_launch_o = $('#dt_launch_o').DataTable({
                    "oLanguage": DATATABLE_PTBR,
                    "autoWidth":  false,
                    "processing": true,
                    // "serverSide": true,
                    "ajax": baseUrl + 'query-ofertas',
                    "columns": [
                        { data: 'type_launch.name', name: 'launch.name' },
                        { data: 'user.name', name: 'user.name' },
                        { data: 'value', name: 'value' },
                        { data: 'operation_date', name: 'operation_date' },
                        {"data": "reference_month",
                            "render": function(data, type, row, meta){
                                return CONSTANT_MES[data];
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                        },
                        { data: 'reference_year', name: 'reference_year' },
                        {"data": "status",
                            "render": function(data, type, row, meta){
                                return data == LANCAMENTO_PENDETE ? "<span class='badge badge-warning'>Pendente</span>" : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>"  : "<span class='badge badge-danger'>Reprovado</span>";
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                        },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'updated_at', name: 'updated_at' },
                        {
                            "data": "action",
                            "render": function(data, type, row, meta){
                                return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                    }
                    ],
                     "drawCallback": function(){
                         btn_edit_launch();
                     }
    });


        /** 
        * tabela Saidas compras
        **/
        var dt_launch_buy = $('#dt_launch_buy').DataTable({
            "oLanguage": DATATABLE_PTBR,
            "autoWidth":  false,
            "processing": true,
            // "serverSide": true,
            "ajax": baseUrl + 'query-buy',
            "columns": [
                { data: 'type_launch.name', name: 'launch.name' },
                { data: 'user.name', name: 'user.name' },
                { data: 'value', name: 'value' },
                { data: 'operation_date', name: 'operation_date' },
                {"data": "reference_month",
                    "render": function(data, type, row, meta){
                        return CONSTANT_MES[data];
                    },
                    columnDefs: [
                        {targets: "no-sort", orderable: false},
                        {targets: "dt-center", ClassName: "dt-center"}
                    ]
                },
                { data: 'reference_year', name: 'reference_year' },
                {"data": "status",
                    "render": function(data, type, row, meta){
                        return data == LANCAMENTO_PENDETE ? "<span class='badge badge-warning'>Pendente</span>" : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>"  : "<span class='badge badge-danger'>Reprovado</span>";
                    },
                    columnDefs: [
                        {targets: "no-sort", orderable: false},
                        {targets: "dt-center", ClassName: "dt-center"}
                    ]
                },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                {
                    "data": "action",
                    "render": function(data, type, row, meta){
                        return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch_exits" id="btn_edit_launch_exits" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch_exits" id="btn_del_launch_exits" > <i class="fa fa-trash"></i></a>';
                    },
                    columnDefs: [
                        {targets: "no-sort", orderable: false},
                        {targets: "dt-center", ClassName: "dt-center"}
                    ]
            }
            ],
            "drawCallback": function(){
                btn_edit_launch_s();
            }
        });

        /** 
        * tabela Saidas serviços
        **/
    var dt_launch_service = $('#dt_launch_service').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth":  false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query-service',
        "columns": [
            { data: 'type_launch.name', name: 'launch.name' },
            { data: 'user.name', name: 'user.name' },
            { data: 'value', name: 'value' },
            { data: 'operation_date', name: 'operation_date' },
            {"data": "reference_month",
                "render": function(data, type, row, meta){
                    return CONSTANT_MES[data];
                },
                columnDefs: [
                    {targets: "no-sort", orderable: false},
                    {targets: "dt-center", ClassName: "dt-center"}
                ]
            },
            { data: 'reference_year', name: 'reference_year' },
            {"data": "status",
                "render": function(data, type, row, meta){
                    return data == LANCAMENTO_PENDETE ? "<span class='badge badge-warning'>Pendente</span>" : data == LANCAMENTO_APROVADO ? "<span class='badge badge-success'>Aprovado</span>"  : "<span class='badge badge-danger'>Reprovado</span>";
                },
                columnDefs: [
                    {targets: "no-sort", orderable: false},
                    {targets: "dt-center", ClassName: "dt-center"}
                ]
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
                "data": "action",
                "render": function(data, type, row, meta){
                    return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch_exits" id="btn_edit_launch_exits" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch_exits" id="btn_del_launch_exits" > <i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    {targets: "no-sort", orderable: false},
                    {targets: "dt-center", ClassName: "dt-center"}
                ]
        }
        ],
        "drawCallback": function(){
            btn_edit_launch_s();
        }
    });


    //Click lançar/editar modal Lançamentos
    $("#launch_form").submit(function(){
       
        $.ajax({
            type: "POST",
            url: "keep-lauch",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function(){
                clearErrors();
                $("#btn_save_launch").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            
            success: function(response){
                clearErrors();
                if(response["status"]){
                    $msg = "Lançamento "+ response["success"] +"  com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_launch").modal('hide');
                    dt_launch.ajax.reload();
                    dt_launch_o.ajax.reload();
                    dt_launch_buy.ajax.reload();
                    dt_launch_service.ajax.reload();
                    
                }else{
                    showErrors(response["error_list"]);
                }
            }
        })
        
     return false;

    });






});