<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\cities;

use App\campaign_groups;
use App\campaign_group_members;


use Auth;
use Hash;
use PDF;
use App\Exports\Excel_voters_projected_province;
use App\Exports\Excel_voters_projected_city;
use App\Exports\Excel_voters_projected_barangay;
use App\Exports\Excel_voters_projected_precint;


use Maatwebsite\Excel\Facades\Excel;


class Report_ProjectedVotersController extends Controller
{

	public function projected_voters(Request $request)
	{
		
		   
		$report_level = $request->report_level;
		
		if($report_level == "Province")
		{
			
			return $this->Province();
		}
		else if($report_level == "City")
		{
			
			return $this->City();
		}
		else if($report_level == "Barangay")
		{
			
			return $this->Barangay();
		}
		else if($report_level == "Precinct")
		{
			
			return $this->Precinct();
		}
		
			
	}



	function Province()
	{
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.name,t1.id,t1.status";
		$registerVoters = \DB::table('votersinfomations as t1')		
		->select(\DB::raw($select))
		->get();
		
		
		$countRegisterdVoters = array();
		$countAbroad = array();
		$countDeceased = array();
		
		
		foreach($registerVoters as $voters)
		{
			$countRegisterdVoters[] = $voters;
			
			if($voters->status == 1)
			{
				$countDeceased[] = $voters;
			}
			else if($voters->status == 2)
			{
				$countAbroad[] = $voters;
			}
			
		}
		
		
		$select ="t1.name,t1.id";
		$getallprovince = \DB::table('provinces as t1')		
		->select(\DB::raw($select))
		->get();
			
			
			$div = "<thead>
						<tr>
						  <th>Province</th>
						  <th>City/Municipality</th>
						  
						  <th><center>Registered Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Projected Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Undecided Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Deceased</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Abroad</center></th>
						  <th><center>%</center></th>
						</tr>
					</thead>";	
		
		$excel =  array();
		
		if($getallprovince && count($getallprovince) > 0)
		{
			$div .= "<tbody>";
				foreach($getallprovince as $province)
				{
					$coordinators = "(SELECT COUNT(coordinator_id) from coordinators where city = t1.id ) as coordinators";
					$leaders 	  = "(SELECT COUNT(leader_id) from leaders where city = t1.id ) as leaders";
					$members      = "(SELECT COUNT(b.id) from campaign_groups as a 
									   LEFT JOIN  campaign_group_members as b ON a.group_id = b.group_id
									   where a.city = t1.id
									 ) as members ";
					
					
					$select ="t1.name,t1.id , {$coordinators},{$leaders},{$members} ";
					$getallcities = \DB::table('cities as t1')		
					->select(\DB::raw($select))
					->where('t1.province_id', '=',$province->id)
					->get();
					
					
					
					foreach($getallcities as $city)
					{
						
						 $projected_voters = $city->coordinators + $city->leaders + $city->members;
						 
						 $undecided = count($countRegisterdVoters) - $projected_voters;
						
							$div .="<tr>";
								$div .="<td>".$province->name."</td>";
								$div .="<td>".$city->name."</td>";
								
								$div .="<td><center>".count($countRegisterdVoters)."</center></td>";
								$percent  = number_format ( (count($countRegisterdVoters) * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$percent."</center></td>";
								
								$div .="<td><center>".$projected_voters."</center></td>";
								$projected_voters_percent  = number_format ( ($projected_voters * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$projected_voters_percent."</center></td>";
								
								
								$div .="<td><center>".$undecided."</center></td>";
								$undecided_percent  = number_format ( ($undecided * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$undecided_percent."</center></td>";
								
								$div .="<td><center>".count($countDeceased)."</center></td>";
								$countDeceased_percent  = number_format ( (count($countDeceased) * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$countDeceased_percent."</center></td>";
								
								
								$div .="<td><center>".count($countAbroad)."</center></td>";
								$countAbroad_percent  = number_format ( (count($countAbroad) * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$countAbroad_percent."</center></td>";
								
								
							$div .="</tr>";
						
						
							//EXCEL
							$excel[] = array(
								
								"province"                 => $province->name,
								"city"                     => $city->name,
								"registered_voters"        => count($countRegisterdVoters),
								"percent"                  => $percent,
								"projected_voters" 		   => $projected_voters,
								"projected_voters_percent" => $projected_voters_percent,  
								"undecided" 			   => $undecided,
								"undecided_percent" 	   => $undecided_percent,
								"countDeceased"			   => count($countDeceased),
								"countDeceased_percent"    => $countDeceased_percent,
								"countAbroad" 			   => count($countAbroad),
								"countAbroad_percent"      => $countAbroad_percent,
							);
						
					
					}
					
					
			
					
				}
			$div .= "</tbody>";	

		}
		else
		{
			$div .= "<tbody>";
			$div .="<tr>";
			$div .="<td colspan='10'><center>No data available in table</center></td>";
			$div .="</tr>";
			$div .= "</tbody>";
		}
		
		$output = array("table" => $div, 'excel' => $excel , 'report_level' => 'Province');
		return response()->json($output);

			
	}
	

	function City(){
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.name,t1.id,t1.status";
		$registerVoters = \DB::table('votersinfomations as t1')		
		->select(\DB::raw($select))
		->get();
		
		
		$countRegisterdVoters = array();
		$countAbroad = array();
		$countDeceased = array();
		
		
		foreach($registerVoters as $voters)
		{
			$countRegisterdVoters[] = $voters;
			
			if($voters->status == 1)
			{
				$countDeceased[] = $voters;
			}
			else if($voters->status == 2)
			{
				$countAbroad[] = $voters;
			}
			
		}
		
					
				$div = "<thead>
						<tr>
						  <th>City/Municipality</th>
						  
						  <th><center>Registered Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Projected Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Undecided Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Deceased</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Abroad</center></th>
						  <th><center>%</center></th>
						  
						  
						</tr>
					</thead>";			
		
		
		$coordinators = "(SELECT COUNT(coordinator_id) from coordinators where city = t1.id ) as coordinators";
		$leaders 	  = "(SELECT COUNT(leader_id) from leaders where city = t1.id ) as leaders";
		$members      = "(SELECT COUNT(b.id) from campaign_groups as a 
						   LEFT JOIN  campaign_group_members as b ON a.group_id = b.group_id
						   where a.city = t1.id
						 ) as members ";
		
		
		$select ="t1.name,t1.id , {$coordinators},{$leaders},{$members} ";
		$getallcities = \DB::table('cities as t1')		
		->select(\DB::raw($select))
		->get();
		

		if($getallcities && count($getallcities) > 0)
		{
			$div .= "<tbody>";
				foreach($getallcities as $city)
				{

					 $projected_voters = $city->coordinators + $city->leaders + $city->members;
					 
					 $undecided = count($countRegisterdVoters) - $projected_voters;
					
						$div .="<tr>";
								$div .="<td>".$city->name."</td>";
							
								$div .="<td><center>".count($countRegisterdVoters)."</center></td>";
								$percent  = number_format ( (count($countRegisterdVoters) * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$percent."</center></td>";
								
								$div .="<td><center>".$projected_voters."</center></td>";
								$projected_voters_percent  = number_format ( ($projected_voters * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$projected_voters_percent."</center></td>";
								
								
								$div .="<td><center>".$undecided."</center></td>";
								$undecided_percent  = number_format ( ($undecided * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$undecided_percent."</center></td>";
								
								$div .="<td><center>".count($countDeceased)."</center></td>";
								$countDeceased_percent  = number_format ( (count($countDeceased) * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$countDeceased_percent."</center></td>";
								
								
								$div .="<td><center>".count($countAbroad)."</center></td>";
								$countAbroad_percent  = number_format ( (count($countAbroad) * 100) / count($countRegisterdVoters) , 2 );
								$div .="<td><center>".$countAbroad_percent."</center></td>";
						
						
						$div .="</tr>";
						
						//EXCEL
						$excel[] = array(
						
							"city"                     => $city->name,
							"registered_voters"        => count($countRegisterdVoters),
							"percent"                  => $percent,
							"projected_voters" 		   => $projected_voters,
							"projected_voters_percent" => $projected_voters_percent,  
							"undecided" 			   => $undecided,
							"undecided_percent" 	   => $undecided_percent,
							"countDeceased"			   => count($countDeceased),
							"countDeceased_percent"    => $countDeceased_percent,
							"countAbroad" 			   => count($countAbroad),
							"countAbroad_percent"      => $countAbroad_percent,
						);
						
						
						

				}
			$div .= "</tbody>";	

		}
		else
		{
			$div .= "<tbody>";
			$div .="<tr>";
			$div .="<td colspan='10'><center>No data available in table</center></td>";
			$div .="</tr>";
			$div .= "</tbody>";
		}
		
		$output = array("table" => $div, 'excel' => $excel , 'report_level' => 'City');
		return response()->json($output);
		
	}
	
	

	
	function Barangay(){
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		
			
			$div = "<thead>
						<tr>
						  <th>Barangay</th>
						  <th><center>Registered Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Projected Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Undecided Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Deceased</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Abroad</center></th>
						  <th><center>%</center></th>
						</tr>
					</thead>";	
		
		
		$coordinators = "(SELECT COUNT(coordinator_id) from coordinators where barangay = t1.id ) as coordinators";
		$leaders 	  = "(SELECT COUNT(leader_id) from leaders where barangay = t1.id ) as leaders";
		$members      = "(SELECT COUNT(b.id) from campaign_groups as a 
						   LEFT JOIN  campaign_group_members as b ON a.group_id = b.group_id
						   where a.barangay = t1.id
						 ) as members ";
		
		
		$select ="t1.name,t1.id , {$coordinators},{$leaders},{$members} ";
		$getallbarangay = \DB::table('barangays as t1')		
		->select(\DB::raw($select))
		->get();
		
		$select ="t1.id";
		$allvoters = \DB::table('votersinfomations as t1')		
		->select(\DB::raw($select))
		->get();
		
		
		$grand_countRegisterdVoters = 0;
		$grand_countRegisterdVoters_percent = 0;
		
		$grand_projected_voters = 0;
		$grand_projected_voters_percent = 0;
		
		$grand_undecided = 0;
		$grand_undecided_percent = 0;
		
		$grand_countDeceased = 0;
		$grand_countDeceased_percent = 0;
		
		$grand_countAbroad = 0;
		$grand_countAbroad_percent = 0;
		

	
		if($getallbarangay && count($getallbarangay) > 0)
		{
			$div .= "<tbody>";
				foreach($getallbarangay as $barangay)
				{
					
					$select ="t1.name,t1.id,t1.status";
					$registerVoters = \DB::table('votersinfomations as t1')		
					->select(\DB::raw($select))
					->where('t1.barangay', $barangay->id)
					->get();
					
						$countRegisterdVoters = array();
						$countAbroad = array();
						$countDeceased = array();
						
						
						
						foreach($registerVoters as $voters)
						{
							$countRegisterdVoters[] = $voters;
							
							if($voters->status == 1)
							{
								$countDeceased[] = $voters;
							}
							else if($voters->status == 2)
							{
								$countAbroad[] = $voters;
							}
							
						}
					
					
	
						$projected_voters = $barangay->coordinators + $barangay->leaders + $barangay->members;
						$undecided = count($countRegisterdVoters) - $projected_voters;
						
						
						$percent 				  = (count($countRegisterdVoters)  == 0 ) ? 0 : ((count($countRegisterdVoters) * 100) / count($countRegisterdVoters));
						$projected_voters_percent = (count($countRegisterdVoters)  == 0 ) ? 0 : (($projected_voters * 100) / count($countRegisterdVoters));
						$undecided_percent  	  = (count($countRegisterdVoters)  == 0 ) ? 0 : (($undecided * 100) / count($countRegisterdVoters));
						$countDeceased_percent    = (count($countRegisterdVoters)  == 0 ) ? 0 : ((count($countDeceased) * 100) / count($countRegisterdVoters));
						$countAbroad_percent      = (count($countRegisterdVoters)  == 0 ) ? 0 : ((count($countAbroad) * 100) / count($countRegisterdVoters));
			
						$div .="<tr>";
							$div .="<td>".$barangay->name."</td>";
							
							
								$div .="<td><center>".count($countRegisterdVoters)."</center></td>";
								$div .="<td><center>".number_format($percent,2)."</center></td>";
								
								$div .="<td><center>".$projected_voters."</center></td>";
								$div .="<td><center>".number_format($projected_voters_percent,2)."</center></td>";

								$div .="<td><center>".$undecided."</center></td>";
								$div .="<td><center>".number_format($undecided_percent,2)."</center></td>";
								
								$div .="<td><center>".count($countDeceased)."</center></td>";
								$div .="<td><center>".number_format($countDeceased_percent,2)."</center></td>";
								
								$div .="<td><center>".count($countAbroad)."</center></td>";
								$div .="<td><center>".number_format($countAbroad_percent,2)."</center></td>";
							
							
						$div .="</tr>";
							
								
								
								$grand_countRegisterdVoters = $grand_countRegisterdVoters + count($countRegisterdVoters);
								//$grand_countRegisterdVoters_percent = $grand_countRegisterdVoters_percent + $percent;

								$grand_projected_voters = $grand_projected_voters + $projected_voters;
								//$grand_projected_voters_percent = $grand_projected_voters_percent  + $projected_voters_percent ;
								
								$grand_undecided = $grand_undecided + $undecided;
								//$grand_undecided_percent = $grand_undecided_percent + $undecided_percent;
								
								$grand_countDeceased = $grand_countDeceased + count($countDeceased);
								//$grand_countDeceased_percent = $grand_countDeceased_percent + $countDeceased_percent;
								
								$grand_countAbroad = $grand_countAbroad  + count($countAbroad) ;
								//$grand_countAbroad_percent = $grand_countAbroad_percent  + $countAbroad_percent ;
								
								
								
							
						//EXCEL
						$excel[] = array(
						
							"city"                     => $barangay->name,
							"registered_voters"        => count($countRegisterdVoters),
							"percent"                  => $percent,
							"projected_voters" 		   => $projected_voters,
							"projected_voters_percent" => $projected_voters_percent,  
							"undecided" 			   => $undecided,
							"undecided_percent" 	   => $undecided_percent,
							"countDeceased"			   => count($countDeceased),
							"countDeceased_percent"    => $countDeceased_percent,
							"countAbroad" 			   => count($countAbroad),
							"countAbroad_percent"      => $countAbroad_percent,
						);
								
								

				}
			$div .= "</tbody>";	
			
			
			
			$grand_countRegisterdVoters_percent = ($grand_countRegisterdVoters == 0 ) ? 0 : ( ($grand_countRegisterdVoters * 100) / ($grand_countRegisterdVoters) );
			$grand_projected_voters_percent 	= ($grand_projected_voters == 0 ) ? 0 : ( ($grand_projected_voters * 100) / ($grand_countRegisterdVoters) );	
			$grand_undecided_percent 			= ($grand_undecided == 0 ) ? 0 : (($grand_undecided * 100) / ($grand_countRegisterdVoters));		
			$grand_countDeceased_percent        = ($grand_countDeceased == 0 ) ? 0 : (($grand_countDeceased * 100) / ($grand_countRegisterdVoters));
			$grand_countAbroad_percent        	= ($grand_countAbroad == 0 ) ? 0 : (($grand_countAbroad * 100) / ($grand_countRegisterdVoters));			
			
				// Grand footer
			$div .="<tfoot style='border-top:3px solid #000;'>";
				$div .="<tr>";
				
				$div .="<td><b>Grand Total</b></td>";
				
				$div .="<td><center><b>".$grand_countRegisterdVoters."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_countRegisterdVoters_percent,2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_projected_voters."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_projected_voters_percent, 2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_undecided."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_undecided_percent, 2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_countDeceased."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_countDeceased_percent, 2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_countAbroad."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_countAbroad_percent, 2)."</b></center></td>";

				$div .="</tr>";
			$div .="</tfoot>";

		}
		else
		{
			$div .= "<tbody>";
			$div .="<tr>";
			$div .="<td colspan='10'><center>No data available in table</center></td>";
			$div .="</tr>";
			$div .= "</tbody>";
		}
		
		$output = array("table" => $div, 'excel' => $excel , 'report_level' => 'Barangay');
		return response()->json($output);
		
	}
	
	
	
	
	
	function Precinct(){
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
	
			
			$div = "<thead>
						<tr>
						  <th>Precint Number</th>
						  <th><center>Registered Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Projected Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Undecided Voters</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Deceased</center></th>
						  <th><center>%</center></th>
						  
						  <th><center>Abroad</center></th>
						  <th><center>%</center></th>
						</tr>
					</thead>";	
		
		
		
		$select ="t1.name,t1.id ";
		$getallbarangay = \DB::table('barangays as t1')		
		->select(\DB::raw($select))
		->get();
	
		$precints = array();
		
		
		$select ="t1.id";
		$allvoters = \DB::table('votersinfomations as t1')		
		->select(\DB::raw($select))
		->get();
		
		$grand_countRegisterdVoters = 0;
		$grand_countRegisterdVoters_percent = 0;
		
		$grand_projected_voters = 0;
		$grand_projected_voters_percent = 0;
		
		$grand_undecided = 0;
		$grand_undecided_percent = 0;
		
		$grand_countDeceased = 0;
		$grand_countDeceased_percent = 0;
		
		$grand_countAbroad = 0;
		$grand_countAbroad_percent = 0;
		

		if($getallbarangay && count($getallbarangay) > 0)
		{
			$div .= "<tbody>";
				foreach($getallbarangay as $barangay)
				{
					
					$div .="<tr>";
							$div .="<td colspan='12'>"."Barangay / <b>".$barangay->name."</b></td>";
					$div .="</tr>";
					
					$coordinators = "(SELECT COUNT(coordinator_id) from coordinators as coor
										LEFT JOIN  votersinfomations as v2 ON v2.vin_number = coor.vin_number
										where v2.precint_number = t1.id 
									 ) as coordinators";
									  
					$leaders 	  = "(SELECT COUNT(leader_id) from leaders as lead
										LEFT JOIN  votersinfomations as v2 ON v2.vin_number = lead.vin_number
										where v2.precint_number = t1.id 
									) as leaders";
					
					
					$members      = "(SELECT COUNT(camp_member.id) from campaign_groups as camp 
									   LEFT JOIN  campaign_group_members as camp_member ON camp.group_id = camp_member.group_id
									   LEFT JOIN  votersinfomations as v2 ON v2.vin_number = camp_member.vin_number
									  where v2.precint_number = t1.id 
									 ) as members ";
					
					
						$select ="t1.name,t1.id, {$coordinators},{$leaders},{$members} ";
						$getallprecints = \DB::table('precincts as t1')		
						->select(\DB::raw($select))
						->where('t1.barangay_id', $barangay->id)
						->get();
						
						$precints[$barangay->name] = $getallprecints;
						
						if($getallprecints && count($getallprecints) > 0)
						{
							
							$sub_countRegisterdVoters = 0;
							$sub_countRegisterdVoters_percent = 0;
							
							$sub_projected_voters = 0;
							$sub_projected_voters_percent = 0;
							
							$sub_undecided = 0;
							$sub_undecided_percent = 0;
							
							$sub_countDeceased = 0;
							$sub_countDeceased_percent = 0;
							
							$sub_countAbroad = 0;
							$sub_countAbroad_percent = 0;
							
							
							
							foreach($getallprecints as $precint)
							{
								
									$select ="t1.name,t1.id,t1.status";
									$registerVoters = \DB::table('votersinfomations as t1')		
									->select(\DB::raw($select))
									->where('t1.precint_number', $precint->id)
									->get();
									
									
									$countRegisterdVoters = array();
									$countAbroad = array();
									$countDeceased = array();
									
									
									foreach($registerVoters as $voters)
									{
										$countRegisterdVoters[] = $voters;
										
										if($voters->status == 1)
										{
											$countDeceased[] = $voters;
										}
										else if($voters->status == 2)
										{
											$countAbroad[] = $voters;
										}
										
									}
		
									$projected_voters = $precint->coordinators + $precint->leaders + $precint->members;
									$undecided = count($countRegisterdVoters) - $projected_voters;
								 
								 
									$sub_countRegisterdVoters = $sub_countRegisterdVoters + count($countRegisterdVoters);					
									$percent = (count($countRegisterdVoters) == 0 ) ? 0 : ((count($countRegisterdVoters) * 100) / count($countRegisterdVoters));
								
									$sub_projected_voters = $sub_projected_voters +  $projected_voters;
									$projected_voters_percent  = ( count($countRegisterdVoters)  == 0 )  ? 0  : (($projected_voters * 100) / count($countRegisterdVoters));
									//$sub_projected_voters_percent = $sub_projected_voters_percent + $projected_voters_percent;
									
									$sub_undecided = $sub_undecided + $undecided;
									$undecided_percent  = ( count($countRegisterdVoters)  == 0 ) ? 0 :  ( ($undecided * 100) / count($countRegisterdVoters) );
									//$sub_undecided_percent = $sub_undecided_percent + $undecided_percent;
									
									$sub_countDeceased = $sub_countDeceased + count($countDeceased);
									$countDeceased_percent  =  ( count($countRegisterdVoters)  == 0 ) ? 0 :  ( (count($countDeceased) * 100) / count($countRegisterdVoters) );
									//$sub_countDeceased_percent = 	$sub_countDeceased_percent + $countDeceased_percent;	
									
									$sub_countAbroad = $sub_countAbroad + count($countAbroad);
									$countAbroad_percent = (count($countRegisterdVoters)  == 0 ) ? 0 :  ( (count($countAbroad) * 100) / count($countRegisterdVoters) );
									//$sub_countAbroad_percent = $sub_countAbroad_percent + $countAbroad_percent;
					
									
									
									
									$div .="<tr>";
									
										$div .="<td>".$precint->name."</td>";
										
										$div .="<td><center>".count($countRegisterdVoters)."</center></td>";
										$div .="<td><center>".number_format($percent,2)."</center></td>";
										
										$div .="<td><center>".$projected_voters."</center></td>";
										$div .="<td><center>".number_format($projected_voters_percent,2)."</center></td>";
										
										$div .="<td><center>".$undecided."</center></td>";
										$div .="<td><center>".number_format($undecided_percent,2)."</center></td>";
										
										$div .="<td><center>".count($countDeceased)."</center></td>";
										$div .="<td><center>".number_format($countDeceased_percent,2)."</center></td>";

										$div .="<td><center>".count($countAbroad)."</center></td>";
										$div .="<td><center>".number_format($countAbroad_percent,2)."</center></td>";
										
										
									$div .="</tr>";
									
									
									//EXCEL
									$excel[$barangay->name][] = array(
									
										"Precint"                  => $precint->name,
										"registered_voters"        => count($countRegisterdVoters),
										"percent"                  => $percent,
										"projected_voters" 		   => $projected_voters,
										"projected_voters_percent" => $projected_voters_percent,  
										"undecided" 			   => $undecided,
										"undecided_percent" 	   => $undecided_percent,
										"countDeceased"			   => count($countDeceased),
										"countDeceased_percent"    => $countDeceased_percent,
										"countAbroad" 			   => count($countAbroad),
										"countAbroad_percent"      => $countAbroad_percent,
									);

							
							}
							
							
						$sub_countRegisterdVoters_percent = ($sub_countRegisterdVoters == 0 ) ? 0 : ( ($sub_countRegisterdVoters * 100) / ($sub_countRegisterdVoters));
						$sub_projected_voters_percent 	  = ($sub_projected_voters == 0 ) ? 0 : ( ($sub_projected_voters * 100) / ($sub_countRegisterdVoters));	
						$sub_undecided_percent 			  = ($sub_undecided == 0 ) ? 0 : ( ($sub_undecided * 100) / ($sub_countRegisterdVoters));	
						$sub_countDeceased_percent	 	  = ($sub_countDeceased == 0 ) ? 0 : ( ($sub_countDeceased * 100) / ($sub_countRegisterdVoters));	
						$sub_countAbroad_percent 		  = ($sub_countAbroad == 0 ) ? 0 : ( ($sub_countAbroad * 100) / ($sub_countRegisterdVoters));	
							
							
								// Sub footer
								$div .="<tr style='border-top:2px solid #b6aeae;'> ";
								
									$div .="<td><b>Sub Total</b></td>";
									
									$div .="<td><center><b>".$sub_countRegisterdVoters."</b></center></td>";
									$div .="<td><center><b>".number_format( $sub_countRegisterdVoters_percent, 2)."</b></center></td>";
									
									$div .="<td><center><b>".$sub_projected_voters."</b></center></td>";
									$div .="<td><center><b>".number_format($sub_projected_voters_percent, 2)."</b></center></td>";
									
									$div .="<td><center><b>".$sub_undecided."</b></center></td>";
									$div .="<td><center><b>".number_format($sub_undecided_percent, 2)."</b></center></td>";
									
									$div .="<td><center><b>".$sub_countDeceased."</b></center></td>";
									$div .="<td><center><b>".number_format($sub_countDeceased_percent, 2)."</b></center></td>";
									
									$div .="<td><center><b>".$sub_countAbroad."</b></center></td>";
									$div .="<td><center><b>".number_format($sub_countAbroad_percent, 2)."</b></center></td>";
								
								$div .="</tr>";
								
								$grand_countRegisterdVoters = $grand_countRegisterdVoters + $sub_countRegisterdVoters;
								//$grand_countRegisterdVoters_percent = $grand_countRegisterdVoters_percent + $sub_countRegisterdVoters_percent;
								
								$grand_projected_voters = $grand_projected_voters + $sub_projected_voters;
								//$grand_projected_voters_percent = $grand_projected_voters_percent  + $sub_projected_voters_percent ;
								
								$grand_undecided = $grand_undecided + $sub_undecided;
								//$grand_undecided_percent = $grand_undecided_percent + $sub_undecided_percent;
								
								$grand_countDeceased = $grand_countDeceased + $sub_countDeceased;
								//$grand_countDeceased_percent = $grand_countDeceased_percent + $sub_countDeceased_percent;
								
								$grand_countAbroad = $grand_countAbroad  + $sub_countAbroad ;
								//$grand_countAbroad_percent = $grand_countAbroad_percent  + $sub_countAbroad_percent ;
							
							
							
							
						}
						else
						{
							$div .="<tr>";
							$div .="<td colspan='12'>No precint available</td>";
							$div .="</tr>";
						}
						
						
				}
			$div .= "</tbody>";	
			
			
			
			$grand_countRegisterdVoters_percent	  = ($grand_countRegisterdVoters == 0 ) ? 0 : ( ($grand_countRegisterdVoters * 100) / ($grand_countRegisterdVoters));
			$grand_projected_voters_percent 	  = ($grand_projected_voters == 0 ) ? 0 : ( ($grand_projected_voters * 100) / ($grand_countRegisterdVoters));	
			$grand_undecided_percent 			  = ($grand_undecided == 0 ) ? 0 : ( ($grand_undecided * 100) / ($grand_countRegisterdVoters));	
			$grand_countDeceased_percent	 	  = ($grand_countDeceased == 0 ) ? 0 : ( ($grand_countDeceased * 100) / ($grand_countRegisterdVoters));	
			$grand_countAbroad_percent 		  	  = ($grand_countAbroad == 0 ) ? 0 : ( ($grand_countAbroad * 100) / ($grand_countRegisterdVoters));	
			
			
			
			// Grand footer
			$div .="<tfoot style='border-top:3px solid #000;'>";
				$div .="<tr>";
				
				$div .="<td><b>Grand Total</b></td>";
				
				$div .="<td><center><b>".$grand_countRegisterdVoters."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_countRegisterdVoters_percent,2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_projected_voters."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_projected_voters_percent, 2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_undecided."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_undecided_percent, 2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_countDeceased."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_countDeceased_percent, 2)."</b></center></td>";
				
				$div .="<td><center><b>".$grand_countAbroad."</b></center></td>";
				$div .="<td><center><b>".number_format($grand_countAbroad_percent, 2)."</b></center></td>";

				
				$div .="</tr>";
			$div .="</tfoot>";
			

		}
		else
		{
			$div .= "<tbody>";
			$div .="<tr>";
			$div .="<td colspan='10'><center>No data available in table</center></td>";
			$div .="</tr>";
			$div .= "</tbody>";
		}
		
	
		$output = array("table" => $div, 'precints' => $precints,  'excel' => $excel , 'report_level' => 'Precinct' );
		return response()->json($output);
		
	}
	
	
	
	
	public function print_projected_voters(Request $requests)
    {
        $array = $requests->data_form;
		 $body_data = json_decode($array);
		/*
		$header_data = array(
						   'city'      =>  $requests->print_city,
						   'barangay'  =>  $requests->print_barangay,
						);
		*/				
       //$header = (object) $header_data;
	   	
       // This  $data array will be passed to our PDF blade
       $datax = [
			  'user_id' 		  =>  Auth::user()->name,
			  'date_genarated'    =>  Carbon::now(),
			  'title' 			  => 'List of Voters Projected Reports',
			  //'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		

	    $pdf = PDF::loadView('pdf_reports.print_report', $datax);  
        $filename = trans('Voters Projected Report');
		return $pdf->download($filename.'.pdf');
		
    }
	
	
	
	public function excel_projected_voters(Request $requests)
    {
		
        $array = $requests->data_form_excel;
        $body_data = json_decode($array);
		$data_report_level = $requests->data_report_level;

		if($data_report_level == "Province")
		{
			$excel_data = $this->ExcelProvince($body_data);
			$filename = trans('Voters Projected Report Province');
			return Excel::download(new Excel_voters_projected_province($excel_data), $filename.'.xlsx');
					
		}
		else if($data_report_level == "City")
		{
			$excel_data = $this->ExcelCity($body_data);
			$filename = trans('Voters Projected Report City');
			return Excel::download(new Excel_voters_projected_city($excel_data), $filename.'.xlsx');
			 
		}
		else if($data_report_level == "Barangay")
		{
			$excel_data = $this->ExcelBarangay($body_data);
			$filename = trans('Voters Projected Report Barangay');
			return Excel::download(new Excel_voters_projected_barangay($excel_data), $filename.'.xlsx');
			
		}
		else if($data_report_level == "Precinct")
		{
			 $excel_data = $this->ExcelPrecint($body_data);
			$filename = trans('Voters Projected Report Precint');
			return Excel::download(new Excel_voters_projected_precint($excel_data), $filename.'.xlsx');
		}
		

    }
	
	
	function ExcelProvince($body_data){
		
		
				$excel_data = array();
			
				foreach ($body_data as  $value) 
				{	
						 $excel_data[] = array(
						 
							"Province" => $value->province,
							"City/Municipality" => $value->city,
							"Registered Voters" => $value->registered_voters,
							"Registered %" => $value->percent,  
							"Projected Voters" => $value->projected_voters,
							"Projected %" => $value->projected_voters_percent,
							"Undecided Voters" => $value->undecided,
							"Undecided %" => $value->undecided_percent,
							"Deceased" => $value->countDeceased,
							"Deceased %" => $value->countDeceased_percent,
							"Abroad" => $value->countAbroad,
							"Abroad %" => $value->countAbroad_percent,
							
						);
				}
				
				return $excel_data;
			
	}
	
	function ExcelCity($body_data){
		
		
				$excel_data = array();
			
				foreach ($body_data as  $value) 
				{	
						 $excel_data[] = array(
						
							"City/Municipality" => $value->city,
							"Registered Voters" => $value->registered_voters,
							"Registered %" => $value->percent,  
							"Projected Voters" => $value->projected_voters,
							"Projected %" => $value->projected_voters_percent,
							"Undecided Voters" => $value->undecided,
							"Undecided %" => $value->undecided_percent,
							"Deceased" => $value->countDeceased,
							"Deceased %" => $value->countDeceased_percent,
							"Abroad" => $value->countAbroad,
							"Abroad %" => $value->countAbroad_percent,
							
						);
				}
				
				return $excel_data;
			
	}
	
	
	function ExcelBarangay($body_data){
		
		
		$excel_data = array();
			
		$grand_countRegisterdVoters = 0;
		$grand_countRegisterdVoters_percent = 0;
		
		$grand_projected_voters = 0;
		$grand_projected_voters_percent = 0;
		
		$grand_undecided = 0;
		$grand_undecided_percent = 0;
		
		$grand_countDeceased = 0;
		$grand_countDeceased_percent = 0;
		
		$grand_countAbroad = 0;
		$grand_countAbroad_percent = 0;
		
			foreach ($body_data as  $value) 
			{	
					 $excel_data[] = array(
					
						"Barangay" => $value->city,
						"Registered Voters" => $value->registered_voters,
						"Registered %" => number_format($value->percent,2),  
						"Projected Voters" => $value->projected_voters,
						"Projected %" => number_format($value->projected_voters_percent,2),
						"Undecided Voters" => $value->undecided,
						"Undecided %" => number_format($value->undecided_percent,2),
						"Deceased" => $value->countDeceased,
						"Deceased %" => number_format($value->countDeceased_percent,2),
						"Abroad" => $value->countAbroad,
						"Abroad %" => number_format($value->countAbroad_percent,2),
						
					);
					
					
					
					$grand_countRegisterdVoters = $grand_countRegisterdVoters + $value->registered_voters;
					$grand_countRegisterdVoters_percent = $grand_countRegisterdVoters_percent + $value->percent;
					
					$grand_projected_voters = $grand_projected_voters + $value->projected_voters;
					$grand_projected_voters_percent = $grand_projected_voters_percent  + $value->projected_voters_percent;
					
					$grand_undecided = $grand_undecided + $value->undecided;
					$grand_undecided_percent = $grand_undecided_percent + $value->undecided_percent;
					
					$grand_countDeceased = $grand_countDeceased + $value->countDeceased;
					$grand_countDeceased_percent = $grand_countDeceased_percent + $value->countDeceased_percent;
					
					$grand_countAbroad = $grand_countAbroad  + $value->countAbroad;
					$grand_countAbroad_percent = $grand_countAbroad_percent  + $value->countAbroad_percent;
			}
			
			$excel_data[] = array(
					
						"Barangay" => "Grand Total",
						"Registered Voters" => $grand_countRegisterdVoters,
						"Registered %" => number_format($grand_countRegisterdVoters_percent,2),
						
						"Projected Voters" => $grand_projected_voters,
						"Projected %" => number_format($grand_projected_voters_percent,2),
						
						"Undecided Voters" => $grand_undecided,
						"Undecided %" => number_format($grand_undecided_percent,2),
						
						"Deceased" => $grand_countDeceased,
						"Deceased %" => number_format($grand_countDeceased_percent,2),
						
						"Abroad" => $grand_countAbroad,
						"Abroad %" => number_format($grand_countAbroad_percent,2),
						
					);
			
			
			return $excel_data;
			
	}
	
	
	function ExcelPrecint($body_data){
		
	
		$excel_data = array();
			
		$grand_countRegisterdVoters = 0;
		$grand_countRegisterdVoters_percent = 0;
		
		$grand_projected_voters = 0;
		$grand_projected_voters_percent = 0;
		
		$grand_undecided = 0;
		$grand_undecided_percent = 0;
		
		$grand_countDeceased = 0;
		$grand_countDeceased_percent = 0;
		
		$grand_countAbroad = 0;
		$grand_countAbroad_percent = 0;
		
		foreach($body_data as $key => $data){
			
			
				$excel_data[] = array(
						
							"Barangay" => $key ,
							"Registered Voters" => '',
							"Registered %" => '',  
							"Projected Voters" => '',
							"Projected %" => '',
							"Undecided Voters" => '',
							"Undecided %" => '',
							"Deceased" => '',
							"Deceased %" => '',
							"Abroad" => '',
							"Abroad %" => '',
							
						);
			
			
				foreach ($data as  $value) 
				{	
						 $excel_data[] = array(
						
							"Precint Number" => $value->Precint,
							"Registered Voters" => $value->registered_voters,
							"Registered %" => number_format($value->percent,2),  
							"Projected Voters" => $value->projected_voters,
							"Projected %" => number_format($value->projected_voters_percent,2),
							"Undecided Voters" => $value->undecided,
							"Undecided %" => number_format($value->undecided_percent,2),
							"Deceased" => $value->countDeceased,
							"Deceased %" => number_format($value->countDeceased_percent,2),
							"Abroad" => $value->countAbroad,
							"Abroad %" => number_format($value->countAbroad_percent,2),
							
						);
						
						
						
						$grand_countRegisterdVoters = $grand_countRegisterdVoters + $value->registered_voters;
						$grand_countRegisterdVoters_percent = $grand_countRegisterdVoters_percent + $value->percent;
						
						$grand_projected_voters = $grand_projected_voters + $value->projected_voters;
						$grand_projected_voters_percent = $grand_projected_voters_percent  + $value->projected_voters_percent;
						
						$grand_undecided = $grand_undecided + $value->undecided;
						$grand_undecided_percent = $grand_undecided_percent + $value->undecided_percent;
						
						$grand_countDeceased = $grand_countDeceased + $value->countDeceased;
						$grand_countDeceased_percent = $grand_countDeceased_percent + $value->countDeceased_percent;
						
						$grand_countAbroad = $grand_countAbroad  + $value->countAbroad;
						$grand_countAbroad_percent = $grand_countAbroad_percent  + $value->countAbroad_percent;
				}
				
				
			
		}
		
		$excel_data[] = array(
						
							"Precint Number" => "Grand Total",
							"Registered Voters" => $grand_countRegisterdVoters,
							"Registered %" => number_format($grand_countRegisterdVoters_percent,2),
							
							"Projected Voters" => $grand_projected_voters,
							"Projected %" => number_format($grand_projected_voters_percent,2),
							
							"Undecided Voters" => $grand_undecided,
							"Undecided %" => number_format($grand_undecided_percent,2),
							
							"Deceased" => $grand_countDeceased,
							"Deceased %" => number_format($grand_countDeceased_percent,2),
							
							"Abroad" => $grand_countAbroad,
							"Abroad %" => number_format($grand_countAbroad_percent,2),
							
						);
		
		
			return $excel_data;
			
	}
	
	
}