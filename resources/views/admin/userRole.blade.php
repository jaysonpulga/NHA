@extends('layouts.main')
@section('content')

<style>
.borderless td, .borderless th {
    border: none !important; 
	margin: 20px  !important; 
}
</style>


<section class="content-header">
  <h1>
	<i class="fa fa-gears"></i> User Roles
  </h1>
</section>

<div style="margin-top:20px;margin-bottom:20px"></div>

<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">

	
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
					
				<div class="box-header with-border margin">
					<div class="row">
					
						<div class="col-lg-12">
							<span class="pull-right">
						
									<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnAdd">
										 ADD NEW ROLE
									</a>	
							</span>
						</div>
					</div>
				</div>
					
				
				<!-- /.box-header -->
				<div class="box-body">
					<table id="mainDatatables"  class="table  table-hover"  width="100%" >
						<thead>
						<tr>
								
							  <th>Role</th>
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
			<h4 class="modal-title">Add New Role</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="FormDetail">
				<input type="hidden"  id="action" name="action">
				<input type="hidden"  id="id" name="id">
				@csrf
				
				
				
				
						<table  class="table  borderless"  width="100%" border="0" >
						
						
							<tr>
								  <td width="17%">Enter  Role</td>
								  <td width="40%"> 	
									<input type="text" required  class="form-control" id="name" name="name"  maxlength="80" required>
								  </td>
							</tr>
							
							
							<tr>
								  <td width="17%">&nbsp;</td>
								  <td width="40%"> 	
									
											
										<div class="box-body margin">
											 <strong style="display:block">Access Settings</strong>
											 
											 
											 <input type="checkbox" name="dashboard"  id="dashboardx" value="1" > Dashboard<br>
											 <input type="checkbox" name="voters_database" id="voters_database" value="1" > Voters'Database<br>
											 <input type="checkbox" name="master_data" id="master_data"  value="1"> Master Data<br>
											 <input type="checkbox" name="campaign_group" id="campaign_group"  value="1"> Campaign Group<br>
											 <input type="checkbox" name="election_turnout" id="election_turnout" value="1" > Election Turnout<br>
											 <input type="checkbox" name="election_reports" id="election_reportsx" value="1"> Election Reports<br>
											 <input type="checkbox" name="user_settings" id="user_settings" value="1"> User Settings<br>
		
										</div>
													
								  </td>
							</tr>
							
							
						</table>
				
						
						
						
				
			
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="FormDetail"  class="btn btn-success btn-primary  btn-flat">Save</button>
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
		"url": "{{asset('loadRoles')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	 "scrollX":  true,
	  "scrollCollapse": true,
	  "bDestroy": true,
	  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
      "iDisplayLength": 25,
	"columns"    : [
		{'data': 'name'},
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
$(document).on('click', '.btnAdd', function(e){
e.preventDefault(); 

$('#name').val("");
$('#email').val("");
$('#password').val("");
$('#action').val("create_new");
var options = { backdrop : 'static'}
$('#modal').modal(options); 

});
</script>


<script>
$(document).on('click','.btnEdit',function(){


	$('.modal-title').html("Edit User");
	$('#action').val("update");

	var id = $(this).attr('data-id');

	$.ajax({
		url:"{{asset('get_role')}}",
		type:'post',
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data:{id,id},
		success:function(d){
			
			
			$("#name").val(d.data.name);
			$("#id").val(d.data.id);
			
			
			
				if(d.data.dashboard === 1)
				{
					$("#dashboardx").prop('checked', true);
				}
				else
				{
				
					$("#dashboardx").prop('checked', false);
				}
				
				
				if(d.data.voters_database === 1)
				{
					$("#voters_database").prop('checked', true);
				}
				else
				{
				
					$("#voters_database").prop('checked', false);
				}
				
				
				if(d.data.master_data === 1)
				{
					$("#master_data").prop('checked', true);
				}
				else
				{
				
					$("#master_data").prop('checked', false);
				}
				
				
				if(d.data.campaign_group === 1)
				{
					$("#campaign_group").prop('checked', true);
				}
				else
				{
				
					$("#campaign_group").prop('checked', false);
				}
				
				
				if(d.data.election_turnout === 1)
				{
					$("#election_turnout").prop('checked', true);
				}
				else
				{
				
					$("#election_turnout").prop('checked', false);
				}
				
				
				console.log(d.data.election_reports);
				if(d.data.election_reports === 1)
				{
					$("#election_reportsx").prop('checked', true);
				}
				else
				{

					$("#election_reportsx").prop('checked', false);
				}
				
				
				if(d.data.user_settings === 1)
				{
					$("#user_settings").prop('checked', true);
				}
				else
				{
				
					$("#user_settings").prop('checked', false);
				}
				
				
			
			var options = { backdrop : 'static'}
			$('#modal').modal(options);   
		
		}
	})

});	
</script>	


<script>
$(document).on('click','.btnDelete',function(){


	var id = $(this).attr('data-id');

	
    swal({
        type:'question',
        title:'Are you sure?',
        text:'Would you delete this role',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){
		
			
	
			$.ajax({
				url:"{{asset('delete_role')}}",
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
$("#FormDetail").on("submit", Htmlsubmit); 
function Htmlsubmit(event){
event.preventDefault();

	let form = $(this);
	let formData = form.serialize();
	

	$.ajax({
		url: "{{ asset('saving_role') }}",
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
					title:"Saved!",
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

	
	
	
}

</script>




@endsection
