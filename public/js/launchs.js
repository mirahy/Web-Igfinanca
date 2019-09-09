LANCAMENTO_PENDETE = 0;
LANCAMENTO_APROVADO = 1;
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


$(function(){

    // botao adicionar usuario tabela usuario ativos
    $("#btn_add_launch_d").click(function(){
        clearErrors();
        $("#launch_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_launch").modal();
    });



    /** 
    * tabela dizimos
    **/
   var dt_users = $('#dt_launch').DataTable({
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
                                return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
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
   var dt_users = $('#dt_launch_o').DataTable({
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
                                return '<a idtb_launch="'+row.id+'" class="btn btn-xs btn-primary btn_edit_launch" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="'+row.id+'" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
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





})