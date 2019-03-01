@extends('templates.master')


@section('css-view')

@endsection


@section('js-view')

@endsection


@section('conteudo-view')

  <div class="container">
    <div class="row">
      <!-- Editable table -->
      <div class="card">
        <h3 class="card-header text-center font-weight-bold text-uppercase py-4">Lista de Usuários</h3>
        <div class="card-body">
          <div id="table" class="table-editable">
            <span class="table-add float-right mb-3 mr-2"><a href="{{ route('user.index') }}" class="text-success"><i class="fa fa-plus fa-2x"
                  aria-hidden="true"></i></a></span>
            <table class="table table-bordered table-responsive-md table-striped text-center">
              <thead>
                <tr>
                  <th class="text-center">Nome</th>
                  <th class="text-center">Email</th>
                  <th class="text-center">Perfil</th>
                  <th class="text-center">Base</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Data Nascimento</th>
                  <th class="text-center"></th>
                </tr>
              </thead>

              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td class="pt-3-half" contenteditable="true">{{ $user->name }}</td>
                    <td class="pt-3-half" contenteditable="true">{{ $user->email }}</td>
                    <td class="pt-3-half" contenteditable="true">{{ $user->idtb_profile }}</td>
                    <td class="pt-3-half" contenteditable="true">{{ $user->idtb_base }}</td>
                    <td class="pt-3-half" contenteditable="true">{{ $user->status }}</td>
                    <td class="pt-3-half" contenteditable="true">{{ $user->birth }} </td>
                    <td>
                      <span class="table-remove"><button type="button" class="btn btn-success btn-rounded btn-sm my-0">Editar</button></span>
                      <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0">Excluir</button></span>
                    </td>

                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
