@extends('templates.master')



@section('css-view')
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
@endsection

@section('js-view')
<script src="js/bootstrap.js"></script>
<script src="js/util.js"></script>
@endsection

@section('conteudo-view')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Logs de Atividades</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
    </div>

    <ul class="nav nav-tabs">
        <li class="nav-item active"><a class="nav-link active" href="#tab_users_active" role="tab" data-toggle="tab">Logs</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab_logs" class="tab-pane active">
            <div class="container-fluid">
                <h2 class="text-center"><strong>Logs</strong></h2>
                <table id="dt_logs" class="table table-striped table-bordered table-hover table-responsive display nowrap">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tabela</th>
                            <th>Descrição</th>
                            <th>Alteração</th>
                            <th>Usuário Alteração</th>
                            <th>Data Alteração</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activies as $activie)
                            <tr>
                                <th>{{$activie->id}}</th>
                                <th>{{$activie->log_name}}</th>
                                <th>{{$activie->description}}</th>
                                <th>{{$activie->properties}}</th>
                                <th>{{$activie->causer_id}}</th>
                                <th>{{$activie->created_at}}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$activies->links()}}
            </div>
        </div>

    </div>



</div>
<!-- /.container-fluid -->
@endsection