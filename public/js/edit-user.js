USUARIO_INATIVO = 0;
USUARIO_ATIVO = 1;
USUARIO_PENDENTE = 2;

$(function () {

    // botao adicionar usuario tabela usuario ativos
    $("#btn_add_user").click(function () {
        clearErrors();
        $("#user_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_user").modal();
    });


    // botao adicionar usuario tabela usuario inativos
    $("#btn_add_user_i").click(function () {
        clearErrors();
        $("#user_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_user").modal();

    });

    // botao adicionar usuario tabela usuario pendentes
    $("#btn_add_user_p").click(function () {
        clearErrors();
        $("#user_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_user").modal();
    });




    // botao editar usuários ativos
    function btn_edit_user() {
        $(".btn_edit_user").click(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "show-user",
                dataType: "json",
                data: { "id": $(this).attr("id_user") },
                success: function (response) {
                    clearErrors();
                    $("#user_form_edit")[0].reset();
                    $.each(response["imput"], function (id, value) {
                        $("#" + id + "_edit").val(value);
                    });
                    $("#modal_user_edit").modal();
                }
            })

        });

        // botao excluir usuários
        $(".btn_del_user").click(function () {
            course_id = $(this);
            Swal.fire({
                title: "Atenção!",
                text: "Deseja deletar este Usuário?",
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
                        data: { "id": course_id.attr("id_user") },
                        success: function (response) {
                            console.log(response);
                            $msg = "Usuário " + response["messages"] + " removido com sucesso!";
                            Swal.fire("Sucesso!", $msg, "success");
                            dt_users.ajax.reload();
                            dt_users_inact.ajax.reload();
                        }
                    })
                }
            })
        });
    }




    /** 
    * tabela usuários ativos
    **/
    var dt_users = $('#dt_users').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // "serverSide": true,
        "ajax": baseUrl + 'edit-users?status=1',
        "columns": [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'profile.name', name: 'profile.name' },
            { data: 'base.name', name: 'base.name' },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == USUARIO_ATIVO ? "<span class='badge badge-success'>Ativo</span>" : data == USUARIO_PENDENTE ? "<span class='badge badge-warning'>Pendente</span>" : "<span class='badge badge-danger'>Inativo</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'birth',

                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
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
                    return '<a id_user="' + row.id + '" class="btn btn-xs btn-primary btn_edit_user" id="btn_edit_user" title="Editar Pessoa"> <i class="fa fa-edit"></i></a> <a id_user="' + row.id + '" class="btn btn-xs btn-danger btn_del_user" id="btn_del_user" > <i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_user();
        }
    });

    /** 
    * tabela usuários inativos
    **/

    var dt_users_inact = $('#dt_users_inact').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // serverSide: true,
        "ajax": baseUrl + 'edit-users?status=0',
        "columns": [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'profile.name', name: 'profile.name' },
            { data: 'base.name', name: 'base.name' },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == USUARIO_ATIVO ? "<span class='badge badge-success'>Ativo</span>" : data == USUARIO_PENDENTE ? "<span class='badge badge-warning'>Pendente</span>" : "<span class='badge badge-danger'>Inativo</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'birth',

                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
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
                    return '<a id_user="' + row.id + '" class="btn btn-xs btn-primary btn_edit_user" id="btn_edit_user" title="Editar Pessoa"> <i class="fa fa-edit"></i></a> <a id_user="' + row.id + '" class="btn btn-xs btn-danger btn_del_user" id="btn_del_user" > <i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_user();
        }

    });

    /** 
   * tabela usuários Pendentes
   **/

    var dt_users_pending = $('#dt_users_pending').DataTable({
        "oLanguage": DATATABLE_PTBR,
        "autoWidth": false,
        "processing": true,
        // serverSide: true,
        "ajax": baseUrl + 'edit-users?status=2',
        "columns": [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'profile.name', name: 'profile.name' },
            { data: 'base.name', name: 'base.name' },
            {
                "data": "status",
                "render": function (data, type, row, meta) {
                    return data == USUARIO_ATIVO ? "<span class='badge badge-success'>Ativo</span>" : data == USUARIO_PENDENTE ? "<span class='badge badge-warning'>Pendente</span>" : "<span class='badge badge-danger'>Inativo</span>";
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            },
            {
                data: 'birth',

                render: function (data, type, row) {
                    return type === "display" || type === "filter" ? Dataformat = FormatData(data) :
                        data;
                }
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
                    return '<a id_user="' + row.id + '" class="btn btn-xs btn-primary btn_edit_user" id="btn_edit_user" title="Editar Pessoa"> <i class="fa fa-edit"></i></a> <a id_user="' + row.id + '" class="btn btn-xs btn-danger btn_del_user" id="btn_del_user" > <i class="fa fa-trash"></i></a>';
                },
                columnDefs: [
                    { targets: "no-sort", orderable: false },
                    { targets: "dt-center", ClassName: "dt-center" }
                ]
            }
        ],
        "drawCallback": function () {
            btn_edit_user();
        }

    });


    //Click salvar modal Usuarios
    $("#user_form").submit(function () {

        $.ajax({
            type: "POST",
            url: "keep",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function () {
                clearErrors();
                $("#btn_save_user").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function (response) {
                clearErrors();
                if (response["status"]) {
                    $msg = "Usuário " + response["success"] + " adicionado com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_user").modal('hide');
                    dt_users.ajax.reload();
                    dt_users_inact.ajax.reload();
                    dt_users_pending.ajax.reload();
                } else {
                    showErrors(response["error_list"]);
                }
            }
        })

        return false;

    });

    //Click editar modal Usuarios
    $("#user_form_edit").submit(function () {

        $.ajax({
            type: "POST",
            url: "keep",
            dataType: "json",
            data: $(this).serialize(),
            beforeSend: function () {
                clearErrors();
                $("#btn_edit_user").parent().siblings(".help-block").html(loadingImg("Verificando..."))
            },
            success: function (response) {
                clearErrors();
                if (response["status"]) {
                    $msg = "Usuário " + response["success"] + " editado com sucesso!";
                    Swal.fire("Sucesso!", $msg, "success");
                    $("#modal_user_edit").modal('hide');
                    dt_users.ajax.reload();
                    dt_users_inact.ajax.reload();
                    dt_users_pending.ajax.reload();
                } else {
                    showErrors(response["error_list"]);
                }
            }
        })

        return false;

    });


})