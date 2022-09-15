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
    @if($dados2->count() <> 0)
      <div class="page-break">
        @endif
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
                    <div class=" font-weight-bold text-gray-800" id="entries">Total: R$ {{ number_format($entries, 2, ',', '.') }}</div>
                    <div style="font-size: 0.6rem !important">
                    <div>Saldo Anterior: R$ {{number_format( $startBalance, 2, ',', '.')}}</div>
                      @foreach ($dados2 as $item)
                      <div>{{$item->payment_type->name}} : R$ {{number_format( $item->total, 2, ',', '.')}}</div>
                      @endforeach
                    </div>
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
              <th scope="col">Valor</th>
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
                <td>
                  <div>
                    <div class="col-md-4">__________________</div>
                    <div class="col-md-8 center">Pastor Cong.</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  @php($e = 1)
                  @if($dados2->count() <> 0)
                    <h6 class="m-2">Este relatório contem o(s) seguinte(s) anexo(s):</h6>
                    @foreach($dados2 as $item1)
                      <h6 class="m-2"> {{'Anexo '.$e.' - '.$item1->payment_type->name}} </h6>
                      @php($e++)
                    @endforeach
                  @endif
                </td>
              </tr>
            </tbody>
            <tfoot>

            </tfoot>
          </table>
        </div>
      </div>
      <!-- Tabelas adicionais -->
      @php($i = 1)
      @if($dados2->count() <> 0)
        @foreach($dados2 as $item1)

        @if($i < $dados2->count())
          <div class="page-break">
            @endif
            <h3 class="text-center"><strong>Fechamento de Caixa - {{$tpCaixa[0]['name']}}</strong></h3>
            <h4 class="text-center"><strong>Período: {{$period[0]['month'].'/'.$period[0]['year']}}</strong></h4>
            <h1> {{'Anexo '.$i.' - '.$item1->payment_type->name}} </h1>
            <table id="dt_report_closing" class="table ">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Tipo</th>
                  <th scope="col">Nome</th>
                  <th scope="col" style="width: 35%;">Descrição</th>
                  <th scope="col">Valor</th>
                  <th scope="col">Caixa</th>
                  <th scope="col">Tipo Pagamento</th>
                  <th scope="col">Data</th>
                </tr>
              </thead>
              <tbody class="tb-size">

                @foreach ($dados as $item)
                @if($item->idtb_payment_type == $item1->payment_type->id && $item->idtb_operation == 1)
                <tr style="font-size: 0.7rem !important">
                  <th scope="row">{{ $item->type_launch->name }}</th>
                  <th>{{ $item->user->name }}</th>
                  <th style="font-size: 0.6rem !important">{{ $item->description }}</th>
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
                  <th>{{ $item->payment_type->name }}</th>
                  <th>{{ $item->dataOperation }}</th>
                </tr>
                @endif
                @endforeach


              </tbody>
              <tfoot>


              </tfoot>
            </table>
          </div>
          @php($i++)
          @endforeach
          @endif
  </div>
  <!-- /.container-fluid -->

</body>

</html>