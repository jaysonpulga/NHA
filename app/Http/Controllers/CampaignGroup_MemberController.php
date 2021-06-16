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
use App\Exports\ExportMember;
use Maatwebsite\Excel\Facades\Excel;


class CampaignGroup_MemberController extends Controller
{

	public function member(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'campaign_groups');
			$cities = cities::where('province_id','1')->get();
			return view('campaign_groups.member',array('menu'=>'member','cities'=>$cities),$arraymenu);
	}
	
	
	
	
	public function loadAllCampaignGroup(Request $request)
	{
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$city = $request->city;
		$barangay = $request->barangay;
		
		$limit  	 = $request->input('length');
		$start   	 = $request->input('start');
		$dir 	     = $request->input('order.0.dir');
		$search	 	 = $request->input('search.value');
		$draw 		 = $request->input('draw');
		
		
		$sel = "(SELECT COUNT(id) from campaign_group_members where group_id = t1.group_id) as number_of_members";
		
		$select ="t1.id,t1.group_id, name_coordinator.name as coordinator, t3.name as city, t4.name as barangay,t5.name as province,name_leader.name as leader";
		$totalData = \DB::table('campaign_groups as t1')
		->select(\DB::raw($select))	
		->leftJoin('coordinators AS coordinator','coordinator.coordinator_id','=','t1.coordinator')
		->leftJoin('votersinfomations AS name_coordinator','name_coordinator.id','=','coordinator.user_id')
		->leftJoin('cities AS t3','t3.id','=','t1.city')
		->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
		->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
		->leftJoin('leaders AS leader','leader.leader_id','=','t1.leader')
		->leftJoin('votersinfomations AS name_leader','name_leader.id','=','leader.user_id')
		->when(!empty($city), function ($q) use ($city) {
			return $q->where('t1.city',$city);
		})
		->when(!empty($barangay), function ($q) use ($barangay) {
			return $q->where('t1.barangay',$barangay);
		})
		->count();
		
		
		if(empty($search))
		{
			
			$select ="t1.id,t1.group_id, name_coordinator.name as coordinator, t3.name as city, t4.name as barangay,t5.name as province,name_leader.name as leader,{$sel}";
			$getallData = \DB::table('campaign_groups as t1')
			->select(\DB::raw($select))	
			->leftJoin('coordinators AS coordinator','coordinator.coordinator_id','=','t1.coordinator')
			->leftJoin('votersinfomations AS name_coordinator','name_coordinator.id','=','coordinator.user_id')
			->leftJoin('cities AS t3','t3.id','=','t1.city')
			->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
			->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
			->leftJoin('leaders AS leader','leader.leader_id','=','t1.leader')
			->leftJoin('votersinfomations AS name_leader','name_leader.id','=','leader.user_id')
			->when(!empty($city), function ($q) use ($city) {
				return $q->where('t1.city',$city);
			})
			->when(!empty($barangay), function ($q) use ($barangay) {
				return $q->where('t1.barangay',$barangay);
			})
			->offset($start)
			->limit($limit)
			->get();
			
			$totalFiltered = $totalData;
		}
		else{
			
			$select ="t1.id,t1.group_id, name_coordinator.name as coordinator, t3.name as city, t4.name as barangay,t5.name as province,name_leader.name as leader,{$sel}";
			$getallData = \DB::table('campaign_groups as t1')
			->select(\DB::raw($select))	
			->leftJoin('coordinators AS coordinator','coordinator.coordinator_id','=','t1.coordinator')
			->leftJoin('votersinfomations AS name_coordinator','name_coordinator.id','=','coordinator.user_id')
			->leftJoin('cities AS t3','t3.id','=','t1.city')
			->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
			->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
			->leftJoin('leaders AS leader','leader.leader_id','=','t1.leader')
			->leftJoin('votersinfomations AS name_leader','name_leader.id','=','leader.user_id')
			->when(!empty($city), function ($q) use ($city) {
				return $q->where('t1.city',$city);
			})
			->when(!empty($barangay), function ($q) use ($barangay) {
				return $q->where('t1.barangay',$barangay);
			})
			->where('name_coordinator.name','LIKE',"%{$search}%")
			->orWhere('name_leader.name', 'LIKE',"%{$search}%")
			->orWhere('t4.name', 'LIKE',"%{$search}%")
			->offset($start)
			->limit($limit)
			->get();
			
			
			
			$select ="t1.id,t1.group_id, name_coordinator.name as coordinator, t3.name as city, t4.name as barangay,t5.name as province,name_leader.name as leader";
			$totalFiltered = \DB::table('campaign_groups as t1')
			->select(\DB::raw($select))	
			->leftJoin('coordinators AS coordinator','coordinator.coordinator_id','=','t1.coordinator')
			->leftJoin('votersinfomations AS name_coordinator','name_coordinator.id','=','coordinator.user_id')
			->leftJoin('cities AS t3','t3.id','=','t1.city')
			->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
			->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
			->leftJoin('leaders AS leader','leader.leader_id','=','t1.leader')
			->leftJoin('votersinfomations AS name_leader','name_leader.id','=','leader.user_id')
			->when(!empty($city), function ($q) use ($city) {
				return $q->where('t1.city',$city);
			})
			->when(!empty($barangay), function ($q) use ($barangay) {
				return $q->where('t1.barangay',$barangay);
			})
			->where('name_coordinator.name','LIKE',"%{$search}%")
			->orWhere('name_leader.name', 'LIKE',"%{$search}%")
			->orWhere('t4.name', 'LIKE',"%{$search}%")
			->offset($start)
			->limit($limit)
			->count();
			
			
		}
		
		
		
		
		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['id'] = $dd->id;
				
				$row['number_of_members'] = $dd->number_of_members;
				
				
				$row['group_id'] = $dd->group_id;
				$row['province'] = $dd->province;
				$row['city'] = $dd->city;
				$row['barangay'] = $dd->barangay;
				$row['coordinator'] = $dd->coordinator;
				$row['leader'] = $dd->leader;
				
				$row['details'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary details' data-group_id=".$dd->group_id." data-id=".$dd->id."> DETAILS </a>&nbsp;";
				
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeleteMember'  data-group_id=".$dd->group_id."> Delete </a>";
				
				$data[] = $row;
			}
		}
		else
		{
			$data = [];
		}
		
			$json_data = array(
						"draw"            => intval($draw),  
						"recordsTotal"    => intval($totalData),  
						"recordsFiltered" => intval($totalFiltered), 
						"data"            => $data,
						);

		
		return response()->json($json_data);
	}
	
	
	
	public function searchAvailableMembers(Request $request)
	{
		

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$search = $request->search;
		
		
		$select ="t1.id,t1.name,t1.dob,t1.address";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		
		//->leftJoin('campaign_group_members AS t2','t2.vin_number','=','t1.vin_number')
		//->whereNull('t2.vin_number')
		
		
		->when(!empty($search), function ($q) use ($search) {
			return $q->where('t1.name', 'LIKE', "%".$search."%" );
		})
		
		->where('t1.barangay', '=',$request->barangay)
		->where('t1.city_municipality', '=',$request->city)
		->groupBy('t1.id')
		->get();
		
		
		
		$select ="t1.user_id";
		$alreadymembers = \DB::table('campaign_group_members as t1')
		->select(\DB::raw($select))
		->get();
		
		$member_array = array();
		foreach($alreadymembers as $members)
		{
			$member_array[] = $members->user_id;
			
		}
		
		
		$select ="t1.user_id";
		$coordinators = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->get();
		
		$member_coordinator = array();
		foreach($coordinators as $members)
		{
			$member_coordinator[] = $members->user_id;
		}
		
		
		$select ="t1.user_id";
		$leaders = \DB::table('leaders as t1')
		->select(\DB::raw($select))
		->get();
		
		$member_leaders = array();
		foreach($leaders as $members)
		{
			$member_leaders[] = $members->user_id;
		}
		
		
	
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$name = $dd->name;
				
				if (in_array($dd->id, $member_array))
				{
					$checkbox =  '<i class="fa fa-fw fa-check-square"></i>';
					
						$select ="coordinator.name as coordinator,leader.name as leader";
						$getdata = \DB::table('campaign_group_members as t1')
						->select(\DB::raw($select))
						->leftJoin('campaign_groups AS t2','t2.group_id','=','t1.group_id')
						->leftJoin('coordinators AS c','c.coordinator_id','=','t2.coordinator')
						->leftJoin('votersinfomations AS coordinator','coordinator.id','=','c.user_id')
						->leftJoin('leaders AS l','l.leader_id','=','t2.leader')
						->leftJoin('votersinfomations AS leader','leader.id','=','l.user_id')
						->where('t1.user_id',$dd->id)
						->first();
					
					$details =  "Assign as a Member";
					$details .=  "<br>";
					$details .=  "Coordinator: ".$getdata->coordinator;
					$details .=  "<br>";
					$details .=  "Leaders: ".$getdata->leader;
				
					  
					$name = '<a href="javascript:void(0)"  data-html="true" data-toggle="tooltip" data-placement="top" data-original-title= "'.$details.'" >'.$dd->name.'</a>';
					
				
				}
				else if (in_array($dd->id, $member_coordinator)) 
				{
					
					$checkbox =  '<i class="fa fa-fw fa-check-square"></i>**';					
					$details =  "Assign as a Coordinator";
					$name = '<a href="javascript:void(0)"  data-html="true" data-toggle="tooltip" data-placement="top" data-original-title= "'.$details.'" >'.$dd->name.'</a>';
				}
				else if (in_array($dd->id, $member_leaders)) 
				{
					
					$checkbox =  '<i class="fa fa-fw fa-check-square"></i>*';
					
					
						$select ="coordinator.name as coordinator";
						$getdata = \DB::table('leaders as t1')
						->select(\DB::raw($select))
						->leftJoin('coordinators AS c','c.coordinator_id','=','t1.coordinator')						
						->leftJoin('votersinfomations AS coordinator','coordinator.id','=','c.user_id')
						->where('t1.user_id',$dd->id)
						->first();
					
					
					$details =  "Assign as a Leader";
					$details .=  "<br>";
					$details .=  "Coordinator: ".$getdata->coordinator;
					$name = '<a href="javascript:void(0)"  data-html="true" data-toggle="tooltip" data-placement="top" data-original-title= "'.$details.'" >'.$dd->name.'</a>';
					
					
				}
				else 
				{
					$checkbox =  '<input type="checkbox" id="selected_checkbox" name="selected_checkbox"  data-vin_number="'.$dd->id.'" data-name="'.$dd->name.'" >';
				}
	
	
				$row = array();
				
				
				$row['checkbox'] = $checkbox;
				$row['vin_number'] = $dd->id;
				
				
				
				
				$row['name'] =  $name;
				
				$row['dob'] = $dd->dob;
				
				$row['address'] = $dd->address;

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
	
	
	
	
	
	public function getLeaderbelongtoCoordinator(Request $request)
	{
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.leader_id,t2.name,t2.id";
		$getcoordinator = \DB::table('leaders as t1')
		->select(\DB::raw($select))
		->join('votersinfomations as t2','t2.id','=','t1.user_id')
		->where('t1.coordinator', '=',$request->coordinator)
		->get();
	
		$data = array("data" => $getcoordinator);
		return response()->json($data);
		
	}
	
	
	public function AssignMembers(Request $request)
	{
		
		
		 if(campaign_groups::where('group_id',$request->GroupId)->count() == 0 )
		 {
						  
			$campaign_groups = new campaign_groups(); 
			$campaign_groups->group_id      =  $request->GroupId;
			$campaign_groups->city          =  $request->city;
			$campaign_groups->barangay      =  $request->barangay;
			$campaign_groups->coordinator   =  $request->coordinator;
			$campaign_groups->leader   		=  $request->leader;
			$campaign_groups->save();					
		 }
		 
		 
		 
		 // insert members 
		foreach($request->array_members as $data){
			
				$campaign_group_members = new campaign_group_members(); 
				$campaign_group_members->group_id       =  $request->GroupId;
				$campaign_group_members->user_id        =  $data['vin_number'];
				$campaign_group_members->save();	
			
		}
		
		
		echo "save";
	}
	
	public function AssignMembersModify(Request $request)
	{
		

		// insert members 
		if(!empty($request->array_members)){
			foreach($request->array_members as $data){
				
				
				
				if($data['status'] == "AddNew")
				{
				
					$campaign_group_members = new campaign_group_members(); 
					$campaign_group_members->group_id       =  $request->GroupId;
					$campaign_group_members->user_id        =  $data['vin_number'];
					$campaign_group_members->save();	
				}
			}
		}
		
		 // Delete members 
		if(!empty($request->delete_item_array)){
		
			foreach($request->delete_item_array as $dd){
				campaign_group_members::where('user_id',$dd['vin_number'])
				->where('id',$dd['id'])
				->where('group_id',$request->GroupId)
				->delete();	
			}
		
		}
		
		echo "save";
	}



	
	
	
	
	public function getMemberList(Request $request)
	{
		
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$group_id = $request->group_id;
		$id = $request->id;
		
		
		$select ="t1.id,t1.group_id, name_coordinator.name as coordinator, t3.name as city, t3.id as city_id , t4.name as barangay, t4.id as barangay_id,t5.name as province,name_leader.name as leader ";
		$details = \DB::table('campaign_groups as t1')
		->select(\DB::raw($select))
		->leftJoin('coordinators AS coordinator','coordinator.coordinator_id','=','t1.coordinator')
		->leftJoin('votersinfomations AS name_coordinator','name_coordinator.id','=','coordinator.user_id')
		->leftJoin('cities AS t3','t3.id','=','t1.city')
		->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
		->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
		->leftJoin('leaders AS leader','leader.leader_id','=','t1.leader')
		->leftJoin('votersinfomations AS name_leader','name_leader.id','=','leader.user_id')
		->where('t1.group_id','=',$group_id)
		->where('t1.id','=',$id)
		->first();
		
		

		
		$select ="t1.id,t1.group_id, t2.name as member,t2.id as user_id";
		$getallData = \DB::table('campaign_group_members as t1')
		->select(\DB::raw($select))
		->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
		->where('t1.group_id','=',$group_id)
		->get();
		
		
		
		$data = array();
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['id'] = $dd->id;
				$row['group_id'] = $dd->group_id;
				$row['member'] = $dd->member;
				$row['vin_number'] = $dd->user_id;
				$row['delete'] = "<input type='checkbox' name='delete_row' id='delete_row' data-vin_number='".$dd->user_id."'  data-id=".$dd->id." data-group_id=".$dd->group_id."  />";
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
	
	
	
	public function deleteRow(Request $request){
		
		foreach ($request->data as $dd) 
		{
			
			campaign_group_members::where('vin_number',$dd['vin_number'])
			->where('id',$dd['id'])
			->where('group_id',$dd['group_id'])
			->delete();
			
		}
	}
	
	public function DeleteMemberlist(Request $request)
	{		
		campaign_groups::where('group_id',$request->group_id)->delete();
		campaign_group_members::where('group_id',$request->group_id)->delete();
		echo 'deleted';
		
	}
	
	public function printmember(Request $requests)
    {
        $array = $requests->data_form;
        $body_data = json_decode($array);
		
		$header_data = array(
						   'city'      =>  $requests->print_city,
						   'barangay'  =>  $requests->print_barangay,
						);
						
       $header = (object) $header_data;
	   	
       // This  $data array will be passed to our PDF blade
       $datax = [
			  'user_id' 		  =>  Auth::user()->name,
			  'date_genarated'    =>  Carbon::now(),
			  'title' 			  => 'Member Data',
			  'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		
		
	    $pdf = PDF::loadView('pdf_reports.print_member', $datax);  
        $filename = trans('Member');
		return $pdf->download($filename.'.pdf');
		
    }
	
	public function exportmember(Request $requests)
    {
        $array = $requests->data_form;
        $body_data = json_decode($array);
		$excel_data = array();
		
		foreach($body_data as $datas)
		{
			
			 $excel_data[] = array(
						'# of Members' => $datas->number_of_members,
						'Leader' => $datas->leader,
					   'Coordinator' => $datas->coordinator,
					   'Province' => $datas->province,
					   'Cities/Municipalities' => $datas->city ,
					   'Barangay' => $datas->barangay
					  
				);
		}
		
		
		$filename = trans('Members');
		return Excel::download(new ExportMember($excel_data), $filename.'.xlsx');
		
    }
	
	
	
	
	
	################################## PRINT DETAILS ###########################
	
	public function printmemberdetails(Request $requests)
    {
       $body_data = $requests->data_form_member;
	   
	   
		$arrayheader = $requests->data_form_header;
        $header = json_decode($arrayheader);
		
	
	   	
       // This  $data array will be passed to our PDF blade
       $datax = [
			  'user_id' 		  =>  Auth::user()->name,
			  'date_genarated'    =>  Carbon::now(),
			  'title' 			  => 'Member list Data',
			  'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		
		
	    $pdf = PDF::loadView('pdf_reports.print_member_list', $datax);  
        $filename = trans('Member_list');
		return $pdf->download($filename.'.pdf');
		
    }
	
	
}