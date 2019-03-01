@extends('templates.master')


@section('css-view')
@endsection


@section('js-view')
@endsection


@section('conteudo-view')


<div class="container">
    <div class="row">


        <div class="mx-auto col-sm-6">
            <!-- form user info -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Dados de Usuário</h4>
                </div>
                <div class="card-body">


                    {!! Form::open(['route' => 'user.store', 'method' => 'post', 'class' => 'form']) !!}


                    @if(!session()->exists('success'))
                        <!--não exibe msg.-->
                        @elseif(session('success')['success'])
                        @include('templates.success-msg')
                        @else
                        @include('templates.danger-msg')
                        @endif

                        @if(Auth::check())
                        <a class="nav-link" href="{{ asset('/query')}}" title="Listar usuários"><i class="fas fa-list"></i></a>
                        @endif

                        @include('templates.formulario.input',['class' => 'col-lg-10 col-form-label form-control-label', 'label' => 'NOME', 'input' => 'name', 'attributes' => ['placeholder' => 'NOME', 'required', 'class' =>
                        'form-control']])
                        @include('templates.formulario.select',['class' => 'col-lg-10 col-form-label form-control-label','label' => 'PERFIL', 'id_item' => 'idtb_profile', 'itens' => ['1' => 'Desenvolvedor', '2' => 'Usuário'], 'selected'
                        =>'','classinput' => ['class' => 'form-control']])
                        @include('templates.formulario.select',['class' => 'col-lg-10 col-form-label form-control-label', 'label' => 'BASE', 'id_item' => 'idtb_base', 'itens' => ['1' => 'TARUMA', '2' => 'SEDE'], 'selected' => '',
                        'classinput'=>['class' => 'form-control']])
                        @include('templates.formulario.date',['class' => 'col-lg-10 col-form-label form-control-label', 'label' => 'DATA NASCIMENTO', 'input' => 'birth', 'attributes' => ['class' => 'form-control']])
                        @include('templates.formulario.email',['class' => 'col-lg-10 col-form-label form-control-label', 'label' => 'EMAIL', 'input' => 'email', 'attributes' => ['placeholder' => 'EMAIL', 'required', 'class' =>
                        'form-control']])
                        @include('templates.formulario.password',['class' => 'col-lg-10 col-form-label form-control-label', 'label' => 'SENHA', 'input' => 'password', 'attributes' => ['placeholder' => 'SENHA', 'required',
                        'class'=>'form-control']])
                        @include('templates.formulario.password',['class' => 'col-lg-10 col-form-label form-control-label', 'label' => 'CONFIME A SENHA', 'input' => 'pass2', 'attributes' => ['placeholder' => 'CONFIME A SENHA!','required','class' => 'form-control']])
                        <input type="hidden" name="status" value="1">
                        <input type="hidden" name="permission" value="1">
                        @include('templates.formulario.submit',['class' => 'col-lg-10 col-form-label form-control-label', 'input' => 'Cadastar','attributes' => ['class' => 'btn btn-primary']])
                        {!! Form::close() !!}
                        @if(!Auth::check())
                        <div class="form-group row">
                            <label class="col-lg-10 col-form-label form-control-label">
                                <div class="col-lg-12">
                                    <a href="{{ asset('/login')}}"><button class="btn btn-primary">Voltar</button></a>
                            </label>
                        </div>
                </div>
                @endif

            </div>
        </div>
        <!-- /form user info -->
    </div>
</div>
</div>

@endsection
