@extends('templates.master_after_acess')


	@section('css-view')
	@endsection


	@section('js-view')
	<!-- scrip recaptcha -->
	<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
	@endsection


	@section('conteudo-view')

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-flex align-items-center "><img src="img/logo-ico.ico" class="rounded mx-auto d-block"  > </div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Crie a sua conta aqui!!</h1>
              </div>
              
              {!! Form::open(['class' =>'user','route' => 'user.store', 'method' => 'post']) !!}

                        @if(!session()->exists('success'))
                        <!--não exibe msg.-->
                        @elseif(session('success')['success'])
                          @include('templates.msg.success-msg')
                        @else
                          @include('templates.msg.danger-msg')
                        @endif
                    
                       <div class="form-group">
                          @include('templates.forms.input',['input' => 'name', 'attributes' => ['placeholder' => 'Nome Completo', 'required','class' => 'form-control form-control-user', 'id' => 'user_Name']])
                      </div>

                      <div class="form-group">
                        @include('templates.forms.email',['input' => 'email', 'attributes' => ['placeholder' => 'EMAIL', 'required', 'class' => 'form-control form-control-user', 'id' => 'User_Email']])
                      </div>

                      <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                          @include('templates.forms.password',['input' => 'password', 'attributes' => ['placeholder' => 'Senha', 'required', 'class' => 'form-control form-control-user', 'id' => 'user_tPassword']])
                        </div>

                        <div class="col-sm-6">
                          @include('templates.forms.password',['input' => 'Repeatpassword', 'attributes' => ['placeholder' => 'Repita a senha', 'required', 'class' => 'form-control form-control-user', 'id' => 'user_Repeat_Password']])
                        </div>
                      </div>

                      @include('templates.forms.submit',['input' => 'Registar Conta','attributes' => ['class' => 'btn btn-primary btn-user btn-block']])
                      
                      <hr>
                      @include('templates.forms.button',['input' => '<i class="fab fa-google fa-fw" disabled></i> Registre-se no Google','attributes' => ['type' => 'submit', 'class' => 'btn btn-google btn-user btn-block ', 'disabled']])
                      @include('templates.forms.button',['input' => '<i class="fab fa-facebook-f fa-fw" disabled></i> Registre-se no Facebook','attributes' => ['type' => 'submit', 'class' => 'btn btn-facebook btn-user btn-block', 'disabled']])
                      <!-- Alterar botoes acima pelos links abaixo apos implementação dos registros socias
                      @include('templates.forms.btnLink',['href' => '#', 'class_a' => 'btn btn-google btn-user btn-block', 'class_i' => 'fab fa-google fa-fw', 'text' => 'Registre-se no Google', 'role' => 'button', 'aria_disabled' => 'true'])
										  @include('templates.forms.btnLink',['href' => '#', 'class_a' => 'btn btn-facebook btn-user btn-block', 'class_i' => 'fab fa-facebook-f fa-fw', 'text' => 'Registre-se no Facebook', 'role' => 'button', 'aria_disabled' => 'true'])-->
                {!! Form::close() !!}

              <hr>
              <div class="text-center">
                <a class="small" href="{{ asset('/forgot-password')}}">Esqueceu a senha?</a>
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

  @endsection