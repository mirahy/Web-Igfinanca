FECHAMENTO_FECHADO = 0;
FECHAMENTO_ABERTO = 1;
FECHAMENTO_PRE_FECHAMENTO = 2;

$(function () {

    // botao adicionar período
    $("#btn_add_closing").click(function () {
        clearErrors();
        $("#closing_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_closing").modal();
    });


    /** 
    * tabela fechamentos
    **/
    var dt_report_closing = $('#dt_closing').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query-closing',
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


    //Click salvar modal Usuarios
    $("#closing_form").submit(function () {

        $.ajax({
            type: "POST",
            url: "keep-closing",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function () {
                clearErrors();
                $("#btn_save_closing").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function (response) {
                clearErrors();
                if (response["status"]) {
                    $msg = "Período de " + response["success"] + " adicionado com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_closing").modal('hide');
                    dt_report_closing.ajax.reload();
                } else {
                    showErrors(response["error_list"]);
                }
            }
        })

        return false;

    });
})