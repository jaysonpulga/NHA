<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\religions;


class ReligionController extends Controller
{


	public function religion(Request $request){
		
		  $arraymenu = array('parent_menu'=>'masterData','child_menu'=>'PersonalInfo');	
		  return view('PersonalInfo.religion',array('menu'=>'religion'),$arraymenu);		
	}
	
	
	public function saving_religion_info(Request $request)
	{

		if($request->action == "create_new")
		{
			$religions = new religions();
			$religions->name  	      =  $request->religion;
			$religions->save();
		}
		else if($request->action == "update")
		{
			
			\DB::table('religions')
			  ->where('id', $request->id)
              ->update([
					'name' => $request->religion
			  ]);
		}
		
		
		echo "save";
	
	}
	
	
	public function get_religion(Request $request)
	{
			$getDetails = religions::where('id',$request->id)->first();
			return response()->json(array('success' => true,'data'=>$getDetails));
	}
	
	
	
	
	public function deletereligion(Request $request)
	{
		religions::where('id',$request->id)->delete();
		echo "delete";
		
	}
	
	
	
	public function loadAllReligions(Request $request)
	{
		
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.name,t1.id";
		$getallData = \DB::table('religions as t1')
		->select(\DB::raw($select))
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['id'] =  $dd->id;
				$row['religion'] =  $dd->name;
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEditReligion'  data-id=".$dd->id."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>&nbsp;";
				$row['action'] .= "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDeleteReligion'  data-id=".$dd->id."> Delete </a>";
				
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