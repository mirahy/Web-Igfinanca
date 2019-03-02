@extends('templates.head-login')


@section('css-view')
@endsection


@section('js-view')
<!-- scrip recaptcha -->
<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

@endsection


@section('conteudo-view')
 <!-- Nested Row within Card Body -->
 <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Bem Vindo de Volta!</h1>
				  </div>
				  {!! Form::open(['route' => 'user.login', 'method' => 'post']) !!}
                  <!--<form class="user">-->
                    <div class="form-group">
						{!! Form::text('email', null, ['class' =>'form-control form-control-user', 'placeholder'=>'Digite seu Email', 'required'])!!}
                      <!--<input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">-->
                    </div>
                    <div class="form-group">
					{!! Form::password('password',['class' =>'form-control form-control-user', 'placeholder'=>'Senha', 'required'])!!}
                      <!--<input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">-->
					</div>
					
					<label < class="g-recaptcha form-group  " data-sitekey="6LfXo1gUAAAAAB2V2SVQCXpHZC4-i5SgQFQcUjAM" data-size="normal" style="transform:scale(0.93);transform-origin:0 0">
					</label><!-- data-theme="dark" valida/acess.php -->

                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
					  {!! Form::checkbox('customCheck', 'false', false, array('class' => 'custom-control-input', 'id'=>'customCheck')) !!}
					  {!! Form::label('customCheck', 'Lembre-me', array('class' => 'custom-control-label')) !!}
                        <!--<input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>-->
                      </div>
					</div>
					{!!Form::submit('Login',['class' =>'btn btn-primary btn-user btn-block']) !!}
                    <!--<a href="index.html" class="btn btn-primary btn-user btn-block">
                      Login
                    </a>-->
					<hr>
                    <a href="index.html" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Entre com o Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Entrar com o Facebook
                    </a>
				  <!--</form>-->
				  {!! Form::close() !!}
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Esqueceu a senha?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.html">Crie a sua conta aqui!</a>
                  </div>
                </div>
              </div>
            </div>

@endsection
