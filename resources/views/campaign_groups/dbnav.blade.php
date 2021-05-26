
<div class="navbar-customize">
	<a class="{{(@$menu == 'coordinator' ) ? 'active_list' : '' }}"   href="{{ url('campaign-groups/coordinator') }}"><i class="fa fa-fw fa-home"></i>Coordinator</a>
	<a class="{{(@$menu == 'leader' ) ? 'active_list' : '' }}"     href="{{ url('campaign-groups/leader') }}" ><i class="fa fa-fw fa-bank"></i>Leader</a>
	<a class="{{(@$menu == 'member' ) ? 'active_list' : '' }}" href="{{ url('campaign-groups/member') }}" ><i class="fa fa-fw fa-bank"></i>Member</a>
</div>


<style>
.navbar-customize {
  width: 100%;
  overflow: auto;
}

.navbar-customize a {
  float: left;
  margin: 17px;
  padding-bottom:5px;
  text-decoration: none;
  font-size: 18px;
  color: #6e7578;
}

.navbar-customize a:hover {
 border-bottom: solid 3px #2c92cd;
 color:#2c92cd !important; 
}

.active_list {
  border-bottom: solid 3px #2c92cd;
  color:#2c92cd !important;
}

@media screen and (max-width: 500px) {
  .navbar-customize a {
    float: none;
    display: block;
  }
}

.dataTables_filter input { 
width:400px !important; 

}



</style>
