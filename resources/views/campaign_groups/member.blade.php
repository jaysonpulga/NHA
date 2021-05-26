@extends('layouts.main')
@section('content')
<style>
.addcolor {
    background-color: #f8fb4699 !important;
}
.modal-body{
    max-height: 70vh;
    overflow-y: auto;
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

		<div>@include('campaign_groups.dbnav')</div>
		<br>
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
			
			
				<div class="box-header with-border margin">
					<div class="row">
						
						<div class="col-lg-8">
							<span>
							
								
								<table  class="table  borderless"  width="100%" border="0" >
									<tr>
										  <td width="17%"> Select City</td>
										  <td width="40%"> 
												<select  class="form-control" id="city_show" name="city_show">
													<option value=""> All City </option>
													 @foreach($cities as $city)
														<option value="{{$city->id}}"    >{{$city->name}}</option>
													@endforeach
												</select>
										  
										  </td>
									</tr>
									<tr>
										  <td>Select Barangay</td>
										  <td> 
												<select class="form-control select2" id="barangay_show" name="barangay_show" style="width: 100%;"  disabled >
													<option value="">All Barangay</option>
												</select>
										  </td>
										   <td><button type="button" class="btn btn-success btn-primary  btn-flat btnShow"  >SHOW</button>
										   
										   
										   </td>
									</tr>
								</table>
								
									
							</span>
						</div>
						
					
						
						<div class="col-lg-4">
							<span class="pull-right">
							<table  class="table  borderless"  width="100%" border="0" >
								<tr>
									<td>
										<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnCreatecampaignGroup">
											CREATE CAMPAIGN GROUP
										</a>
									</td>
								</tr>
								<tr>
									<td>
									   <button type="button" class="btn btn-warning btn-flat exportData">EXPORT</button>
									   <button type="button" class="btn btn-danger btn-flat printData">PRINT</button>   
									</td>
								</tr>
							</table>
						
									
							</span>
						</div>
						
						
						
						
						
						
						
						
					</div>
				</div>
					
				
				<!-- /.box-header -->
				<div class="box-body margin">
					<table id="mainDatatables"  class="table  table-hover"  width="100%" >
						<thead>
						<tr>
								
							  <th>Members</th>	
							  <th># of Members</th>	
							  <th>Leader</th>
							  <th>Coordinator</th>
							  <th>Province</th>
							  <th>Cities/Municipalities</th>
							  <th>Barangay</th>
							  <th>Action</th>
							  
							  

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



<div id="modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Assign Member</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="MemberDetail">
				@csrf
					
					<table  class="table  borderless"  width="100%" border="0" >
						<tr>
							  <td>Group ID</td>
							  <td> 	
								<input type="text" class="form-control" id="GroupId" name="GroupId" readonly />
							  </td>
						</tr>
						<tr>
							  <td>City/Municipality</td>
							  <td> 
									<select  class="form-control" id="city" name="city">
										<option value="">Select City</option>
										 @foreach($cities as $city)
											<option value="{{$city->id}}"    >{{$city->name}}</option>
										@endforeach
									</select>
							  
							  </td>
						</tr>
						
						<tr>
							  <td>Barangay</td>
							  <td> 
									<select class="form-control select2" id="barangay" name="barangay" style="width: 100%;"  disabled ></select>
							  </td>
						</tr>
						
						
						<tr>
							  <td>Coordinator</td>
							  <td> 
									<select class="form-control select2" id="coordinator" name="coordinator" style="width: 100%;"  disabled ></select>
							  </td>
						</tr>
						
						
							<tr>
							  <td>Leader</td>
							  <td> 
									<select class="form-control select2" id="leader" name="leader" style="width: 100%;"  disabled ></select>
							  </td>
						</tr>
						
						<tr>
							  <td>Assign Member</td>
							  <td> 	
							   <input type="text" class="form-control" id="search_member" name="search_member" />
							  </td>
							  <td><button type="button"   class="btn btn-success btn-primary  btn-flat" id="btnSearchmember" >SEARCH</button></td>
						</tr>
						
					</table>
					
					
					
					
					
					
					<br>
					
					
					<div id="preview">
						<table  class="table AssignTable"  width="100%" border="0" width="100%" >
							<thead>
								<tr>
									  <th>Vin Number</th>
									  <th>Name</th>
									  <th></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					
					
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="MemberDetail"   class="btn btn-success btn-primary  btn-flat submitmember" disabled>Save</button>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.content -->




<div id="modal_edit" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Member Details</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
			<form id="MemberDetail_EDIT">
				@csrf
					<table  class="table  borderless"  width="100%" border="0" >
						<tr>
							  <td>Group ID</td>
							  <td> 	
									<span id="GroupId_val" name="GroupId_val" ></span>
							  </td>
						</tr>
						<tr>
							  <td>City/Municipality</td>
							  <td> 
									<span id="city_val" name="city_val"></span>
									<input type="hidden"  id="city_value" name="city_value" />
							  </td>
						</tr>
						
						<tr>
							  <td>Barangay</td>
							  <td> 
									<span id="barangay_val" name="barangay_val" ></span>
									<input type="hidden"  id="barangay_value" name="barangay_value" />
							  </td>
						</tr>
						
						
						<tr>
							  <td>Coordinator</td>
							  <td> 
									<span id="coordinator_val" name="coordinator_val"></span>
							  </td>
						</tr>
						
						
							<tr>
							  <td>Leader</td>
							  <td> 
									<span id="leader_val" name="leader_val"></span>
							  </td>
						</tr>
						
						</tr>
						
						
						<tr>
							  <td>Member</td>
							  <td> 	
							   <input type="text" class="form-control" id="search_member" name="search_member" />
							  </td>
							  <td><button type="button"   class="btn btn-success btn-primary  btn-flat" id="btnSearchmember_EDIT" >SEARCH</button></td>
						</tr>
						
	
					</table>
					
					<br>
					
					<div id="preview" >
						<table  class="table AssignTable"  width="100%" border="0" width="100%"  >
							<thead>
								<tr>
									  <th>Vin Number</th>
									  <th>Name</th>
									  <th></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					
					
					
					
            </div>
			
					
					
			</form>
		  
		  </div>
		  <div class="modal-footer">
				<div style="float:left" >
					<button type="button"  class="btn btn-danger btn-primary  btn-flat PRINT">PRINT</button>
				</div>
				<div style="float:right" >
					<button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
					<button type="submit" form="MemberDetail_EDIT"   class="btn btn-success btn-primary btn-flat" >SAVE</button>
				</div>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.content -->




<!--// div view details modal -->
@include('campaign_groups.searchMember_modal')  
<!--  ~end view details modal -->


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


$(document).on('click', '.btnShow', function(e){
e.preventDefault(); 
	loadMainAccount();
});


$(document).ready(function(){
	loadMainAccount();
});

function loadMainAccount()
{
	table =  $('#mainDatatables').DataTable({ 
	"order":[],
	"processing": true, //Feature control the processing indicator.	
	"ajax": {
			url: "{{asset('loadAllCampaignGroup')}}",
			type    : "POST",
			data : {_token: "{{csrf_token()}}", "city":$("#city_show option:selected" ).val(),"barangay":$("#barangay_show option:selected" ).val() },
			dataSrc : function ( json ) {
				$("#data_form").val(JSON.stringify(json.data));
				
				$("#print_city").val($("#city_show option:selected" ).text());
				$("#print_barangay").val($("#barangay_show option:selected" ).text());
				
				
				console.log(JSON.stringify(json.data));
				
				return json.data;
			},
     },
	
	 "scrollX":  true,
	  "scrollCollapse": true,
	  "bDestroy": true,
	  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
        "iDisplayLength": 25,
	"columns"    : [
		
		{'data': 'details'},
		{'data': 'number_of_members'},
		{'data': 'leader'},
		{'data': 'coordinator'},
		{'data': 'province'},
		{'data': 'city'},
		{'data': 'barangay'},
		{'data': 'action'},
		
		
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


<script type="text/javascript">
$(document).on('click', '.btnCreatecampaignGroup', function(e){
e.preventDefault(); 


var count =  "<?php echo (count($cities));  ?>";
if(count == 1){
	$('select[id="city"]').val("<?php echo $cities[0]->id; ?>");
	$('select[id="city"]').trigger("change");
}
else{
	$('#city').val("");
	$('select[id="barangay"]').attr('disabled', 'disabled');
}

$('#preview').css('display','none');
$('#barangay').val("");
$('#coordinator').val("");
$('#leader').val("");
$('#search_member').val("");
$("#GroupId").val("");	


var options = { backdrop : 'static'}
$('#modal').modal(options); 

});
</script>


<script>
$('#city').change(function() {
	var value =  $(this).val(); 
	
	
	$("#GroupId").val("");	
	$('#preview').css('display','none');
	$(".submitmember").attr("disabled", true);
	
	temporary_array = [];
	delete_item_array =[];
	
	if( value == ""){
		
		$('select[id="barangay"]').attr('disabled', 'disabled');
		$('select[id="coordinator"]').attr('disabled', 'disabled');
		$('select[id="leader"]').attr('disabled', 'disabled');
		$('#search_coordinator').val("");
		$('#barangay').val("");
		$('#coordinator').val("");
		$('#leader').val("");
		
	}
	else
	{
		
		$('select[id="barangay"]').removeAttr("disabled");
		$('#coordinator').val("");
		$('#leader').val("");
		
	
		
		$.ajax({
			url: "{{ asset('getbarangaybelongtoCity') }}",
			data : {'city':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = '<option value="">Select Barangay</option>';
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.id+"\">"+value.name+"</option>";
						
				});

				
				$('#barangay').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>


<script>
$('#barangay').change(function() {
	var barangay =  $(this).val();
	
	
	$("#GroupId").val("");
	$('#preview').css('display','none');
	$(".submitmember").attr("disabled", true);
	
	temporary_array = [];
	delete_item_array =[];

	
	if( barangay == ""){
		
		$('select[id="coordinator"]').attr('disabled', 'disabled');
		$('select[id="leader"]').attr('disabled', 'disabled');
		$('#search_leader').val("");
		$('#coordinator').val("");
		$('#leader').val("");
		
	}
	else
	{
		
		$('select[id="coordinator"]').removeAttr("disabled");
		$('#leader').val("");
	
		
		$.ajax({
			url: "{{ asset('getCoordinatorbelongtocityandbarangay') }}",
			data : {'barangay':barangay},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = '<option value="">Select Coordinator</option>';
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.coordinator_id+"\">"+value.name+"</option>";
						
				});

				
				$('#coordinator').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>


<script>
$('#coordinator').change(function() {
	var coordinator =  $(this).val(); 	
	
	$("#GroupId").val("");
	$('#preview').css('display','none');
	$(".submitmember").attr("disabled", true);
	
	temporary_array = [];
	delete_item_array =[];
	
	if( coordinator == ""){
		
		$('select[id="leader"]').attr('disabled', 'disabled');
		$('#search_member').val("");
		$('#leader').val("");
		
	}
	else
	{
		
		$('select[id="leader"]').removeAttr("disabled");
		
	
		
		$.ajax({
			url: "{{ asset('getLeaderbelongtoCoordinator') }}",
			data : {'coordinator':coordinator},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = '<option value="">Select Leader</option>';
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.leader_id+"\">"+value.name+"</option>";
						
				});

				
				$('#leader').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>


<script>
$('#leader').change(function() {
	
	
	$("#GroupId").val("");	
	$('#preview').css('display','none');
	$(".submitmember").attr("disabled", true);
	
	var city = $("#city option:selected" ).text();
	var city_val = $("#city option:selected" ).val();
	
	var barangay = $("#barangay option:selected" ).text();
	var barangay_val = $("#barangay option:selected" ).val();
	
	
	var coordinator = $("#coordinator option:selected" ).text();
	var coordinator_val = $("#coordinator option:selected" ).val();
	
	
	var leader = $("#leader option:selected" ).text();
	var leader_val = $("#leader option:selected" ).val();
	
	temporary_array = [];
	delete_item_array =[];
	
	if(leader_val == "")
	{
		$("#GroupId").val("");
	}
	else
	{
	
		$("#GroupId").val(city_val+'-'+barangay_val+'-'+coordinator_val+'-'+leader_val);
	}
});
</script>



<script>
$('#MemberDetail').on('submit', function(event){
event.preventDefault();
	
	
	var city = $("#city option:selected" ).text();
	var city_val = $("#city option:selected" ).val();
	
	var barangay = $("#barangay option:selected" ).text();
	var barangay_val = $("#barangay option:selected" ).val();
	
	
	var coordinator = $("#coordinator option:selected" ).text();
	var coordinator_val = $("#coordinator option:selected" ).val();
	
	
	var leader = $("#leader option:selected" ).text();
	var leader_val = $("#leader option:selected" ).val();
	
	
	var GroupId = $("#GroupId").val();
	
		
	if( temporary_array.length == 0){
		
		swal({
			type:'warning',
			text:"Please select data"
		})
		return false;
		
	}
	
	
	$.ajax({
		url: "{{ asset('AssignMembers') }}",
		data : {city:city_val,barangay:barangay_val,coordinator:coordinator_val,leader:leader_val,GroupId:GroupId,array_members:temporary_array},
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		type : 'POST',
		beforeSend:function(){
			
			 $("body").waitMe({
				effect: 'timer',
				text: 'Saving  Details ........ ',
				bg: 'rgba(255,255,255,0.90)',
				color: '#555'
			}); 
		},
		success:function(data){
			$('body').waitMe('hide');
			
			if(data =="save")
			{
				
				swal({
					type:'success',
					title:"Data Saved!",
					text:""
				}).then(function(){
					
						temporary_array = [];
						delete_item_array =[];
						
						reload_table();
						$('#modal').modal('hide'); 
						
				});
				
			}
		
		},
		error:function(){
			$('body').waitMe('hide');
			swal({
				type:'error',
				title:"Oops..",
				text:"Internal error"
			})
		}
				
	});


});
</script>



<script>
$('#MemberDetail_EDIT').on('submit', function(event){
event.preventDefault();
	
	
	
	var GroupId = $("#GroupId_val").text();
	
		
	if( temporary_array.length == 0){
		
		swal({
			type:'warning',
			text:"Please select data"
		})
		return false;
		
	}
	
	
	$.ajax({
		url: "{{ asset('AssignMembersModify') }}",
		data : {GroupId:GroupId,array_members:temporary_array,delete_item_array:delete_item_array},
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		type : 'POST',
		beforeSend:function(){
			
			 $("body").waitMe({
				effect: 'timer',
				text: 'Saving  Details ........ ',
				bg: 'rgba(255,255,255,0.90)',
				color: '#555'
			}); 
		},
		success:function(data){
			$('body').waitMe('hide');
			
			if(data =="save")
			{
				
				swal({
					type:'success',
					title:"Data Saved!",
					text:""
				}).then(function(){
					
						temporary_array = [];
						delete_item_array =[];
						
						reload_table();
						$('#modal_edit').modal('hide'); 
						
				});
				
			}
		
		},
		error:function(){
			$('body').waitMe('hide');
			swal({
				type:'error',
				title:"Oops..",
				text:"Internal error"
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
		url: "{{ asset('getMemberList') }}",
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data : {group_id:group_id,id:id},
		type : 'POST',
		dataType : 'json',
		success:function(data){
			
			
		
			$("#GroupId_val").empty().html(data.details.group_id);
			
			$("#city_val").empty().html(data.details.city);
			$("#city_value").val(data.details.city_id);
			
			$("#barangay_val").empty().html(data.details.barangay);
			$("#barangay_value").val(data.details.barangay_id);
			
			$("#coordinator_val").empty().html(data.details.coordinator);
			$("#leader_val").empty().html(data.details.leader);
			
			temporary_array = [];
			delete_item_array =[];
			
			
			var table = "";
			$.each(data.members, function(i,res){
				//$("#memberlist_table > tbody").append("<tr><td>"+res.member+"</td><td>"+res.vin_number+"</td><td>"+res.delete+"</td></tr>");
		
					table += "<tr><td>"+res.vin_number+"</td><td>"+res.member+"</td></tr>";
		
					/*
					var value =  {
					  "member" :res.member,
					  "vin_number" : res.vin_number,
					  "delete" : res.delete,
					  "id" : res.id,
					  "group_id" : res.group_id,
					};
					*/
				
					  	var value =  {
						   "id"         : res.id,
						   "vin_number" : res.vin_number,
						   "name"	    : res.member,
						   "action"     : '<a href="javascript:void(0)" class="delete" data-member_vin_number="'+res.vin_number+'"  title="Delete Member" ><i class="fa fa-fw fa-ellipsis-v"></i></a>',
						   "status"     : 'current',
						  
						  
						};
			
					if(temporary_array.contains(value)) 
					{ } 
					else
					{ 
						temporary_array.push(value);
					}
				
				
		
			});
			
				loadMemberlist();
				
				$("#data_form_member").val(table);
				$("#data_form_header").val(JSON.stringify(data.details));
				
				
				var options = { backdrop : 'static'}
				$('#modal_edit').modal(options); 
				

		
			
			
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


<script>
var tablex;

function LoadMemberlist_table()
{
	  tablex = $('#memberlist_table').DataTable({ 
			"data" : temporary_array,	
			"processing": true, //Feature control the processing indicator.
			"bFilter": false,
			  "bDestroy": true,
			  "aLengthMenu": [[5,7,10, 15, 25, 50, 75, -1], [5,7,10, 15, 25, 50, 75, "All"]],
			  "iDisplayLength": 7,
			  "columns"    : [
			  
					{'data': 'member'},
					{'data': 'vin_number'},
					{'data': 'delete'},
			],
			

	});
}
</script>


<script type="text/javascript">
function reload_tablex()
{
    tablex.ajax.reload(null,false); //reload datatable ajax 
}
</script>

<script>

$(document).on('click','.DeleteMembers',function(){
	
	var delete_array = [];
	
	$('input[name=delete_row]:checked').each(function() {	
		
		let vin_number = $(this).attr("data-vin_number");
		let id = $(this).attr("data-id");
		let group_id = $(this).attr("data-group_id");
		
		
		objIndex = temporary_array.findIndex((obj => obj.id == id)); 
		temporary_array.splice(objIndex,1);  
		
			var value =  {
			  "vin_number" : vin_number,
			  "id" : id,
			  "group_id" : group_id,
			};
		
			
			if(delete_array.contains(value)) 
			{ } 
			else
			{ 
				delete_array.push(value);
			}

	}); 

	$.GetResponseFunction("{{ asset('deleteRow') }}",{data:delete_array,"_token":$('#token').val()});


	$('#memberlist_table').empty();
	LoadMemberlist_table()
	$("#memberlist_table").DataTable().columns.adjust();
	
});


$.extend({
    GetResponseFunction: function(url, data) {
        // local var
        var theResponse = null;
        // jQuery ajax
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            async: false,
            success: function(respText) {
                theResponse = respText;
            }
        });
        // Return the response text
        return theResponse;
    }
});

</script>

<script type="text/javascript">
$('#memberlist_table').on('change', 'input[name="delete_row"]', function() {

	if ($(this).is(":checked")) 
	{
	
	  $(this).closest("tr").addClass('addcolor');
  
    }
	else
	{		
		$(this).closest("tr").removeClass('addcolor');		
	}
	
});  
</script>





<script type="text/javascript">
function loadMemberlist()
{
	
	// check if assign member
	//checkAssignTable();

	var getTotal  = $('.AssignTable').DataTable({ 
		
		"data" : temporary_array,	
		"order":[],
		"processing": true, //Feature control the processing indicator.
		"bFilter": false,
		"bDestroy": true,
		"aLengthMenu": [[5,7,10, 15, 25, 50, 75, -1], [5,7,10, 15, 25, 50, 75, "All"]],
		"iDisplayLength": 7,
		
		"columns" : [
		{'data': 'vin_number'},
		{'data': 'name'},
		{'data': 'action'},


		],

		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                if (aData["action"] === "deleteline") {
                    $(nRow).remove();
                    $(nRow).hide();
                }
				
				if ( aData['status'] == "AddNew" )
				{
					//$('td', nRow).css('background-color', '#dedddd');
					 $(nRow).css('background-color', 'rgb(255 255 150)');
				}
				
				return nRow;
        }
		

	});
	
	
	// check the count of table
	
	var  checked =  getTotal.data().length;
	
	if(checked > 0)
	{
		$(".submitmember").attr("disabled", false);
	}
	else{
		$(".submitmember").attr("disabled", true);
	}
	

}
</script

<!--- DELETE  -->
<script>

$(document).on('click','.delete', function(e){
e.preventDefault();
	
	
	var vin_number = $(this).attr('data-member_vin_number');
	
	// Find index of specifi object using findIndex method.
	var objIndex = temporary_array.findIndex(obj => obj.vin_number  === vin_number );

	// get the  status value if  is equal to current
	var status = temporary_array[objIndex].status;
	var name = temporary_array[objIndex].name;
	var id = temporary_array[objIndex].id;
	
	

	swal({
        type:'question',
        title:'',
        text:'Do you really wish to delete this Member',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){



		// if status = current delete the current data 
		if(status == "current")
		{

			value_delete =  {"vin_number":vin_number,name:name,id:id};
			
			if(delete_item_array.contains(value_delete)) 
			{ } 
			else
			{ 
				delete_item_array.push(value_delete);
			}
			
			 temporary_array.splice(objIndex,1);   

		}
		else
		{

			// remove on array
			temporary_array.splice(objIndex,1);
		}


	// reload table
	loadMemberlist();
	
	
	});
	
	
});
</script>

<!--- DELETE  -->
<script>
$(document).on('click','.btnDeleteMember', function(e){
e.preventDefault();
	
	
	var group_id = $(this).attr('data-group_id');
	
	

	swal({
        type:'question',
        title:'',
        text:'Do you really wish to delete this Member List',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){



			
			$.ajax({
				url: "{{ asset('DeleteMemberlist') }}",
				data : {group_id:group_id},
				headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
				type : 'POST',
				beforeSend:function(){
					
					 $("body").waitMe({
						effect: 'timer',
						text: 'Deleting  ........ ',
						bg: 'rgba(255,255,255,0.90)',
						color: '#555'
					}); 
				},
				success:function(data){
					$('body').waitMe('hide');
					
					if(data =="deleted")
					{
						
						swal({
							type:'success',
							title:"Deleted",
							text:""
						}).then(function(){
							
								reload_table();
								
						});
						
					}
				
				},
				error:function(){
					$('body').waitMe('hide');
					swal({
						type:'error',
						title:"Oops..",
						text:"Internal error"
					})
				}
			});
	
	});
	
	
});
</script>


<!--- ////   PRINT FUNCTION   /////////// -->
<script type="text/javascript">
$(document).on('click', '.printData', function(e){
e.preventDefault();

$("#print_Datax").attr('action', "{{asset('printmember')}}");
$("#print_Datax" ).submit();

});
</script>

<!--- ////   PRINT FUNCTION   /////////// -->
 <script type="text/javascript">
$(document).on('click', '.exportData', function(e){
e.preventDefault();

$("#print_Datax").attr('action', "{{asset('exportmember')}}");
$("#print_Datax" ).submit();

});
</script>


<form id="print_Datax" target="_blank" action="" method="POST">
 @csrf
<input type="hidden"  id="print_city" name="print_city" />
<input type="hidden"  id="print_barangay" name="print_barangay" />
<input type="hidden"  id="data_form" name="data_form" />
</form>



<!--- ////   PRINT FUNCTION   /////////// -->
<script type="text/javascript">
$(document).on('click', '.PRINT', function(e){
e.preventDefault();

$("#print_member").attr('action', "{{asset('printmemberdetails')}}");
$("#print_member" ).submit();

});
</script>
<form id="print_member" target="_blank" action="" method="POST">
 @csrf
 <input type="hidden"  id="data_form_header" name="data_form_header" />
<input type="hidden"  id="data_form_member" name="data_form_member" />
</form>


@endsection
