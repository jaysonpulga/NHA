 <div id="modal_form_tally_votes" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Tally Windows</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			<form id="formMember" >
				
					<table  class="table  borderless"  width="100%" border="0" >
					
						
						<tr>
							  <td>Campaign Period</td>
							  <td> 
									<select  class="form-control" id="campaign_period" name="campaign_period">
										<option value=""></option>
										 @foreach($campaign_periods as $cmp)
											<option value="{{$cmp->id}}"   >{{$cmp->from_date}} to {{$cmp->to_date}}</option>
										@endforeach
									</select>
							  
							  </td>
						</tr>
						
						
						<tr>
							  <td>Group Id</td>
							  <td> 
									<select  class="form-control" id="groupId" name="groupId">
										<option value=""></option>
										 @foreach($campaign_groups as $group)
											<option value="{{$group->group_id}}" data-id="{{$group->id}}" >{{$group->group_id}}</option>
										@endforeach
									</select>
							  
							  </td>
						</tr>
						
						
						
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
						
						
					
						
						
					</table>
					
				</form>	
					
					<div class="col-md-6  col-md-offset-3">
						<center>
							<table  id="memberlist_table" class="table table-bordered "  border="0" width="20%" >
								<thead>
									<tr>
										  <th>Member List</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</center>	
					</div>
					
					
		
              </div>
		  </div>
		  <div class="modal-footer">
			
			<center>
				<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button"   class="btn btn-success btn-primary  btn-flat nextaction"  >Next</button>
			</center>
		  
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.modal -->


<script>
$(document).on('click','.tally_votes',function(){
	
	temporary_array = [];
	var options = { backdrop : 'static'}
	$('#modal_form_tally_votes').modal(options);   

});	
</script>


<!--- ######################################################################################## -->

<script>
$('#groupId').change(function() {
	
	temporary_array = [];
	
	
	var group_id =  $(this).val();
	let id = $("#groupId option:selected" ).attr("data-id");
	
		$.ajax({
		url: "{{ asset('getMemberlistAndDetails') }}",
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data : {group_id:group_id,id:id},
		type : 'POST',
		dataType : 'json',
		success:function(data){
			
			if(data.details == null){
				
				$('#city_municipality').val("");
				$('select[id="barangay"]').attr('disabled', 'disabled').empty();
				$('select[id="assign_purokleader"]').attr('disabled', 'disabled').empty();
				$('select[id="assign_member"]').attr('disabled', 'disabled').empty();
				
				$('#subcoordinator').val("");
				$('#coordinator').val("");
				
				
				$("#memberlist_table > tbody").empty();
				
				return false;
			}
			
			
			$('#city_municipality').val(data.details.city_municipality);
			$('#city_municipality').trigger('change');
			
			
			
			setTimeout(function(){ 
				
				
				$('#coordinator').val(data.details.coordinator);
				$('#barangay').val(data.details.barangay);
				$("#subcoordinator").val(data.details.subcoordinator);
				
				
				
				$('#barangay').trigger('change');
				
				
			}, 400);
			
			
			setTimeout(function(){
				
				
				
				
				$("#assign_purokleader").val(data.details.vin_number);
				
				
			
				$("#memberlist_table > tbody").empty();
				$.each(data.members, function(i,res){
					$("#memberlist_table > tbody").append("<tr><td>"+res.member+"</td></tr>");
					
						var value =  {
							
						  "member":res.member,
						  "yes": '<input type="radio" id="yes_'+res.member+'" name="vote_'+res.member+'" value="yes" />',
						  "no":'<input type="radio" id="no_'+res.member+'" name="vote_'+res.member+'" value="no" />',
						  "undecided":'<input type="radio" id="undecided'+res.member+'" name="vote_'+res.member+'" value="undecided" />'
						};
						
						if(temporary_array.contains(value)) 
						{ } 
						else
						{ 
							temporary_array.push(value);
						}
					
					
				});
			
			
			},800);
			
		
			
			
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
$('#city_municipality').change(function() {
	var value =  $(this).val();
	var coordinator = $("#city_municipality option:selected").attr("data-coordinator");

	

	
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
			url: "{{ asset('getPurokleaderbelongstobarangay') }}",
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
		
	let city_municipality = $( "#city_municipality option:selected" ).val();
	let coordinator = $( "#city_municipality option:selected" ).attr("data-coordinator");
	let barangay = $("#barangay option:selected" ).val();
	let subcoordinator = $( "#barangay option:selected" ).attr("data-subcoordinator");
	let purokleader = $( "#assign_purokleader option:selected" ).text();
	let vin_number = $( "#assign_purokleader option:selected" ).val();
	
	let args = { 
				'city_municipality' : city_municipality,
				'coordinator' : coordinator,
				'barangay' : barangay,
				'subcoordinator' : subcoordinator,
				'purokleader' : purokleader,
				'vin_number' : vin_number,
			};
	

	
	doAjax(args,"{{asset('getGroupId') }}").then((data) =>  {
		
		$('#groupId').val(data);
		LoadMemberList(data);
		
	}).catch((err) => console.log(err));
	
	
});
</script>

<script>

function LoadMemberList(group_id)
{
	temporary_array = [];
	
	$.ajax({
		url: "{{ asset('getMemberlistByGroupId') }}",
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		data : {group_id:group_id},
		type : 'POST',
		dataType : 'json',
		success:function(data){
			
			
		
			$("#memberlist_table > tbody").empty();
			
			$.each(data.members, function(i,res){
				
				$("#memberlist_table > tbody").append("<tr><td>"+res.member+"</td></tr>");
				
				
				var value =  {
							
						  "member":res.member,
						  "yes": '<input type="radio" id="yes_'+res.member+'" name="vote_'+res.member+'" value="yes" />',
						  "no":'<input type="radio" id="no_'+res.member+'" name="vote_'+res.member+'" value="no" />',
						  "undecided":'<input type="radio" id="undecided'+res.member+'" name="vote_'+res.member+'" value="undecided" />'
				};
				
				if(temporary_array.contains(value)) 
				{ } 
				else
				{ 
					temporary_array.push(value);
				}
				
				
			});
			
			
		},
		error:function(){
			swal({
				type:'error',
				title:"Oops..",
				text:"Internal error "
			})
		}
				
	});
	
}
</script>
