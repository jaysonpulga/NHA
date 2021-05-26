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
	<i class="fa fa-gears"></i> User Managements
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
										 ADD NEW ACCOUNT
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
								
							  <th>Name</th>
							  <th>Email</th>
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
			<h4 class="modal-title">Add User</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="FormDetail">
				<input type="hidden"  id="action" name="action">
				<input type="hidden"  id="id" name="id">
				@csrf
				
				
				
				
						<table  class="table  borderless"  width="100%" border="0" >
							<tr>
								  <td width="17%">Enter Name</td>
								  <td width="40%"> 	
									<input type="text" required  class="form-control" id="name" name="name"  maxlength="80" required>
								  </td>
							</tr>
							
							<tr>
								  <td width="17%">Enter Email Address</td>
								  <td width="40%"> 	
									<input type="email" required  class="form-control" id="email" name="email"  maxlength="80" required>
								  </td>
							</tr>
							
							<tr>
							
								<td width="17%">Role</td>
								<td width="40%"> 	
									
												<select  class="form-control" id="role" name="role" required>
													<option value="">-- Select Role --</option>
													 @foreach($user_roles as $role)
														<option value="{{$role->id}}"  >{{$role->name}}</option>
													@endforeach
												</select>
									
								  </td>
							</tr>
							
							
							
							
						
							
							
							<tr>
								  <td width="17%">Password</td>
								  <td width="40%"> 	
									<input type="password"   class="form-control" id="password" name="password"  maxlength="80" >
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
		"url": "{{asset('loadAllUsers')}}",
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
		{'data': 'email'},
		{'data': 'role'},
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
		url:"{{asset('get_user')}}",
		type:'post',
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data:{id,id},
		success:function(d){
			
			
			$("#name").val(d.data.name);
			$("#email").val(d.data.email); 
			$("#password").val(""); 
			$("#id").val(d.data.id);
			
			$("#role").val(d.data.role);
			
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
        text:'Would you delete this user',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){
		
			
	
			$.ajax({
				url:"{{asset('delete_user')}}",
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
		url: "{{ asset('saving_account') }}",
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
