<section class="sidebar">
<!-- sidebar: style can be found in sidebar.less -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu">
  


	
	<li>
		<a><i class="fa fa-dashboard"></i><span></span></a>
	</li>

	
	@if($dashboard  == 1)
	<li id='dashboard' >
		<a href="{{ url('dashboard') }}" ><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
	</li>
	@endif
	
	@if($voters_database  == 1)
	<li id='database' >
		<a href="{{ url('database/voters') }}" ><i class="fa fa-fw fa-list"></i> <span>Voters Database</span></a>
	</li>
	@endif
	
	
	@if($master_data  == 1)
	<li id='masterData' class="treeview active">
          <a href="#">
            <i class="fa fa-fw fa-database"></i> <span>Master Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu menu-open" >
            <li id="PersonalInfo" ><a href="{{ url('personalInfo/religion') }}" ><i class="fa fa-fw fa-user"></i> Personal Info</a></li>
            <li id="LocationInfo"><a href="{{ url('locationInfo/province') }}" ><i class="fa fa-fw fa-map-pin"></i> Location Info </a></li>
          </ul>
	</li>
	@endif
	
	 <!--<li id='campaign' ><a href="{{ url('campaign-periods') }}" ><i class="fa fa-fw fa-folder-open"></i> <span>Campaign Group period</span></a></li>-->
	
	@if($campaign_group  == 1)
		<li id='campaign_groups' ><a href="{{ url('campaign-groups/coordinator') }}" ><i class="fa fa-fw fa-users"></i> <span>Campaign Group</span></a></li>
	@endif
	
	@if($election_turnout  == 1)
	<li id='turnout' ><a href="{{ url('/turnout') }}" ><i class="fa fa-fw fa-file-text-o"></i> <span>Election Turnout</span></a></li>
	@endif
	
	@if($election_reports  == 1)
	<li id='election_reports' ><a href="{{ url('/reports') }}" ><i class="fa fa-fw fa-bar-chart-o"></i> <span>Election Reports</span></a></li>
	@endif
	
	
  </ul>
</section>
<!-- /.sidebar -->


<script>
$(document).ready(function(){

	var parent_menu = "<?php echo @$parent_menu ?>";
	var child_menu =  "<?php echo @$child_menu ?>";
	var sub_child_menu =  "<?php echo @$sub_child_menu ?>";

	if(parent_menu != "")
	{	
		$('.sidebar ul li').removeClass('active');
		$('.sidebar ul li#'+parent_menu).addClass('active');
	}
	
	if(child_menu != "")
	{	
		$('.sidebar ul.treeview-menu li').removeClass('active');
		$('.sidebar ul.treeview-menu li#'+child_menu).addClass('active');
	}
	
	
	if(sub_child_menu != "")
	{	
		$('li#'+child_menu+'  ul.treeview-menu li').removeClass('active');
		$('li#'+child_menu+'  ul.treeview-menu li#'+sub_child_menu).addClass('active');
	}
	
});
</script>