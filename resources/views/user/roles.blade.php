@extends('templates.master')



@section('css-view')
<link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
@endsection

@section('js-view')
<script src="js/sweetalert2.all.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/util.js"></script>
<script src="js/roles.js"></script>
<script src="js/jquery-ui.js"></script>
@endsection

@section('conteudo-view')


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Funções</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
    </div>

    <ul class="nav nav-tabs">
        <li class="nav-item active"><a class="nav-link active" href="#tab_role" role="tab" data-toggle="tab">Relação</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab_role" class="tab-pane active">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Funções</strong></h2>
                <a id="btn_add_role" class="btn btn-primary my-1"><i class="fas fa-plus ">&nbsp Adicionar
                        Função</i></a>
                <table id="dt_role" class="table table-striped table-hover table-responsive display nowrap">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Data lançamento</th>
                            <th>Data Ataulização</th>
                            <th data-orderable="false">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal  -->
<div id="modal_role" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Função</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['class' => 'user', 'id' => 'role_form']) !!}

            <div class="modal-body">


                <!-- Imput hidden -->
                @include('templates.forms.input',['input' => 'text','value' => '0', 'class' => 'd-none', 'attributes' =>
                [ 'name' => 'id', 'hidden', 'id' => 'id']])
                <!-- \ Imput hidden -->

                @include('templates.forms.input',['input' => 'text','label' => 'Nome Função', 'attributes' =>
                ['placeholder' => 'Nome Função','class' => 'form-control form-control-user', 'id' => 'name', 'name' => 'name', 'maxlength' => '100']])


                <div class="col-lg-12 check_roles " id="check_roles">
                    <label class="col-lg-4 control-label">Permissões</label>
                    <div class="form-group ">
                        @foreach($permission as $value)
                        <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => [$value->name, 'permission'], 'id' => 'permission')) }}
                            {{ $value->name }}</label>
                        <br />
                        @endforeach
                        <span class="help-block"></span>
                    </div>
                </div>


            </div>
            <div class="modal-footer">

                @include('templates.forms.button',['input' => '<i class="fa fa-save fa-fw"></i> Gravar','attributes' =>
                ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btn_save_role']])
                @include('templates.forms.button',['input' => '<i class="fas fa-times fa-fw"></i> Fechar','attributes'
                => ['type' => 'button', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal']])

                <!--<button type="button" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            </div>


            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /Modal  -->


@endsection