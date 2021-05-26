
<div class="navbar-customize">
	  <a class="{{(@$menu == 'religion' ) ? 'active_list' : '' }}"   href="{{ url('personalInfo/religion') }}"><i class="fa fa-fw fa-group"></i>Religion</a>
	  <!--
	  <a class="{{(@$menu == 'city' ) ? 'active' : '' }}"     href="{{ url('database/city') }}" ><i class="fa fa-fw fa-bank"></i>City</a>
	  <a class="{{(@$menu == 'barangay' ) ? 'active' : '' }}" href="{{ url('database/barangay') }}" ><i class="fa fa-fw fa-bank"></i>Barangay</a>
	  <a class="{{(@$menu == 'precint' ) ? 'active' : '' }}"  href="{{ url('database/precint') }}" ><i class="fa fa-fw fa-bank"></i>Precinct</a>
	  -->
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
