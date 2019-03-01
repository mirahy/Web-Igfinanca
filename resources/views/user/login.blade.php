@extends('templates.master')


@section('css-view')
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endsection


@section('js-view')
<!-- scrip recaptcha -->
<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
@endsection


@section('conteudo-view')
<section id="conteudo-view" class="login">
	<div class="login-form">

		{!! Form::open(['route' => 'user.login', 'method' => 'post']) !!}
                <label class="inputData"> 
		<h2>CF - ADB NAVIRAÍ</h2>
                </label>

                    @if(isset($error))
                    <label class="inputData"> 
                    @include('templates.danger-msg-login')
                    </label>
                    @endif
                
		<h3>Acesse o sistema</h3>

		<label class="inputData">
			{!! Form::text('email', null, ['class' =>'form-control', 'placeholder'=>'Email', 'required'])!!}
		</label>

		<label class="inputData">
			{!! Form::password('password',['class' =>'form-control', 'placeholder'=>'Senha', 'required'])!!}
		</label>


		<label < class="g-recaptcha form-group  " data-sitekey="6LfXo1gUAAAAAB2V2SVQCXpHZC4-i5SgQFQcUjAM" data-size="normal" style="transform:scale(0.93);transform-origin:0 0">
		</label><!-- data-theme="dark" valida/acess.php -->

		{!!Form::submit('Entrar',['class' =>'btn btn-primary btn-block']) !!}


		<div class="clearfix inputCheck">
			{!! Form::checkbox('remember', 'true', true) !!} Lembre-me
			<a href="#" class="pull-right">Esqueceu a senha?</a>
		</div>


		{!! Form::close() !!}

		<p class="text-center"><a href="{{ asset('/user')}}" class="linkCad">Criar usuário</a></p>

	</div>
</section>

@endsection
