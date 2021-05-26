<div id="modal_form_tally_vote_members" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Tally Windows</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">

				
					<table  class="table  borderless"  width="100%" border="0" >
					
						
						<tr>
							  <td>Campaign Period</td>
							  <td> 
								
								
									<div class="form-group">
										 <input type="text" class="form-control" id="campaign_period_tally" name="campaign_period_tally" readonly />
										 <input type="hidden" class="form-control" id="campaign_period_id_tally" name="campaign_period_id_tally" readonly />
								  </div>
									
							  
							  </td>
						</tr>
						
						
						<tr>
							  <td>Group Id</td>
							  <td> 
									<div class="form-group">
										 <input type="text" class="form-control" id="group_id_tally" name="group_id_tally" readonly />
								  </div>
									
							  
							  </td>
						</tr>
						
						
						
						<tr>
							  <td>City/Municipality</td>
							  <td> 
							  
								  <div class="form-group">
										 <input type="text" class="form-control" id="city_municipality_tally" name="city_municipality_tally" readonly />
								  </div>
									
							  </td>
						</tr>
						
						
						<tr>
							  <td>Coordinator</td>
							  <td>
								  <div class="form-group">
										 <input type="text" class="form-control" id="coordinator_tally" name="coordinator_tally" readonly />
								  </div>
							  </td>
							
						</tr>
						
						<tr>
							  <td>Barangay</td>
							  <td> 
								  <div class="form-group">
										 <input type="text" class="form-control" id="barangay_tally" name="barangay_tally" readonly />
								  </div>
							  
							  </td>
						</tr>
						<tr>
							  <td>Sub-Coordinator</td>
							  <td>
								  <div class="form-group">
										 <input type="text" class="form-control" id="subcoordinator_tally" name="subcoordinator_tally" readonly />
								  </div>
							  </td>
						</tr>
						
						<tr>
							  <td>Purok leader</td>
							  <td>
								  <div class="form-group">			 
										 <input type="text" class="form-control" id="purokleader_tally" name="purokleader_tally" readonly />
								  </div>
							  </td>
						</tr>
						
						
					
						
						
					</table>
					
				</form>						
					<div class="col-lg-12">
						<table  id="tally_voters_member" class="table table-bordered "  border="0" width="100%" >
							<thead>
								<tr>
									  <th>Member List</th>
									  <th>YES</th>
									  <th>NO</th>
									  <th>UNDECIDED</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					
					
		
              </div>
		  </div>
		  <div class="modal-footer">
			
			<center>
				<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Back</button>
				<button type="submit"   class="btn btn-success btn-primary  btn-flat" id="view_preview" >view preview</button>
			</center>
		  
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.modal -->





<!-- PREVIEW -->

<div id="modal_form_preview" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Tally Windows</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			<form id="campaign_tally_periods" >
				
					<table  class="table  borderless"  width="100%" border="0" >
					
						
						<tr>
							  <td>Campaign Period</td>
							  <td>:</td>
							  <td><span id="campaign_period_preview"></span></td>
							  
							  <input type="hidden" id="campaign_period_value" name="campaign_period_value" />
							  <input type="hidden" id="campaign_period_id_value" name="campaign_period_id_value" />
							  
						</tr>
						
						
						<tr>
							  <td>Group Id</td>
							  <td>:</td>
							  <td><span id="groupId_preview"></span></td>
							  <input type="hidden" id="groupId_value" name="groupId_value" />
						</tr>
						
						
						
						<tr>
							  <td>City/Municipality</td>
							  <td>:</td>
							  <td><span id="city_municipality_preview"></span></td>
							  <input type="hidden" id="city_municipality_value" name="city_municipality_value" />
						</tr>
						
						
						<tr>
							  <td>Coordinator</td>
							  <td>:</td>
							  <td><span id="coordinator_preview"></span></td>
							  <input type="hidden" id="coordinator_value" name="coordinator_value" />
							
						</tr>
						
						<tr>
							  <td>Barangay</td>
							  <td>:</td>
							  <td><span id="barangay_preview"></span></td>
							  <input type="hidden" id="barangay_value" name="barangay_value" />
						</tr>
						<tr>
							  <td>Sub-Coordinator</td>
							  <td>:</td>
							  <td><span id="subcoordinator_preview"></span></td>
							  <input type="hidden" id="subcoordinator_value" name="subcoordinator_value" />
						</tr>
						
						<tr>
							  <td>Purok leader</td>
							  <td>:</td>
							  <td><span id="purokleader_preview"></span></td>
							  <input type="hidden" id="purokleader_value" name="purokleader_value" />
						</tr>
						
						
					
						
						
					</table>
					
				
					
					<div class="col-lg-12">
						<table  id="tally_voters_preview" class="table table-bordered "  border="0" width="100%" >
							<thead>
								<tr>
									  <th>Member List</th>
									  <th>YES</th>
									  <th>NO</th>
									  <th>UNDECIDED</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					
					
					<input type="hidden" id="yes_value" name="yes_value" />
					<input type="hidden" id="no_value" name="no_value" />
					<input type="hidden" id="undecided_value" name="undecided_value" />
					
		
              </div>
		  </div>
		  
		   </form>
		  
		  <div class="modal-footer">
			
			<center>
				<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Back</button>
				<button type="submit" form="campaign_tally_periods" class="btn btn-success btn-primary  btn-flat" >Submit</button>
			</center>
		  
		  </div>
		  
		 
		 
		</div>
	  </div>
 </div>
<!-- /.modal -->




<script>
$(document).on('click','.nextaction',function(){
	
	var campaign_period_id = $("#campaign_period option:selected").val();
	var campaign_period_text = $("#campaign_period option:selected").text();
	var groupId = $("#groupId option:selected").val();
	var city_municipality = $("#city_municipality option:selected").val();
	var coordinator = $("#coordinator").val();
	var barangay = $("#barangay option:selected").val();
	var subcoordinator = $("#subcoordinator").val();
	var assign_purokleader = $("#assign_purokleader option:selected").text();

	
	
	$("#campaign_period_tally").val(campaign_period_text);
	$("#campaign_period_id_tally").val(campaign_period_id);
	
	
	
	$("#group_id_tally").val(groupId);
	$("#city_municipality_tally").val(city_municipality);
	$("#coordinator_tally").val(coordinator);
	$("#barangay_tally").val(barangay);
	$("#subcoordinator_tally").val(subcoordinator);
	$("#purokleader_tally").val(assign_purokleader);
	
	

	loadMemberlistTallyVotes();
	
	
	var options = { backdrop : 'static'}
	$('#modal_form_tally_vote_members').modal(options);   

});	
</script>

<script>
var member_preview_array = [];
</script>

<script>
$(document).on('click','#view_preview',function(){

			// reset Array
			member_preview_array = [];
			
			var tablex = $('#tally_voters_member').DataTable();
			
			var count_yes = 0;
			var count_no = 0;
			var count_undecided = 0;

			
			tablex.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
				var res = this.data();
				
				
					//console.log(data.member + " = " + $('input[name="vote_'+data.member+'"]:checked').val() );
					
					var check_yes= "";
					var check_no= "";
					var check_undecided = "";
					
				
					if($('input[name="vote_'+res.member+'"]:checked').val() == "yes")
					{
						
						count_yes = count_yes + 1;
						check_yes = '<i class="fa fa-fw fa-check"></i>';
					}
					else if($('input[name="vote_'+res.member+'"]:checked').val() == "no")
					{
						count_no = count_no + 1;
						check_no = '<i class="fa fa-fw fa-check"></i>';
						
					} 
					else if($('input[name="vote_'+res.member+'"]:checked').val() == "undecided")
					{
						count_undecided = count_undecided + 1;
						check_undecided = '<i class="fa fa-fw fa-check"></i>';
						
					}
					
					
						var value =  {
							
						  "member":res.member,
						  "yes": check_yes,
						  "no":check_no,
						  "undecided":check_undecided,
						};
						
						
						
			
						
						if(member_preview_array.contains(value)) 
						{ } 
						else
						{ 
							member_preview_array.push(value);
						}
					
					
					
				
			});
			
			

			
			
			var newFirstElement =  {
						  "member":"",
						  "yes": "<b>"+count_yes+"</b>", 
						  "no":"<b>"+count_no+"</b>",
						  "undecided":"<b>"+count_undecided+"</b>",
				};
				
			$("#yes_value").val(count_yes);
			$("#no_value").val(count_no);
			$("#undecided_value").val(count_undecided);	
			
			
			var preview_array = [newFirstElement].concat(member_preview_array) // [ 4, 3, 2, 1 ]

			loadPreview(preview_array);
			
			
			
			var campaign_period_id = $("#campaign_period option:selected").val();		
			var campaign_period_text = $("#campaign_period option:selected").text();
			var groupId = $("#groupId option:selected").val();
			var city_municipality = $("#city_municipality option:selected").val();
			var coordinator = $("#coordinator").val();
			var barangay = $("#barangay option:selected").val();
			var subcoordinator = $("#subcoordinator").val();
			var assign_purokleader = $("#assign_purokleader option:selected").text();
			
			
			$("#campaign_period_preview").empty().html(campaign_period_text);
			$("#groupId_preview").empty().html(groupId);
			$("#city_municipality_preview").empty().html(city_municipality);
			$("#coordinator_preview").empty().html(coordinator);
			$("#barangay_preview").empty().html(barangay);
			$("#subcoordinator_preview").empty().html(subcoordinator);
			$("#purokleader_preview").empty().html(assign_purokleader);
			
			
			$("#campaign_period_value").val(campaign_period_text);
			$("#campaign_period_id_value").val(campaign_period_id);
			$("#groupId_value").val(groupId);
			$("#city_municipality_value").val(city_municipality);
			$("#coordinator_value").val(coordinator);
			$("#barangay_value").val(barangay);
			$("#subcoordinator_value").val(subcoordinator);
			$("#purokleader_value").val(assign_purokleader);
			
			
			
				
			var options = { backdrop : 'static'}
			$('#modal_form_preview').modal(options); 
		
			
});	
</script>




<script type="text/javascript">
function loadMemberlistTallyVotes()
{
	
	
		$('#tally_voters_member').DataTable({ 
		
		"data" : temporary_array,	
		"bDestroy": true,
		"bLengthChange": false,
        "paging" :         false,
		"searching": false,   // Search Box will Be Disabled
		"ordering": false,    // Ordering (Sorting on Each Column)will Be Disabled
		"info": false,         // Will show "1 to n of n entries" Text at bottom	 
        "paging":  false,
		"language": {
			  "emptyTable": "<center>No data available in table</center>"
		},
		
		"columns" : [
		
			{'data': 'member'},
			{'data': 'yes'},
			{'data': 'no'},
			{'data': 'undecided'},
			
	
			
		],


	});
	

}
</script>



<script type="text/javascript">
function loadPreview(preview_array)
{
	
	
	$('#tally_voters_preview').DataTable({ 
		
		"data" : preview_array,	
		"bDestroy": true,
		"bLengthChange": false,
        "paging" :         false,
		"searching": false,   // Search Box will Be Disabled
		"ordering": false,    // Ordering (Sorting on Each Column)will Be Disabled
		"info": false,         // Will show "1 to n of n entries" Text at bottom	 
        "paging":  false,
		"language": {
			  "emptyTable": "<center>No data available in table</center>"
		},
		
		"columns" : [
		
			{'data': 'member'},
			{'data': 'yes'},
			{'data': 'no'},
			{'data': 'undecided'},
			
	
			
		],


	});
	

}
</script




<!-- ###########  SUBMIT  ##############  --->

<script>
$("#campaign_tally_periods").on("submit", SaveData); 

function SaveData(event){	
event.preventDefault();
let form = $('#campaign_tally_periods');
let formData = form.serialize();




			$.ajax({
                url: "{{ asset('campaign_tally_periods') }}",
				headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
                type:"POST",
                data:formData,
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
					
                    if(data === "save")
                    {	
				
						reload_table();
						
						
                    }
					else
					{
						swal({
							type:'error',
							title:"Oops..",
							text:"data not save"
						})
					}
                },
                error:function(){
                    $('body').waitMe('hide');
                    swal({
                        type:'error',
                        title:"Oops..",
                        text:"Internal error "
                    })
                }
            })





};
</script>