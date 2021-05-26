<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\user_roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserRoleController extends Controller
{

	public function userRole(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'userRole');
			return view('admin.userRole',$arraymenu);
	}
	
	
	
	
	public function loadRoles(Request $request)
	{
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.*";
		$getallData = \DB::table('user_roles as t1')
		->select(\DB::raw($select))
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['id'] =  $dd->id;
				$row['name'] =  $dd->name;
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEdit'  data-id=".$dd->id."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>&nbsp;";
				
				$row['action'] .= "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDelete'  data-id=".$dd->id."> Delete </a>";
					
				
				
				
				$data[] = $row;
			}
		}
		else{
			
			$data = [];
		}
		
		$output = array("data" => $data);
		return response()->json($output);
		
		
	}
	
	public function saving_role(Request $request)
	{
		
		if($request->action == "create_new")
		{
			$user_role = new user_roles();
			
			$user_role->name  =  $request->name;
			$user_role->dashboard  		 =  (@$request->dashboard != '') ? @$request->dashboard : 0;
			$user_role->voters_database  =  (@$request->voters_database != '') ? @$request->voters_database : 0;
			$user_role->master_data      =  (@$request->master_data != '') ? @$request->master_data : 0;
			$user_role->campaign_group   =  (@$request->campaign_group != '') ? @$request->campaign_group : 0;
			$user_role->election_turnout =  (@$request->election_turnout != '') ? @$request->election_turnout : 0;
			$user_role->election_reports =  (@$request->election_reports != '') ? @$request->election_reports : 0;
			$user_role->user_settings    =  (@$request->user_settings != '') ? @$request->user_settings : 0;
			$user_role->save();
		}
		else if($request->action == "update")
		{
			
			\DB::table('user_roles')
			  ->where('id', $request->id)
              ->update([
					'name' => $request->name,
					
					'dashboard'  	   =>  (@$request->dashboard != '') ? @$request->dashboard : 0,
					'voters_database'  =>  (@$request->voters_database != '') ? @$request->voters_database : 0,
					'master_data'      =>  (@$request->master_data != '') ? @$request->master_data : 0,
					'campaign_group'   =>  (@$request->campaign_group != '') ? @$request->campaign_group : 0,
					'election_turnout' =>  (@$request->election_turnout != '') ? @$request->election_turnout : 0,
					'election_reports' =>  (@$request->election_reports != '') ? @$request->election_reports : 0,
					'user_settings'    =>  (@$request->user_settings != '') ? @$request->user_settings : 0,
					
					
			  ]);
		}
		
		
		echo "save";
		

	}
	
	public function get_role(Request $request)
	{
			$getDetails = user_roles::where('id',$request->id)->first();
			return response()->json(array('success' => true,'data'=>$getDetails));
	}
	
	
	
	
	public function delete_role(Request $request)
	{
		user_roles::where('id',$request->id)->delete();
		echo "delete";
		
	}
	
	
	
	
}
