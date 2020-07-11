@extends('templates.master')



@section('css-view')
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
@endsection

@section('js-view')
<script src="js/sweetalert2.all.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/util.js"></script>
<script src="js/edit-user.js"></script>
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
                            <table id="dt_users" class="table table-striped table-bordered table-hover table-responsive display nowrap" >
                                <thead>
                                    <tr >
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Perfil</th>
                                        <th>Base</th>
                                        <th data-orderable="false" >Status</th>
                                        <th>Data Nascimento</th>
                                        <th>Criado</th>
                                        <th>Editado</th>
                                        <th data-orderable="false" >Ações</th>
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
                            <table id="dt_users_inact" class="table table-striped table-bordered table-hover table-responsive display nowrap">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Perfil</th>
                                        <th>base</th>
                                        <th data-orderable="false" >Status</th>
                                        <th>Data Nascimento</th>
                                        <th>Criado</th>
                                        <th>Editado</th>
                                        <th data-orderable="false" >Ações</th>
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
    <!-- Modal users Create -->
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

            
            
                            @include('templates.forms.input',['input' => 'text','value' => '0', 'attributes' => [ 'name' => 'id', 'hidden', 'id' => 'id']])
                        
                            @include('templates.forms.input',['input' => 'text','label' => 'Nome Completo', 'attributes' => ['placeholder' => 'Nome Completo','class' => 'form-control form-control-user', 'id' => 'name', 'name' => 'name',  'maxlength' => '100']])

                            @include('templates.forms.email',['input' => 'email', 'label' => 'Email', 'attributes' => ['placeholder' => 'Email', 'class' => 'form-control form-control-user', 'id' => 'email', 'name' => 'email',  'maxlength' => '100']])

                            @include('templates.forms.select',['select' => 'Perfil', 'label' => 'Perfil', 'data' => $perfil_list, 'attributes' => [ 'class' => 'form-control form-control-user', 'id' => 'idtb_profile', 'name' => 'idtb_profile']])

                            @include('templates.forms.select',['select' => 'Base', 'label' => 'Base', 'data' => $base_list, 'attributes' => ['class' => 'form-control form-control-user', 'id' => 'idtb_base', 'name' => 'idtb_base']])

                            @include('templates.forms.select',['select' => 'Status', 'label' => 'Status', 'data' => ['1' => 'Ativo', '0' => 'Inativo'],'attributes' => ['placeholder' => 'Status', 'class' => 'form-control form-control-user', 'id' => 'status', 'name' => 'status']])
                            
                            @include('templates.forms.date',['date' => 'birth', 'label' => 'Data Nascimento','attributes' => ['placeholder' => 'Status', 'class' => 'form-control form-control-user col-lg-5', 'id' => 'birth', 'name' => 'birth']])
                            
                            @include('templates.forms.password',['input' => 'password', 'label' => 'Senha', 'attributes' => ['placeholder' => 'Senha',  'class' => 'form-control form-control-user', 'id' => 'password', 'name' => 'password']])
                                
                            @include('templates.forms.password',['input' => 'Repeatpassword', 'label' => 'Repita a senha', 'attributes' => ['placeholder' => 'Repita a senha',  'class' => 'form-control form-control-user', 'id' => 'Repeatpassword', 'name' => 'Repeatpassword']])
                                

                        </div>
                         <div class="modal-footer">
                
                            @include('templates.forms.button',['input' => '<i class="fa fa-save fa-fw"></i> Salvar','attributes' => ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btn_save_user']])
                            @include('templates.forms.button',['input' => '<i class="fas fa-times fa-fw"></i> Fechar','attributes' => ['type' => 'button', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal']])
                            
                            <!--<button type="button" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                        </div>
           

                    {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /Modal users create -->

    <!-- Modal edit Create -->
    <div id="modal_user_edit" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        
                    {!! Form::open(['class' =>'user', 'id' => 'user_form_edit']) !!}

                        <div class="modal-body">

            
            
                            @include('templates.forms.input',['input' => 'text','value' => '0', 'attributes' => [ 'name' => 'id', 'hidden', 'id' => 'id_edit']])
                        
                            @include('templates.forms.input',['input' => 'text','label' => 'Nome Completo', 'attributes' => ['placeholder' => 'Nome Completo','class' => 'form-control form-control-user', 'id' => 'name_edit', 'name' => 'name',  'maxlength' => '100']])

                            @include('templates.forms.email',['input' => 'email', 'label' => 'Email', 'attributes' => ['placeholder' => 'Email', 'class' => 'form-control form-control-user', 'id' => 'email_edit', 'name' => 'email',  'maxlength' => '100']])

                            @include('templates.forms.select',['select' => 'Perfil', 'label' => 'Perfil', 'data' => $perfil_list, 'attributes' => [ 'class' => 'form-control form-control-user', 'id' => 'idtb_profile_edit', 'name' => 'idtb_profile']])

                            @include('templates.forms.select',['select' => 'Base', 'label' => 'Base', 'data' => $base_list, 'attributes' => ['class' => 'form-control form-control-user', 'id' => 'idtb_base_edit', 'name' => 'idtb_base']])

                            @include('templates.forms.select',['select' => 'Status', 'label' => 'Status', 'data' => ['1' => 'Ativo', '0' => 'Inativo'],'attributes' => ['placeholder' => 'Status', 'class' => 'form-control form-control-user', 'id' => 'status_edit', 'name' => 'status']])

                            @include('templates.forms.date',['date' => 'birth', 'label' => 'Data Nascimento','attributes' => ['placeholder' => 'Status', 'class' => 'form-control form-control-user col-lg-5', 'id' => 'birth_edit', 'name' => 'birth']])
                            
                                

                        </div>
                         <div class="modal-footer">
                
                            @include('templates.forms.button',['input' => '<i class="fa fa-save fa-fw"></i> Editar','attributes' => ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btn_edit_user']])
                            @include('templates.forms.button',['input' => '<i class="fas fa-times fa-fw"></i> Fechar','attributes' => ['type' => 'button', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal']])
                            
                            <!--<button type="button" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                        </div>
           

                    {!! Form::close() !!}
            </div>
        </div>
    </div>
<!-- /Modal edit users -->
@endsection