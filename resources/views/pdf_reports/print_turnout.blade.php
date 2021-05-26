<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ $title }}</title>
<style>
body
{
   font-family: Arial, Helvetica, sans-serif;
   font-size: 13px;
}
 
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

thead
{
	border: 1px solid #ddd;
}
table th, #table td {
  text-align: left;
  padding: 8px;
}

table tr:nth-child(even) {
  background-color: #f2f2f2;
}

#company_name
{
	font-size : 14px;
	font-weight : bold;
}

#company_address
{
	font-size : 14px;
	font-weight : normal;
}

.heading
{
	font-size : 14px;
	font-weight : bold;
}
</style>

</head>
<body>

<div  style='clear:both;width:100%'> 
	<div><center><span id='company_address'><b>{{$title}}</b></span></center></div>
</div>  


<br>
<br>


<table width="100%" style="width:100%" border="0" id='table_data'>
	<thead>
	<tr>
	  	  <th>Province</th>
		  <th>Cities/Municipalities</th>
		  <th>Barangay</th>
		  <th>Precints</th>
		  <th>Voter Count</th>
		  <th>Turn Out</th>
		  <th>Variance</th>
	</tr>
	</thead>
	
	@if(!empty($table_data))
			 <tbody>
			        
			         @php($totalvoterscount = 0)
                     @php($totalturnout = 0)
					 @php($totalvariance = 0)
			
			        
                      
					@foreach ($table_data as $datas)
					
				        <?php
					         
					         $totalvoterscount = $totalvoterscount + (float)$datas->voters_count;
					         $totalturnout = $totalturnout + (float)$datas->turnout;
					         $totalvariance = $totalvariance + (float)$datas->variance;

					    ?>

		 
						<tr>
							<td> {{$datas->province }} </td>
							<td> {{$datas->city }} </td>
							<td> {{$datas->barangay }} </td>
							<td> {{$datas->precinct }} </td>
							<td> {{$datas->voters_count }} </td>
							<td> {{$datas->turnout }} </td>
							<td> {{$datas->variance }} </td>

						</tr>
					  
					 @endforeach
			 </tbody>
			 
			 <tfoot style="border-top:1px solid #000;">
                    <tr>
                      <th></th>
    				   <th></th>
    				   <th></th>
                       <th></th>
    				   
    				   <th><span style="font-size:12px;font-weight:500;">{{ $totalvoterscount }}  </th>
    				   <th><span style="font-size:12px;font-weight:500;"> {{ $totalturnout }}   </th>
    				   <th><span style="font-size:12px;font-weight:500;"> {{ $totalvariance }}   </th>
    				   
    				   
    				   
    				   <th></th>
                    </tr>
                </tfoot>
			       
			 
			 
		@else	 
			 
			<tbody><tr><td colspan="7"><center>No data available in table</center></td></tr></tbody>
		
		@endif
	
	
	
	
	
</table>
 

</body>
</html>