@extends('layouts.main')
@section('content')

<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">

		<div>@include('PersonalInfo.dbnav')</div>
		<br>
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
					
				<div class="box-header with-border margin">
					<div class="row">
					
						<div class="col-lg-12">
							<span class="pull-right">
						
									<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnAddReligion">
										 ADD RELIGION
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
								
							  <th> Religion </th>
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



<div id="religional_modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Religion</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="ReligionDetail">
				<input type="hidden"  id="action" name="action">
				<input type="hidden"  id="id" name="id">
				@csrf
					<div class="form-group">
					  <label for="account_type" class="col-sm-3 control-label">Enter Religion</label>
					  <div class="col-sm-9">
						<input type="text" required  class="form-control" id="religion" name="religion"  maxlength="80">
					  </div>
					</div>
				
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="ReligionDetail"  class="btn btn-success btn-primary  btn-flat">Save</button>
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
		"url": "{{asset('loadAllReligions')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	 "scrollX":  true,
	  "scrollCollapse": true,
	  "bDestroy": true,
	  "aLengthMenu": [[5,10, 15, 25, 50, 75, -1], [5,10, 15, 25, 50, 75, "All"]],
        "iDisplayLength": 25,
	"columns"    : [
		{'data': 'religion'},
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
$(document).on('click', '.btnAddReligion', function(e){
e.preventDefault(); 

$('#religion').val("");
$('#action').val("create_new");
var options = { backdrop : 'static'}
$('#religional_modal').modal(options); 

});
</script>


<script>
$(document).on('click','.btnEditReligion',function(){


	$('.modal-title').html("Edit Religion");
	$('#action').val("update");

	var id = $(this).attr('data-id');

	$.ajax({
		url:"{{asset('get_religion')}}",
		type:'post',
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data:{id,id},
		success:function(d){
			
			
			$("#religion").val(d.data.name) 
			$("#id").val(d.data.id);
			
			var options = { backdrop : 'static'}
			$('#religional_modal').modal(options);   
		
		}
	})

});	
</script>	


<script>
$(document).on('click','.btnDeleteReligion',function(){


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
				url:"{{asset('deletereligion')}}",
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
$("#ReligionDetail").on("submit", Htmlsubmit); 
function Htmlsubmit(event){
event.preventDefault();

	let form = $(this);
	let formData = form.serialize();
	

	$.ajax({
		url: "{{ asset('saving_religion_info') }}",
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
					title:"Religion Saved!",
					text:""
				}).then(function(){
					
						reload_table();
						$('#religional_modal').modal('hide'); 
						
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
