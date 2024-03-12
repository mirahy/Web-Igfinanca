$(function () {

    // botao adicionar função
    $("#btn_add_role").on("click", function () {
        clearErrors();
        $("#role_form")[0].reset();
        $("#name").prop( "disabled", false );
        $("#btn_save_role").show();
        $("#modal_role").modal();
    });

    // botao editar função
    function btn_table_function() {
        $(".btn_edit_role").on("click", function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-roles",
                dataType: "json",
                data: { "id": $(this).attr("id") },
                success: function (response) {
                    clearErrors();
                    $("#role_form")[0].reset();
                    $("#name").prop( "disabled", false );
                    $.each(response["imput"], function (id, value) {
                            $("#" + id).val(value);    
                    });
                    $.each(response["permissions"][0], function (id, value) {
                        $("." + value.name).prop('checked', true);    
                    });
                    $("#btn_save_role").show();
                    $("#modal_role").modal();
                }
            })

        });

        // botao excluir função
        $(".btn_del_role").on("click", function () {
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar esta função?",
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
                        url: "destroy-roles",
                        dataType: "json",
                        data: { "id": course_id.attr("id") },
                        success: function (response) {
                            if (response["status"]) {
                                $msg = "Função removida com sucesso!";
                                Swal.fire("Sucesso!", $msg, "success");

                            } else {
                                $.each(response["error_list"], function (id, value) {
                                    $msg = "Mensagens: " + value;
                                    Swal.fire("Atenção!", $msg, "error");
                                });

                            }

                            dt_role.ajax.reload();

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
                url: "show-roles",
                dataType: "json",
                data: { "id": $(this).attr("id") },
                success: function (response) {
                    clearErrors();
                    $("#role_form")[0].reset();
                    $.each(response["imput"], function (id, value) {
                        $("#" + id).val(value);    
                    });
                    $.each(response["permissions"][0], function (id, value) {
                    $("." + value.name).prop('checked', true);    
                    });
                    $("#name").prop( "disabled", true );
                    $("#btn_save_role").hide();
                    $("#modal_role").modal();

                }

            })

        });
    }

    /** 
    * tabela funções
    **/
    var dt_role = $('#dt_role').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'query-roles',
        "order": [],
        "columns": [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            {data: 'created_at', name:'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                "data": "action",
                "render": function (data, type, row, meta) {
                    return '<a id="' + row.id + '" class="btn btn-xs btn-secondary btn_vizualizar " id="btn_vizualizar" title="Vizualizar"><i class="fas fa-eye"></i></a>'
                    +'<a id="' + row.id + '" class="btn btn-xs btn-primary btn_edit_role " id="btn_edit_role" title="Editar Função">'
                    +'<i class="fa fa-edit"></i></a> <a id="' + row.id + '" class="btn btn-xs btn-danger btn_del_role" id="btn_del_role" >'
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
    $("#role_form").on("submit", function () {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "keep-roles",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function () {
                clearErrors();
                $("#btn_save_role").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function (response) {
                clearErrors();
                if (response["status"]) {
                    $msg = "Função " + response["success"] + " com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_role").modal('hide');
                    dt_role.ajax.reload();
                } else {
                    showErrors(response["error_list"]);
                }
            }
        })

        return false;

    });



})