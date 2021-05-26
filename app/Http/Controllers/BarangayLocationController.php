<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\provinces;
use App\cities;
use App\barangays;
use App\precincts;




class BarangayLocationController extends Controller
{
	
	

	
	public function barangay(Request $request){
		
		$arraymenu = array('parent_menu'=>'masterData','child_menu'=>'LocationInfo');
		  return view('LocationInfo.barangay',array('menu'=>'barangay','cities'=>cities::All(),'barangays'=>barangays::All() ),$arraymenu);		
	}
	
	
	public function saving_barangay_info(Request $request)
	{

		if($request->action == "create_new")
		{
			$provinces = new barangays();
			$provinces->city_id   =  $request->city;
			$provinces->name  =  $request->barangay;
			$provinces->save();
		}
		else if($request->action == "update")
		{
			
			\DB::table('barangays')
			  ->where('id', $request->id)
              ->update([
					'name' => $request->barangay,
					'city_id' => $request->city
			  ]);
		}
		
		
		echo "save";
	
	}
	
	
	public function get_barangay(Request $request)
	{
			$getDetails = barangays::where('id',$request->id)->first();
			return response()->json(array('success' => true,'data'=>$getDetails));
	}
	
	
	
	
	public function deletebarangay(Request $request)
	{
		barangays::where('id',$request->id)->delete();
		echo "delete";
		
	}
	
	public function loadAllbarangay(Request $request)
	{
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		$no_precinct = "(SELECT COUNT(id) from precincts  where barangay_id = t1.id ) as no_precinct";
		$no_registered_voters = "(SELECT COUNT(id) from votersinfomations   where barangay = t1.id ) as no_registered_voters";
		
		
		
		
		$select_barangay = $request->select_barangay;
		
		$select ="t1.name,t1.id,t2.name as city,t3.name as province,{$no_precinct},{$no_registered_voters}";
		$getallData = \DB::table('barangays as t1')
		->join('cities as t2','t2.id','=','t1.city_id')
		->join('provinces as t3','t3.id','=','t2.province_id')
		->when(!empty($select_barangay), function ($q) use ($select_barangay) {
			return $q->where('t1.id', $select_barangay );
		})
		->select(\DB::raw($select))
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['id'] =  $dd->id;
				$row['barangay'] =  $dd->name;
				$row['city'] =  $dd->city;
				$row['province'] =  $dd->province;
				$row['no_precinct'] =  $dd->no_precinct;
				$row['no_registered_voters'] = $dd->no_registered_voters;
				
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEditBarangay'  data-id=".$dd->id."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>";
				
				if($dd->no_precinct == 0 )
				{
				
					$row['action'] .= " <a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeleteBarangay'  data-id=".$dd->id."> Delete </a>";
				
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