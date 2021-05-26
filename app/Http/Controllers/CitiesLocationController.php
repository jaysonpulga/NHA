<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\provinces;
use App\cities;
use App\barangays;
use App\precincts;




class CitiesLocationController extends Controller
{
	
	
	public function city(Request $request){
		
		  $arraymenu = array('parent_menu'=>'masterData','child_menu'=>'LocationInfo');
		  return view('LocationInfo.city',array('menu'=>'city','provinces'=>provinces::All(),'cities'=>cities::All()),$arraymenu);		
	}
	
	
	public function saving_city_info(Request $request)
	{

		if($request->action == "create_new")
		{
			$provinces = new cities();
			$provinces->province_id   =  $request->province;
			$provinces->name  =  $request->city;
			$provinces->save();
		}
		else if($request->action == "update")
		{
			
			\DB::table('cities')
			  ->where('id', $request->id)
              ->update([
					'name' => $request->city,
					'province_id' => $request->province
			  ]);
		}
		
		
		echo "save";
	
	}
	
	
	public function get_city(Request $request)
	{
			$getDetails = cities::where('id',$request->id)->first();
			return response()->json(array('success' => true,'data'=>$getDetails));
	}
	
	
	
	
	public function deletecity(Request $request)
	{
		cities::where('id',$request->id)->delete();
		echo "delete";
		
	}
	
	public function loadAllCities(Request $request)
	{
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		
		$no_barangay = "(SELECT COUNT(id) from barangays where city_id = t1.id ) as no_barangay";
		$no_precinct = "(SELECT COUNT(p.id) from precincts as p LEFT JOIN barangays as b ON b.id = p.barangay_id  LEFT JOIN cities as c ON c.id = b.city_id   where b.city_id = t1.id ) as no_precinct";
		$no_registered_voters = "(SELECT COUNT(id) from votersinfomations   where city_municipality = t1.id ) as no_registered_voters";
		
		
		
		$select_city = $request->select_city;
		
		$select ="t1.name,t1.id,t2.name as province,{$no_barangay},{$no_precinct},{$no_registered_voters}";
		$getallData = \DB::table('cities as t1')
		->join('provinces as t2','t2.id','=','t1.province_id')
		
		->when(!empty($select_city), function ($q) use ($select_city) {
			return $q->where('t1.id', $select_city );
		})
		
		->select(\DB::raw($select))
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['id'] =  $dd->id;
				$row['city'] =  $dd->name;
				$row['province'] =  $dd->province;
				$row['no_barangay'] =  $dd->no_barangay;
				$row['no_precinct'] =  $dd->no_precinct;
				$row['no_registered_voters'] = $dd->no_registered_voters;
				
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEditcity'  data-id=".$dd->id."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>";
				
				if($dd->no_barangay == 0 )
				{
				
					$row['action'] .= " <a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeletecity'  data-id=".$dd->id."> Delete </a>";
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