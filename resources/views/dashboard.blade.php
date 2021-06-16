@extends('layouts.main')
@section('content')
<style>

.panel-heading {
  padding: 0;
	border:0;
	background-color: #fff !important;
}
 .panel-title>a, .panel-title>a:active{
	display:block;
	padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
  word-spacing:3px;
	text-decoration:none;
}

 .panel-heading  a:before {
   font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;
  
}
 .panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
} 

.borderless2 td, .borderless2 th {
    border: none !important; 
}

.small-box>.inner {
    padding: 20px;
}

.sr-only-text {
	position: relative !important; 
	padding-top:10px
}
.progress-bar-female,{
    background-color: #f16fb9 !important; 
}
.progress-bar-male,{
    background-color: #f16fb9;
}

.progress-bar-pink, .progress-bar-info {
    background-color: #f16fb9 !important; 
}

.progress-bar-blue, .progress-bar-info {
    background-color: #349bdd !important;
	
}

.progress-pink {
    height: 30px !important; 
	margin-bottom: 1px !important; 
	background-color: #fec7e6;
}

.progress-blue {
    height: 30px !important; 
	margin-bottom: 1px !important; 
	background-color: #caeaff;
}

.progress-bar {
    font-size: 15px !important; 
    line-height: 30px !important; 
}

.centerText{
   text-align: center;
}

.centerText2{
   text-align: center;
   font-size : 17px;
   font-weight: bold;
   
}

.ageCount{
	font-size:37px !important; 
}

.young_age{
	
	border: 8px solid #4698d4;
}

.middle_age{
	
	border: 8px solid #01a096;
}

.old_age{
	
	border: 8px solid #e82b96;
}

.senior_citizen{
	
	border: 8px solid #81ae0a;
}


.circle_main
{
	width : 100%;
	height : 100%;
	border-radius : 50%;
	margin : 0;
	padding : 0;
}

.circle_container
{
	width : 80px;
	height : 80px;
	margin : 0;
	padding : 0;
	margin-top:20px;
	margin-bottom:20px;
}

.circle_text_container
{
	width : 70%;
	height : 70%;
	max-width : 70%;
	max-height : 70%;
	margin : 0;
	padding : 0;
	position : relative;
	top : 15%;
	transform-style : preserve-3d;
}
.circle_text
{
	font-size: 14px;
	text-align : center;
	font-weight: bold;
	position : relative;
	top : 50%;
	transform : translateY(-50%);
}

#ttble td {
  padding: 12px;
}


.progress-bar-male-voters {
    height: 30% !important;
	position:absolute !important;
	background-color : #002137 !important;
	 opacity: 0.9 !important;
}

.progress-bar-female-voters {
    height: 30% !important;
	position:absolute !important;
	background-color : #970658 !important;
	 opacity: 0.9 !important;
}

</style>

<div style="padding-top:20px;padding-bottom:5px"></div>

<!-- Main content -->
<section class="content">
<!-- Main row -->
<div class="row">
	
	<?php

		$TotalprojectedVoters_percentage = number_format (($TotalprojectedVoters * 100) / $TotalVoters ,2);

		$age18to30_percentage = number_format (($ChartAge->Young * 100) / $TotalVoters ,2);
		$age31to45_percentage = number_format (($ChartAge->Middle * 100) / $TotalVoters ,2);
		$age46to59_percentage = number_format (($ChartAge->OLD * 100) / $TotalVoters ,2);
		$age60plus_percentage = number_format (($ChartAge->SeniorCitizens * 100) / $TotalVoters ,2);

		$male_percentage = number_format (($ChartSex->Male * 100) / $TotalVoters ,2);
		$female_percentage = number_format (($ChartSex->Female * 100) / $TotalVoters ,2);

		$voterMemberMale_percentage = number_format (($ChartSex->voterMemberMale * 100) / $TotalVoters ,2);
		$voterMemberFemale_percentage = number_format (($ChartSex->voterMemberFemale * 100) / $TotalVoters ,2);
		
		
	?>


	<div class="col-lg-8">

		  <div class="box box-default">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-3">
						<h4>Projected Votes</h4>
						<center><img src="{{ asset('image/nominee.png') }}" alt="nominee" width="70%"  class="img-responsive"/></center>
					</div>
					<div class="col-lg-4">
						<br>
						<div>
							<span style="font-size:30px;font-weight:bold;color:#46bdc3">{{ $TotalVoters }}</span><br>
							<span style="font-size:20px;font-weight:normal;color:#5a5858">Total Voters</span>
						</div>
						<div>
							<span style="font-size:30px;font-weight:bold;color:#007bc9">{{ $TotalprojectedVoters }}</span><br>
							<span style="font-size:20px;font-weight:normal;color:#5a5858">Total Projected Votes</span>
						</div>
					</div>
					<div class="col-lg-5">
						<div id="donut-chart" style="height:130px;"></div>
						<center style="margin-top:5px">
							<span style="font-size:18px;color:#007bc9;font-weight:bold;" id="percentvoters">{{$TotalprojectedVoters_percentage}}% Total Projected Votes</span>
						</center>
					</div>
				</div>
			</div>
		</div>


		<!-- VOTERS AGE -->
	  <div class="box box-default">
		<div class="box-header">
		  <h4>Voters' Age</h4>
		</div>
		<div class="box-body">
		
			<table id="ttble" width="100%" border=0 >
				<tr>
					<td class="centerText">
					<center>
						<img src="{{ asset('image/age18-30.png') }}" alt="age18-30" width="55%" class="img-responsive"  />
					</center>	
					</td>
					<td class="centerText">
					<center>
						<img src="{{ asset('image/age31-45.png') }}" alt="age18-30" width="55%" class="img-responsive" />
					</center>		
					</td>
					<td class="centerText">
					<center>
						<img src="{{ asset('image/age46-59.png') }}" alt="age18-30" width="55%" class="img-responsive" />
					</center>		
					</td>
					<td class="centerText">
					<center>
						<img src="{{ asset('image/age60+.png') }}" alt="age18-30" width="55%" class="img-responsive"  />
					</center>	
					</td>
				</tr>
				
				<tr>
					<td class="centerText">
						<center>
							<div class="circle_container">
								<div class="circle_main young_age">
									<div class="circle_text_container">
										<div class = "circle_text">
										{{ $age18to30_percentage }}%
										</div>
									</div>
								</div>
							</div>
						</center>
					</td>
					<td class="centerText">
						<center>
							<div class="circle_container">
								<div class="circle_main middle_age">
									<div class="circle_text_container">
										<div class = "circle_text">
										{{$age31to45_percentage}}%	
										</div>
									</div>
								</div>
							</div>
						</center>
					</td>
					<td class="centerText">
						<center>
							<div class="circle_container">
								<div class="circle_main old_age">
									<div class="circle_text_container">
										<div class = "circle_text">
										{{$age46to59_percentage}}%
										</div>
									</div>
								</div>
							</div>
						</center>
					</td>
					<td class="centerText">
						<center>
							<div class="circle_container">
								<div class="circle_main senior_citizen">
									<div class="circle_text_container">
										<div class = "circle_text">
										{{$age60plus_percentage}}%
										</div>
									</div>
								</div>
							</div>
						</center>
					</td>
				</tr>
				
			
				<tr>
					<td class="centerText2"><span>Young Adults</span><br><span class="ageCount">{{ @$ChartAge->Young }}</span></td>
					<td class="centerText2"><span>Middle-Age Adults</span><br><span class="ageCount">{{ @$ChartAge->Middle }}</span></td>
					<td class="centerText2"><span>Old-Age Adults</span><br><span class="ageCount">{{ @$ChartAge->OLD }}</span></td>
					<td class="centerText2"><span>Senior Citizens</span><br><span class="ageCount">{{ @$ChartAge->SeniorCitizens }}</span></td>
				</tr>
				
			</table>

		
		</div>
		<!-- /.box-body -->
	  </div>


	</div><!-- end col-lg-8 -->


	<div class="col-lg-4">	

	
	  <div class="box box-default">
		<div class="box-header">
		  <h4>Voters' Gender</h4>
		</div>
		<div class="box-body">		  
		  <div class="row">
				<div class="col-lg-4">
					<img src="{{ asset('public/image/Male.png') }}" alt="male" width="100%" class="img-responsive" />
				</div>
				<div class="col-lg-8">
					<div style="margin-top:25px;margin-left:5px;margin-right:10px">
						
						<div class="progress-blue">
							
							<span class="progress-bar-male-voters" style="width:{{$voterMemberMale_percentage}}%;"></span>
							<div class="progress-bar progress-bar-blue" role="progressbar" style="width: {{$male_percentage}}%">
							<span class="sr-only-text">{{$male_percentage}}%</span>
							</div>
						 </div>
						 
					</div>
					<span style="font-size:14px;margin-left:5px;display:block">Total Male - {{@$ChartSex->Male}} </span>
					<span style="font-size:14px;margin-left:5px">Voter's Male - {{@$ChartSex->voterMemberMale}} </span>
						 
				</div>
		  
		  </div>
		  <div style="padding:10px"></div>
		 <div class="row">
				<div class="col-lg-4">
					<img src="{{ asset('public/image/Female.png') }}" alt="male" width="100%" class="img-responsive"/>
				</div>
				<div class="col-lg-8">
					<div style="margin-top:25px;margin-left:5px;margin-right:10px">
						<div class="progress-pink">
							<span class="progress-bar-female-voters" style="width:{{$voterMemberFemale_percentage}}%;"></span>
							<div class="progress-bar progress-bar-pink" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: {{$female_percentage}}%">
							  <span class="sr-only-text">{{$female_percentage}}%</span>
							</div>
						 </div>
						 
					</div>
					<span style="font-size:14px;margin-left:5px;display:block">Total Female - {{@$ChartSex->Female}}</span>
					 <span style="font-size:14px;margin-left:5px">Voter's Female - {{@$ChartSex->voterMemberFemale}} </span>
				</div>
		  </div>
		 
		  
		</div>		
		<!-- /.box-body -->
		
	  </div>
	  <!-- /.box -->


	 <!-- PIE  CHART -->
	  <div class="box box-default">
		<div class="box-header">
		   <h4>Voters' Religion</h4>
		</div>
		<div class="box-body">
		  <canvas id="pieChart_balancesheet" style="height:233px"></canvas>
		</div>		
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
		


	</div><!-- end col-lg-4 -->



	
	
	<!-- VOTERS JOIN -->
	<div class="col-lg-12">
		<div class="box box-default">
			<div class="box-body">
				<div id="barStackedChart" style="height:600px; width: 100%; "></div>
			</div>
		</div>
	</div>
	
	


</div>
<!-- /.row (main row) -->
</section>
<!-- /.content -->



<!-- ChartJS 1.0.1 -->
<script src="{{ asset('adminlte/plugins/chartjs/Chart.min.js')}}" ></script>
<script src="{{ asset('adminlte/plugins/chartjs/Chart2.js')}}" ></script>

<!-- FLOT CHARTS -->
<script src="{{ asset('adminlte/plugins/flot/jquery.flot.min.js')}}"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="{{ asset('adminlte/plugins/flot/jquery.flot.resize.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/flot/jquery.flot.pie.min.js')}}"></script>
<!-- jQuery Knob -->
<script src="{{ asset('adminlte/plugins/knob/jquery.knob.js')}}"></script>

<script  src="{{ asset('adminlte/plugins/chartjs/canvasChart.js')}}"></script>




<!-- 1st GRAPH -->
<script>
$(function () {
	
	 var donutData = [
      
      {label: "Total Projected Voters", data: "{{ $TotalprojectedVoters }}", color: "#007bc9"},
	  {label: "Total Voters", data: "{{ $TotalVoters }}", color: "#bcdedf"},

    ];
    $.plot("#donut-chart", donutData, {
      series: {
        pie: {
          show: true,
          radius: 1,
          innerRadius: 0.5,
          label: {
            show: true,
            radius: 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    });
	
	
	 function labelFormatter(label, series) {
		 return ''
		
	}

});		
</script>


<script>
var pieOptions  = {
	
      maintainAspectRatio : false,
	  responsive : true,
	  
	  tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                  label: function(tooltipItem, data) {
                    var label = data.labels[tooltipItem.index];
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

					
					 //var value =  datasetLabel.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    return label + ': ' + datasetLabel ;
				
                  }
                }
      },

	  legend: {
            display: true,
			position: 'bottom',
			labels: {
                fontColor: '#000',
				padding : 12,
				boxWidth: 15,
            }
			
      }
		
}
</script>

<script>
$(function () {
	
var Religions = <?php echo json_encode(@$dataReligion); ?>;
//console.log(Religions);


var rel = [];
var count = [];
var  color = [];
var  percent = [];
$.each(Religions, function(index, value) {
   color.push(value['backgroundColor']);
   count.push(value['count']);
   rel.push(index+' : '+value['percentage']+'%');
});

   var balancesheetData = {
	   
		  labels: rel,
		  datasets: [
				{
					data:count,
					backgroundColor:color
				}
		  ]
    }

	

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas_balancesheet = $('#pieChart_balancesheet').get(0).getContext('2d')
    var pieChart = new Chart(pieChartCanvas_balancesheet, {
		
      type: 'pie',
      data: balancesheetData,
      options: pieOptions,
	 
    });

});	
</script>


<script>
window.onload = function () {
	
		$.ajax({
		url: "{{ asset('BarangayData') }}",
		type : 'POST',
		headers:{'X-CSRF-TOKEN':$('meta[name="_token"]').attr('content')},
		beforeSend:function(){
			
			 $("#barStackedChart").waitMe({
				effect: 'timer',
				text: 'Loading Please Wait ........ ',
				bg: 'rgba(255,255,255,0.90)',
				color: '#555'
			}); 
		},
		success:function(data){
			$('#barStackedChart').waitMe('hide');
			
			DisplayBarData(data);
		
		},
		error:function(){
			$('#barStackedChart').waitMe('hide');

		}
				
	});
	
	

	
	


}


</script>


<script>

function DisplayBarData(barangays){
	
	console.log(barangays);
	
var Members = [];
var Leaders = [];
var Coordinators = [];
var Total_Voters = [];



$.each(barangays, function(index, value) {

	Coordinators.push({
		y: value['coordinators'],
		label : value['barangay'] + ":" + value['total_voters'],
	
	});

	Leaders.push({
		y: value['leaders'],
		label : value['barangay'] + ":" + value['total_voters'],
	});

	Members.push({
		y: value['members'],
		label : value['barangay'] + ":" + value['total_voters'],
	});


	Total_Voters.push({
			label : value['total_voters']
		});


});

console.log("Members =" +Members);


//Better to construct options first and then pass it as a parameter
var options = {


	animationEnabled: true,
	theme: "light1",
	title:{
		text: "MEMBERS' COUNT BY BARANGAY",
		fontSize: 25,
	},
	zoomEnabled: true,
	

	
	axisX:{
		reversed: true,
			labelWrap: false,   // change it to false
			interval: 1,
		labelFontSize: 12,

		contentFormatter: function ( e ) {
			return  e.label; 
		} 


	},
	axisY:{
		includeZero: true,
		labelFontSize: 12,
	
	},
	
	  
	  
	  
	  toolTip: {
        shared: true,
	
        contentFormatter: function(e){
          var str = "";
		  var tot= 0;
		  var label = "";
		  
		  var tooltip = "";
		  
          for (var i = 0; i < e.entries.length; i++){

			 label =  e.entries[i].dataPoint.label;
			 
			  
            var  temp = "<br>"+e.entries[i].dataSeries.name + "- <span>"+  e.entries[i].dataPoint.y + "</span>"; 
            tot = tot + e.entries[i].dataPoint.y;

			str = str.concat(temp);
          }
		  var total = "<br> Total : " + "<strong>"+  tot + "</strong>"; 
		  
		
		  
		  tooltip = tooltip.concat(label);
		  tooltip = tooltip.concat(str);
		  tooltip = tooltip.concat(total);
		  
          return (tooltip);
        }
		
		
      },
	  
	
	 legend: {
       horizontalAlign: "center", // "center" , "right"
       verticalAlign: "top",  // "top" , "bottom"
       fontSize: 15,
	   	cursor: "pointer",
		itemclick: toggleDataSeries
     },
	
	data:[
	
	{
		type: "stackedBar",
		name: "Coordinators",
		showInLegend: true, 
		cursor: "pointer",
		dataPoints: Coordinators
	},
	
	{
		type: "stackedBar",
		name: "Leaders",
		showInLegend: true, 
		cursor: "pointer",	
		dataPoints:Leaders
	},
	
	
	{
		type: "stackedBar",
		name: "Members",
		showInLegend: true, 
		cursor: "pointer",
		//indexLabel: "#total",
		//indexLabelOrientation: "horizontal",
		//indexLabelPlacement: "outside",
		//indexLabelFontSize: 15,
		//indexLabelFontWeight: "bold",
		dataPoints: Members
	},
	
	
	/*
	{
		type: "bar",
		name: "Total Voters",
		showInLegend: true, 
		indexLabel: "{y}",
		cursor: "pointer",	
		 indexLabelOrientation: "horizontal",
		//indexLabelPlacement: "outside",
		//indexLabelFontSize: 15,
		//indexLabelFontWeight: "bold",
		
		dataPoints:Total_Voters
	},
	*/
	
	
	
	
	]
	
	
};


function toggleDataSeries(e) {
	
	if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else {
		e.dataSeries.visible = true;
	}
	$("#barStackedChart").CanvasJSChart(options);
	$(".canvasjs-chart-credit").css('display','none');
}


$("#barStackedChart").CanvasJSChart(options);
$(".canvasjs-chart-credit").css('display','none');
	
}


</script>




<script>
 $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
</script>





@endsection
