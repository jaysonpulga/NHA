@extends('layouts.main')
@section('content')


<style>
.navbar-customize {
  width: 100%;
}

.navbar-customize a {
  float: left;
  margin: 17px;
  padding-bottom:5px;
  text-decoration: none;
  font-size: 18px;
  color: #6e7578;
}

.navbar-customize button {
  float: right;
  margin: 17px;
  font-size: 17px;

}

.navbar-customize a:hover {
 border-bottom: solid 3px #2c92cd;
 color:#2c92cd !important; 
}

.active {
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


.borderless td, .borderless th {
    border: none !important; 
	margin: 20px  !important; 
}

</style>




<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">
	
	
		<div class="navbar-customize" style="margin-bottom:10px">
			<div class="row">
				<div class="col-lg-6">
					<a class="active"   href="{{ url('RoleAssignment/Overview') }}"></i>Campaign Results</a>
				</div>
				<div class="col-lg-6">
				  <button type="button" class="btn btn-primary  btn-flat set_campaign"> SET CAMPAIGN PERIOD </button>
				  <button type="button" class="btn btn-primary  btn-flat tally_votes"> TALLY VOTES </button>
				</div>
			</div>	
		</div>
		
		<!-- Main Box 1-->
		<div class="col-lg-12">
			<div class="box">
				<div class="box-body margin">
				
						<div class="col-lg-3"><h1>Overall</h1></div>
						
						<div class="col-lg-3">
							<center>
								<span><h1>1000</h1></span>
								<span><h4>YES</h4></span>
							</center>
						</div>
						
						<div class="col-lg-3">
							<center>
								<span><h1>200</h1></span>
								<span><h4>NO</h4></span>
							</center>
						</div>
						
						<div class="col-lg-3">
							<center>
								<span><h1>30</h1></span>
								<span><h4>UNDECIDED</h4></span>
							</center>
						</div>
						
			
				</div>
				<!-- /.box-body -->
			</div>
		</div>	

		
		<!-- Main Box 2-->
		<div class="col-lg-12">
			<div class="box">
				<div class="box-header with-border margin">
					<div class='row'>
						<div class="col-lg-6">
								
								 <div class="form-group" class="form-inline">
									<span style="white-space: nowrap">
									  <label for="size">Campaign Periods:</label>
									  <select class="form-control" style="width:35%" >
										<option value="">Date</option>
									  </select>
									</span>
								</div>
								
						</div>
					</div>
				</div>	
				<!-- /.box-header -->
				
				<div class="box-body margin">
					<table id="mainDatatables"  class="table  table-hover"  width="100%" >
						<thead>
						<tr>
							  <th>Location</th>
							  <th>YES</th>
							  <th>NO</th>
							  <th>UNDECIDED</th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<!-- /.box-body -->
			
			
			</div>
		</div>
			
			
	</div>	  
</div>
<!-- /.row (main row) -->
</section>
<!-- /.content -->

<script>
var temporary_array = [];
var delete_item_array = [];
</script>

<script>
Array.prototype.contains = function (val) 
{ 

	for(var i = 0; i < this.length; i++ )
	{
		if(JSON.stringify(this[i]) === JSON.stringify(val)) return true;
	}
	return false;
	
} 
</script>


<script>
async function doAjax(args,ajaxurl) {
    let result;

    try {
        result = await $.ajax({
            url: ajaxurl,
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
            type: 'POST',
            data: args
        });

        return result;
    } catch (error) {
        console.error(error);
    }
}
</script>



@include('campaign_periods.set_campaign_period')
@include('campaign_periods.tally_votes')
@include('campaign_periods.tally_vote_members')





@endsection
