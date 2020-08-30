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
    <script src="js/closing.js"></script>
    <script src="js/jquery-ui.js"></script>
@endsection

@section('conteudo-view')


    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Fechamentos</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item active"><a class="nav-link active" href="#tab_closing" role="tab"
                    data-toggle="tab">Relação</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_closing" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Períodos de Fechamentos</strong></h2>
                    <a id="btn_add_closing" class="btn btn-primary my-1"><i class="fas fa-plus ">&nbsp Adicionar
                            Período</i></a>
                    <table id="dt_closing" class="table table-striped table-hover table-responsive display nowrap">
                        <thead>
                            <tr>
                                <th>Mês</th>
                                <th>Ano</th>
                                <th>Status</th>
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

    <!-- Modal lançamentos -->
    <div id="modal_closing" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Fechamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {!! Form::open(['class' => 'user', 'id' => 'closing_form']) !!}

                <div class="modal-body">


                    <!-- Imput hidden -->
                    @include('templates.forms.input',['input' => 'text','value' => '0', 'class' => 'd-none', 'attributes' =>
                    [ 'name' => 'id', 'hidden', 'id' => 'id']])
                    <!-- \ Imput hidden -->

                    @include('templates.forms.select',['select' => 'mes', 'label' => 'Mês', 'data' => $month
                    ,'attributes' => ['placeholder' => 'Mês', 'class' => 'form-control form-control-user', 'id' =>
                    'month', 'name' => 'month']])

                    @include('templates.forms.select',['select' => 'Ano', 'label' => 'Ano', 'data' => $year
                    ,'attributes' => ['placeholder' => 'Ano', 'class' => 'form-control form-control-user', 'id' =>
                    'year', 'name' => 'year']])

                    @include('templates.forms.select',['select' => 'status', 'label' => 'Status', 'data' => [ '1' =>
                    'Aberto', '2' => 'Pre-Fechamento', '0' => 'Fechado']
                    ,'attributes' => ['placeholder' => 'Status', 'class' => 'form-control form-control-user', 'id' =>
                    'status', 'name' => 'status']])

                </div>
                <div class="modal-footer">

                    @include('templates.forms.button',['input' => '<i class="fa fa-save fa-fw"></i> Gravar','attributes' =>
                    ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btn_save_closing']])
                    @include('templates.forms.button',['input' => '<i class="fas fa-times fa-fw"></i> Fechar','attributes'
                    => ['type' => 'button', 'class' => 'btn btn-secondary', 'data-dismiss' => 'modal']])

                    <!--<button type="button" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /Modal lançamentos -->


@endsection
