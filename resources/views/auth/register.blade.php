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

  
</head>
<body class="hold-transition login-page body">
     <div class="login-nav-logo"><a href=""></a></div>
<div class="login-box">
  <div class="login-logo">
   <!-- <a href="../../index2.html"><b>Admin</b>LTE</a> -->
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Register</p>

   <form method="POST" action="{{ route('register') }}">
    @csrf
     
       <div class="form-group has-feedback">
	   
         <input placeholder="Full Name" id="name" type="text" class="required form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>

			@if ($errors->has('name'))
				<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('name') }}</strong>
				</span>
			@endif
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
		
      </div>
     
     
      <div class="form-group has-feedback">
         <input placeholder="Email" id="email" type="email" required class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                                
                                
               <span id="email_error" class="invalid-feedback disabled" role="alert"></span>                  
                                
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        
        
      </div>
      
      <div class="form-group has-feedback">
   	
		<input  placeholder="Password" id="password" type="password" class="required form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

			@if ($errors->has('password'))
				<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('password') }}</strong>
				</span>
			@endif
			
			
			<span id="password_error" class="invalid-feedback disabled" role="alert"></span>
			
		
		
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
	  
	  
      <div class="form-group has-feedback">
		 <input placeholder="Retype password" id="password_confirmation" type="password" class="required form-control" name="password_confirmation" required>
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
	  
      <div class="row">
           <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat"> {{ __('Register') }} </button>
        </div>
        <!-- /.col -->
        <div class="col-xs-8">

        </div>
       
      </div>
    </form>

	<div><br></div>
    <a href="{{ route('login') }}" class="text-center">I already have an account</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script  src="{{ asset('public/adminlte/plugins/jQuery/jquery-2.2.3.min.js')}}" ></script>



</body>
</html>