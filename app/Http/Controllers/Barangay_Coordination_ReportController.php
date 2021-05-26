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
use App\Exports\Excel_Barangay_Coordination_Report;

use Maatwebsite\Excel\Facades\Excel;


class Barangay_Coordination_ReportController extends Controller
{

	public function Barangay_Coordination_Report(Request $request)
	{
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		
		$select ="t1.id,t1.group_id,name_coordinator.name as coordinator,coordinator_precint.name as  coordinator_precint ,name_leader.name as leader,leader_precint.name as  leader_precint";
		$details = \DB::table('coordinators as coordinator')
		->select(\DB::raw($select))
		->leftJoin('campaign_groups AS t1','coordinator.coordinator_id','=','t1.coordinator')
		->leftJoin('votersinfomations AS name_coordinator','name_coordinator.vin_number','=','coordinator.vin_number')
		->leftJoin('precincts AS coordinator_precint','coordinator_precint.id','=','name_coordinator.precint_number')		
		->leftJoin('leaders AS leader','leader.coordinator','=','coordinator.coordinator_id')
		->leftJoin('votersinfomations AS name_leader','name_leader.vin_number','=','leader.vin_number')
		->leftJoin('precincts AS leader_precint','leader_precint.id','=','name_leader.precint_number')
		->get();
		
	
		
		$coordinatorArray = array();
		
		
		foreach($details as $data){
			
			
			$select ="t2.name as member,precint.name as  precint";
			$getallmembers= \DB::table('campaign_group_members as t1')
			->select(\DB::raw($select))
			->leftJoin('votersinfomations AS t2','t2.vin_number','=','t1.vin_number')
			->leftJoin('precincts AS precint','precint.id','=','t2.precint_number')
			->where('t1.group_id','=',$data->group_id)
			->get();
			
			$members = array();
			foreach($getallmembers as $member)
			{
				$members[] = $member;
			}
			
			
			$row['id'] = $data->id;
			$row['group_id'] = $data->group_id;
			$row['coordinator'] = $data->coordinator;
			$row['coordinator_precint'] = $data->coordinator_precint;
			$row['leader'] = $data->leader;
			$row['leader_precint'] = $data->leader_precint;
			$row['members'] = 	$members;
			
			$coordinatorArray[$data->coordinator][$data->leader] = $row;
		}
		
		
			$div = "<thead>
						<tr>
						
						  <th>Coordinator</th>
						  <th>Precint #</th>
						  
						  <th>Leader</th>
						  <th>Precint #</th>
						  
						  <th></th>
						  <th>Member</th>
						  <th>Precint #</th>
						  
						</tr>
					</thead>";	
			
			
			$Count_coordinator = 0;
			$Count_leader = 0;
			$Count_member = 0;
		
			foreach($coordinatorArray as $coordinator => $leader)
			{
					
					$div .= "<tbody>";
					 $temp = array();
					 
					 $Count_coordinator = $Count_coordinator + 1; 
					 
					foreach($leader as $keyleader => $member){
						
						
						
					
						
						if($member['members'] && count($member['members']) > 0)
						{
							$count = 1;
						}
						else
						{
							$count = "";
						}
						
						$coordinatorName = $member['coordinator'];
						$coordinator_precint = $member['coordinator_precint'];
						
						if(in_array($coordinatorName, $temp)) {
									$coordinatorName = "";
									$coordinator_precint = "";
						}
						else{
							$temp[] = $coordinatorName;
						}				
						
						
						if(!empty($member['leader_precint']))
						{
							$Count_leader = $Count_leader + 1; 
						}
						
						
						if(!empty(@$member['members'][0]->precint))
						{
							$Count_member = $Count_member + 1; 
						}
						
				
						$div .="<tr style='border-top:2px solid #000;' class='divider'>";
						$div .="<td>".$coordinatorName."</td>";
						$div .="<td>".$coordinator_precint."</td>";
						$div .="<td>".$member['leader']."</td>";
						$div .="<td>".$member['leader_precint']."</td>";
						
						$div .="<td>".$count."</td>";
						$div .="<td>".@$member['members'][0]->member."</td>";
						$div .="<td>".@$member['members'][0]->precint."</td>";
						$div .="</tr>";		
						
						
						
						
						
						if($member['members'] && count($member['members']) > 0){
						
							for ($i = 1; $i < count($member['members']); $i++)  {

								$Count_member = $Count_member + 1; 
							
								$count = $count + 1;
								
								$div .="<tr>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td>".$count."</td>";
								$div .="<td>".$member['members'][$i]->member."</td>";
								$div .="<td>".$member['members'][$i]->precint."</td>";
								$div .="</tr>";	
								
							}
						
						}
						
					}
							
					$div .= "</tbody>";	
					
				
						
			}
			
			
				// Grand footer
					$div .="<tfoot style='border-top:3px solid #000;'>";
						
						$div .="<tr>";
						
						
								$div .="<td>".$Count_coordinator."</td>";
								$div .="<td></td>";
								$div .="<td>".$Count_leader."</td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td>".$Count_member."</td>";
								$div .="<td></td>";
								$div .="</tr>";	

						
						$div .="</tr>";
					$div .="</tfoot>";
						
			
			
			$output = array("table" => $div, 'excel'=>$coordinatorArray);
			return response()->json($output);
		
			
			
		
		
	}
	
	
	
	public function print_Barangay_Coordination_Report(Request $requests)
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
			  'title' 			  => 'Barangay Corrdination Report',
			  //'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		
		//return view('pdf_reports.print_report', $datax);
		
	
		
	    $pdf = PDF::loadView('pdf_reports.print_report', $datax);  
        $filename = trans('Barangay Corrdination Report');
		return $pdf->download($filename.'.pdf');
		
    }

	public function excel_Barangay_Coordination_Report(Request $requests)
	{
		
		$array = $requests->data_form_excel;
        $coordinatorArray = json_decode($array);
		
		$excel_data = array();
		
		foreach($coordinatorArray as $coordinator => $leader)
		{
				
				
				 $temp = array();
				 
				foreach($leader as $keyleader => $member){
					
					$count = 1;
					
			
					$coordinatorName = $member->coordinator;
					$coordinator_precint = $member->coordinator_precint;
					
					if(in_array($coordinatorName, $temp)) {
								$coordinatorName = "";
								$coordinator_precint = "";
					}
					else{
						$temp[] = $coordinatorName;
					}				
					
					
					
					$excel_data[] = array(
							
							0 => $coordinatorName,  
							1 => $coordinator_precint,
							2 => $member->leader,
							3 => $member->leader_precint,
							4 => $count,
							5 => $member->members[0]->member,
							6 => $member->members[0]->precint,
					
					);
							
				
					for ($i = 1; $i < count($member->members); $i++)  {							
					
						$count = $count + 1;
			
						
						$excel_data[] = array(
							
								0 => $coordinatorName,  
								1 => $coordinator_precint,
								2 => $member->leader,
								3 => $member->leader_precint,
								4 => $count,
								5 => $member->members[$i]->member,
								6 => $member->members[$i]->precint,
						
						);
						
					}
					
			
				}
	
		}
			

			$filename = trans('Barangay Coordination Report');
			return Excel::download(new Excel_Barangay_Coordination_Report($excel_data), $filename.'.xlsx');
	
	}

}