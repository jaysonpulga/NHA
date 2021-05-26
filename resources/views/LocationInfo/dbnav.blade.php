
<div class="navbar-customize">
	  <a class="{{(@$menu == 'province' ) ? 'active_list' : '' }}"   href="{{ url('locationInfo/province') }}"><i class="fa fa-fw fa-home"></i>Province</a>
	  <a class="{{(@$menu == 'city' ) ? 'active_list' : '' }}"     href="{{ url('locationInfo/city') }}" ><i class="fa fa-fw fa-bank"></i>City</a>
	  <a class="{{(@$menu == 'barangay' ) ? 'active_list' : '' }}" href="{{ url('locationInfo/barangay') }}" ><i class="fa fa-fw fa-bank"></i>Barangay</a>
	  <a class="{{(@$menu == 'precinct' ) ? 'active_list' : '' }}"  href="{{ url('locationInfo/precinct') }}" ><i class="fa fa-fw fa-bank"></i>Precinct</a>
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
