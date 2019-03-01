<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="1024" />
    <meta id="meta-keywords" name="keywords" content="controle financeiro, relatórios, cadastros caixas  "/>
    <meta id="meta-description" name="description" content="Site para lançamento sdo caixa da igreja Assembleia de Deus Belem"/>
    <meta name="Mirahy" content="MF WEB soluções">
    <!--<meta http-equiv="refresh" content="30">-->
    <title>Controle Financeiro</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Fredoka+One">


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link rel="stylesheet" href="{{asset('css/ie10-viewport-bug-workaround.css')}}">
    <!-- Bootstrap icons fontawesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    @yield('css-view')
</head>

<body>
  <div class="background">
        @if(Auth::check())
        @include('templates.menu-superior')
        @endif
        @yield('conteudo-view')
        @yield('js-view')
        <script src="{{ asset('js/bootstrap.min.js') }}" ></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  </div>

</body>

</html>
