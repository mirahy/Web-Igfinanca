FECHAMENTO_FECHADO = 0;
FECHAMENTO_ABERTO = 1;
FECHAMENTO_PRE_FECHAMENTO = 2;

$(function () {

    // botao adicionar período
    $("#btn_add_closing").click(function () {
        clearErrors();
        $("#status").hide();
        $("#status").parent().siblings(".control-label").hide();
        $("#closing_form")[0].reset();
        $("#status").val(0);
        //$("#img")[0].attr("src", "");
        $("#modal_closing").modal();
    });


     // botao editar períodos
     function btn_table_function() {
        $(".btn_edit_closing").click(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-closing",
                dataType: "json",
                data: { "id": $(this).attr("id") },
                success: function (response) {
                    clearErrors();
                    $("#status").show();
                    $("#status").parent().siblings(".control-label").show();
                    $("#closing_form")[0].reset();
                    $.each(response["imput"], function (id, value) {
                        $("#" + id ).val(value);
                    });
                    $("#modal_closing").modal();
                }
            })

        });

        // botao excluir período
        $(".btn_del_closing").click(function () {
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar este período?",
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
                        url: "destroy",
                        dataType: "json",
                        data: { "id": course_id.attr("id") },
                        success: function (response) {
                            console.log(response);
                            $msg = "Período " + response["success"] + " removido com sucesso!";
                            Swal.fire("Sucesso!", $msg, "success");
                            dt_report_closing.ajax.reload();
                            
                        }
                    })
                }
            })
        });
    }


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
                    return '<a id="' + row.id + '" class="btn btn-xs btn-primary btn_edit_closing" id="btn_edit_closing" title="Editar Período"> <i class="fa fa-edit"></i></a> <a id="' + row.id + '" class="btn btn-xs btn-danger btn_del_closing" id="btn_del_closing" > <i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function(){
            btn_table_function();
        },

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
                    $msg = "Período de " + response["success"] + " com sucesso!";
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