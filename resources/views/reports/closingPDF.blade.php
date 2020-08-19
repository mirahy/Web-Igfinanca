<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

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
                
                @foreach ($dados as $item)
                <tr>
                    <th>{{$item->type_launch->name}}</th>
                    <th>{{$item->user->name}}</th>
                    <th>{{$item->value}}</th>
                    <th>{{$item->caixa->name }}</th>
                    <th>{{$item->operation_date}}</th>
                </tr>    
                @endforeach
               
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style=" text-align:right">Total: {{$tot}}</th>

                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.container-fluid -->

</body>

</html>