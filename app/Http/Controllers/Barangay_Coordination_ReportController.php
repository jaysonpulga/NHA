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
		
		
		$barangayData = $request->barangayData;
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		$select ="t1.coordinator_id,v.name as coordinator,p.name as c_precint";
		$coordinators = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->leftJoin('votersinfomations AS v','v.id','=','t1.user_id')
		->leftJoin('precincts AS p','p.id','=','v.precint_number')
		->when(!empty($barangayData), function ($q) use ($barangayData) {
			return $q->where('t1.barangay', $barangayData );
		})
		->orderBy('t1.coordinator_id', 'ASC')
		->get();
		
		
		
		$groupCoordinator = array();
		foreach($coordinators as $coordinator )
		{
			
	
					$select ="t1.leader_id,v.name as leader,p.name as c_precint,t3.group_id";
					$leaders = \DB::table('leaders as t1')
					->select(\DB::raw($select))
					->leftJoin('votersinfomations AS v','v.id','=','t1.user_id')
					->leftJoin('precincts AS p','p.id','=','v.precint_number')
					->where('t1.coordinator','=',$coordinator->coordinator_id)
					->leftJoin('campaign_groups AS t3','t1.leader_id','=','t3.leader')
					//->offset(0)
					//->limit(1)
					->orderBy('t1.leader_id', 'ASC')
					->get();
					
					$groupLeader = array();
					foreach($leaders as $leader)
					{
						
						
						$select ="t2.name as member,p.name as  precint";
						$getallmembers= \DB::table('campaign_group_members as t1')
						->select(\DB::raw($select))
						->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
						->leftJoin('precincts as p','p.id','=','t2.precint_number')
						->where('t1.group_id','=',$leader->group_id)
						->get();
						
						$members = array();
						foreach($getallmembers as $member)
						{
							$members[] = $member;
						}
						
						
						
						/*
						$groupLeader[$leader->leader] = array(
								'leader_precint' => $leader->c_precint,	
								'members' => $members,
								
								
						);
						*/
						
						
						
						$groupCoordinator[] = array(
							'coordinator' => $coordinator->coordinator,
							'c_precint' => $coordinator->c_precint,
							'leader' => $leader->leader,
							'l_precint' => $leader->c_precint,
							'members' => $members,
					
							);
						
						
						
						
						
			}
			
			
		
			/*
			$groupCoordinator[$coordinator->coordinator] = array(
							'c_precint' => $coordinator->c_precint,
							'leaders' => $groupLeader,
					
			);
			*/

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
		$temp = array();
		
		$div .= "<tbody>";		
		foreach($groupCoordinator as $value)
		{
					
			
			$Count_coordinator =
			$Count_coordinator + 1; 
					 
					 
						$coordinatorName = $value['coordinator'];
						$coordinator_precint = $value['c_precint'];
						 
						if(in_array($coordinatorName, $temp)) {
									$coordinatorName = "";
									$coordinator_precint = "";
								$style = ""	;
						}
						else{
							$temp[] = $coordinatorName;
							
							$style = "style='border-top:2px solid #000;background-color:#dddcdc;font-size:13px'";
							
						}
					 
					 
						
						if($value['members'] && count($value['members']) > 0)
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
						$div .="<td>".$value['leader']."</td>";
						$div .="<td>".$value['l_precint']."</td>";
						$div .="<td>".$count."</td>";
						$div .="<td>".@$value['members'][0]->member."</td>";
						$div .="<td>".@$value['members'][0]->precint."</td>";
						$div .="</tr>";		
						
					
						if($value['members'] && count($value['members']) > 0){
						
							for ($i = 1; $i < count($value['members']); $i++)  {
								$count = $count + 1;
								
								$div .="<tr>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td>".$count."</td>";
								$div .="<td>".$value['members'][$i]->member."</td>";
								$div .="<td>".$value['members'][$i]->precint."</td>";
								$div .="</tr>";	
								
							}
						
						}
						
						
					
							
					
		}
		$div .= "</tbody>";	
			
	
		$output = array("table" => $div, 'excel'=>$groupCoordinator);
		return response()->json($output);	
	

	}
	
	public function Barangay_Coordination_Report2(Request $request)
	{
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
	
		
		
		/*
		$select ="t1.id,t1.group_id,name_coordinator.name as coordinator,coordinator_precint.name as  coordinator_precint ,name_leader.name as leader,leader_precint.name as  leader_precint";
		$details = \DB::table('coordinators as coordinator')
		->select(\DB::raw($select))
		->leftJoin('campaign_groups AS t1','coordinator.coordinator_id','=','t1.coordinator')
		->leftJoin('votersinfomations AS name_coordinator','name_coordinator.id','=','coordinator.user_id')
		->leftJoin('precincts AS coordinator_precint','coordinator_precint.id','=','name_coordinator.precint_number')		
		->leftJoin('leaders AS leader','leader.coordinator','=','coordinator.coordinator_id')
		->leftJoin('votersinfomations AS name_leader','name_leader.id','=','leader.user_id')
		->leftJoin('precincts AS leader_precint','leader_precint.id','=','name_leader.precint_number')
		->get();
		*/
		
		
		/*
		$select ="v.name as coordinator,p.name as c_precint,v2.name as leader, p2.name as leader_precint,t3.group_id";
		$groupCoordinator = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->leftJoin('votersinfomations AS v','v.id','=','t1.user_id')
		->leftJoin('precincts AS p','p.id','=','v.precint_number')
		->leftJoin('leaders AS t2','t2.coordinator','=','t1.coordinator_id')
		->leftJoin('votersinfomations AS v2','v2.id','=','t2.user_id')
		->leftJoin('precincts AS p2','p2.id','=','v2.precint_number')
		->leftJoin('campaign_groups AS t3','t3.leader','=','t2.leader_id')
		->orderBy('t1.coordinator_id', 'ASC')
		->get();
		
		$output = array("table" => $groupCoordinator);
		return response()->json($output);
		exit;
		*/
		
		
		/*
		$coordinatorArray = array();
		foreach($groupCoordinator as $data){
			
			
			$select ="t2.name as member,p.name as  precint";
			$getallmembers= \DB::table('campaign_group_members as t1')
			->select(\DB::raw($select))
			->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
			->leftJoin('precincts as p','p.id','=','t2.precint_number')
			->where('t1.group_id','=',$data->group_id)
			->get();
			
			$members = array();
			foreach($getallmembers as $member)
			{
				$members[] = $member;
			}
			
			
			$row['group_id'] = $data->group_id;
			$row['coordinator_id'] = $data->coordinator_id;
			$row['coordinator'] = $data->coordinator;
			$row['c_precint'] = $data->c_precint;
			$row['leader_id'] = $data->leader_id;
			$row['leader'] = $data->leader;
			$row['leader_precint'] = $data->leader_precint;
			$row['members'] = 	$members;
			
			$coordinatorArray[$data->coordinator_id][$data->leader_id] = $row;
		}
		
		
		echo "<pre>";
		print_r($coordinatorArray);
		echo "</pre>";
		exit;
		*/
		
		
		
		
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
		
		
		
		$select ="t1.coordinator_id,v.name as coordinator,p.name as c_precint";
		$coordinators = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->leftJoin('votersinfomations AS v','v.id','=','t1.user_id')
		->leftJoin('precincts AS p','p.id','=','v.precint_number')
		->orderBy('t1.coordinator_id', 'ASC')
		->get();
		
		
		
		$groupCoordinator = array();
		foreach($coordinators as $coordinator )
		{
			
	
					$select ="t1.leader_id,v.name as leader,p.name as c_precint,t3.group_id";
					$leaders = \DB::table('leaders as t1')
					->select(\DB::raw($select))
					->leftJoin('votersinfomations AS v','v.id','=','t1.user_id')
					->leftJoin('precincts AS p','p.id','=','v.precint_number')
					->where('t1.coordinator','=',$coordinator->coordinator_id)
					->leftJoin('campaign_groups AS t3','t1.leader_id','=','t3.leader')
					->orderBy('t1.leader_id', 'ASC')
					->get();
					
					$groupLeader = array();
					foreach($leaders as $leader)
					{
						
						
						$select ="t2.name as member,p.name as  precint";
						$getallmembers= \DB::table('campaign_group_members as t1')
						->select(\DB::raw($select))
						->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
						->leftJoin('precincts as p','p.id','=','t2.precint_number')
						->where('t1.group_id','=',$leader->group_id)
						->get();
						
						$members = array();
						foreach($getallmembers as $member)
						{
							$members[] = $member;
						}
						
						
						
						/*
						$groupLeader[$leader->leader] = array(
								'leader_precint' => $leader->c_precint,	
								//'members' => $members,
								
								
						);
						*/
						
						
						$groupCoordinator[] = array(
							'coordinator' => $coordinator->coordinator,
							'c_precint' => $coordinator->c_precint,
							'leader' => $leader->leader,
							'l_precint' => $leader->c_precint,
							'members' => $members,
					
							);
						
						
			}
			
			
		
			/*
			$groupCoordinator[$coordinator->coordinator] = array(
							'c_precint' => $coordinator->c_precint,
							'leaders' => $groupLeader,
					
			);
			*/
			
			
			
		
		
		
		}
		
		
		
		
		
		
		/*
		$output = array($groupCoordinator);
		return response()->json($output);
		exit;
		*/
		
		
		
		//$output = array($groupCoordinator);
		//return response()->json($output);
		
		
		$div = "<thead>
				<tr>
				  <th>Coordinator</th>
				  <th>Precint#</th>
				  <th>Leader</th>
				  <th>Precint#</th>
				  <th></th>
				  <th>Member</th>
				  <th>Precint#</th>
				</tr>
			</thead>";	
			
			
		
			$Count_coordinator = 0;
			$Count_leader = 0;
			$Count_member = 0;
			
			 $temp = array();
			
			foreach($groupCoordinator as $value)
			{
					
					$div .= "<tbody>";
					
					 
					 $Count_coordinator = $Count_coordinator + 1; 
					 
					 
					$coordinatorName = $value['coordinator'];
					$coordinator_precint = $value['c_precint'];
					 
					if(in_array($coordinatorName, $temp)) {
								$coordinatorName = "";
								$coordinator_precint = "";
					}
					else{
						$temp[] = $coordinatorName;
					}
					 
					 
						
						if($value['members'] && count($value['members']) > 0)
						{
							$count = 1;
						}
						else
						{
							$count = "";
						}
						
						

						
					
						/*
						if(!empty(@$member['members'][0]->precint))
						{
							$Count_member = $Count_member + 1; 
						}
						*/
						
						
				
						$div .="<tr style='border-top:2px solid #000;' class='divider'>";
						$div .="<td>".$coordinatorName."</td>";
						$div .="<td>".$coordinator_precint."</td>";
						$div .="<td>".$value['leader']."</td>";
						$div .="<td>".$value['l_precint']."</td>";
						$div .="<td>".$count."</td>";
						$div .="<td>".@$value['members'][0]->member."</td>";
						$div .="<td>".@$value['members'][0]->precint."</td>";
						
						
						$div .="</tr>";		
						
						
						
						
						
						if($value['members'] && count($value['members']) > 0){
						
							for ($i = 1; $i < count($value['members']); $i++)  {

								//$Count_member = $Count_member + 1; 
								$count = $count + 1;
								
								$div .="<tr>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td></td>";
								$div .="<td>".$count."</td>";
								$div .="<td>".$value['members'][$i]->member."</td>";
								$div .="<td>".$value['members'][$i]->precint."</td>";
								$div .="</tr>";	
								
							}
						
						}
						
						
					
							
					$div .= "</tbody>";	
					
				
						
			}
			
		
		
		
		
		$output = array("table" => $div, 'excel'=>$groupCoordinator);
		return response()->json($output);
		
		
		
		
		exit;
		foreach($groupCoordinator as $keycoordinator => $coordinator)
		{
			$div .= "<tbody>";
			$temp = array();
			$Count_coordinator = $Count_coordinator + 1; 
		
			
			$div .="<tr style='border-top:2px solid #000;' class='divider'>";
			$div .="<td>".$keycoordinator."</td>";
			$div .="<td>".$coordinator['c_precint']."</td>";
			$div .="<td></td>";
			$div .="<td></td>";
			$div .="<td></td>";
			$div .="<td></td>";
			$div .="<td></td>";
			$div .="</tr>";	
			
			foreach($coordinator['leaders'] as $keyleader => $leader){
				
						$div .="<tr class='divider'>";
						$div .="<td></td>";
						$div .="<td></td>";
						$div .="<td>".$keyleader."</td>";
						$div .="<td>".$leader['leader_precint']."</td>";
						$div .="<td></td>";
						$div .="<td></td>";
						$div .="<td></td>";
						$div .="</tr>";	
						
						
						foreach($leader['members'] as $member)
						{
							$div .="<tr>";
							$div .="<td></td>";
							$div .="<td></td>";
							$div .="<td></td>";
							$div .="<td></td>";
							$div .="<td></td>";
							$div .="<td>".$member->member."</td>";
							$div .="<td></td>";
							$div .="</tr>";	
							
						}
						
				
			}
			
			
			
			$div .= "</tbody>";	
		}
		
		
		
		$output = array("table" => $div, 'excel'=>$groupCoordinator);
		return response()->json($output);
	
		exit;
		
		$coordinatorArray = array();
		foreach($details as $data){
			
			
			$select ="t2.name as member,precint.name as  precint";
			$getallmembers= \DB::table('campaign_group_members as t1')
			->select(\DB::raw($select))
			->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
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
		ini_set('max_execution_time', 10000); //300 seconds = 5 minutes
		ini_set('memory_limit', -1);
		
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
     		
		//return view('pdf_reports.print_barangay_coordination_report', $datax);
		
	
		
	    $pdf = PDF::loadView('pdf_reports.print_report', $datax);  
		return $pdf->stream();
		
		
		//return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf_reports.print_report', $datax)->stream();
		
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