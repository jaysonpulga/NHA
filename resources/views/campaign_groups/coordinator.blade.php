@extends('layouts.main')
@section('content')


<style>
.addcolor {
    background-color: #f8fb4699 !important;
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
										   <td>
										   
										   <button type="button" class="btn btn-success btn-primary  btn-flat btnShow"  >SHOW</button>
										  
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
										<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnAssignCoordinator">
											 ASSIGN COORDINATOR
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
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Assign Coordinator</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="CoordinatorDetail">
				@csrf
					
					<table  class="table  borderless"  width="100%" border="0" >
						<tr>
							  <td>City/Municipality</td>
							  <td> 
							  
								
									<select  class="form-control" id="city" name="city">
										<option value="">Select City</option>
										 @foreach($cities as $city)
												<option value="{{$city->id}}" >{{$city->name}}</option>
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
							  <td>Assign Coordinator</td>
							  <td> 	
							   <input type="text" class="form-control" id="search_coordinator" name="search_coordinator" />
							  </td>
							  <td><button type="button"   class="btn btn-success btn-primary  btn-flat" id="btnSearchCoordinator" >SEARCH</button></td>
						</tr>
						
					</table>
					
					
					
					<div id="preview">
						<table  id="AssignTable" class="table"  width="100%" border="0" width="100%" >
							<thead>
								<tr>
									  <th></th>
									  <th>Vin Number</th>
									  <th>Name</th>
									  <th>DOB</th>
									  <th>Address</th>
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
			<button type="submit" form="CoordinatorDetail"   class="btn btn-success btn-primary  btn-flat submitCoordinator" disabled>Save</button>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.content -->




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
	"serverSide": true,
	"ajax": {
			url: "{{asset('LoadCoordinator')}}",
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
$(document).on('click', '.btnAssignCoordinator', function(e){
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

$('#search_coordinator').val("");


var options = { backdrop : 'static'}
$('#modal').modal(options); 

});
</script>


<script>
$('#city').change(function() {
	var value =  $(this).val(); 	

	
	if( value == ""){
		
		$('select[id="barangay"]').attr('disabled', 'disabled');
		$('#search_coordinator').val("");
		$('#barangay').val("");
		
	}
	else
	{
		
		$('select[id="barangay"]').removeAttr("disabled");
		
	
		
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
$('#btnSearchCoordinator').click(function() {

	
	var city = $("#city option:selected" ).text();
	var city_val = $("#city option:selected" ).val();
	
	var barangay = $("#barangay option:selected" ).text();
	var barangay_val = $("#barangay option:selected" ).val();
	
	var search_coordinator = $('#search_coordinator').val();
	
	

	if(city_val == "" || barangay_val == "")
	{
		
		swal({
				type:'warning',
				title:"",
				text:"Please Select City And Barangay"
			})
		
		return false;
	}
	else{

			$('#preview').css('display','block');
			$(".submitCoordinator").attr("disabled", true);	
			
			
			 $('#AssignTable').DataTable({ 
				"order":[],
				"processing": true, //Feature control the processing indicator.
				"ajax": {
					"url": "{{asset('searchAvailableCoordinator')}}",
					"data" : {_token: "{{csrf_token()}}",city:city_val,barangay:barangay_val,search:search_coordinator},
					"type": "post",
				},
				"bFilter": false,
				 "scrollX":  true,
				  "scrollCollapse": true,
				  "bDestroy": true,
				  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
				  "iDisplayLength": 10,
				  "columns"    : [
					
					
					
					{'data': 'checkbox'},
					{'data': 'vin_number'},
					{'data': 'name'},
					{'data': 'dob'},
					{'data': 'address'},

					
					
				],
				

			});
	
	}

});
</script>


<script type="text/javascript">
$('#AssignTable').on('change', 'input[name="selected_checkbox"]', function() {
		
	
	if ($(this).is(":checked")) 
	{
		
		 
		
      $('input[name="selected_checkbox"]').prop("checked", false);
	  $('#AssignTable tbody tr').removeClass('addcolor');
      $(this).prop("checked", true);
	  $(this).closest("tr").addClass('addcolor').removeAttr('disabled');	  
	  $('#AssignTable tbody tr td select').attr("disabled", true);	  
	  $(this).closest('tr').find('td select.seleclValue').removeAttr('disabled');
	  
	   $(".submitCoordinator").attr("disabled", false);
	  
    }
	else
	{
		
		$(this).closest("tr").removeClass('addcolor');
		$(this).closest('tr').find('td select.seleclValue').attr("disabled", true);	

		$(".submitCoordinator").attr("disabled", true);	
		
		
	}
	
});  
</script>


<script>
$('#CoordinatorDetail').on('submit', function(event){
event.preventDefault();
	
	
	var city = $("#city option:selected" ).val();	
	var barangay = $("#barangay option:selected" ).val();
	
	
	 let vin_number = $("input[name='selected_checkbox']:checked").attr("data-vin_number");

	
	
	$.ajax({
		url: "{{ asset('AssignCoordinator') }}",
		data : {city:city,barangay:barangay,coordinator_id:vin_number},
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



<!--- DELETE  -->
<script>
$(document).on('click','.btnDeleteCoordinator', function(e){
e.preventDefault();
	
	
	var coordinator_id = $(this).attr('data-coordinator_id');
	
	

	swal({
        type:'question',
        title:'',
        text:'Do you really wish to delete this Coordinator',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){



			
			$.ajax({
				url: "{{ asset('DeleteCoordinator') }}",
				data : {coordinator_id:coordinator_id},
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
							title:"Deleted!",
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

$("#print_Datax").attr('action', "{{asset('printcoordinator')}}");
$("#print_Datax" ).submit();

});
</script>

<!--- ////   PRINT FUNCTION   /////////// -->
 <script type="text/javascript">
$(document).on('click', '.exportData', function(e){
e.preventDefault();

$("#print_Datax").attr('action', "{{asset('exportcoordinator')}}");
$("#print_Datax" ).submit();

});
</script>


<form id="print_Datax" target="_blank" action="" method="POST">
 @csrf
<input type="hidden"  id="print_city" name="print_city" />
<input type="hidden"  id="print_barangay" name="print_barangay" />
<input type="hidden"  id="data_form" name="data_form" />
</form>

@endsection
