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
<script src="js/launchs.js"></script>
<script src="js/jquery-ui.js"></script>
@endsection

@section('conteudo-view')


    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->      

      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lançamentos</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
      </div>

        <ul class="nav nav-tabs">
            <li class="nav-item active"><a class="nav-link active" href="#tab_consult" role="tab"  data-toggle="tab">Consulta</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_consult" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong></strong></h2>
                            <table id="dt_consult" class="table table-striped table-hover table-responsive display nowrap" >
                                <thead>
                                    <tr >
                                        <th>Tipo</th>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                        <th>Caixa</th>
                                        <th>Tp Pagamento</th>
                                        <th>Coleta</th>
                                        <th>Status</th>
                                        <th data-orderable="false" >Período</th>
                                        <th>Status Período</th>
                                        <th>Data lançamento</th>
                                        <th>Data Ataulização</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <th colspan="11"  style=" text-align:right">Total:</th>
                                    </tr>
                                </tfoot>
                            </table>
                </div>
            </div>

        </div>

      

    </div>
    <!-- /.container-fluid -->

@endsection