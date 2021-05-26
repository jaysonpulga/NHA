    <!-- Logo -->
    
    <a href="{{ url('dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">
		<img src="{{asset('image/minilogo.png')}}" class="user-image" alt="User Image">
	  </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
	   <img src="{{asset('image/logo.png')}}" class="user-image" alt="User Image">
	  </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	  
	  
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">	
		
		
		
			
		
		
		<!-- Control Sidebar Toggle Button -->
		<!--
		<li  id='userManagement'>
            <a href="{{ url('/userManagement') }}" title="User Management"  ><i class="fa fa-gears"></i></a>
          </li>
		 --> 
		  
		  
		  
		    <!-- User Account: style can be found in dropdown.less -->
			
		@if($user_settings  == 1)	
          <li class="dropdown user">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-gears"></i>
            </a>
			 <ul class="dropdown-menu">
				<li><a href="{{ url('/userManagement') }}">User Management</a></li>
				<li><a href="{{ url('/userRole') }}">User Role</a></li>
			  </ul>
    
          </li>
		  @endif;
		  
		
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('image/avatar/photo.png')}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ @Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{asset('public/image/avatar/photo.png')}}" class="img-circle" alt="User Image">
                <p>
					{{ @Auth::user()->name}}
                </p>
              </li>

             <li class="user-footer">				
                 <div class="pull-right">
				   <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
				   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
				</div>
              </li>
			  
			  
            </ul>
          </li>
		 
		  
        </ul>
      </div>
</nav>