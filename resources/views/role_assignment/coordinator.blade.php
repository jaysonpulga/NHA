@extends('layouts.main')
@section('content')

<style>
.borderless td, .borderless th {
    border: none !important; 
	margin: 20px  !important; 
}

.clickable{
	
	cursor: pointer !important; 
}
</style>

<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">

		<div>@include('role_assignment.dbnav')</div>
		<br>
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
					

			<div class="box-header with-border margin">
				<div class='row'>
					<div class="col-lg-12">
						<span class="pull-right">
								<a href="javascript:void(0)" type="button" class="btn btn-primary  btn-flat assign_coordinator">
									Assign Coordinator
								</a>	
						</span> 
					</div>
				</div>
			</div>	
				
				<!-- /.box-header -->
				<div class="box-body margin">
					<table id="mainDatatables"  class="table  table-hover"  width="100%" >
						<thead>
						<tr>
							  <th>City/Municipality</th>
							  <th>Coordinator</th>
							  <th>Vin Number</th>
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



<div id="modal_form" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Assign Coordinator</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			
				
					<table  class="table  borderless"  width="100%" border="0" >
					
						<tr>
							  <td>City/Municipality</td>
							  <td> 
									<select  class="form-control" id="city_municipality" name="city_municipality"  >
										<option value="">Select City</option>
										 @foreach($city_municipality as $city)
											<option value="{{$city->city_municipality}}">{{$city->city_municipality}}</option>
										@endforeach
									</select>
							  
							  </td>
							  <td></td>
						</tr>
						<tr>
							  <td>Assign Coordinator</td>
							  <td>
								  <div class="form-group">
										 <select class="form-control select2" id="assign_coordinator"  name="assign_coordinator" style="width: 100%;" disabled></select>
								  </div>
							  </td>
							  
							  
							  <td><button type="button"   class="btn btn-success btn-primary  btn-flat" id="addData" >ADD</button></td>
						</tr>
						
						<tbody></tbody>
					</table>
					
					
					
					<div id="preview">
						<table  id="AssignTable" class="table"  width="100%" border="0" width="100%" >
							<thead>
								<tr>
									  <th>City/Municipality</th>
									  <th>Assign Coordinator</th>
									  <th></th>
								 </tr>
							 </thead>
							<tbody></tbody>
						</table>
					</div>
					
					
		
              </div>
		  </div>
		  <div class="modal-footer">
			
		
			<!-- <button type="button"  class="btn btn-success btn-primary  btn-flat pull-left" id="testdata" >test data</button> -->
			
			<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button"   class="btn btn-success btn-primary  btn-flat" id="saveData" >Save</button>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.modal -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  });
</script>

<script>
var temporary_array = [];
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


<script type="text/javascript">
var table;

$(document).ready(function(){
	loadMainAccount();
});

function loadMainAccount()
{
	table =  $('#mainDatatables').DataTable({ 
	"order":[],
	"processing": true, //Feature control the processing indicator.
	//"serverSide": true,
	// Load data for the table's content from an Ajax source
	/*
	'language': {
            'loadingRecords': '&nbsp;',
           'processing': '<div class="spinner"></div>'
    },
	*/
	"ajax": {
		"url": "{{asset('getCoordinatorList')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	"bDestroy": true,
	"columns"    : [
		{'data': 'city_municipality'},
		{'data': 'name'},
		{'data': 'vin_number'},
	],

});
	
}
</script>

<script type="text/javascript">
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>



<script>
$(document).on('click','.assign_coordinator',function(){
	
	loadAssign_table();
	var options = { backdrop : 'static'}
	$('#modal_form').modal(options);   

});	
</script>




<script>
$('#city_municipality').change(function() {
	var value =  $(this).val(); 
	
	if( value == ""){
		$('select[id="assign_coordinator"]').attr('disabled', 'disabled');
		$('#assign_coordinator').empty().html("");
		
	}
	else
	{
		
		$('select[id="assign_coordinator"]').removeAttr("disabled");
		
		$.ajax({
			url: "{{ asset('getUserbelongtoMunicipality') }}",
			data : {'municipality':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = "<option value=''>Select User</option>";
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.vin_number+"\">"+value.name+"</option>";
						
				});

				
				$('#assign_coordinator').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>


<script>
$(document).on('click','#addData',function(){
	
	let city_municipality = $( "#city_municipality option:selected" ).val();
	let coordinator_id = $( "#assign_coordinator option:selected" ).val();
	let coordinator_name = $( "#assign_coordinator option:selected" ).text();
	

	let check = CityExists(city_municipality);
	if(check == true)
	{
		alert('City/municipality already exist in the table');
		return false;
	}
	
	if(coordinator_id == "" || coordinator_id == undefined)
	{
		alert('coordinator cannot be null!');
		return false;
	}
	
	
	
	var value =  {
					  "city":city_municipality,
					  "coordinator_name":coordinator_name,
					  "coordinator_id":coordinator_id,
					  "action": '<a href="javascript:void(0)" class="delete" data-coordinator_id="'+coordinator_id+'"  title="Delete coordinator" ><i class="fa fa-fw fa-ellipsis-v"></i></a>',
				};
	
	if(temporary_array.contains(value)) 
	{ } 
	else
	{ 
		temporary_array.push(value);
	}
	
	// update table and count
	loadAssign_table();
	
	
});
</script>	


<script type="text/javascript">
function loadAssign_table()
{
    
	$('#AssignTable').DataTable({ 
		"data" : temporary_array,	
		"bDestroy": true,
		"bLengthChange": false,
        "paging" :         false,
		"searching": false,   // Search Box will Be Disabled
		"ordering": false,    // Ordering (Sorting on Each Column)will Be Disabled
		"info": false,         // Will show "1 to n of n entries" Text at bottom	 
        "paging":  false,
		"language": {
			  "emptyTable": "<center>No data available in table</center>"
			},
	
		"columns" : [
			{'data': 'city'},
			{'data': 'coordinator_name'},
			{'data': 'action'},
		],

	
	});
	

}
</script>


<script>
function CityExists(city) {
  return temporary_array.some(function(el) {
    return el.city === city;
  }); 
}
</script>


<!-- #################################### SAVE ##################################    --->

<script>
$(document).on('click','#testdata',function(){
	
	alert(JSON.stringify(temporary_array));

});
</script>


<script>
$(document).on('click','#saveData',function(){
	
	
	
	if(temporary_array.length == 0)
	{
		  swal({
				type:'warning',
				title:"Table cannot be null",
				text:""
			})
		
		return false;
	}
	
	
	var obj = { city_municipality :	temporary_array	}
	
	$.ajax({
		url: "{{ asset('create_assign_coordinator') }}",
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data : obj,
		type : 'POST',
		dataType : 'json',
		beforeSend:function(){
			 $("body").waitMe({
				effect: 'timer',
				text: 'Saving  ........ ',
				bg: 'rgba(255,255,255,0.90)',
				color: '#555'
			}); 
		},
		success:function(data){
			$('body').waitMe('hide');
			
			
			if(data.status =="save")
			{
				
				swal({
					type:'success',
					title:"Data Saved!",
					text:""
				}).then(function(){
				
						location.reload();
						$('#modal_form').modal("hide");
				});
				
			}
			
			
			
			console.log(data);			
		},
		error:function(){
			$('body').waitMe('hide');
			swal({
				type:'error',
				title:"Oops..",
				text:"Internal error "
			})
		}
				
	});
	

});
</script>



<!--- DELETE  -->
<script>

$(document).on('click','.delete', function(e){

e.preventDefault();

var coordinator_id = $(this).attr('data-coordinator_id');

// Find index of specifi object using findIndex method.
var objIndex = temporary_array.findIndex(obj => obj.coordinator_id  === coordinator_id );

// remove on array
temporary_array.splice(objIndex,1);


// reload table
loadAssign_table();
	
	
});
</script>


@endsection
