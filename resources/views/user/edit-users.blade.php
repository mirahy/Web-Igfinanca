@extends('templates.master')



@section('css-view')
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="css/datatables.min.css" rel="stylesheet">
@endsection

@section('js-view')
<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!}
</script>
<script src="js/sweetalert2.all.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/datatables.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/util.js"></script>
<script src="js/edit-user.js"></script>
<script>

/** 
* Exibe confirmação de exclusão ao clicar no botão excluir 
* O metodo confirmation() é de um componente do bootstrap
* chamado bootstrap confirmation
**/
$('table tbody').on('click','a[id^="person-delete"]', function (e) {
    e.preventDefault();
    $(this).confirmation('show');
});

 
    $(function() {

        
    });



</script>

@endsection

@section('conteudo-view')
    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->      

      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gerenciar  Usuários</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
      </div>

        <ul class="nav nav-tabs">
            <li class="nav-item active"><a class="nav-link active" href="#tab_users_active" role="tab"  data-toggle="tab">Usuários Ativos</a></li>
            <li class="nav-item"><a class="nav-link" href="#tab_users_inactive" role="tab"  data-toggle="tab">Usuários Inativos</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_users_active" class="tab-pane active">
                <div class="container-fluid">
                <h2 class="text-center"><strong>Usuários Ativos</strong></h2>
                <a id="btn_add_user" class="btn btn-primary my-1"><i class="fas fa-plus ">&nbsp Adicionar Usuário</i></a>
                <table id="dt_users" class="table table striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Base</th>
                            <th>Status</th>
                            <th>Criado</th>
                            <th>Editado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
            <div id="tab_users_inactive" class="tab-pane ">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Usuários Inativos</strong></h2>
                <a id="btn_add_user_inactive" class="btn btn-primary my-1"><i class="fas fa-plus">&nbsp Adicionar Usuário</i></a>
                <table id="dt_users_inact" class="table table striped table-bordered">
                    <thead>
                        <tr class="tableheader">
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>base</th>
                            <th>Status</th>
                            <th>Criado</th>
                            <th>Editado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            
            </div>
        </div>

      

    </div>
    <!-- /.container-fluid -->

    <div id="modal_user" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title">Usuário</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        {!! Form::open(['class' =>'user', 'id' => 'user_form']) !!}

        <div class="modal-body">

            
            
                    @include('templates.forms.input',['input' => 'id', 'attributes' => [ 'name' => 'user_id', 'hidden', 'id' => 'user_id']])
                 
                    @include('templates.forms.input',['input' => 'text','label' => 'Nome Completo', 'attributes' => ['placeholder' => 'Nome Completo', 'required','class' => 'form-control form-control-user', 'id' => 'name', 'name' => 'name',  'maxlength' => '100']])

                    @include('templates.forms.email',['input' => 'email', 'label' => 'Email', 'attributes' => ['placeholder' => 'Email', 'required','class' => 'form-control form-control-user', 'id' => 'email', 'name' => 'email',  'maxlength' => '100']])

                    @include('templates.forms.input',['input' => 'text', 'label' => 'Perfil', 'attributes' => ['placeholder' => 'Perfil', 'required', 'class' => 'form-control form-control-user', 'id' => 'idtb_profile', 'name' => 'idtb_profile',  'maxlength' => '100']])

                    @include('templates.forms.input',['input' => 'text', 'label' => 'Base', 'attributes' => ['placeholder' => 'Base', 'required','class' => 'form-control form-control-user', 'id' => 'idtb_base', 'name' => 'idtb_base',  'maxlength' => '100']])

                    @include('templates.forms.input',['input' => 'text', 'label' => 'Status', 'attributes' => ['placeholder' => 'Status', 'required','class' => 'form-control form-control-user', 'id' => 'status', 'name' => 'status',  'maxlength' => '100']])
                    
                    @include('templates.forms.password',['input' => 'password', 'label' => 'Senha', 'attributes' => ['placeholder' => 'Senha', 'required', 'class' => 'form-control form-control-user', 'id' => 'password', 'name' => 'password']])
                        
                    @include('templates.forms.password',['input' => 'Repeatpassword', 'label' => 'Repita a senha', 'attributes' => ['placeholder' => 'Repita a senha', 'required', 'class' => 'form-control form-control-user', 'id' => 'user_Repeat_Password', 'name' => 'Repeatpassword']])
                        

        </div>
            <div class="modal-footer">
                
                @include('templates.forms.button',['input' => '<i class="fa fa-save fa-fw"></i> Salvar','attributes' => ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btn_save_user']])
                @include('templates.forms.button',['input' => '<i class="fas fa-times fa-fw"></i> Fechar','attributes' => ['type' => 'button', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal']])
                
                <!--<button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            </div>
        </div>

        {!! Form::close() !!}

    </div>
    </div>

@endsection