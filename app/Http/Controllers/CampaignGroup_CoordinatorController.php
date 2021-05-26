<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\cities;
use App\Coordinator;
use App\Leaders;

use Auth;
use Hash;
use PDF;
use App\Exports\ExportCoordinator;
use Maatwebsite\Excel\Facades\Excel;

class CampaignGroup_CoordinatorController extends Controller
{

	public function coordinator(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'campaign_groups');	
			$cities = cities::where('province_id','1')->get();
			return view('campaign_groups.coordinator',array('menu'=>'coordinator','cities'=>$cities),$arraymenu);
	}

	
	
	public function searchAvailableCoordinator(Request $request)
	{
		

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$search = $request->search;
		
		
		$select ="t1.vin_number,t1.name,t1.dob,t1.address";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		
		/*
		->leftJoin('coordinators AS t2','t2.vin_number','=','t1.vin_number')
		->whereNull('t2.vin_number')
		*/
		
		
		->when(!empty($search), function ($q) use ($search) {
			return $q->where('t1.name', 'LIKE', "%".$search."%" );
		})
		
		->where('t1.barangay', '=',$request->barangay)
		->where('t1.city_municipality', '=',$request->city)
		->groupBy('t1.vin_number')
		->get();
		
		
		$select ="t1.vin_number";
		$alreadymembers = \DB::table('campaign_group_members as t1')
		->select(\DB::raw($select))
		->get();
		
		$member_array = array();
		foreach($alreadymembers as $members)
		{
			$member_array[] = $members->vin_number;
			
		}
		
		
		$select ="t1.vin_number";
		$coordinators = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->get();
		
		$member_coordinator = array();
		foreach($coordinators as $members)
		{
			$member_coordinator[] = $members->vin_number;
		}
		
		
		$select ="t1.vin_number";
		$leaders = \DB::table('leaders as t1')
		->select(\DB::raw($select))
		->get();
		
		$member_leaders = array();
		foreach($leaders as $members)
		{
			$member_leaders[] = $members->vin_number;
		}
		
		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$name = $dd->name;
				
				if (in_array($dd->vin_number, $member_array))
				{
					$checkbox =  '<i class="fa fa-fw fa-check-square"></i>';
					
						$select ="coordinator.name as coordinator,leader.name as leader";
						$getdata = \DB::table('campaign_group_members as t1')
						->select(\DB::raw($select))
						->leftJoin('campaign_groups AS t2','t2.group_id','=','t1.group_id')
						->leftJoin('coordinators AS c','c.coordinator_id','=','t2.coordinator')
						->leftJoin('votersinfomations AS coordinator','coordinator.vin_number','=','c.vin_number')
						->leftJoin('leaders AS l','l.leader_id','=','t2.leader')
						->leftJoin('votersinfomations AS leader','leader.vin_number','=','l.vin_number')
						->where('t1.vin_number',$dd->vin_number)
						->first();
					
					$details =  "Assign as a Member";
					$details .=  "<br>";
					$details .=  "Coordinator: ".$getdata->coordinator;
					$details .=  "<br>";
					$details .=  "Leaders: ".$getdata->leader;
				
					  
					$name = '<a href="javascript:void(0)"  data-html="true" data-toggle="tooltip" data-placement="top" data-original-title= "'.$details.'" >'.$dd->name.'</a>';
					
				
				}
				else if (in_array($dd->vin_number, $member_coordinator)) 
				{
					
					$checkbox =  '<i class="fa fa-fw fa-check-square"></i>**';					
					$details =  "Assign as a Coordinator";
					$name = '<a href="javascript:void(0)"  data-html="true" data-toggle="tooltip" data-placement="top" data-original-title= "'.$details.'" >'.$dd->name.'</a>';
				}
				else if (in_array($dd->vin_number, $member_leaders)) 
				{
					
					$checkbox =  '<i class="fa fa-fw fa-check-square"></i>*';
					
					
						$select ="coordinator.name as coordinator";
						$getdata = \DB::table('leaders as t1')
						->select(\DB::raw($select))
						->leftJoin('coordinators AS c','c.coordinator_id','=','t1.coordinator')						
						->leftJoin('votersinfomations AS coordinator','coordinator.vin_number','=','c.vin_number')
						->where('t1.vin_number',$dd->vin_number)
						->first();
					
					
					$details =  "Assign as a Leader";
					$details .=  "<br>";
					$details .=  "Coordinator: ".$getdata->coordinator;
					$name = '<a href="javascript:void(0)"  data-html="true" data-toggle="tooltip" data-placement="top" data-original-title= "'.$details.'" >'.$dd->name.'</a>';
					
					
				}
				else 
				{
					$checkbox =  '<input type="checkbox" id="selected_checkbox" name="selected_checkbox"  data-vin_number="'.$dd->vin_number.'" >';
				}
	
				
				$row = array();
				$row['checkbox'] = $checkbox;
				$row['vin_number'] = $dd->vin_number;
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
	
	
	
	public function LoadCoordinator(Request $request)
	{
		

		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$city = $request->city;
		$barangay = $request->barangay;
		
		$select ="t1.coordinator_id,t1.vin_number,t2.name as coordinator,t3.name as city, t4.name as barangay,t5.name as province";
		$getallData = \DB::table('coordinators as t1')
		->select(\DB::raw($select))
		->leftJoin('votersinfomations AS t2','t2.vin_number','=','t1.vin_number')
		->leftJoin('cities AS t3','t3.id','=','t1.city')
		->leftJoin('barangays AS t4','t4.id','=','t1.barangay')
		->leftJoin('provinces AS t5','t5.id','=','t3.province_id')
		->when(!empty($city), function ($q) use ($city) {
			return $q->where('t1.city',$city);
		})
		->when(!empty($barangay), function ($q) use ($barangay) {
			return $q->where('t1.barangay',$barangay);
		})
		->get();
		
		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				$countLeader = Leaders::where('coordinator',$dd->coordinator_id)->count();
				
				
				$row = array();
				$row['coordinator_id'] = $dd->coordinator_id;
				$row['province'] = $dd->province;
				$row['city'] = $dd->city;
				$row['barangay'] = $dd->barangay;
				$row['coordinator'] = $dd->coordinator;
				
				
				if($countLeader > 0 )
				{
					$row['action'] = "";
				}
				else
				{
					$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeleteCoordinator'  data-coordinator_id=".$dd->coordinator_id."> Delete </a>";
					
				}
				
				

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
	
	
	
	public function AssignCoordinator(Request $request)
	{
		$Coordinator = new Coordinator(); 
		$Coordinator->city  		 =  $request->city;
		$Coordinator->barangay 		 =  $request->barangay;
		$Coordinator->vin_number     =  $request->coordinator_id;
		$Coordinator->save();
		
		echo "save";
		
	}
	
	
	public function DeleteCoordinator(Request $request)
	{		
		Coordinator::where('coordinator_id',$request->coordinator_id)->delete();
		echo 'deleted';
		
	}

	public function printcoordinator(Request $requests)
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
			  'title' 			  => 'List of Coordinators',
			  'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		
		
	    $pdf = PDF::loadView('pdf_reports.print_coordinator', $datax);  
        $filename = trans('Coordinator');
		return $pdf->download($filename.'.pdf');
		
    }
	
	public function exportcoordinator(Request $requests)
    {
        $array = $requests->data_form;
        $body_data = json_decode($array);
		$excel_data = array();
		
		foreach($body_data as $datas)
		{
			
			 $excel_data[] = array(
					   'Coordinator' => $datas->coordinator,
					   'Province' => $datas->province,
					   'Cities/Municipalities' => $datas->city ,
					   'Barangay' => $datas->barangay
					  
				);
		}
		
		
		$filename = trans('Coordinator');
		return Excel::download(new ExportCoordinator($excel_data), $filename.'.xlsx');
		
    }


}	
