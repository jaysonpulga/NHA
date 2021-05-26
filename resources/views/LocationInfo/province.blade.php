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
					
						<div class="col-lg-12">
							<span class="pull-right">
						
									<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnAddprovince">
										 ADD PROVINCE
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
								
							
							  <th>Province</th>
							  <th># of Cities/Municipalities</th>
							  <th># of Barangay</th>
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



<div id="province_modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Province</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="ProvinceDetail">
				<input type="hidden"  id="action" name="action">
				<input type="hidden"  id="id" name="id">
				@csrf
					<div class="form-group">
					  <label for="account_type" class="col-sm-3 control-label">Enter Province</label>
					  <div class="col-sm-9">
						<input type="text" required  class="form-control" id="province" name="province" onkeyup="clear_onfield('province')" maxlength="80">
					  </div>
					</div>
				
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="ProvinceDetail"  class="btn btn-success btn-primary  btn-flat">Save</button>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.content -->




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
		"url": "{{asset('loadAllprovince')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	 "scrollX":  true,
	  "scrollCollapse": true,
	  "bDestroy": true,
	  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
        "iDisplayLength": 25,
	"columns"    : [
		
		{'data': 'province'},
		{'data': 'no_city'},
		{'data': 'no_barangay'},
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
$(document).on('click', '.btnAddprovince', function(e){
e.preventDefault(); 

$('#province').val("");
$('#action').val("create_new");
var options = { backdrop : 'static'}
$('#province_modal').modal(options); 

});
</script>


<script>
$(document).on('click','.btnEditprovince',function(){


	$('.modal-title').html("Edit Province");
	$('#action').val("update");

	var id = $(this).attr('data-id');

	$.ajax({
		url:"{{asset('get_province')}}",
		type:'post',
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data:{id,id},
		success:function(d){
			
			
			$("#province").val(d.data.name) 
			$("#id").val(d.data.id);
			
			var options = { backdrop : 'static'}
			$('#province_modal').modal(options);   
		
		}
	})

});	
</script>	



<script>
$(document).on('click','.btnDeleteprovince',function(){


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
				url:"{{asset('deleteprovince')}}",
				type:'post',
				headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
				data:{id,id},
				success:function(data){
					reload_table();
				}
			})
			
			
		
    })
});
</script>



<script>
// submit html
$("#ProvinceDetail").on("submit", Htmlsubmit); 
function Htmlsubmit(event){
event.preventDefault();

	let form = $(this);
	let formData = form.serialize();
	

	$.ajax({
		url: "{{ asset('saving_province_info') }}",
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
					title:"Province Saved!",
					text:""
				}).then(function(){
					
						reload_table();
						$('#province_modal').modal('hide'); 
						
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
