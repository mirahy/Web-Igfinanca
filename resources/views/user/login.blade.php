@extends('templates.master_after_acess')


	@section('css-view')
	@endsection


	@section('js-view')
	<!-- scrip recaptcha -->
	<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
	<script src="js/util.js"></script>
	<script src="js/login.js"></script>
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
				<div class="col-lg-6 d-none d-flex align-items-center "><img src="img/logo.png" width="300" height="300" class="rounded mx-auto d-block"  > </div>
					<div class="col-lg-6">
						<div class="p-5">
						<div class="text-center">
							<h1 class="h4 text-gray-900 mb-4">Bem vindo de volta!</h1>
								</div >

									{!! Form::open(['class' =>'user', 'method' => 'post', 'id' => 'login_form']) !!}

										@foreach (['danger', 'warning', 'success', 'info'] as $msg)
											@if(Session::has('alert-' . $msg))
												<div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
													{{ Session::get('alert-' . $msg) }}
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>

												{{Session::forget('alert-' . $msg)}}
											@endif
										@endforeach

										<div class="col-sm-12 d-none">
											<div class=" ">
												<label id="message"> </label>
											</div>
												<span class="help-block"></span>
										</div>

										@include('templates.forms.email',['input' => 'email', 'class' =>'input-login', 'attributes' => ['placeholder' => 'Email', 'required','class' => 'form-control form-control-user ', 'id' => 'email', 'name' => 'email',  'maxlength' => '100']])
										
										@include('templates.forms.password',['input' => 'password', 'class' =>'input-login', 'attributes' => ['placeholder' => 'Senha', 'required', 'class' => 'form-control form-control-user 	', 'id' => 'password', 'name' => 'password']])

										@include('templates.forms.select',['select' => 'Base',  'data' => $base_list, 'class' =>'input-login' , 'attributes' => ['class' => 'form-control form-control-user', 'id' => 'base', 'name' => 'base']])
										
										<div class="col-lg-12 input-recaptcha" >
											<div class="col-sm-12 ">
												<label  id="g-recaptcha" class="g-recaptcha " data-sitekey="6LfXo1gUAAAAAB2V2SVQCXpHZC4-i5SgQFQcUjAM" data-size="normal" style="transform:scale(0.93);transform-origin:0 0">
												</label><!-- data-theme="dark" valida/acess.php -->
											</div>
											<span class="help-block"></span>
										</div>
										<div class="form-group " >
											<div class="col-sm-12 ">
												<div class="custom-control custom-checkbox small">
													{!! Form::checkbox('customCheck', true, null, array('class' => 'custom-control-input', 'id'=>'customCheck')) !!}
													{!! Form::label('customCheck', 'Lembre-me', array('class' => 'custom-control-label')) !!}
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-lg-12">
												<div class="form-group">
													{!!Form::submit('Login',['class' =>'btn btn-primary btn-user btn-block', 'id' => 'btn_login']) !!}
												</div>
												<span class="help-block"></span>
											</div>
										</div>
										<!-- 
										<hr>
										@include('templates.forms.button',['input' => '<i class="fab fa-google fa-fw" disabled></i> Entre com o Google','attributes' => ['type' => 'submit', 'class' => 'btn btn-google btn-user btn-block ', 'disabled']])
										 @include('templates.forms.button',['input' => '<i class="fab fa-facebook-f fa-fw" disabled></i> Entrar com o Facebook','attributes' => ['type' => 'submit', 'class' => 'btn btn-facebook btn-user btn-block', 'disabled']])-->
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
