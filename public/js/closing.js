FECHAMENTO_FECHADO = 0;
FECHAMENTO_ABERTO = 1;
FECHAMENTO_PRE_FECHAMENTO = 2;

$(function () {

    /** 
    * tabela fechamentos
    **/
    var dt_report_closing = $('#dt_closing').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query_closing',
        "order": [],
        "columns": [
            { data: 'month', name: 'month' },
            { data: 'year', name: 'year' },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == FECHAMENTO_PRE_FECHAMENTO ? "<span class='badge badge-warning'>Pré-Fechamento</span>" : data == FECHAMENTO_ABERTO ? "<span class='badge badge-success'>Aberto</span>" : "<span class='badge badge-danger'>Fechado</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a idtb_launch="' + row.id + '" class="btn btn-xs btn-primary btn_edit_launch" id="btn_edit_launch" title="Editar laçamento"> <i class="fa fa-edit"></i></a> <a idtb_launch="' + row.id + '" class="btn btn-xs btn-danger btn_del_launch" id="btn_del_launch" > <i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        // "drawCallback": function(){
        //     btn_aprov();
        // },

    });
})