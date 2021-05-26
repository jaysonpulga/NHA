<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Coordinator;

class CoordinatorController extends Controller
{
  
	
	public function getUserbelongtoMunicipality(Request $request){
		
		$select ="t1.vin_number,t1.name";
		$getUsers = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->where('city_municipality', '=',$request->municipality)
		->get();
	
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	
	public function getCoordinatorList(Request $request){
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$getallData = Coordinator::all();
	
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['coordinator_id'] = $dd->coordinator_id;
				$row['city_municipality'] = $dd->city_municipality;
				$row['vin_number'] = $dd->vin_number;
				$row['name'] =  $dd->name;
				
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
	
	
	
	
	public function create_assign_coordinator(Request $request){
		
		$city_municipality = $request->city_municipality;
		
		
		foreach($city_municipality as $data)
		{
				$Coordinator = new Coordinator(); 
				$Coordinator->city_municipality  =  $data['city'];
				$Coordinator->name      	     = $data['coordinator_name'];
				$Coordinator->vin_number         =  $data['coordinator_id'];
				$Coordinator->save();		
		}
		
		
		return response()->json(array('status' => 'save'));
	}
	
}