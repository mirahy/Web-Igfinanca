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
<script src="js/reports.js"></script>
<script src="js/jquery-ui.js"></script>
@endsection

@section('conteudo-view')


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Relatórios</h1>
    </div>
    <div class="container-fluid ">
        <div class="col-xs-4">
            @include('templates.forms.select',['select' => 'mes', 'data' => $data ,'attributes' => ['placeholder' => 'Mês', 'class' => 'form-control form-control-user col-md-2 ', 'id' => 'reference_month', 'name' => 'reference_month']])
            @include('templates.forms.input',['input' => 'text','value' => $year, 'attributes' => [ 'class' => 'form-control form-control-user col-md-2', 'name' => 'reference_year', 'id' => 'reference_year']])
            <a href="{{ asset('/closingPDF?status=1')}}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
        </div>
    </div>
    <div class="container-fluid">
        <h2 class="text-center"><strong>Fechamentos</strong></h2>
        <table id="dt_report_closing" class="table table-striped table-hover table-responsive display nowrap">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Caixa</th>
                    <th>Coleta</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style=" text-align:right">Total:</th>

                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>




</div>
<!-- /.container-fluid -->


@endsection