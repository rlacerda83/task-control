<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

	<title>Car Wash - Login</title>

	{{ Html::style('styles/bootstrap/bootstrap.min.css') }}
	{{ Html::style('styles/libs/font-awesome.css') }}
	{{ Html::style('styles/compiled/theme_styles.css') }}
	{{ Html::style('styles/extras.css') }}
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400">

	{{ Html::script('scripts/jquery.js') }}
	{{ Html::script('scripts/bootstrap.js') }}
	{{ Html::script('scripts/scripts.js') }}
	{{ Html::script('scripts/validation/jquery.validate.min.js') }}
	{{ Html::script('scripts/validation/localization/messages_pt_BR.js') }}


	<script type="text/javascript">
		$(document).ready(function(){
			$('#frmLogin').validate({
				rules: {
					email: {required: true, email: true},
					password: {required: true, minlength: 5}
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement:function(error, element){
					if(element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				},
				highlight: function(element) {
					$(element).closest('.input-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.input-group').removeClass('has-error');
				}
			});
		});
	</script>
</head>
<body id="login-page-full" class="theme-blue-gradient">
	<div id="login-full-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div id="login-box">
						<div id="login-box-holder">
							<div class="row">
								<div class="col-xs-12">
									<header id="login-header">
										<div id="login-logo">
											<img src="img/logo.png" alt=""/>
										</div>
									</header>
									<div id="login-box-inner">
										{!! Form::Open(array('route' => array('login.process'), 'id' => 'frmLogin')) !!}

											@if(Session::has('message'))
												<div class="alert alert-danger">
													<i class="fa fa-times-circle fa-fw fa-lg"></i>
													<span>{{ Session::get('message') }}</span>
												</div>
											@endif

											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-user"></i></span>
												{{ Form::text('email', null, array(
													'class'=>'form-control',
													'maxlength' => 150,
													'placeholder'=>'E-mail',
													'id' => 'email',
												)) }}
											</div>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-key"></i></span>
												{{ Form::input('password', 'password', null, array(
													'class'=>'form-control',
													'maxlength' => 20,
													'placeholder'=>'Senha',
													'id' => 'password'
												)) }}
											</div>

											<div id="remember-me-wrapper">
												<div class="row">
													<a href="{{ url('/password/reset') }}" class="col-xs-6">
														Esqueceu a senha?
													</a>
												</div>
											</div>

											<div class="row">
												<div class="col-xs-12">
													<button type="submit" class="btn btn-success col-xs-12">Entrar</button>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12">
													<p class="social-text">&copy; Mobly Manager - Todos os direitos reservados</p>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>