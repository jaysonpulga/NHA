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

		<div>@include('database.dbnav')</div>
		<br>
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
					
				
				<!-- /.box-header -->
				<div class="box-body margin">
					<table id="mainDatatables"  class="table  table-hover"  width="200%" >
						<thead>
						<tr>
								
							  <th width="8%">Voters ID</th>
							  <th>Name</th>
							  <th width="3%">Gender</th>
							  <th>DOB</th>
							  <th width="3%">Age</th>
							  <th>Mobile</th>
							  <th>Address</th>
							  <th>Barangay</th>
							  <th>City</th>
							  <th>Precinct</th>
							  <th>Cluster</th>
							  <th>IS_SC_TAG</th>
							  <th>IS_PWD_TAG</th>
							  <th>IS_IL_TAG</th>

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




<div id="modal_info" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Voter's Info</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
				
				<span id="editpanel" style="float:right;"><a href="javascript:void(0)" id="editinfo" title="Edit information" style="font-size:20px"><i class="fa fa-fw fa-pencil" ></i></a></span>
				
				
				<form id="fomrDetails">
				@csrf
				<input type="hidden" id="hiddenid" name="hiddenid" />
					<table   class="table  borderless" >
						<tr>
							<td width="35%">Voters ID Number</td>
							<td width="10%">:</td>
							<td> <span id="vin_id_number"></span> </td>							
						</tr>
						
						<tr>
							<td>Name</td>
							<td>:</td>
							<td> <span id="name"></span></td>
						</tr>
						
						<tr>
							<td>Address</td>
							<td>:</td>
							<td><span id="address"></span></td>
						</tr>
						
						<tr>
							<td>City</td>
							<td>:</td>
							<td><span id="city"></span></td>
						</tr>
						
						<tr>
							<td>Barangay</td>
							<td>:</td>
							<td><span id="barangay"></span></td>
						</tr>
						
						<tr>
							<td>Precinct Number</td>
							<td>:</td>
							<td><span id="precint_number"></span></td>
						</tr>
						
					
						<tr>
							<td>Cluster</td>
							<td>:</td>
							<td><span id="cluster"></span></td>
						</tr>
						
						
						<tr>
							<td>Date of Birth</td>
							<td>:</td>
							<td><span id="dob"></span></td>
						</tr>
						
					
						<tr>
							<td>Age</td>
							<td>:</td>
							<td><span id="age"></span></td>
						</tr>
						
						
						<tr>
							<td>Gender</td>
							<td>:</td>
							<td><span id="sex"></span></td>
						</tr>
						
						
						<tr>
							<td>Religion</td>
							<td>:</td>
							<td>
							
							<span id="religion_value"></span>
							<select   class='form-control' id='modify_religions' name='modify_religions'>
								<option value="">Select Religion</option>
								 @foreach ($religions as $religion)
								   <option value="{{$religion->id}}">{{$religion->name}}</option>
								 @endforeach
							</select>
							
							
							</td>
						</tr>
						
						
						<tr>
							<td>Mobile Number</td>
							<td>:</td>
							<td><span id="mobile_number"></span></td>
						</tr>
						
						
					
						<tr>
							<td>Person with Disability</td>
							<td>:</td>
							<td><span id="IS_PWD_TAG"></span></td>
						</tr>
						
						
						<tr>
							<td>Illiterate</td>
							<td>:</td>
							<td><span id="IS_IL_TAG"></span></td>
						</tr>
						
						
						<tr>
							<td>Senior Citizen</td>
							<td>:</td>
							<td><span id="Senior_Citizen"></span></td>
						</tr>
						
							
						<tr>
							<td>Status</td>
							<td>:</td>
							<td><span id="status"></span></td>
						</tr>
						
						
						<tr>
							<td>Remarks</td>
							<td>:</td>
							<td><span id="remarks"></span></td>
						</tr>
						
						
					
					</table>
					
					
				</form>
              </div>
		  </div>
		  <div class="modal-footer">
			
			<span id="cancel"></span>
			<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="fomrDetails"  class="btn btn-success btn-primary  btn-flat" id="saveData" style="display:none">Save</button>
			
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.modal -->







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
		"url": "{{asset('getVotersList')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	 "scrollX":  true,
	  "scrollCollapse": true,
	  "bDestroy": true,
	  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
        "iDisplayLength": 25,
	"columns"    : [
		{'data': 'vin_number'},
		{'data': 'name'},
		{'data': 'gender'},
		{'data': 'dob'},
		{'data': 'age'},
		{'data': 'mobile_number'},
		{'data': 'address'},
		{'data': 'barangay'},
		{'data': 'city_municipality'},
		{'data': 'precint_number'},
		{'data': 'cluster'},
		
		{'data': 'IS_SC_TAG'},
		{'data': 'IS_PWD_TAG'},
		{'data': 'IS_IL_TAG'},
		
	],
	
	
	"fnRowCallback": function( nRow, aData, iDisplayIndex) {

		$(nRow).attr("vin_id_number",aData['complete_vin_number']);
		$(nRow).attr("class",'clickable');
		
		return nRow;
	}
	

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

$('#mainDatatables tbody').on('dblclick','tr', function() {
    var currentRowData = omainDatatables.row(this).data();
    // alert(currentRowData[0]) // wil give you the value of this clicked row and first index (td)
    //your stuff goes here
	
	alert(currentRowData);
});

$('#mainDatatables tbody').on('click', 'tr', function () { 

	var data = table.row( this ). data();
	console.log(data);
	
	
	//alert( 'You clicked on '+data['vin_number']+'\'s row' ); 
	
	
	$("#vin_id_number").empty().html(data['complete_vin_number']);
	$("#name").empty().html(data['complete_name']);
	$("#sex").empty().html(data['gender']);
	$("#address").empty().html(data['complete_address']);
	$("#city").empty().html(data['city_municipality']);
	$("#barangay").empty().html(data['barangay']);
	$("#dob").empty().html(data['dob']);
	$("#age").empty().html(data['age']);
	$("#mobile_number").empty().html(data['mobile_number']);
	$("#precint_number").empty().html(data['precint_number']);
	$("#cluster").empty().html(data['cluster']);
	
	$("#religion_value").empty().html(data['religion']).css('display','block');
	$("#modify_religions").val("").css('display','none');
	
	
	var StatusArray = {0:"ACTIVE", 1:"DECEASED", 2:"ABROAD"};
	
	$("#status").empty().html(StatusArray[data['status']]);
	$("#remarks").empty().html(data['remarks']);
	
	
	if(data['IS_PWD_TAG'] !=null )
	{
		$("#IS_PWD_TAG").empty().html('YES');
	}
	else
	{
		$("#IS_PWD_TAG").empty();
	}
	
	if(data['IS_IL_TAG'] !=null )
	{
		$("#IS_IL_TAG").empty().html('YES');
	}
	else if(data['IS_IL_TAG'] == null){
	      $("#IS_IL_TAG").empty();
	}
	
	
	if(data['age'] >= 60 )
	{
		$("#Senior_Citizen").empty().html('YES');
	}
	else{
	      $("#Senior_Citizen").empty();
	}
	
	
	$("#hiddenid").val(data['complete_vin_number']);
	$('#editinfo').attr('data-address', data['complete_address']);
	$('#editinfo').attr('data-mobile_number', data['mobile_number']);
	$('#editinfo').attr('data-status', data['status']);
	$('#editinfo').attr('data-remarks', data['remarks']);
	$('#editinfo').attr('data-religion_id', data['religion_id']);
	$('#editinfo').attr('data-religion', data['religion']);
	
	$("#editpanel").css('display','block');
	$("#closed").css('display','inline');
	$("#saveData").css('display','none');
	$("#cancel").empty().html('')
	
	
	var options = { backdrop : 'static'}
	$('#modal_info').modal(options); 
	

}); 


</script>



<script type="text/javascript">
$(document).on('click', '#editinfo', function(e){
e.preventDefault(); 

	var address = $(this).data("address");
	var mobile_number = $(this).data("mobile_number");
	var status = $(this).data("status");
	var remarksx = $(this).data("remarks");
	var religion_id = $(this).data("religion_id");
	var religion = $(this).data("religion");
	
	
	$("#address").empty().html('<input type="text"  required class="form-control" id="modify_address" name="modify_address" value="'+address+'" />');
	$("#mobile_number").empty().html('<input type="number"  class="form-control" id="modify_mobile_number" name="modify_mobile_number" value="'+mobile_number+'" />');
	
	$("#status").empty().html('<select  class="form-control" id="modify_status" name="modify_status"><option value="0">ACTIVE</option><option value="1">DECEASED</option><option value="2">ABROAD</option></select>');
	
	
	
	
	
	$("#modify_status").val(status);
	$("#remarks").empty().html("<textarea  class='form-control' id='modify_remarks' name='modify_remarks' rows='4' cols='50'>"+remarksx+"</textarea>");
	
	$("#editpanel").css('display','none');
	$("#closed").css('display','none');
	$("#saveData").css('display','inline');
	
	$("#cancel").empty().html('<button id="cancel_edit_info"  type="button" class="btn btn-default"  data-add="'+address+'"  data-mn="'+mobile_number+'"  data-status="'+status+'" data-remarks="'+remarksx+'" data-religion="'+religion+'"  >Cancel</button>')
	
	
	$("#religion_value").empty().css('display','none');
	$("#modify_religions").val(religion_id).css('display','block');
	
	//$("#religion").empty().html(religion);
	
	
});
</script>

<script type="text/javascript">
$(document).on('click', '#cancel_edit_info', function(e){
e.preventDefault(); 

	var address = $(this).data("add");
	var mobile_number = $(this).data("mn");
	
	var status = $(this).data("status");
	var remarks = $(this).data("remarks");
	
	var religion = $(this).data("religion");
	
	if(religion == 'undefined')
	{
		religion = "";
	}

	   
	$("#address").empty().html(address);
	$("#mobile_number").empty().html(mobile_number);
	
	var StatusArray = {0:"ACTIVE", 1:"DECEASED"};
	
	$("#status").empty().html(StatusArray[status]);
	$("#remarks").empty().html(remarks);
	
	
	
	$("#religion_value").empty().html(religion).css('display','block');
	$("#modify_religions").val("").css('display','none');
	
	$("#editpanel").css('display','block');
	$("#closed").css('display','inline');
	$("#saveData").css('display','none');
	
	
	$("#cancel").empty().html();
	
	
});
</script>



<script>
// submit html
$("#fomrDetails").on("submit", Htmlsubmit); 
function Htmlsubmit(event){
event.preventDefault();

let form = $(this);
let formData = form.serialize();
	

	$.ajax({
		url: "{{ asset('update_information') }}",
		data : formData,
		type : 'POST',
		beforeSend:function(){
			 $("body").waitMe({
				effect: 'timer',
				text: 'UPdating  Details ........ ',
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
					
						location.reload();
				
						$('#modal_info').modal('hide'); 
						
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

	
	
	
}

</script>



@endsection
