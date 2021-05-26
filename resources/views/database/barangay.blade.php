@extends('layouts.main')
@section('content')

<style>

tr.group,
tr.group:hover {
    background-color: #ddd !important;
	font-size:17px;
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
								<th>City / Barangay</th>
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

<script type="text/javascript">
var table;

$(document).ready(function(){
	loadMainAccount();
});

function loadMainAccount()
{
	
	 var groupColumn = 0;
	
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
	
	 "columnDefs": [
            { "visible": false, "targets": groupColumn }
    ],
	
	"order": [[ groupColumn, 'asc' ]],
    "displayLength": 50,
	
	"ajax": {
		"url": "{{asset('getBarangayList')}}",
		"data" : {_token: "{{csrf_token()}}"},
		"type": "post",
	},
	"bDestroy": true,
	
	   "drawCallback": function ( settings ) {
			var api = this.api();
			var rows = api.rows( {page:'current'} ).nodes();
			var last=null;

			api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
				if ( last !== group ) {
					$(rows).eq( i ).before(
						'<tr class="group"><td colspan="2">'+group+'</td></tr>'
					);

					last = group;
				}
			} );
		},

	
	"columns"    : [
	
		{'data': 'city'},
		{'data': 'barangay'},

		
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



@endsection
