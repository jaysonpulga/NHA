@extends('layouts.main')
@section('content')


<style>

.borderless td, .borderless th {
    border: none !important; 
	margin: 20px  !important; 
}


.form-control-numbers {
    display: block;
    width: 60%;
    height: 70px;
    padding: 6px 12px;
    font-size: 50px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}


.small-box>.inner {
    padding: 20px;
}

</style>

<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">

		
		<div class="col-lg-9">
			<!-- MAIN GROUP-->
			  <div class="row">
				<div class="col-lg-6 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-aqua">
					<div class="inner">
					  <h3>{{$getCountCluster}}</h3>
					  <h4>TOTAL CLUSTER</h4>
					</div>
					<div class="icon">
					  <i class="fa fa-fw fa-users"></i>
					</div>
				  </div>
				</div>
				<!-- ./col -->
				<div class="col-lg-6 col-xs-6">
				  <!-- small box -->
				  <div class="small-box bg-green">
					<div class="inner">
					  <h3>{{$getCountPrecintwithTally}}</h3>
					  <h4>TOTAL CLUSTER WITH TALLY</h4>
					</div>
					<div class="icon">
					  <i class="fa fa-fw fa-users"></i>
					</div>
				  </div>
				</div>
				<!-- ./col -->
			  </div>
		</div>
		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
			
			
				<div class="box-header with-border margin">
					<div class="row">

						<div class="col-lg-6">
							<span class="pull-left">
									<button type="button" class="btn btn-warning btn-flat exportData">EXPORT</button>
									 <button type="button" class="btn btn-danger btn-flat printData">PRINT</button>  
							</span>
						</div>
						
						<div class="col-lg-6">
							<span class="pull-right">
						
									<a href="javascript:void(0)" type="button" class="btn btn-success  btn-flat  btn-lg btnTallyTurnout">
										TALLY TURN-OUT
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
							  <th>Cities/Municipalities</th>
							  <th>Barangay</th>
							  <th>Cluster</th>
							  <th>Voter Count</th>
							  <th>Turn Out</th>
							  <th>Variance</th>
							  <th>Action</th>

						</tr>
						</thead>
						<tbody></tbody>
						
    					<tfoot style="border-top:1px solid #000;">
                            <tr>
                              <th></th>
        					   <th></th>
        					   <th></th>
                               <th></th>
        					   <th><span style="font-size:12px;font-weight:500;text-align: right;" id='totalvotercount'></span></th>
        					   <th><span style="font-size:12px;font-weight:500;text-align: right;" id='totalturnout'></span></th>
        					   <th><span style="font-size:12px;font-weight:500;text-align: right;" id='totalvariance'></span></th>
        					   <th></th>
                            </tr>
                        </tfoot>
						
						
						
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
			<h4 class="modal-title">Tally Turn-Out</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="formdetails">
				@csrf
					
					<table  class="table borderless"  width="100%" border="0" >
						<tr>
							  <td>City/Municipality</td>
							  <td> 
									<select  class="form-control select2" id="city" name="city">
										<option value="">Select City</option>
										 @foreach($cities as $city)
											<option value="{{$city->id}}"  data-province_id="{{$city->province_id}}" >{{$city->name}}</option>
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
							  <td>Cluster</td>
							  <td> 
									<select class="form-control select2" id="cluster" name="cluster" style="width: 100%;"  disabled ></select>
							  </td>
						</tr>

					</table>
					
					<hr>
					
					<center>
						<div class="row">
							<div class="col-lg-12">
								
								
								<div class="col-lg-6">
									
									<h3>TOTAL VOTERS</h3>
									<input type="number"  class="form-control-numbers" id="voters_count" name="voters_count" readonly    min="0"   pattern="[0-9]*"  />
									
								</div>
								
								
								<div class="col-lg-6">
									<h3>TOTAL TURN-OUT</h3>
									<input type="number"  class="form-control-numbers" id="turnout" name="turnout" required   min="0"  pattern="[0-9]*"  disabled />
								</div>
								
						
							</div>
						</div>
					</center>
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="formdetails"   class="btn btn-success btn-primary  btn-flat submitTurnout" disabled>Save</button>
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
			<h4 class="modal-title">Modify Tally Turn-Out</h4>
		  </div>
		  <div class="modal-body">
			
			<div class="box-body">
			
                <form id="Editformdetails">
				@csrf
				<input type="hidden"   id="id_val" name="id_val"  />
					
					<table  class="table borderless"  width="100%" border="0" >
						<tr>
							  <td>City/Municipality</td>
							   <td>:</td>
							  <td> 
									<span id="city_val" name="city_val"></span>
							  
							  </td>
						</tr>
						<tr>
							  <td>Barangay</td>
							   <td>:</td>
							  <td> 
									<span id="barangay_val" name="barangay_val"></span>
							  </td>
						</tr>
						<tr>
							  <td>Cluster</td>
							   <td>:</td>
							  <td> 
									<span id="cluster_val" name="cluster_val"></span>
							  </td>
						</tr>

					</table>
					
					<hr>
					
					<center>
						<div class="row">
							<div class="col-lg-12">
								
								
								<div class="col-lg-6">
									
									<h3>TOTAL VOTERS</h3>
									<input type="number"  class="form-control-numbers" id="voters_count_val" name="voters_count_val" readonly    min="0"   pattern="[0-9]*"  />
								</div>
								
								
								<div class="col-lg-6">
									<h3>TOTAL TURN-OUT</h3>
									<input type="number"  class="form-control-numbers" id="turnout_val" name="turnout_val" required   min="0"  pattern="[0-9]*"  />
								</div>
								
						
							</div>
						</div>
					</center>
				</form>
            </div>
		  
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="Editformdetails"   class="btn btn-success btn-primary  btn-flat">Save</button>
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
			"url": "{{asset('LoadTurnout')}}",
			"data" : {_token: "{{csrf_token()}}"},
			"type": "post",
			dataSrc : function ( json ) {
				$("#data_form").val(JSON.stringify(json.data));
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
		

		{'data': 'province'},
		{'data': 'city'},
		{'data': 'barangay'},
		{'data': 'cluster'},
		{'data': 'voters_count'},
		{'data': 'turnout'},
		{'data': 'variance'},
		{'data': 'action'},
		

		
		
	],
	
	
	   "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 

 
            
            Total5 = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            Total6 = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 
                
            
             Total7 = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ); 
            
            // Update footer Credit
            $( api.column( 4 ).footer() ).html( (Total5) );
            
            $( api.column( 5 ).footer() ).html( (Total6) );
            
            $( api.column( 6 ).footer() ).html(  (Total7) );
            



             
             
            
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


<script type="text/javascript">
$(document).on('click', '.btnTallyTurnout', function(e){
e.preventDefault(); 

$('#city').val("");

$('#barangay').val("");
$('#cluster').val("");
$('#voters_count').val("");
$('input[name="turnout"]').val("");


var options = { backdrop : 'static'}
$('#modal').modal(options); 

});
</script>



<script>
$('#city').change(function() {
	var value =  $(this).val();
	

	$('#voters_count').val("");
	$('input[name="turnout"]').val("");
	
	
	$('select[id="precints"]').attr('disabled', 'disabled');
	
	if( value == ""){
		
		$('select[id="barangay"]').attr('disabled', 'disabled');
		
		$('#precints').val("");
		$('#barangay').val("");
		
	}
	else
	{
		
		$('select[id="barangay"]').removeAttr("disabled");
		
	
		
		$.ajax({
			url: "{{ asset('getAvailableBarangayforTurnOut') }}",
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
	var value =  $(this).val(); 	
	

	$('#voters_count').val("");
	$('input[name="turnout"]').val("");

	
	if( value == ""){
		
		$('select[id="cluster"]').attr('disabled', 'disabled');
		$('#cluster').val("");

		
	}
	else
	{
		
		$('select[id="cluster"]').removeAttr("disabled");
		
	
		
		$.ajax({
			url: "{{ asset('getClusterbelongtoBarangay') }}",
			data : {'barangay':value},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				
				let selectValue = '<option value="">Select Cluster</option>';
							
				$.each(res.data, function(index, value) {

					selectValue += "<option value=\""+value.cluster+"\">"+value.cluster+"</option>";
						
				});

				
				$('#cluster').empty().html(selectValue);
							
			}	
		});
		
	}
	
});
</script>




<script>
$('#cluster').change(function() {
	var value =  $(this).val(); 	

	var barangay = $("#barangay option:selected").val();
	var city = $("#city option:selected").val();
	
	
	$('#voters_count').val("");
	$('input[name="turnout"]').val("");
	
	if( value == ""){
		
		$('#voters_count').val("");
		$('#turnout').val("").attr('disabled', 'disabled');

		
	}
	else
	{

		
	
		
		$.ajax({
			url: "{{ asset('getClusterTotalVoters') }}",
			data : {'cluster':value,'barangay':barangay,'city':city},
			headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
			type : 'POST',
			datatype : 'JSON',
			success:function(res){
				
				console.log(res.total_voters);
				$('#voters_count').val(res.total_voters);
				$('input[name="turnout"]').attr("max",res.total_voters).attr('disabled',false);
				
				
				$(".submitTurnout").attr("disabled", false);
				
			}	
		});
		
	}
	
});


</script>



<script>
$('#formdetails').on('submit', function(event){
event.preventDefault();
	
	
	var province_id = $("#city option:selected").data('province_id');
	
	
	let form = $(this);
	let formData = form.serialize()+'&province=' + province_id;
	
	$.ajax({
		url: "{{ asset('save_turnout') }}",
		data : formData,
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
					
						location.reload();
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




<!-- EDIT -->
<script>
$(document).on('click','.btnEdit',function(){


	let id = $(this).attr("data-id");
	
	
	$.ajax({
		url: "{{ asset('editTurnout') }}",
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data : {id:id},
		type : 'POST',
		dataType : 'json',
		success:function(data){
			
			
		

			$("#id_val").val(data.details.id);		
			$("#city_val").empty().html(data.details.city);			
			$("#barangay_val").empty().html(data.details.barangay);
			$("#cluster_val").empty().html(data.details.cluster);
			
			$("#voters_count_val").val(data.details.voters_count);
			$("#turnout_val").val(data.details.turnout).attr("max",data.details.voters_count);
			
			
			
			

			
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
$('#Editformdetails').on('submit', function(event){
event.preventDefault();
	
	
	
	
	
	let form = $(this);
	let formData = form.serialize();
	
	$.ajax({
		url: "{{ asset('update_turnout') }}",
		data : formData,
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
					
						location.reload();
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


<!--- DELETE  -->
<script>
$(document).on('click','.btnDelete', function(e){
e.preventDefault();
	
	
	let id = $(this).attr("data-id");
	
	

	swal({
        type:'question',
        title:'',
        text:'Do you really wish to delete this  Data',
        showCancelButton: true,
        confirmButtonText: 'Yes',
		confirmButtonColor: '#d33',
        cancelButtonText: 'No'
    }).then(function(isConfirm){



			
			$.ajax({
				url: "{{ asset('delete_turnout') }}",
				data : {id:id},
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
							
								location.reload();
								
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

$("#print_Datax").attr('action', "{{asset('print_turnout')}}");
$("#print_Datax" ).submit();

});
</script>

<!--- ////   PRINT FUNCTION   /////////// -->
 <script type="text/javascript">
$(document).on('click', '.exportData', function(e){
e.preventDefault();

$("#print_Datax").attr('action', "{{asset('excel_turnout')}}");
$("#print_Datax" ).submit();

});
</script>


<form id="print_Datax" target="_blank" action="" method="POST">
 @csrf
<input type="hidden"  id="data_form" name="data_form" />
</form>


@endsection
