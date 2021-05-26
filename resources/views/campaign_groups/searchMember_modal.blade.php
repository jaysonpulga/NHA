<div id="memberTables_modal" tabindex="-1"  class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h5>Search Member</h5>
		  </div>
		  
		
		  <div class="modal-body">
			<div class="box-body">
				<div class="row">
				
						<table id="memberTables"  class="table table-bordered table-striped" >
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
            </div>
		  </div>
		  
		  
		  <div class="modal-footer">
			  <div class='row'>
				  <div class="col-sm-12">
					  <table width="100%" style="width:100%" border="0" id='table'>
						  <tr>
								<td>	
									<span class="pull-left">
										<button type="button"   type="button"    class="btn btn-default  btn-flat select_member" disabled>
											 Select Member
										</button>
									</span>
								</td>
								<td>
								<span class="pull-right">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</span>	
								<td>
								
						  </tr>
					  </table>
				 </div> 
			 </div>
		 </div>
		  
		 
		</div>
	  </div>
 </div> 
 
 
 
 <script>
$('#btnSearchmember').click(function() {
	
	var city = $("#city option:selected" ).text();
	var city_val = $("#city option:selected" ).val();
	
	var barangay = $("#barangay option:selected" ).text();
	var barangay_val = $("#barangay option:selected" ).val();
	
	
	var coordinator = $("#coordinator option:selected" ).text();
	var coordinator_val = $("#coordinator option:selected" ).val();
	
	
	var leader = $("#leader option:selected" ).text();
	var leader_val = $("#leader option:selected" ).val();
	
	var search_member = $('#search_member').val();
	
	
	$(".select_member").attr("disabled", true);

	if(city_val == "" || barangay_val == "" || coordinator_val == "" || leader_val == "")
	{
		
		swal({
				type:'warning',
				title:"",
				text:"Please Select City , Barangay , Coordinator and Leader"
			})
		
		return false;
	}
	else{


			SearchMember(city_val,barangay_val,search_member);
			
			var options = { backdrop : 'static'}
			$('#memberTables_modal').modal(options); 
	
	}

});

</script>


 <script>
$('#btnSearchmember_EDIT').click(function() {
	
	
	var city_val = $("#barangay_value").val();
	var barangay_val = $("#city_value").val();
	var search_member = $('#search_member').val();

	SearchMember(city_val,barangay_val,search_member);
	
	var options = { backdrop : 'static'}
	$('#memberTables_modal').modal(options); 
	
});
</script>





<script>
var tablemember;

function SearchMember(city_val,barangay_val,search_member)
{
	
	
	
	
		
	tablemember =	 $('#memberTables').DataTable({ 
				"order":[],
				"processing": true, //Feature control the processing indicator.
				"ajax": {
					"url": "{{asset('searchAvailableMembers')}}",
					"data" : {_token: "{{csrf_token()}}",city:city_val,barangay:barangay_val,search:search_member},
					"type": "post",
				},
					"bFilter": true,
				  "bDestroy": true,
				  "aLengthMenu": [[5,7,10, 15, 25, 50, 75, -1], [5,7,10, 15, 25, 50, 75, "All"]],
				  "iDisplayLength": 15,
				  
				  "columns"    : [
					
					
					
					{'data': 'checkbox'},
					{'data': 'vin_number'},
					{'data': 'name'},
					{'data': 'dob'},
					{'data': 'address'},

					
					
				],
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                	
					// Find index of specifi object using findIndex method.
					var objIndex = temporary_array.findIndex(obj => obj.vin_number  === aData["vin_number"] );
				
					 if (objIndex < 0 ) {
						
					}
					else{
						
						$(nRow).remove();
						$(nRow).hide();
					}
				
				
				}	
				

			});
			
    


}
</script>



<script type="text/javascript">
$('#memberTables').on('change', 'input[name="selected_checkbox"]', function() {
		
	
		
	
	if ($(this).is(":checked")) 
	{
		

	  $(this).closest("tr").addClass('addcolorThis');
	   
	  
    }
	else
	{
		
		$(this).closest("tr").removeClass('addcolorThis');		
	}
	
	
	var checked = $('#memberTables').find('input[name=selected_checkbox]:checked').length;
	
	

	if(checked > 0)
	{
		$(".select_member").attr("disabled", false);
	}
	else{
		$(".select_member").attr("disabled", true);
	}
	
	
});  
</script>


<script type="text/javascript">
$(document).on('click', '.select_member', function(e){
e.preventDefault(); 
    

	 
     $.each($("input[name='selected_checkbox']:checked"), function(){
           vin_number =  $(this).attr('data-vin_number');
		   name =  $(this).attr('data-name');
		   
		   
		   	var value =  {
				  "vin_number" : vin_number,
				  "name"	   : name,
				  "action": '<a href="javascript:void(0)" class="delete" data-member_vin_number="'+vin_number+'"  title="Delete Member" ><i class="fa fa-fw fa-ellipsis-v"></i></a>',
				   "id":'',
				   "status": 'AddNew',
				  
				};
	
			if(temporary_array.contains(value)) 
			{ } 
			else
			{ 
				temporary_array.push(value);
			}
				   
		   
     });
	 
		
	
	loadMemberlist();
	
	$('#preview').css('display','block');
	$('#memberTables_modal').modal('hide'); 

});
</script>




<style>
.dataTables_filter input {
    width: 300px !important;
}
.addcolorThis {
    background-color: #f8fb4699 !important;
}
</style>