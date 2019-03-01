<nav class="navbar navbar-expand-lg navbar-light bg-light navfont navstyle">

  @if(Auth::check())
    <a class="navbar-brand" href="{{ asset('/dashboard')}}">CF</a>
  @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ">

            <li class="nav-item active">
                <a class="nav-link" href="{{ asset('/dashboard')}}"><i class="fas fa-home icon-home"></i></a>
            </li>

            <li class="nav-item dropdown">
              @if(Auth::check())
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-users"></i>
                    Usuários

                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <a class="dropdown-item" href="{{ route('user.index') }}">Cadastrar</a>
                    <a class="dropdown-item" href="#">Editar</a>
                    <a class="dropdown-item" href="#">Excluir</a>
                    <a class="dropdown-item" href="#">Ativar/Desativar</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('user.query') }}">Consultar usuários</a>

                </div>
                @endif
            </li>

            <li class="nav-item dropdown">
              @if(Auth::check())
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-edit"></i>
                    Lançamentos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Inserir</a>
                    <a class="dropdown-item" href="#">Editar</a>
                    <a class="dropdown-item" href="#">Excluir</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Consultar lançamentos</a>
                </div>
              @endif
            </li>

            <li class="nav-item dropdown">
              @if(Auth::check())
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-book"></i>
                    Fechamentos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Gerar</a>
                    <a class="dropdown-item" href="#">Editar</a>
                    <a class="dropdown-item" href="#">Excluir</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Consultar Fechamentos</a>
                </div>
              @endif
            </li>

            <li class="nav-item dropdown">
              @if(Auth::check())
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-database"></i>
                    Acess DB
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Users</a>
                    <a class="dropdown-item" href="#">Releases</a>
                    <a class="dropdown-item" href="#">Locks</a>
                    <a class="dropdown-item" href="#">Users Social</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Consult tables</a>
                </div>
              @endif
            </li>

        </ul>
        @if(Auth::check())
        <form class="form-inline my-2 my-lg-0 ml-auto" id="custom-search-input">
            <input type="text" class="form-control input-lg" placeholder="Buscar" />
            <span class="input-group-btn">
                <button class="btn btn-info btn-lg" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </span>
        </form>

        <ul class="navbar-nav ml-auto user-drop">
            <li class="nav-item dropdown ">
                <strong>

                    {{ 'Olá '.Auth::user()->name}}

                </strong>
                <a class=" nav-link dropdown-toggle ml-auto " href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                    Conta
                </a>
                <div class="dropdown-menu dropdown-menu-right ml-auto" aria-labelledby="navbarDropdown">
                    <label class="dropdown-header disabled">Dados:</label>
                    <label class="dropdown-header disabled">{{ Auth::user()->email }}</label>
                    <a class="dropdown-item disabled" href="#"><i class="fas fa-user-edit icon-drop-user"></i>Editar</a>
                    <a class="dropdown-item disabled" href="#"><i class="fas fa-unlock-alt icon-drop-user"></i>Alterar senha</a>
                    <a class="dropdown-item disabled" href="#"><i class="fas fa-envelope icon-drop-user"></i>Alterar email</a>
                    <div class="dropdown-divider"></div>
                    <label class="dropdown-header disabled">Acessos:</label>
                    <a class="dropdown-item disabled" href="#">Base: {{ Auth::user()->idtb_base }} </a>
                    <a class="dropdown-item disabled" href="#">Perfil: {{ Auth::user()->idtb_profile }} </a>
                    <a class="dropdown-item disabled" href="#">Status: {{ Auth::user()->status }} </a>
                    <!--#  "Perfil: ".$_SESSION['usuarioPerfil']-->
                    <!--#  "Cargo: ".$_SESSION['usuarioCargo']-->
                    <!--#  "Status: ".$_SESSION['usuariostatus']-->
                    <!--#  "Nivel acesso: ".$_SESSION['usuarioAcess']-->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ asset('/logout')}}">Sair</a>
                </div>
            </li>
        </ul>
      @endif
    </div>
</nav>
