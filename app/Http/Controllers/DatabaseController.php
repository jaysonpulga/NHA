<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\religions;

class DatabaseController extends Controller
{
  
	
	
	public function voters(Request $request){
			
			$arraymenu = array('parent_menu'=>'database');	
			$religions = religions::select('id','name')->get();
			return view('database.voters',array('menu'=>'voters','religions'=>$religions),$arraymenu);		
	}
	
	
	public function update_information(Request $request){
		
		//print_r($request->all());
		
		$update = \DB::table('votersinfomations') ->where('vin_number', $request->hiddenid)
			->update( 
				[ 
				'address' => (@$request->modify_address != '') ? @$request->modify_address : "",
				'mobile_number' => (@$request->modify_mobile_number != '') ? @$request->modify_mobile_number : "" ,
				'status' =>   (@$request->modify_status != '') ? @$request->modify_status : "",
				'remarks' =>  (@$request->modify_remarks != '') ? @$request->modify_remarks : "",
				'religion' => (@$request->modify_religions != '') ? @$request->modify_religions : "",
			]); 
			
		echo "save";	
		
	}
	
	
	public function updatecluster(Request $request){
		
		 $hiddenbarangay = $request->hiddenbarangay;
		 $hiddenprecinct = $request->hiddenprecinct;
		 $cluster = $request->cluster;
		 
		 
		 $affected = \DB::table('votersinfomations')
              ->where('barangay', $hiddenbarangay)
			  ->where('precint_number', $hiddenprecinct)
              ->update(['cluster' => $cluster]);
			  
		echo "save";	  
		 
	}
	
	
	
		public function getVotersList(Request $request){
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.*,t2.name as religion,t2.id as religion_id, cities.name as city,  barangays.name as barangay,  precincts.name as precinct,  precincts.cluster as cluster  ";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->leftJoin('religions AS t2','t2.id','=','t1.religion')
		->leftJoin('cities AS cities','cities.id','=','t1.city_municipality')
		->leftJoin('barangays AS barangays','barangays.id','=','t1.barangay')
		->leftJoin('precincts AS precincts','precincts.id','=','t1.precint_number')
		->get();
		
	
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				$vin = substr($dd->vin_number,0,11)."...";
				
				

				
				if(strlen($dd->address) > 25 )
				{
					$add = substr($dd->address,0,22)."...";
					
					$address = '<a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="'.$dd->address.'">'.$add.'</a>';
				}
				else{
					$address = $dd->address;
				}
				
				
				if(strlen($dd->name) > 20 )
				{
					$namex = substr($dd->name,0,20)."...";
					
					$name = '<a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="'.$dd->name.'">'.$namex.'</a>';
				}
				else{
					$name = $dd->name;
				}
				
				
				
				$row = array();
				
				$row['vin_number'] = '<a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="'.$dd->vin_number.'">'.$vin.'</a>';
				$row['name'] =  $name;
				$row['gender'] = $dd->gender;
				$row['dob'] =   Carbon::parse(@$dd->dob)->format('m/d/Y');
				$row['age'] = $dd->age;
				$row['mobile_number'] = $dd->mobile_number;
				
				//$row['address'] = '<a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="'.$dd->address.'">'.$address.'</a>';
				
				$row['address'] = $address;
				
				
				$row['barangay'] = $dd->barangay;
				$row['city_municipality'] = $dd->city;
				$row['precint_number'] = $dd->precinct;
				$row['cluster'] = $dd->cluster;

				
				$row['IS_SC_TAG'] = $dd->IS_SC_TAG;
				$row['IS_PWD_TAG'] = $dd->IS_PWD_TAG;
				$row['IS_IL_TAG'] = $dd->IS_IL_TAG;
				
				$row['complete_vin_number'] = $dd->vin_number;
				$row['complete_name'] = $dd->name;
				$row['complete_address'] = $dd->address;
				
				
				$row['status'] = $dd->status;
				$row['remarks'] = $dd->remarks;
				
				$row['religion'] = $dd->religion;
				$row['religion_id'] = $dd->religion_id;
				
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
	
	
	
	
	/*
	public function city(Request $request){
		
		  return view('database.city',array('menu'=>'city'));	
		
	}
	
	public function barangay(Request $request){
		
		  return view('database.barangay',array('menu'=>'barangay'));	
		
	}
	
	public function precint(Request $request){
		
		  return view('database.precint',array('menu'=>'precint'));	
	}
	*/
	
	
	/*

	
	public function getCitiesList(Request $request){
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.city_municipality";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->groupBy('t1.city_municipality')
		->orderby('t1.city_municipality','asc')
		->get();

		$data = array();
		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
					
				$row = array();
				$row['city'] =  $dd->city_municipality;
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
	
	public function getBarangayList(Request $request){
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.city_municipality,t1.barangay";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->groupBy('t1.barangay')
		->orderby('t1.city_municipality','asc')
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['city'] =  $dd->city_municipality;
				$row['barangay'] =  $dd->barangay;
				$data[] = $row;
			}
		}
		else{
			
			$data = [];
		}
		
		$output = array("data" => $data);
		return response()->json($output);
		
		
	}
	
	
	
	public function getPrecintList(Request $request){
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.city_municipality,t1.barangay,t1.precint_number,t1.cluster";
		$getallData = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->orderby('t1.city_municipality','asc')
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['city'] =  $dd->city_municipality;
				$row['barangay'] =  $dd->barangay;
				$row['barangay_placeholder'] =  "";
				
				$row['precint_number'] =  $dd->precint_number;
				$row['cluster'] =  $dd->cluster;
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary edit'  data-cluster=".$dd->cluster." data-barangay=".$dd->barangay."  data-precinct=".$dd->precint_number."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>";
				$data[] = $row;
			}
		}
		else{
			
			$data = [];
		}
		
		$output = array("data" => $data);
		return response()->json($output);
		
		
	}
	*/
	
	

	
	
	
	
}
