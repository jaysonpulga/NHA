<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\cities;
use App\Coordinator;
use App\Leaders;
use App\campaign_groups;

use Auth;
use Hash;
use PDF;
use App\Exports\ExportLeader;
use Maatwebsite\Excel\Facades\Excel;



class CampaignGroup_LeaderController extends Controller
{

	public function leader(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'campaign_groups');
			$cities = cities::where('province_id','1')->get();
			return view('campaign_groups.leader',array('menu'=>'leader','cities'=>$cities),$arraymenu);
	}
	
	public function getCoordinatorbelongtocityandbarangay(Request $request)
	{
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.coordinator_id,t2.name,t2.id";
		$getcoordinator = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->join('votersinfomations as t2','t2.id','=','t1.user_id')
		->where('t1.barangay', '=',$request->barangay)
		->get();
	
		$data = array("data" => $getcoordinator);
		return response()->json($data);
		
	}
	
	public function searchAvailableLeader(Request $request)
	{
		

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$search = $request->search;
		
		
		$select ="t1.id,t1.name,t1.dob,t1.address";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		
		/*
		->leftJoin('coordinators AS t2','t2.vin_number','=','t1.vin_number')
		->whereNull('t2.vin_number')
		
		->leftJoin('leaders AS t3','t3.vin_number','=','t1.vin_number')
		->whereNull('t3.vin_number')
		*/
		
		
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
				$row['name'] = $name;
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
	
	
	
	public function LoadLeader(Request $request)
	{
		

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$city = $request->city;
		$barangay = $request->barangay;
		
		$limit  	 = $request->input('length');
		$start   	 = $request->input('start');
		$dir 	     = $request->input('order.0.dir');
		$search	 	 = $request->input('search.value');
		$draw 		 = $request->input('draw');
		
		$select ="t1.leader_id,t1.user_id,t2.name as leader,t7.name as coordinator,t3.name as city, t4.name as barangay,t5.name as province";
		$totalData = \DB::table('leaders as t1')
		->select(\DB::raw($select))
		->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
		->leftJoin('cities AS t3','t3.id','=','t1.city')
		->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
		->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
		->leftJoin('coordinators AS t6','t6.coordinator_id','=','t1.coordinator')
		->leftJoin('votersinfomations AS t7','t7.id','=','t6.user_id')
		->when(!empty($city), function ($q) use ($city) {
			return $q->where('t1.city',$city);
		})
		->when(!empty($barangay), function ($q) use ($barangay) {
			return $q->where('t1.barangay',$barangay);
		})
		->count();
			
		
		
		if(empty($search))
		{
			
			$select ="t1.leader_id,t1.user_id,t2.name as leader,t7.name as coordinator,t3.name as city, t4.name as barangay,t5.name as province";
			$getallData = \DB::table('leaders as t1')
			->select(\DB::raw($select))
			->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
			->leftJoin('cities AS t3','t3.id','=','t1.city')
			->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
			->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
			->leftJoin('coordinators AS t6','t6.coordinator_id','=','t1.coordinator')
			->leftJoin('votersinfomations AS t7','t7.id','=','t6.user_id')
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
			
			
			$select ="t1.leader_id,t1.user_id,t2.name as leader,t7.name as coordinator,t3.name as city, t4.name as barangay,t5.name as province";
			$getallData = \DB::table('leaders as t1')
			->select(\DB::raw($select))
			->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
			->leftJoin('cities AS t3','t3.id','=','t1.city')
			->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
			->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
			->leftJoin('coordinators AS t6','t6.coordinator_id','=','t1.coordinator')
			->leftJoin('votersinfomations AS t7','t7.id','=','t6.user_id')
			->when(!empty($city), function ($q) use ($city) {
				return $q->where('t1.city',$city);
			})
			->when(!empty($barangay), function ($q) use ($barangay) {
				return $q->where('t1.barangay',$barangay);
			})
			->where('t2.name','LIKE',"%{$search}%")
			->orWhere('t4.name', 'LIKE',"%{$search}%")
			->orWhere('t7.name', 'LIKE',"%{$search}%")
			->offset($start)
			->limit($limit)
			->get();
			
			
			$select ="t1.leader_id,t1.user_id,t2.name as leader,t7.name as coordinator,t3.name as city, t4.name as barangay,t5.name as province";
			$totalFiltered = \DB::table('leaders as t1')
			->select(\DB::raw($select))
			->leftJoin('votersinfomations AS t2','t2.id','=','t1.user_id')
			->leftJoin('cities AS t3','t3.id','=','t1.city')
			->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
			->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
			->leftJoin('coordinators AS t6','t6.coordinator_id','=','t1.coordinator')
			->leftJoin('votersinfomations AS t7','t7.id','=','t6.user_id')
			->when(!empty($city), function ($q) use ($city) {
				return $q->where('t1.city',$city);
			})
			->when(!empty($barangay), function ($q) use ($barangay) {
				return $q->where('t1.barangay',$barangay);
			})
			->where('t2.name','LIKE',"%{$search}%")
			->orWhere('t4.name', 'LIKE',"%{$search}%")
			->orWhere('t7.name', 'LIKE',"%{$search}%")
			->offset($start)
			->limit($limit)
			->count();
			
			
		}
		
		

		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				$countmemberGroup = campaign_groups::where('leader',$dd->leader_id)->count();
				
				
				$row = array();
				$row['leader_id'] = $dd->leader_id;
				$row['province'] = $dd->province;
				$row['city'] = $dd->city;
				$row['barangay'] = $dd->barangay;
				$row['coordinator'] = $dd->coordinator;
				$row['leader'] = $dd->leader;
				
				if($countmemberGroup > 0 )
				{
					$row['action'] = "";
				}
				else
				{
					
					
					$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeleteLeader'  data-leader_id=".$dd->leader_id."> Delete </a>";
				}

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
	
	public function AssignLeader(Request $request)
	{
		$Leaders = new Leaders(); 
		$Leaders->city  		 =  $request->city;
		$Leaders->barangay 		 =  $request->barangay;
		$Leaders->coordinator 	 =  $request->coordinator;
		$Leaders->user_id        =  $request->leader;
		$Leaders->rec_num        =  "";
		$Leaders->save();
		
		echo "save";
		
	}
	
	
	public function DeleteLeader(Request $request)
	{		
		Leaders::where('leader_id',$request->leader_id)->delete();
		echo 'deleted';
		
	}
	
	public function printleader(Request $requests)
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
			  'title' 			  => 'List of Leaders',
			  'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		
		
	    $pdf = PDF::loadView('pdf_reports.print_leader', $datax);  
        $filename = trans('Leader');
		return $pdf->download($filename.'.pdf');
		
    }
	
	public function exportleader(Request $requests)
    {
        $array = $requests->data_form;
        $body_data = json_decode($array);
		$excel_data = array();
		
		foreach($body_data as $datas)
		{
			
			 $excel_data[] = array(
					   'Leader' => $datas->leader,
					   'Coordinator' => $datas->coordinator,
					   'Province' => $datas->province,
					   'Cities/Municipalities' => $datas->city ,
					   'Barangay' => $datas->barangay
					  
				);
		}
		
		
		$filename = trans('Leader');
		return Excel::download(new ExportLeader($excel_data), $filename.'.xlsx');
		
    }

	
	
}