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
									<a href="javascript:void(0)" type="button" class="btn btn-primary  btn-flat create_campaign_group">
										CREATE CAMPAIGN GROUP
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
							  <th>Details</th>
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
			<h4 class="modal-title">Assign Members</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			<form id="formMember" >
				
					<table  class="table  borderless"  width="100%" border="0" >
					
					
						<tr>
							  <td>City/Municipality</td>
							  <td> 
									<select  class="form-control" id="city_municipality" name="city_municipality">
										<option value="">Select City</option>
										 @foreach($city_coordinators as $city)
											<option value="{{$city->city_municipality}}"  data-coordinator="{{$city->name}}" data-coordinator_id="{{$city->coordinator_id}}"  >{{$city->city_municipality}}</option>
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
							  <td>Purok leader</td>
							  <td>
								  <div class="form-group">
										 <select class="form-control select2" id="assign_purokleader"  name="assign_purokleader" style="width: 100%;" disabled>
										 </select>
								  </div>
							  </td>
						</tr>
						
						
						<tr>
							  <td>Assign Members</td>
							  <td>
								  <div class="form-group">
										 <select class="form-control select2" id="assign_member"  name="assign_member" style="width: 100%;" disabled>
										 </select>
								  </div>
							  </td>
							  <td><button type="button"   class="btn btn-success btn-primary  btn-flat" id="addmember" >ADD</button></td>
						</tr>
						
						
					</table>
					
				</form>	
					
					<div id="preview">
						<center>
							<table  id="AssignTable" class="table"  border="0" width="80%" >
								<thead>
									<tr>
										  <th>Purok Leader</th>
										  <th>Group ID</th>
										  <th>Member List</th>
										  <th></th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</center>	
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








<div id="modal_assign_member" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Members List</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			<form id="formMember" >
				
					<table  class="table  borderless"  width="100%" border="0" >
					
						
						<tr>
							  <td width="30%">Group ID</td>
							  <td width="2%">:</td>
							  <td> 
								 <div class="form-group">
										<span id="group_id_value"></span>
								  </div>	
							  </td>
						</tr>
						
						<tr>
							  <td>City/Municipality</td>
							  <td>:</td>
							  <td> 
								 <div class="form-group">
									<span id="city_municipality_value"></span>
								  </div>	
							  </td>
						</tr>
						<tr>
							  <td>Coordinator</td>
							  <td>:</td>
							  <td>
								  <div class="form-group">
									<span id="coordinator_value"></span>
								  </div>
							  </td>
							
						</tr>
						<tr>
							  <td>Barangay</td>
							  <td>:</td>
							  <td> 
								<div class="form-group">
									<span id="barangay_value"></span>
								  </div>
							  
							  </td>
						</tr>
						<tr>
							  <td>Sub-Coordinator</td>
							  <td>:</td>
							  <td>
								   <div class="form-group">
										<span id="subcoordinator_value"></span>
								  </div>
							  </td>
						</tr>
						
						<tr>
							  <td>Purok leader</td>
							  <td>:</td>
							  <td>
								  <div class="form-group">
										<span id="purokleader_value"></span>
								  </div>
							  </td>
						</tr>
						
						
						<tr>
							  <td>Members</td>
							  <td>:</td>
							  <td>
								  <div class="form-group">
								  
										<table  id="memberlist_table" class="table"  border="0" width="50%" >
											<tbody></tbody>
										</table>
										
								  </div>
							  </td>

						</tr>
						
						
					</table>
					

					
				
					
		
              </div>
		  </div>
		  <div class="modal-footer">
			
		
			<!--<button type="button"  class="btn btn-success btn-primary  btn-flat pull-left" id="testdata" >test data</button>-->
			
			<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
		"url": "{{asset('getCampaignGroupList')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	"bDestroy": true,
	"columns"    : [
		{'data': 'city_municipality'},
		{'data': 'coordinator'},
		{'data': 'barangay'},
		{'data': 'subcoordinator'},
		{'data': 'purokleader'},
		{'data': 'details'},
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
$(document).on('click','.create_campaign_group',function(){
	temporary_array = [];
	loadMemberlist();
	
	
	//Reset
	
	$('select[id="city_municipality"]').val("");
	$('select[id="barangay"]').attr('disabled', 'disabled').empty();
	$('select[id="assign_purokleader"]').attr('disabled', 'disabled').empty();
	$('select[id="assign_member"]').attr('disabled', 'disabled').empty();
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
	
	temporary_array = [];
	loadMemberlist();
	
	
	$("#groupid").val("");
	
	if( value == ""){
		
		$('select[id="barangay"]').attr('disabled', 'disabled').empty();
		$('select[id="assign_purokleader"]').attr('disabled', 'disabled').empty();
		$('select[id="assign_member"]').attr('disabled', 'disabled').empty();
		
		$('#subcoordinator').val("");
		$('#coordinator').val("");
		
	}
	
	else
	{
		
		$('select[id="barangay"]').removeAttr("disabled");
		$('#coordinator').val(coordinator);
		
		$('select[id="assign_purokleader"]').attr('disabled', 'disabled').empty();
		$('#subcoordinator').val("");
		$('select[id="assign_member"]').attr('disabled', 'disabled').empty();
		
		$.ajax({
			url: "{{ asset('getSubcoandbarangaybelongtoMunicipality') }}",
			data : {'municipality':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = "<option value=''>Select Barangay</option>";
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.barangay+"\"  data-subcoordinator=\""+value.sub_coordinator+"\"  data-subcoordinator_id=\""+value.subcoordinator_id+"\"      >"+value.barangay+"</option>";
						
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
	var minucipality = $( "#city_municipality option:selected" ).val();

	temporary_array = [];
	loadMemberlist();
	
	
	$('select[id="assign_member"]').attr('disabled', 'disabled').empty();

	if( value == ""){
	
		$('select[id="assign_purokleader"]').attr('disabled', 'disabled');
		$('#subcoordinator').val("");

		
	}
	else
	{
		
		$('select[id="assign_purokleader"]').removeAttr("disabled");
		$('#subcoordinator').val(subcoordinator);
		
		$.ajax({
			url: "{{ asset('getPurokleaderbelongstobarangayNotyetSelected') }}",
			data : {'barangay':value,'city_municipality':minucipality},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = "<option value=''>Select Purok Leader</option>";
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.vin_number+"\"   data-purokleader_id=\""+value.purokleader_id+"\"  >"+value.purok_leader+"</option>";
						
				});

				
				$('#assign_purokleader').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>


<script>
$('#assign_purokleader').change(function() {
	
	var value =  $(this).val(); 	
	var barangay = $( "#barangay option:selected" ).val();

	if( value == ""){
	
		$('select[id="assign_member"]').attr('disabled', 'disabled');


		
	}
	else
	{
		
		$('select[id="assign_member"]').removeAttr("disabled");

		
		$.ajax({
			url: "{{ asset('getUserAssignAsMember') }}",
			data : {'barangay':barangay},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = "<option value=''>Select User</option>";
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.vin_number+"\">"+value.name+"</option>";
						
				});

				
				$('#assign_member').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>

<!---- ################################################  --->


<script type="text/javascript">
function loadMemberlist()
{
    var groupColumn = 0;
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
			
		"columnDefs": [
            { "visible": false, "targets": groupColumn }
		],	
		"order": [[ groupColumn, 'asc' ]],
		
		 "drawCallback": function ( settings ) {
			var api = this.api();
			var rows = api.rows( {page:'current'} ).nodes();
			var last=null;

			api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
				if ( last !== group ) {
					$(rows).eq( i ).before(
						'<tr class="group"><td colspan="2">'+group+'</td></tr>'
					);

					last = group;
				}
			} );
		},
		
		"columns" : [
		{'data': 'purokleader'},
		{'data': 'group_id'},
		{'data': 'member'},
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
</script


<!-- ##################################  ########################################## -->

<script>
function checkmember(member_vin_number) {
  return temporary_array.some(function(el) {
    return el.member_vin_number === member_vin_number;
  }); 
}
</script>


<script>
$(document).on('click','#addmember',function(){
	
	let city_municipality = $( "#city_municipality option:selected" ).val();
	let coordinator = $( "#city_municipality option:selected" ).attr("data-coordinator");
	let barangay = $("#barangay option:selected" ).val();
	let subcoordinator = $( "#barangay option:selected" ).attr("data-subcoordinator");
	let purokleader = $( "#assign_purokleader option:selected" ).text();
	
	
	let member = $( "#assign_member option:selected" ).text();
	let member_vin_number = $( "#assign_member option:selected" ).val();
	
	
	
	let coordinator_id = $( "#city_municipality option:selected" ).attr("data-coordinator_id");
	let subcoordinator_id = $("#barangay option:selected" ).attr("data-subcoordinator_id");
	let purokleader_id = $("#assign_purokleader option:selected" ).attr("data-purokleader_id");
	
	

	let check = checkmember(member_vin_number);
	if(check == true)
	{
		alert('user already assign as member');
		return false;
	}
	
	if(member_vin_number == "" || member_vin_number == undefined)
	{
		alert('assign member  cannot be null!');
		return false;
	}
	
	
	
	let group_id = coordinator_id + '-' + subcoordinator_id + '-' + purokleader_id;
	
	
	var value =  {
				  "city_municipality":city_municipality,
				  "coordinator":coordinator,
				  "barangay":barangay,
				  "subcoordinator" : subcoordinator,
				  "purokleader" : purokleader,
				  "group_id" : group_id,
				  "member" : member,
				  "member_vin_number" : member_vin_number,
				  "action": '<a href="javascript:void(0)" class="delete" data-member_vin_number="'+member_vin_number+'"  title="Delete Purok Leader" ><i class="fa fa-fw fa-ellipsis-v"></i></a>',
				};
	
	if(temporary_array.contains(value)) 
	{ } 
	else
	{ 
		temporary_array.push(value);
	}
	
	// update table and count
	loadMemberlist();
	
	
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
	
	var obj = { members : temporary_array	}
	
	$.ajax({
		url: "{{ asset('create_member') }}",
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
<script>
$(document).on('click','.details',function(){
	
	let group_id = $(this).attr("data-group_id");
	let id = $(this).attr("data-id");
	
	$.ajax({
		url: "{{ asset('getMemberlistAndDetails') }}",
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data : {group_id:group_id,id:id},
		type : 'POST',
		dataType : 'json',
		success:function(data){
			
			
			
			$("#group_id_value").empty().html(data.details.group_id);
			$("#city_municipality_value").empty().html(data.details.city_municipality);
			$("#coordinator_value").empty().html(data.details.coordinator);
			$("#barangay_value").empty().html(data.details.barangay);
			$("#subcoordinator_value").empty().html(data.details.subcoordinator);
			$("#purokleader_value").empty().html(data.details.purokleader);
			
			$("#memberlist_table > tbody").empty();
			
			
				
	
				
						
			
			$.each(data.members, function(i,res){
				$("#memberlist_table > tbody").append("<tr><td>"+res.member+"</td></tr>");
				
				var value =  {
				  "city_municipality":city_municipality,
				  "coordinator":coordinator,
				  "barangay":barangay,
				  "subcoordinator" : subcoordinator,
				  "purokleader" : purokleader,
				  "group_id" : group_id,
				  "member" : member,
				  "member_vin_number" : member_vin_number,
				  "action": '<a href="javascript:void(0)" class="delete" data-member_vin_number="'+member_vin_number+'"  title="Delete Purok Leader" ><i class="fa fa-fw fa-ellipsis-v"></i></a>',
				};
				
				
				if(temporary_array.contains(value)) 
				{ } 
				else
				{ 
					temporary_array.push(value);
				}
				
			});
			
			
		
			
			
			var options = { backdrop : 'static'}
			$('#modal_assign_member').modal(options); 
		},
		error:function(){
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

var member_vin_number = $(this).attr('data-member_vin_number');

// Find index of specifi object using findIndex method.
var objIndex = temporary_array.findIndex(obj => obj.member_vin_number  === member_vin_number );

// remove on array
temporary_array.splice(objIndex,1);


// reload table
loadMemberlist();
	
	
});
</script>



@endsection
