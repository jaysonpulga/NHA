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
									<a href="javascript:void(0)" type="button" class="btn btn-primary  btn-flat assign_purokleader">
										Assign Purok Leader
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
							  <th>Barangay</th>
							  <th>Sub-coordinator</th>
							  <th>Purok Leader</th>
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
			<h4 class="modal-title">Assign Sub-Coordinator</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			
				
					<table  class="table  borderless"  width="100%" border="0" >
						<tr>
							  <td>City/Municipality</td>
							  <td> 
									<select  class="form-control" id="city_municipality" name="city_municipality">
										<option value="">Select City</option>
										 @foreach($city_coordinators as $city)
											<option value="{{$city->city_municipality}}"  data-coordinator="{{$city->name}}"  >{{$city->city_municipality}}</option>
										@endforeach
									</select>
							  
							  </td>
						</tr>
						<tr>
							  <td>Coordinator</td>
							  <td>
								  <div class="form-group">
										 <input type="text" class="form-control" id="coordinator" name="coordinator" readonly />
								  </div>
							  </td>
							
						</tr>
						<tr>
							  <td>Barangay</td>
							  <td> 
									<select  class="form-control select2" id="barangay" name="barangay" style="width: 100%;"  disabled >
									</select>
							  
							  </td>
						</tr>
						<tr>
							  <td>Sub-Coordinator</td>
							  <td>
								  <div class="form-group">
										 <input type="text" class="form-control" id="subcoordinator" name="subcoordinator" readonly />
								  </div>
							  </td>
						</tr>
						
						<tr>
							  <td>Assign Purok leader</td>
							  <td>
								  <div class="form-group">
										 <select class="form-control select2" id="assign_purokleader"  name="assign_purokleader" style="width: 100%;" disabled>
										 </select>
								  </div>
							  </td>
							  <td><button type="button"   class="btn btn-success btn-primary  btn-flat" id="addpurokleader" >ADD</button></td>
						</tr>
						
						
					</table>
					
					
					
					<div id="preview">
						<table  id="AssignTable" class="table"  width="100%" border="0" width="100%" >
							<thead>
								<tr>
									  <th>Barangay</th>
									  <th>Sub-coordinator</th>
									  <th>Purok Leader</th>
									  <th></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					
					
		
              </div>
		  </div>
		  <div class="modal-footer">
			
		
			<!--<button type="button"  class="btn btn-success btn-primary  btn-flat pull-left" id="testdata" >test data</button>-->
			
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
		"url": "{{asset('getPurokLeaderList')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	"bDestroy": true,
	"columns"    : [
		{'data': 'city_municipality'},
		{'data': 'coordinator'},
		{'data': 'barangay'},
		{'data': 'sub_coordinator'},
		{'data': 'purok_leader'},
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
$(document).on('click','.assign_purokleader',function(){
	
	
	
	temporary_array = [];
	loadPurokleader();
	
	$('select[id="city_municipality"]').val("");
	$('select[id="barangay"]').attr('disabled', 'disabled').empty();
	$('select[id="assign_purokleader"]').attr('disabled', 'disabled').empty();
	$('#subcoordinator').val("");
	$('#coordinator').val("");
	
	
	var options = { backdrop : 'static'}
	$('#modal_form').modal(options);   

});	
</script>



<script>
$('#city_municipality').change(function() {
	var value =  $(this).val();
	var coordinator = $( "#city_municipality option:selected" ).attr("data-coordinator");
	
	if( value == ""){
		
		$('select[id="barangay"]').attr('disabled', 'disabled');
		$('#subcoordinator').val("");
		$('#coordinator').val("");
		
	}
	else
	{
		
		$('select[id="barangay"]').removeAttr("disabled");
		$('#coordinator').val(coordinator);
		
		
		$.ajax({
			url: "{{ asset('getSubcoandbarangaybelongtoMunicipality') }}",
			data : {'municipality':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = "<option value=''>Select Barangay</option>";
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.barangay+"\"  data-subcoordinator=\""+value.sub_coordinator+"\" >"+value.barangay+"</option>";
						
				});

				
				$('#barangay').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>


<script>
$('#barangay').change(function() {
	var value =  $(this).val(); 	
	var subcoordinator = $( "#barangay option:selected" ).attr("data-subcoordinator");
	

	if( value == ""){
	
		$('select[id="assign_purokleader"]').attr('disabled', 'disabled');
		$('#subcoordinator').val("");

		
	}
	else
	{
		
		$('select[id="assign_purokleader"]').removeAttr("disabled");
		$('#subcoordinator').val(subcoordinator);
		
		$.ajax({
			url: "{{ asset('getUserAssignPurokLeadertobarangay') }}",
			data : {'barangay':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = "<option value=''>Select User</option>";
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.vin_number+"\"    >"+value.name+"</option>";
						
				});

				
				$('#assign_purokleader').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>



<!-- ##################################  ########################################## -->

<script>
function checkpurokleader(vin_number) {
  return temporary_array.some(function(el) {
    return el.vin_number === vin_number;
  }); 
}
</script>


<script>
$(document).on('click','#addpurokleader',function(){
	
	let city_municipality = $( "#city_municipality option:selected" ).val();
	let coordinator = $("#coordinator" ).val();
	let barangay = $("#barangay option:selected" ).val();
	let subcoordinator = $("#subcoordinator" ).val();
	
	let vin_number = $("#assign_purokleader option:selected" ).val();
	let purokleader = $( "#assign_purokleader option:selected" ).text();
	

	let check = checkpurokleader(vin_number);
	if(check == true)
	{
		alert('user already assign as purok leader');
		return false;
	}
	
	if(vin_number == "" || vin_number == undefined)
	{
		alert('purok leader cannot be null!');
		return false;
	}
	
	
	
	var value =  {
				  "city_municipality":city_municipality,
				  "coordinator":coordinator,
				  "barangay":barangay,
				  "subcoordinator" : subcoordinator,
				  "purokleader" : purokleader,
				  "vin_number" : vin_number,
				  "action": '<a href="javascript:void(0)" class="delete" data-vin_number="'+vin_number+'"  title="Delete Purok Leader" ><i class="fa fa-fw fa-ellipsis-v"></i></a>',
				};
	
	if(temporary_array.contains(value)) 
	{ } 
	else
	{ 
		temporary_array.push(value);
	}
	
	// update table and count
	loadPurokleader();
	
	
});
</script>	


<script type="text/javascript">
function loadPurokleader()
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
		{'data': 'barangay'},
		{'data': 'subcoordinator'},
		{'data': 'purokleader'},
		{'data': 'action'},


		],

		"fnRowCallback": function( nRow, aData, iDisplayIndex) {
		$(nRow).attr("data-ln_num",aData['ln_num']);
		return nRow;
		},
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                if (aData["action"] === "deleteline") {
                    $(nRow).remove();
                    $(nRow).hide();
                }
        }
		

	});
	

}
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
	
	var obj = { purokleader : temporary_array	}
	
	$.ajax({
		url: "{{ asset('create_assign_purok_leader') }}",
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
				
						reload_table();
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

var vin_number = $(this).attr('data-vin_number');

// Find index of specifi object using findIndex method.
var objIndex = temporary_array.findIndex(obj => obj.vin_number  === vin_number );

// remove on array
temporary_array.splice(objIndex,1);


// reload table
loadPurokleader();
	
	
});
</script>



@endsection
