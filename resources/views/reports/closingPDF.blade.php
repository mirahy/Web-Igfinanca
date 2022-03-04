<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDFClosing</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Custom styles for this template-->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body>

  <div class="container" style="font-size: 0.8rem !important">
    <h3 class="text-center"><strong>Fechamento de Caixa - {{$tpCaixa[0]['name']}}</strong></h3>
    <h4 class="text-center"><strong>Período: {{$period[0]['month'].'/'.$period[0]['year']}}</strong></h4>
    <div class="row col-6">
      <table class="table table-borderless table-sm">
        <thead>
        </thead>
        <tbody>
          <tr class="table-secondary">
            <td>
              <!-- Entrada  -->
              <div class="p-2">
                <div class="text-xs font-weight-bold text-success text-uppercase">Entradas </div>
                <div class=" font-weight-bold text-gray-800" id="entries">R$ {{ number_format($entries, 2, ',', '.') }}</div>
              </div>
            </td>
            <td>
              <!-- saídas -->
              <div class="p-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase">Saídas</div>
                <div class=" font-weight-bold text-gray-800" id="exits">R$ {{ number_format($exits, 2, ',', '.') }}</div>
              </div>
            </td>
            <td>
              <!-- Saldo Caixa  -->
              <div class="p-2">
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
        <thead class="thead-dark">
          <tr>
            <th scope="col">Tipo</th>
            <th scope="col">Nome</th>
            <th scope="col" style="width: 35%;">Descrição</th>
            <th scope="col" >Valor</th>
            <th scope="col">Caixa</th>
            <th scope="col">Tipo Pagamento</th>
            <th scope="col">Data</th>
          </tr>
        </thead>
        <tbody class="tb-size">

          @foreach ($dados as $item)
          <tr style="font-size: 0.7rem !important">
            <th scope="row">{{ $item->type_launch->name }}</th>
            <th>{{ $item->user->name }}</th>
            <th style="font-size: 0.6rem !important">{{ $item->description }}</th>
            <th class= @if ($item->idtb_operation == 2)
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
            <th>{{ $item->payment_type->name }}</th>
            <th>{{ $item->dataOperation }}</th>
          </tr>
          @endforeach

        </tbody>
        <tfoot>


        </tfoot>
      </table>
    <div class="col-sm-12" style="font-size: 0.9rem !important">
      <table class="table table-borderless table-sm">
        <thead>
        </thead>
        <tbody>
          <tr class="table-secondary">
            <td>
              <div class="m-1">
                <div class="col-md-4">__________________</div>
                <div class="col-md-8 center">Tesouraria Sede</div>
              </div>
            </td>
            <td>
              <div class="m-1">
                <div class="col-md-4">__________________</div>
                <div class="col-md-8 center">Tesouraria Cong.</div>
              </div>
            </td>
            <td >
              <div>
                <div class="col-md-4">__________________</div>
                <div class="col-md-8 center">Pastor Cong.</div>
              </div>
            </td>
          </tr>
        </tbody>
        <tfoot></tfoot>
      </table>
    </div>
  </div>
  <!-- /.container-fluid -->

</body>

</html>