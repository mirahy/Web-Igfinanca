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
        <h2 class="h3 mb-0 text-gray-800">Entradas</h2>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
      </div>

        <ul class="nav nav-tabs">
            <li class="nav-item active"><a class="nav-link active" href="#tab_launch_d" role="tab"  data-toggle="tab">Dízimos</a></li>
            <li class="nav-item"><a class="nav-link" href="#tab_launch_o" role="tab"  data-toggle="tab">Ofertas</a></li>
            <li class="nav-item"><a class="nav-link" href="#tab_launch_others" role="tab"  data-toggle="tab">Geral</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_launch_d" class="tab-pane active">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Dízimos</strong></h2>
                        <a id="btn_add_launch_d" class="btn btn-primary my-1 font-weight-bold">Lançar Dízimo</a>
                            <table id="dt_launch" class="table table-striped table-hover table-responsive display nowrap" >
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
                                        <th data-orderable="false" >Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="10"  style=" text-align:right">Total:</th>
                                        <th colspan="2"></th>                                     
                                    </tr>
                                </tfoot>
                            </table>
                </div>
            </div>

            <div id="tab_launch_o" class="tab-pane ">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Ofertas</strong></h2>
                        <a id="btn_add_launch_o" class="btn btn-primary my-1 font-weight-bold">Lançar Ofertas</a>
                            <table id="dt_launch_o" class="table table-striped  table-hover table-responsive display nowrap">
                                <thead>
                                    <tr>
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
                                        <th data-orderable="false" >Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="10"  style=" text-align:right">Total:</th>
                                        <th colspan="2"></th>                                     
                                    </tr>
                                </tfoot>
                            </table>
                </div>
            
            </div>

            <div id="tab_launch_others" class="tab-pane">
                <div class="container-fluid">
                    <h2 class="text-center"><strong>Geral</strong></h2>
                        <a id="btn_add_launch_others" class="btn btn-primary my-1 font-weight-bold">Lançar</a>
                            <table id="dt_launch_others" class="table table-striped table-hover table-responsive display nowrap" >
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
                                        <th data-orderable="false" >Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="10"  style=" text-align:right">Total:</th>
                                        <th colspan="2"></th>                                     
                                    </tr>
                                </tfoot>
                            </table>
                </div>
            </div>
        </div>

      

    </div>
    <!-- /.container-fluid -->
    
    <!-- Modal lançamentos -->

    @extends('templates.forms.form-launch')
    
    <!-- /Modal lançamentos -->

   
@endsection