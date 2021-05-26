@extends('layouts.main')
@section('content')


<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">

		<div>@include('LocationInfo.dbnav')</div>
		<br>
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
			
			
				<div class="box-header with-border margin">
					<div class="row">
					
						<div class="col-lg-6">
						<div class="row">
							<div class="form-group">
								  <label for="select_barangay" class="col-lg-3 control-label">SELECT BARANGAY</label>

								  <div class="col-lg-6">
									<select class="form-control" id="select_barangay" name="select_barangay">
									  	<option value="">Display All  Barangay</option>
											@foreach($barangays as $barangay)
												<option value="{{$barangay->id}}">{{$barangay->name}}</option>
											@endforeach
									  </select>
								  </div>
							</div>
						</div>	
						</div>
					
						<div class="col-lg-6">
							<span class="pull-right">
						
									<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnAddBarangay">
										 ADD BARANGAY
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
								
							
							  <th>Barangay</th>
							  <th>Cities/Municipalities</th>
							  <th>Province</th>
							  <th># of Precincts</th>
							  <th># of Registered Voters</th>
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



<div id="barangay_modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Barangay</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="BarangayDetail">
				<input type="hidden"  id="action" name="action">
				<input type="hidden"  id="id" name="id">
				@csrf
					<div class="form-group">
					  <label for="account_type" class="col-sm-3 control-label">City</label>
					  <div class="col-sm-9">
						  <select class="form-control" id="city" name="city" required>
							<option value="">Select City</option>
							@foreach($cities as $city)
								<option value="{{$city->id}}">{{$city->name}}</option>
							@endforeach
						  </select>
					  </div>
					</div>
					
					<div style="display:block;margin-bottom:12px;clear:both"></div>
					
					<div class="form-group">
					  <label for="account_type" class="col-sm-3 control-label">Enter Barangay</label>
					  <div class="col-sm-9">
						<input type="text" required  class="form-control" id="barangay" name="barangay"  maxlength="80">
					  </div>
					</div>				
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="BarangayDetail"  class="btn btn-success btn-primary  btn-flat">Save</button>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.content -->


<script>
$('#select_barangay').change(function() {
	
	loadMainAccount();
});
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
	"ajax": {
		"url": "{{asset('loadAllbarangay')}}",
		"data" : {_token: "{{csrf_token()}}",'select_barangay':$("#select_barangay option:selected").val()},
		"type": "post",
	},
	 "scrollX":  true,
	  "scrollCollapse": true,
	  "bDestroy": true,
	  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
      "iDisplayLength": 25,
	  "columns"    : [
		
		{'data': 'barangay'},
		{'data': 'city'},
		{'data': 'province'},
		{'data': 'no_precinct'},
		{'data': 'no_registered_voters'},
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
$(document).on('click', '.btnAddBarangay', function(e){
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


$('#barangay').val("");
$('#action').val("create_new");
var options = { backdrop : 'static'}
$('#barangay_modal').modal(options); 

});
</script>



<script>
$(document).on('click','.btnEditBarangay',function(){


	$('.modal-title').html("Edit Barangay");
	$('#action').val("update");

	var id = $(this).attr('data-id');

	$.ajax({
		url:"{{asset('get_barangay')}}",
		type:'post',
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data:{id,id},
		success:function(d){
			
			
			$("#barangay").val(d.data.name) 
			$("#city").val(d.data.city_id) 
			$("#id").val(d.data.id);
			
			var options = { backdrop : 'static'}
			$('#barangay_modal').modal(options);   
		
		}
	})

});	
</script>	



<script>
$(document).on('click','.btnDeleteBarangay',function(){


	var id = $(this).attr('data-id');

	
    swal({
        type:'question',
        title:'Are you sure?',
        text:'Would you delete this item',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){
		
			
	
			$.ajax({
				url:"{{asset('deletebarangay')}}",
				type:'post',
				headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
				data:{id,id},
				success:function(data){
					location.reload();
				}
			})
			
			
		
    })
});
</script>



<script>
// submit html
$("#BarangayDetail").on("submit", Htmlsubmit); 
function Htmlsubmit(event){
event.preventDefault();

	let form = $(this);
	let formData = form.serialize();
	

	$.ajax({
		url: "{{ asset('saving_barangay_info') }}",
		data : formData,
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
					title:"Barangay Saved!",
					text:""
				}).then(function(){
					
						location.reload();
						$('#city_modal').modal('hide'); 
						
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

	
	
	
}

</script>

@endsection
