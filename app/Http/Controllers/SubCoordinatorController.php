<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Subcoordinator;


class SubCoordinatorController extends Controller
{
  
	
	public function getbarangaybelongtoMunicipality(Request $request){
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.barangay";
		$getUsers = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		
		->leftJoin('subcoordinators AS t2','t2.barangay','=','t1.barangay')
		->whereNull('t2.barangay')
		
		->where('t1.city_municipality', '=',$request->municipality)
		->groupBy('t1.barangay')
		->orderby('t1.barangay','asc')
		->get();
	
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	public function getUserbelongtobarangay(Request $request){
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.vin_number,t1.name";
		$getUsers = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->leftJoin('coordinators AS t2','t2.vin_number','=','t1.vin_number')
		->where('barangay', '=',$request->barangay)
		->whereNull('t2.vin_number')
	
		
		->groupBy('t1.vin_number')
		->orderby('t1.name','asc')
		->get();
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	
	
	public function getSubCoordinatorList(Request $request){
		
		    //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$getallData = Subcoordinator::all();
		
		

		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['subcoordinator_id'] = $dd->subcoordinator_id;
				$row['city_municipality'] = $dd->city_municipality;
				$row['coordinator'] = $dd->coordinator;
				$row['barangay'] = $dd->barangay;
				$row['sub_coordinator'] = $dd->sub_coordinator;
				$row['vin_number'] =  $dd->vin_number;
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
	
	
	
	
	public function create_assign_sub_coordinator(Request $request){
		
		$subcoordinator = $request->subcoordinator;
		
		
		foreach($subcoordinator as $data)
		{
				$Subcoordinator = new Subcoordinator(); 
				$Subcoordinator->city_municipality      =  $data['city_municipality'];
				$Subcoordinator->coordinator      	     = $data['coordinator'];
				$Subcoordinator->barangay      	     = $data['barangay'];
				$Subcoordinator->sub_coordinator      	 = $data['sub_coordinator'];
				$Subcoordinator->vin_number         	 =  $data['vin_number'];
				$Subcoordinator->save();		
		}
		
		
		return response()->json(array('status' => 'save'));
	}
	
}