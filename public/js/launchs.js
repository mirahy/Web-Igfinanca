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
                        $("#id_user").val(2);
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
                        $("#id_user").val(41);
                        //$("#img")[0].attr("src", "");
                        $("#modal_launch").modal();
                }   
        })
    });

    function btn_edit_launch_d(){


         // botao excluir dízimos
    $(".btn_del_launch").click(function(){
        course_id = $(this);
        Swal.fire({
            title: "Atenção!",
            text: "Deseja deletar este lançamento?",
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
                    url: "destroy",
                    dataType: "json",
                    data: {"id": course_id.attr("id_user")},
                    success: function(response){
                        console.log(response);
                            $msg = "Lançamento "+ response["success"] +" removido com sucesso!";
                            Swal.fire("Sucesso!", $msg, "success");
                            dt_launch.ajax.reload();
                            dt_launch_o.ajax.reload();
                    }
                })
            }
        })
    });



    }

    function btn_edit_launch_o(){


         // botao excluir ofertas
    $(".btn_del_launch").click(function(){
        course_id = $(this);
        Swal.fire({
            title: "Atenção!",
            text: "Deseja deletar este lançamento?",
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
                    url: "destroy",
                    dataType: "json",
                    data: {"id": course_id.attr("id_user")},
                    success: function(response){
                        console.log(response);
                            $msg = "Lançamento "+ response["success"] +" removido com sucesso!";
                            Swal.fire("Sucesso!", $msg, "success");
                            dt_launch.ajax.reload();
                            dt_launch_o.ajax.reload();
                    }
                })
            }
        })
    });


    }

   



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
                        { data: 'launch.name', name: 'launch.name' },
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
                                return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch_d" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                    }
                    ],
                    // "drawCallback": function(){
                    //     btn_edit_user();
                    // }
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
                        { data: 'launch.name', name: 'launch.name' },
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
                                return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch_o" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
                            },
                            columnDefs: [
                                {targets: "no-sort", orderable: false},
                                {targets: "dt-center", ClassName: "dt-center"}
                            ]
                    }
                    ],
                    // "drawCallback": function(){
                    //     btn_edit_user();
                    // }
    });


    //Click lançar modal Lançamentos
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
                    $msg = "Lançamento "+ response["success"] +" adicionado com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_launch").modal('hide');
                    dt_launch.ajax.reload();
                    dt_launch_o.ajax.reload();
                }else{
                    showErrors(response["error_list"]);
                }
            }
        })
        
     return false;

    });





})