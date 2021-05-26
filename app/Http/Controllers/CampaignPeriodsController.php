<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\campaign_periods;
use App\Overview_campaign_group;
use App\overview_campaign_group_members;
use App\campaign_tally_periods;


class CampaignPeriodsController extends Controller
{
	
	public function getPurokleaderbelongstobarangay(Request $request)
	{
		 $request->barangay;
		 $request->city_municipality;
	
	

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.purok_leader,t1.vin_number,t1.purokleader_id";
		$getUsers = \DB::table('purokleaders as t1')
		->select(\DB::raw($select))	
		->where('t1.barangay', '=',$request->barangay)
		->where('t1.city_municipality', '=',$request->city_municipality)
		->groupBy('t1.vin_number')
		->get();
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	public function getGroupId(Request $request){
		
		
		$data = Overview_campaign_group::where('city_municipality', '=', $request->city_municipality)
								->where('coordinator', '=', $request->coordinator)
								->where('barangay', '=', $request->barangay)
								->where('subcoordinator', '=', $request->subcoordinator)
								->where('purokleader', '=', $request->purokleader)
								->first();
								
		if(empty($data)){
			echo "";
		}
		else{
			
			echo $data->group_id;
		}
		
	}
	
	
	public function campaignperiods(Request $request){
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

		$select ="*";
		$city_coordinators = \DB::table('coordinators')
		->select(\DB::raw($select))
		->groupBy('city_municipality')
		->orderby('city_municipality','asc')
		->get();
		
		$select ="*";
		$campaign_periods = \DB::table('campaign_periods')
		->select(\DB::raw($select))
		->get();
		
		$select ="*";
		$campaign_groups = \DB::table('overview_campaign_groups')
		->select(\DB::raw($select))
		->get();
		
		
		return view('campaign_periods.campaign',array('city_coordinators' => $city_coordinators,'campaign_periods'=>$campaign_periods,'campaign_groups'=>$campaign_groups));	
		
	}
	
	
	public function create_campaign_period(Request $request){
		
		 $from_date = $request->from_date;
		 $to_date = $request->to_date;
		 
		 if(campaign_periods::where('from_date', '=', $from_date)->where('to_date', '=', $to_date)->exists()) {
		    echo "exist";
		    exit;
		 }
		 
		 
		 $campaign_periods = new campaign_periods(); 
		 $campaign_periods->from_date  =  $from_date;
		 $campaign_periods->to_date    = $to_date;
		 $campaign_periods->save();
		 
		 echo "save";
	}
	
	
	
	public function getMemberlistByGroupId(Request $request){
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$group_id = $request->group_id;
		
		
		$getallData  = overview_campaign_group_members::where('group_id',$group_id)->get();
		
		$data = array();
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['id'] = $dd->id;
				$row['member'] = $dd->member;
				$row['vin_number'] = $dd->vin_number;
				$data[] = $row;
			}
		}
		else
		{
			$data = [];
		}
		
		
		
		
		$output = array('members' =>$data);
		return response()->json($output);
		
	}
	
	
	
	
	public function campaign_tally_periods(Request $request){
		
		//print_r($request->all());
		
		
		 $campaign_tally_periods = new campaign_tally_periods(); 
		 $campaign_tally_periods->campaign_period_id  =  $request->campaign_period_id_value;
		 $campaign_tally_periods->group_id            = $request->groupId_value;
		 $campaign_tally_periods->total_yes           = $request->yes_value;
		 $campaign_tally_periods->total_no            = $request->no_value;
		 $campaign_tally_periods->total_undecided     = $request->undecided_value;
		 $campaign_tally_periods->save();
		 
		 
		 echo "save";
	}
	
  
}