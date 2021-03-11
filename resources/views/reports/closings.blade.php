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
            <h2 class="text-center"><strong>Fechamentos</strong></h2>
        </div>
    </div>

    {!! Form::open(['class' =>'user', 'id' => 'form_report_closing', 'action' => 'PdfController@closing_pdf' ]) !!}
    @include('templates.forms.input',['input' => 'text','value' => $status,  'attributes' => ['hidden', 'class' => 'd-none ', 'name' => 'status', 'id' => 'status']])
    @include('templates.forms.select',['select' => 'mes', 'data' => $month ,'attributes' => ['placeholder' => 'Mês', 'class' => 'form-control form-control-user col-md-2 ', 'id' => 'reference_month', 'name' => 'reference_month', 'required']])
    @include('templates.forms.select',['select' => 'Caixa','data' => $caixa_list,  'attributes' => ['placeholder' => 'Caixa', 'class' => 'form-control form-control-user col-md-2','name' => 'caixa', 'id' => 'caixa', 'required']])
    @include('templates.forms.button',['input' => '<i class="fas fa-download fa-sm text-white-50"></i> Exportar PDF','attributes' => ['formtarget' => '_blank', 'type' => 'submit', 'class' => 'd-none d-sm-inline-block btn btn-sm btn-primary shadow-sm m-2', 'id' => 'btn_pdf_closing']])
    {!! Form::close() !!}
    

    {{-- <div class="container-fluid ">
      <div class="col-xs-4">
          <a href="{{ asset('/closingPDF?caixa=1&status=1')}}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-2"><i class="fas fa-download fa-sm text-white-50"></i> Exportar PDF</a>
      </div>
  </div> --}}

     <!-- Content Row -->
     <div class="row">

        <!-- Entrada dízimos -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <a class="collapse-item link-none-line" href="{{ asset('/launchs-e')}}">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">  
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Entradas </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="entries">R$ <?=$entries?></div> 
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <!-- saídas dízimos-->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-danger shadow h-100 py-2">
            <a class="collapse-item link-none-line" href="{{ asset('/launchs-s')}}">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Saídas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="exits">R$ <?=$exits?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <!-- Saldo Caixa dízimo -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Saldo</div>
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto">
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="balance">R$ <?=$balance?></div>
                    </div>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /Content Row -->


    <div class="container-fluid">
        <table id="dt_report_closing" class="table table-striped table-hover table-responsive display nowrap">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Período</th>
                    <th>Status Período</th>
                    <th>Caixa</th>
                    <th>Coleta</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </div>




</div>
<!-- /.container-fluid -->


@endsection