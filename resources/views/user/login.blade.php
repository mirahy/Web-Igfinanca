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
				<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
					<div class="col-lg-6">
						<div class="p-5">
						<div class="text-center">
							<h1 class="h4 text-gray-900 mb-4">Bem vindo de volta!</h1>
								</div >

									{!! Form::open(['class' =>'user','route' => 'user.login', 'method' => 'post']) !!}

										@if(isset($error))
										<div class="form-group bnt-user"> 
										@include('templates.msg.danger-msg-login')
										</div>
										@endif
									
										<div class="form-group">
										{!! Form::Email('email', null, ['class' =>'form-control form-control-user', 'placeholder'=>'Digite seu Email', 'required', 'id'=>'exampleInputEmail', 'aria-describedby'=>'emailHelp'])!!}
										</div>
										
										<div class="form-group">
										{!! Form::password('password',['class' =>'form-control form-control-user', 'placeholder'=>'Senha', 'required'])!!}
										</div>
										
										<div class="form-group">
										<label  class="g-recaptcha " data-sitekey="6LfXo1gUAAAAAB2V2SVQCXpHZC4-i5SgQFQcUjAM" data-size="normal" style="transform:scale(0.93);transform-origin:0 0">
										</label><!-- data-theme="dark" valida/acess.php -->
										</div>

										<div class="form-group">
										<div class="custom-control custom-checkbox small">
										{!! Form::checkbox('customCheck', true, null, array('class' => 'custom-control-input', 'id'=>'customCheck')) !!}
										{!! Form::label('customCheck', 'Lembre-me', array('class' => 'custom-control-label')) !!}
										</div>
										</div>

										{!!Form::submit('Login',['class' =>'btn btn-primary btn-user btn-block']) !!}
									
										<hr>
										@include('templates.forms.button',['input' => '<i class="fab fa-google fa-fw" disabled></i> REntre com o Google','attributes' => ['type' => 'submit', 'class' => 'btn btn-google btn-user btn-block ', 'disabled']])
										 @include('templates.forms.button',['input' => '<i class="fab fa-facebook-f fa-fw" disabled></i> Entrar com o Facebook','attributes' => ['type' => 'submit', 'class' => 'btn btn-facebook btn-user btn-block', 'disabled']])
										 <!-- Alterar botoes acima pelos links abaixo apos implementação dos registros socias
										@include('templates.forms.btnLink',['href' => '#', 'class_a' => 'btn btn-google btn-user btn-block', 'class_i' => 'fab fa-google fa-fw', 'text' => 'Entre com o Google', 'role' => 'button', 'aria_disabled' => 'true'])
										@include('templates.forms.btnLink',['href' => '#', 'class_a' => 'btn btn-facebook btn-user btn-block', 'class_i' => 'fab fa-facebook-f fa-fw', 'text' => 'Entrar com o Facebook', 'role' => 'button', 'aria_disabled' => 'true'])-->
										<hr>
									
									{!! Form::close() !!}

				<div class="text-center">
					<a class="small" href="{{ asset('/forgot-password')}}">Esqueceu a senha?</a>
				</div>
				<div class="text-center">
					<a class="small" href="{{ asset('/register')}}">Crie a sua conta aqui!</a>
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
