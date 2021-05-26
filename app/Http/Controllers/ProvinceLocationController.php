<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\provinces;
use App\cities;
use App\barangays;
use App\precincts;




class ProvinceLocationController extends Controller
{
	
	
	public function province(Request $request){
		
		 $arraymenu = array('parent_menu'=>'masterData','child_menu'=>'LocationInfo');	
		  return view('LocationInfo.province',array('menu'=>'province'),$arraymenu);		
	}
	
	
	
	public function saving_province_info(Request $request)
	{
		//print_r($request->all());
	
		
		if($request->action == "create_new")
		{
			$provinces = new provinces(); 
			$provinces->name  =  $request->province;
			$provinces->save();
		}
		else if($request->action == "update")
		{
			
			\DB::table('provinces')
			  ->where('id', $request->id)
              ->update(['name' => $request->province]);
		}
		
		
		echo "save";
	
	}
	
	
	public function get_province(Request $request)
	{
			$getDetails = provinces::where('id',$request->id)->first();
			return response()->json(array('success' => true,'data'=>$getDetails));
	}
	
	
	
	
	public function delete_province(Request $request)
	{
		provinces::where('id',$request->id)->delete();
		echo "delete";
		
	}
	
	public function loadAllprovince(Request $request)
	{
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		$no_city = "(SELECT COUNT(id) from cities  where province_id = t1.id) as no_city";
		$no_barangay = "(SELECT COUNT(b.id) from barangays as b LEFT JOIN cities as c ON c.id = b.city_id   where c.province_id = t1.id ) as no_barangay";
		
		$no_precinct = "(SELECT COUNT(p.id) from precincts as p LEFT JOIN barangays as b ON b.id = p.barangay_id  LEFT JOIN cities as c ON c.id = b.city_id   where c.province_id = t1.id ) as no_precinct";
		
		
		$no_registered_voters = "(SELECT COUNT(v.id) from votersinfomations as v    LEFT JOIN cities as c ON c.id = v.city_municipality  where c.province_id = t1.id ) as no_registered_voters";

		
		$select ="t1.name,t1.id,{$no_city},{$no_barangay},{$no_precinct},{$no_registered_voters}";
		$getallData = \DB::table('provinces as t1')		
		->select(\DB::raw($select))
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
				
				//$countLeader = Leaders::where('coordinator',$dd->coordinator_id)->count();
				
				
				$row = array();
				$row['id'] =  $dd->id;
				$row['province'] =  $dd->name;
				$row['no_city'] =  	   $dd->no_city;
				$row['no_barangay'] =  $dd->no_barangay;
				$row['no_precinct'] =  $dd->no_precinct;
				$row['no_registered_voters'] = $dd->no_registered_voters;
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEditprovince'  data-id=".$dd->id."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>";
				
				if($dd->no_city == 0 )
				{
						$row['action'] .= " <a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeleteprovince'  data-id=".$dd->id."> Delete </a>";
				}
			
				
				$data[] = $row;
			}
		}
		else{
			
			$data = [];
		}
		
		$output = array("data" => $data);
		return response()->json($output);
		
		
	}

}