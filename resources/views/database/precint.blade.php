@extends('layouts.main')
@section('content')

<style>

tr.group,
tr.group:hover {
    background-color: #ddd !important;
	font-size:17px;
}


tr.barangay,
tr.barangay:hover {
    background-color: #f8f1f1 !important;
	font-size:15px;
}

td.align-left{
	padding-left: 15em !important;
	
}

tr.odd td:first-child,
tr.even td:first-child {
    padding-left: 4em;
}
</style>

<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	<div class="col-lg-12">

		<div>@include('database.dbnav')</div>
		<br>

		
		<!-- Main Box-->
		<div class="col-lg-12">
			<div class="box">
					
				
				<!-- /.box-header -->
				<div class="box-body margin">
					<table id="mainDatatables"  class="table  table-hover"  width="100%" >
						<thead>
							<tr>
								<th>Cities</th>
								<th>Barangay</th>
								<th width="50%">City <span style="padding-left:15em">Barangay</span></th>
								<th>Precinct</th>
								<th>Cluster</th>
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



<div id="modal_form" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Edit Cluster</h4>
		  </div>
		  		
		  <div class="modal-body">
			<div class="box-body">
			
				<form id="fomrDetails">
				@csrf
				
				
				<input type="hidden" id="hiddenbarangay" name="hiddenbarangay" />
				<input type="hidden" id="hiddenprecinct" name="hiddenprecinct" />
				
				<div class="form-group">
                  <label>Cluster</label>
                  <input type="text" class="form-control" id="cluster" name="cluster" required>
                </div>
					
				</form>
              </div>
		  </div>
		  <div class="modal-footer">
			
			<span id="cancel"></span>
			<button id="closed" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" form="fomrDetails"  class="btn btn-success btn-primary  btn-flat" id="saveData" >Save</button>
		  </div>
		 
		</div>
	  </div>
 </div>
<!-- /.modal -->











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
	//"serverSide": true,
	// Load data for the table's content from an Ajax source
	/*
	'language': {
            'loadingRecords': '&nbsp;',
           'processing': '<div class="spinner"></div>'
    },
	*/
	
	/*
	 "columnDefs": [
            { "visible": false, "targets": groupColumn }
    ],
	*/
	
	
	 //order: [[0, 'asc'], [1, 'asc']],
    
  
		rowGroup: {
            dataSrc: [ 0, 1 ]
        },
		
        columnDefs: [{
            targets: [ 0,1 ],
            visible: false
        }],
	


	
    "displayLength": 50,
	
	"ajax": {
		"url": "{{asset('getPrecintList')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	"bDestroy": true,
	

	   "drawCallback": function ( settings ) {
		   
			var api = this.api();
			var rows = api.rows( {page:'current'} ).nodes();
			var last=null;
			
			
			var api2 = this.api();
			var rows2 = api2.rows( {page:'current'} ).nodes();
			var lastbarangay=null;

			api.column(0, {page:'current'} ).data().each( function ( group, i ) {
				if ( last !== group ) {
					$(rows).eq( i ).before(
					
						'<tr class="group" data-name=""><td colspan="5">'+group+'</td></tr>'
					);

					last = group;
				}
			});
			
			
			api2.column(1, {page:'current'} ).data().each( function ( barangay, i ) {
				if ( lastbarangay !== barangay ) {
					$(rows2).eq( i ).before(
						'<tr class="barangay"><td colspan="4" class="align-left">'+barangay+'</td></tr>'
					);

					lastbarangay = barangay;
				}
			});
		
			
		},
	

	
	
	"columns"    : [
	
		{'data': 'city'},
		{'data': 'barangay'},
		{'data': 'barangay_placeholder'},
		{'data': 'precint_number'},
		{'data': 'cluster'},
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


<script>
$(document).on('click','.edit',function(){


	var cluster = $(this).attr('data-cluster');
	var barangay = $(this).attr('data-barangay');
	var precinct = $(this).attr('data-precinct');


	$("#hiddenbarangay").val(barangay);
	$("#hiddenprecinct").val(precinct);	
	$("#cluster").val(cluster);	
	
	
	var options = { backdrop : 'static'}
	$('#modal_form').modal(options);   

	

});	
</script>

<script>
// submit html
$("#fomrDetails").on("submit", Htmlsubmit); 
function Htmlsubmit(event){
event.preventDefault();

let form = $(this);
let formData = form.serialize();
	

	$.ajax({
		url: "{{ asset('updatecluster') }}",
		data : formData,
		type : 'POST',
		beforeSend:function(){
			 $("body").waitMe({
				effect: 'timer',
				text: 'Saving  Fields ........ ',
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
					
					
						reload_table();
						$('#modal_form').modal('hide'); 
						
				});
				
			}
			
			
			
			console.log(data);			
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





@endsection
