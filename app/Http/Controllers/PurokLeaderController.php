<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Purokleader;

class PurokLeaderController extends Controller
{

	public function getSubcoandbarangaybelongtoMunicipality(Request $request)
	{
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.barangay,t1.sub_coordinator,t1.subcoordinator_id";
		$getUsers = \DB::table('subcoordinators as t1')
		->select(\DB::raw($select))
		->where('t1.city_municipality', '=',$request->municipality)
		->groupBy('t1.barangay')
		->orderby('t1.barangay','asc')
		->get();
	
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	public function getUserAssignPurokLeadertobarangay(Request $request){
		
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.vin_number,t1.name";
		$getUsers = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->leftJoin('coordinators AS t2','t2.vin_number','=','t1.vin_number')
		->leftJoin('subcoordinators AS t3','t3.vin_number','=','t1.vin_number')
		->leftJoin('purokleaders AS t4','t4.vin_number','=','t1.vin_number')
		
		->where('t1.barangay', '=',$request->barangay)
		->whereNull('t2.vin_number')
		->whereNull('t3.vin_number')
		->whereNull('t4.vin_number')
		
		->groupBy('t1.vin_number')
		->orderby('t1.name','asc')
		->get();
		$data = array("data" => $getUsers);
		return response()->json($data);
		
	}
	
	
	public function create_assign_purok_leader(Request $request)
	{
		$purokleader = $request->purokleader;
		
		
		foreach($purokleader as $data)
		{
				$Purokleader = new Purokleader(); 
				$Purokleader->city_municipality      =  $data['city_municipality'];
				$Purokleader->coordinator      	     = $data['coordinator'];
				$Purokleader->barangay      	     = $data['barangay'];
				$Purokleader->sub_coordinator      	 = $data['subcoordinator'];
				$Purokleader->purok_leader      	 = $data['purokleader'];
				$Purokleader->vin_number         	 =  $data['vin_number'];
				$Purokleader->save();		
		}
		
		
		return response()->json(array('status' => 'save'));
		
	}
	
	
	public function getPurokLeaderList(Request $request)
	{
		  //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$getallData = Purokleader::all();
		
		

		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['purokleader_id '] = $dd->purokleader_id ;
				$row['city_municipality'] = $dd->city_municipality;
				$row['coordinator'] = $dd->coordinator;
				$row['barangay'] = $dd->barangay;
				$row['sub_coordinator'] = $dd->sub_coordinator;
				$row['purok_leader'] = $dd->purok_leader;
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
	


}