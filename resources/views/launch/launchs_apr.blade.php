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
        <h1 class="h3 mb-0 text-gray-800">Aprovações de Lançamentos</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
      </div>

        <ul class="nav nav-tabs">
            <li class="nav-item active"><a class="nav-link active" href="#tab_launch_apr" role="tab"  data-toggle="tab">Lançamentos</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_launch_apr" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Lançamentos</strong></h2>
                            <table id="dt_launch_apr" class="table table-striped table-hover table-responsive display nowrap" >
                                <thead>
                                    <tr >
                                        <th>Tipo</th>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                        <th>Coleta</th>
                                        <th data-orderable="false" >Mês Referência</th>
                                        <th data-orderable="false" >Ano Referência</th>
                                        <th>Status</th>
                                        <th>Data lançamento</th>
                                        <th>Data Ataulização</th>
                                        <th data-orderable="false" >Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9"  style=" text-align:right">Total:</th>
                                        
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                </div>
            </div>

        </div>    

    </div>
    <!-- /.container-fluid -->

   
@endsection