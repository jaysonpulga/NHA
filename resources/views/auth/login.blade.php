<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->  
  <link rel="stylesheet" href="{{ asset('adminlte/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/AdminLTE.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet"  href="{{ asset('adminlte/plugins/iCheck/square/blue.css')}}">
 
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
	<style>
	.body{
		background-image: url("{{ asset('image/Background-Login.png') }}");
		/*background-repeat : no-repeat;*/
	}
	</style>
  
</head>
<body class="hold-transition login-page body">
<br>
<br>

<div class="login-box">
  <div class="login-logo">
   <!-- <a href="../../index2.html"><b>Admin</b>LTE</a> -->
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  <div class="margin">
  
    <!--<p class="login-box-msg">Sign-in</p>-->
    <div style="margin-bottom:12px">
		<span class="login-box-header1"><b>Welcome back<b></span>
		<span class="login-box-header2">Login to your account</span>
	</div>
	

	
    @if (Session::has('message'))
           <div class="alert alert-danger">{{ Session::get('message') }}</div>
    @endif
	
	
	
    <form method="POST" action="{{ route('login') }}" >
     @csrf
 
	  
	  
	   <div class="form-group">
		  <label for="exampleInputEmail1">Email</label>
		  <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required autofocus />
		  
			@if ($errors->has('email'))
				<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
			@endif
		
		</div>
		
		
		<div class="form-group">
		  <label for="exampleInputPassword1">Password</label>
		  <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
		  
		  @if ($errors->has('password'))
				<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('password') }}</strong>
				</span>
			@endif
		  
		</div>
	  
	  

	  
      <div class="row">
         <!-- /.col -->
		
		<!-- /.col -->
        <div class="col-xs-6">
          <div class="checkbox icheck">
        
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

				<label class="form-check-label" for="remember">
					{{ __('Remember Me') }}
				</label>
 
          </div>
        </div> 
        <div class="col-xs-6">
		  <div class="checkbox pull-right">
			<a href="#">Forgot password?</a>
		  </div>
        </div>
      </div>
	  
	  <div class="social-auth-links text-center">
		
		
		 <button type="submit" class="btn btn-default btn-block btn-flat">Login now</button>
		</div>
	  
    </form>
	
	<div class="large-space"></div>
	
	<!-- <a href="{{ route('register') }}" class="text-center">Register</a> -->

  </div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script  src="{{ asset('public/adminlte/plugins/jQuery/jquery-2.2.3.min.js')}}" ></script>
<!-- Bootstrap 3.3.6 -->
<script  src="{{ asset('public/adminlte/bootstrap/js/bootstrap.min.js')}}" ></script>
<!-- iCheck -->
<script  src="{{ asset('public/adminlte/plugins/iCheck/icheck.min.js')}}"></script>

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>



</body>
</html>