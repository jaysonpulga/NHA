<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ $title }}</title>
<style>
body
{
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
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
	font-size : 12px;
	font-weight : bold;
}

.divider
{
	border-top:2px solid #ddd;
}

</style>

</head>
<body>

	<div  style='clear:both;width:100%'> 
		<div><center><span id='company_address'><b>{{$title}}</b></span></center></div>
	</div>  
	
	<br>
	<br>
	
	<table width="100%" style="width:100%" border="0" id='table'>
		
		<thead>
			<tr>
			
			  <th>Coordinator</th>
			  <th>Precint #</th>
			  
			  <th>Leader</th>
			  <th>Precint #</th>
			  
			  <th></th>
			  <th>Member</th>
			  <th>Precint #</th>
			  
			</tr>
		</thead>
		
		@if(!empty($table_data))
		<tbody>
		<?php
		
		$Count_coordinator = 0;
		$Count_leader = 0;
		$Count_member = 0;
		$temp = array();
		$div = "";
		
		$div .= "<tbody>";		
		foreach($table_data as $value)
		{
					
			
			$Count_coordinator =
			$Count_coordinator + 1; 
					 
					 
						$coordinatorName = $value->coordinator;
						$coordinator_precint = $value->c_precint;
						 
						if(in_array($coordinatorName, $temp)) {
									$coordinatorName = "";
									$coordinator_precint = "";
								$style = ""	;
						}
						else{
							$temp[] = $coordinatorName;
							
							$style = "style='border-top:2px solid #000;background-color:#dddcdc;font-size:13px'";
							
						}
					 
					 
						
						if($value->members && count($value->members) > 0)
						{
							$count = 1;
						}
						else
						{
							$count = "";
						}
						
				
						$div .="<tr {$style} >";
						$div .="<td>".$coordinatorName."</td>";
						$div .="<td>".$coordinator_precint."</td>";
						$div .="<td>".$value->leader."</td>";
						$div .="<td>".$value->l_precint."</td>";
						$div .="<td>".$count."</td>";
						$div .="<td>".@$value->members[0]->member."</td>";
						$div .="<td>".@$value->members[0]->precint."</td>";
						$div .="</tr>";		
						
					
						if($value->members && count($value->members) > 0){
						
							for ($i = 1; $i < count($value->members); $i++)  {
								$count = $count + 1;
								
								$div .="<tr>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td>".$count."</td>";
								$div .="<td>".$value->members[$i]->member."</td>";
								$div .="<td>".$value->members[$i]->precint."</td>";
								$div .="</tr>";	
								
							}
						
						}
						
						
					
							
					
		}
		
		echo $div;
		
		?>	
		 </tbody>
		@else	 
			 
			<tbody><tr><td colspan="7"><center>No data available in table</center></td></tr></tbody>
		
		@endif
		
    </table>


</body>
</html>