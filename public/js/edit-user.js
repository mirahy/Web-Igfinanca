// Base url do site
baseUrl = getBaseUrl();
$(function(){

    $("#btn_add_user").click(function(){
        clearErrors();
        $("#user_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_user").modal();

    })
    $("#btn_add_user_inactive").click(function(){
        clearErrors();
        $("#user_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_user").modal();

    })
    $("#btn_edit_user").click(function(){
        clearErrors();
        $("#user_form")[0].reset();
        //$("#img")[0].attr("src", "");
        $("#modal_user").modal();

    })

   /** 
        * tabela usuários ativos
        **/
       $('#dt_users').DataTable({
        autoWidth:  false,
        processing: true,
        //serverSide: true,
        ajax: baseUrl + ':8000/edit-users',
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'idtb_profile', name: 'idtb_profile' },
            { data: 'idtb_base', name: 'idtb_base' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
            "data": "action",
            "render": function(data, type, row, meta){
                return '<a id_user="'+row.id+'" class="btn btn-xs btn-primary" id="btn_edit_user" title="Editar Pessoa"> <i class="fa fa-edit"></i></a> <a id_user="'+row.id+'" class="btn btn-xs btn-danger" data-toggle="confirmation" data-btn-ok-label="Sim" data-btn-ok-class="btn-success" data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="" data-btn-cancel-label="Não" data-btn-cancel-class="btn-danger" data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="" data-title="Tem certeza que deseja excluir o cadastro de '+ row.name +'?" data-content="Esta ação não poderá ser desfeita." title="Excluir Pessoa"> <i class="fa fa-trash"></i></a>';
            }
        }
        ],
    });

    /** 
    * tabela usuários inativos
    **/

    $('#dt_users_inact').DataTable({
        autoWidth:  false,
        processing: true,
        //serverSide: true,
        ajax: baseUrl + ':8000/edit-users-inact',
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'idtb_profile', name: 'idtb_profile' },
            { data: 'idtb_base', name: 'idtb_base' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
            "data": "action",
            "render": function(data, type, row, meta){
                return '<a id_user="'+row.id+'" class="btn btn-xs btn-primary" id="btn_edit_user" title="Editar Pessoa"> <i class="fa fa-edit"></i></a> <a  id_user="'+row.id+'" class="btn btn-xs btn-danger" data-toggle="confirmation" data-btn-ok-label="Sim" data-btn-ok-class="btn-success" data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="" data-btn-cancel-label="Não" data-btn-cancel-class="btn-danger" data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="" data-title="Tem certeza que deseja excluir o cadastro de '+ row.name +'?" data-content="Esta ação não poderá ser desfeita." title="Excluir Pessoa"> <i class="fa fa-trash"></i></a>';
            }
        }
        ],
        "initComplete": function(){
        // active_btn_user();
        }
    });

})