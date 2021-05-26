<div id="modal_form_set_campaign" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	  <form id="campaignform">
	  @csrf
			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Set Campaign Periods</h4>
			  </div>	
			  <div class="modal-body">
				<div class="box-body">
						<table  class="table  borderless"  width="100%" border="0" >
							<tr>
								  <td>From</td>	
								  <td>:</td>								  
								  <td><input type="text" class="form-control" id="from_date" required name="from_date" /></td>
							</tr>
							<tr>
								  <td>To</td>	
								  <td>:</td>								  
								  <td><input type="text" class="form-control" id="to_date" required name="to_date" /></td>
							</tr>
						</table>
						
						<script>
							 $(function () {
							   var d = new Date();
							   var currMonth = d.getMonth();
							   var currYear = d.getFullYear();
							   var startDate = new Date(currYear, currMonth, 1);
						
							   $("#from_date").datepicker({ format: 'mm/dd/yyyy' , autoclose : true });
							   $("#from_date").datepicker("setDate", startDate);
							   
							   $('#to_date').datepicker({ format: 'mm/dd/yyyy' , autoclose : true });
							   $("#to_date").datepicker("setDate", d);
							});
						</script>
			
				  </div>
			  </div>
			  <div class="modal-footer">
				<center>
					<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit"  class="btn btn-success btn-primary  btn-flat" id="saveData" >Submit</button>
				</center>
			 </div>
			</div>
	 </form>		
	  </div>
</div>
<!-- /.modal -->


<script>
$(document).on('click','.set_campaign',function(){

	var options = { backdrop : 'static'}
	$('#modal_form_set_campaign').modal(options);   

});	
</script>


<script>
// submit form
$("#campaignform").on("submit", submitCampaign); 
function submitCampaign(event){
event.preventDefault();

let form = $('#campaignform');
let formData = form.serialize();

	$.ajax({
		url: "{{ asset('create_campaign_period')}}",
		type:'post',
		data:formData,
		beforeSend:function(){
			 $("body").waitMe({
				effect: 'timer',
				text: 'Saving  ........ ',
				bg: 'rgba(255,255,255,0.90)',
				color: '#555'
			}); 
		},
		success:function(data){
			$('body').waitMe('hide');
			
			if(data === "exist")
			{
				swal({
					type:'warning',
					title:"Oops..",
					text:"Campaign Period already added on the database"
				})
				
			}
			
			
			else if(data === "save")
			{
				
				swal({
					type:'success',
					title:"Data Saved!",
					text:""
				}).then(function(){
				
						location.reload();
						$('#modal_form_set_campaign').modal("hide");
				});
				
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
				
	});

}
</script>
