$(function () {

    // botao adicionar função
    $("#btn_add_permission").on("click", function () {
        clearErrors();
        $("#permission_form")[0].reset();
        $("#name").prop( "disabled", false );
        $("#btn_save_permission").show();
        $("#modal_permission").modal();
    });

    // botao editar função
    function btn_table_function() {
        $(".btn_edit_permission").on("click", function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-permission",
                dataType: "json",
                data: { "id": $(this).attr("id") },
                success: function (response) {
                    clearErrors();
                    $("#permission_form")[0].reset();
                    $("#name").prop( "disabled", false );
                    $.each(response["imput"], function (id, value) {
                            $("#" + id).val(value);    
                    });
                    $("#btn_save_permission").show();
                    $("#modal_permission").modal();
                }
            })

        });

        // botao excluir função
        $(".btn_del_permission").on("click", function () {
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar esta Permissão?",
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
                        url: "destroy-permission",
                        dataType: "json",
                        data: { "id": course_id.attr("id") },
                        success: function (response) {
                            if (response["status"]) {
                                $msg = "Premissão removida com sucesso!";
                                Swal.fire("Sucesso!", $msg, "success");

                            } else {
                                $.each(response["error_list"], function (id, value) {
                                    $msg = "Mensagens: " + value;
                                    Swal.fire("Atenção!", $msg, "error");
                                });

                            }

                            dt_permission.ajax.reload();

                        }
                    })
                }
            })
        });
    }

    function btn_vizualizar() {

        // botao vizualizar função
        $(".btn_vizualizar").on("click", function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-permission",
                dataType: "json",
                data: { "id": $(this).attr("id") },
                success: function (response) {
                    clearErrors();
                    $("#permission_form")[0].reset();
                    $.each(response["imput"], function (id, value) {
                        $("#" + id).val(value);    
                    });
                    $("#name").prop( "disabled", true );
                    $("#btn_save_permission").hide();
                    $("#modal_permission").modal();

                }

            })

        });
    }

    /** 
    * tabela permission
    **/
    var dt_permission = $('#dt_permission').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query-permission',
        "order": [],
        "columns": [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            {data: 'created_at',name: 'created_at'},
            // {
            //     data: 'created_at',
            //     render: function (data, type, row) {
            //         return type === "display" || type === "filter" ? Dataformat = FormatDataGMT(data) :
            //             data;
            //     }
            // },
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
                    return '<a id="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a id="' + row.id + '" class="btn btn-xs btn-primary btn_edit_permission " id="btn_edit_permission" title="Editar Permissão">'
                    +'<i class="fa fa-edit"></i></a> <a id="' + row.id + '" class="btn btn-xs btn-danger btn_del_permission" id="btn_del_permission" >'
                    +'<i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_table_function();
            btn_vizualizar();
        },

    });


    //Click salvar modal funçõe
    $("#permission_form").on("submit", function () {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "keep-permission",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function () {
                clearErrors();
                $("#btn_save_permission").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function (response) {
                clearErrors();
                if (response["status"]) {
                    $msg = "Permissão " + response["success"] + " com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_permission").modal('hide');
                    dt_permission.ajax.reload();
                } else {
                    showErrors(response["error_list"]);
                }
            }
        })

        return false;

    });



})