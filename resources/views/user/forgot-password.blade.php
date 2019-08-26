@extends('templates.master_after_acess')


	@section('css-view')
	@endsection


	@section('js-view')
	<!-- scrip recaptcha -->
	<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
	@endsection


	@section('conteudo-view')

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">Esqueceu sua senha?</h1>
                    <p class="mb-4">Nós entendemos, coisas acontecem. Basta digitar seu endereço de e-mail abaixo e nós lhe enviaremos um link para redefinir sua senha!</p>
                  </div>

                  {!! Form::open(['class' =>'user','route' => 'user.login', 'method' => 'post']) !!}

										@if(isset($error))
										<div class="form-group bnt-user"> 
										@include('templates.msg.danger-msg-login')
										</div>
                    @endif
                    
                    <div class="form-group">
                      {!! Form::Email('email', null, ['class' =>'form-control form-control-user', 'placeholder'=>'Insira o endereço de e-mail...', 'required', 'id'=>'exampleInputEmail', 'aria-describedby'=>'emailHelp'])!!}
                    </div>

                    {!!Form::submit('Redefinir Senha',['class' =>'btn btn-primary btn-user btn-block', 'disabled']) !!}

                  {!! Form::close() !!}
                  <hr>
                  <div class="text-center">
                    <a class="small" href="{{ asset('/register')}}">Crie a sua conta aqui!</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="{{ asset('/login')}}">Já tem uma conta? Login!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  @endsection
