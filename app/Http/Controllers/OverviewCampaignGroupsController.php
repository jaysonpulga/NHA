<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Purokleader;

use App\Overview_campaign_group;
use App\overview_campaign_group_members;

class OverviewCampaignGroupsController extends Controller
{
	
	
	
	public function getPurokleaderbelongstobarangayNotyetSelected(Request $request)
	{
		 $request->barangay;
		 $request->city_municipality;
	
	

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.purok_leader,t1.vin_number,t1.purokleader_id";
		$getUsers = \DB::table('purokleaders as t1')
		->select(\DB::raw($select))
		
		->leftJoin('overview_campaign_groups AS t2','t2.purokleader','=','t1.purok_leader')
		->whereNull('t2.purokleader')
		
		->where('t1.barangay', '=',$request->barangay)
		->where('t1.city_municipality', '=',$request->city_municipality)
		->groupBy('t1.vin_number')
		->get();
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	public function getUserAssignAsMember(Request $request){
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.vin_number,t1.name";
		$getUsers = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->leftJoin('overview_campaign_group_members AS t2','t2.vin_number','=','t1.vin_number')
		->where('barangay', '=',$request->barangay)
		->whereNull('t2.vin_number')
		->groupBy('t1.vin_number')
		->orderby('t1.name','asc')
		->get();
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	public function create_member(Request $request){
		
		//print_r($request->members);
		$arrayMerge = json_decode(json_encode($request->members), true);
		 
		// merge duplicate group id
        $new_array = array();
		
		 foreach($arrayMerge as $key=>$value){
			 
			 
               if(!isset($new_array[$value['group_id']])){
                 
                      $new_array[$value['group_id']]  = array(
                            'group_id'=> $value['group_id'], 
                            'city_municipality' => $value['city_municipality'], 
                            'coordinator' => $value['coordinator'], 
                            'barangay' => $value['barangay'],
							'subcoordinator' => $value['subcoordinator'],
							'purokleader' => $value['purokleader']
                          );
						  
						  
						  if(Overview_campaign_group::where('group_id',$value['group_id'])->exists()){
						  
								
						  }else{
							  
							  
							    $Overview_campaign_group = new Overview_campaign_group(); 
								$Overview_campaign_group->group_id      =  $value['group_id'];
								$Overview_campaign_group->city_municipality      =  $value['city_municipality'];
								$Overview_campaign_group->coordinator      	     = $value['coordinator'];
								$Overview_campaign_group->barangay      	     = $value['barangay'];
								$Overview_campaign_group->subcoordinator      	 = $value['subcoordinator'];
								$Overview_campaign_group->purokleader      	 	 = $value['purokleader'];
								$Overview_campaign_group->save();	
						  
							  
						  }
               }
			 
		 }
		
		// insert members 
		foreach($request->members as $data){
			
				$overview_campaign_group_members = new overview_campaign_group_members(); 
				$overview_campaign_group_members->group_id       =  $data['group_id'];
				$overview_campaign_group_members->member      	 = $data['member'];
				$overview_campaign_group_members->vin_number     =  $data['member_vin_number'];
				$overview_campaign_group_members->save();	
			
		}
		
		
		return response()->json(array('status' => 'save'));
		

	}
	
	
	public  function  getCampaignGroupList(Request $request)
	{
		  //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$getallData = Overview_campaign_group::all();
		
		

		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['id'] = $dd->id;
				$row['group_id'] = $dd->group_id;
				$row['city_municipality'] = $dd->city_municipality;
				$row['coordinator'] = $dd->coordinator;
				$row['barangay'] = $dd->barangay;
				$row['subcoordinator'] = $dd->subcoordinator;
				$row['purokleader'] = $dd->purokleader;
				$row['details'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary details' data-group_id=".$dd->group_id." data-id=".$dd->id."> DETAILS </a>&nbsp;";
				$data[] = $row;
			}
		}
		else
		{
			$data = [];
		}
		
		$output = array("data" => $data);
		return response()->json($output);
		
		
		
		
	}
	
	public function  getMemberlistAndDetails(Request $request){
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$group_id = $request->group_id;
		$id = $request->id;
		
		//$details  = Overview_campaign_group::where('group_id',$group_id)->where('id',$id)->first();
		
		$select ="t2.vin_number,t1.*";
		$details = \DB::table('overview_campaign_groups as t1')
		->select(\DB::raw($select))
		->leftJoin('purokleaders AS t2','t2.purok_leader','=','t1.purokleader')
		->where('t1.group_id','=',$group_id)
		->where('t1.id','=',$id)
		->first();
		
		
		
		
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
		
		
		
		
		$output = array("details" => $details, 'members' =>$data);
		return response()->json($output);
	}
	
	

}