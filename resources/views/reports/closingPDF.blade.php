<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDFClosing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">


</head>

<body>

    <div class="container-fluid">
        <h3 class="text-center"><strong>Fechamento de Caixa - {{$tpCaixa[0]['name']}}</strong></h3>
        <h4 class="text-center"><strong>Período: {{$period[0]['month'].'/'.$period[0]['year']}}</strong></h4>
          <div class="col-sm-6">
            <table class="table table-borderless table-sm">
              <thead>
              </thead>
              <tbody>
                <tr class="table-secondary">>
                  <td class="col-sm-1"><!-- Entrada dízimos -->
                      <div class="m-1">
                        <div class="text-xs font-weight-bold text-success text-uppercase">Entradas </div>
                        <div class=" font-weight-bold text-gray-800" id="entries">R$ {{ number_format($entries, 2, ',', '.') }}</div>
                      </div>
                  </td>
                  <td class="col-sm-1"><!-- saídas dízimos-->
                      <div class="m-1">
                        <div class="text-xs font-weight-bold text-danger text-uppercase">Saídas</div>
                        <div class=" font-weight-bold text-gray-800" id="exits">R$ {{ number_format($exits, 2, ',', '.') }}</div>
                      </div>
                  </td>
                  <td class="col-sm-1"><!-- Saldo Caixa dízimo -->
                    <div class="m-1">
                        <div class="text-xs font-weight-bold text-info text-uppercase">Saldo</div>
                        <div class="font-weight-bold text-gray-800" id="balance">R$ {{ number_format($balance, 2, ',', '.') }}</div>
                    </div>
                  </td>
                </tr>
              </tbody>
              <tfoot></tfoot>
            </table>
          </div>                   
        <table id="dt_report_closing" class="table ">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Caixa</th>
                    <th>Coleta</th>
                </tr>
            </thead>
            <tbody class="tb-size">
                
                @foreach ($dados as $item)
                <tr>
                    <th>{{ $item->type_launch->name }}</th>
                    <th>{{ $item->user->name }}</th>
                    <th class=@if ($item->idtb_operation == 2)
                        {{"text-danger"}}
                        @else
                        {{"text-success"}} 
                        @endif>
                        {{'R$'}}
                        @if ($item->idtb_operation == 2)
                        {{'-'}}    
                        @endif
                        {{number_format( $item->value, 2, ',', '.') }}
                      </th>
                    <th>{{ $item->caixa->name }}</th>
                    <th>{{ $item->dataOperation }}</th>
                </tr>    
                @endforeach

            </tbody>
            <tfoot>

               
            </tfoot>
        </table>
        <div class="col-sm-12">
          <table class="table table-borderless table-sm">
            <thead>
            </thead>
            <tbody>
              <tr class="table-secondary">>
                <td class="col-sm-1"><!-- Entrada dízimos -->
                    <div class="m-1">
                      <div class="col-md-4">__________________</div>
                      <div class="col-md-4 center">Tesouraria Sede</div>
                    </div>
                </td>
                <td class="col-sm-1"><!-- saídas dízimos-->
                    <div class="m-1">
                      <div class="col-md-4">__________________</div>
                      <div class="col-md-4 center">Tesouraria Cong.</div>
                    </div>
                </td>
                <td class="col-sm-1"><!-- Saldo Caixa dízimo -->
                  <div class="m-1">
                    <div class="col-md-4">__________________</div>
                    <div class="col-md-4 center">Pastor Cong.</div>
                  </div>
                </td>
              </tr>
            </tbody>
            <tfoot></tfoot>
          </table>
        </div>    
    </div>
    <!-- /.container-fluid -->

     <!-- Bootstrap core JavaScript-->
 <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript-->
 <script src="vendor/jquery-easing/jquery.easing.min.js"></script>


     <!-- Url base-->
   <script src="js/url.js"></script>

</body>

</html>
