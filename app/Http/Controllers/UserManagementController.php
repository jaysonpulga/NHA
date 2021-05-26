<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\user_roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserManagementController extends Controller
{

	public function userManagement(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'userManagement');
			
			$user_roles = user_roles::all();
			return view('admin.userManagement',$arraymenu,array('user_roles'=>$user_roles));
	}
	
	
	
	
	public function loadAllUsers(Request $request)
	{
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.*,t2.name as role";
		$getallData = \DB::table('users as t1')
		->select(\DB::raw($select))
		->leftJoin('user_roles AS t2','t2.id','=','t1.role')
		->get();

		$data = array();
		
		if(!empty($getallData)){
		
			foreach ($getallData as $dd){
					
				$row = array();
				$row['id'] =  $dd->id;
				$row['name'] =  $dd->name;
				$row['email'] =  $dd->email;
				$row['role'] =  $dd->role;
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEdit'  data-id=".$dd->id."><i class='fa fa-fw fa-pencil-square-o'></i> Edit</a>&nbsp;";
				
				if($dd->id != 1)
				{
					$row['action'] .= "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDelete'  data-id=".$dd->id."> Delete </a>";
					
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
	
	public function saving_account(Request $request)
	{
		
		
		if($request->action == "create_new")
		{
			$User = new User();
			$User->name  =  $request->name;
			$User->email  =  $request->email;
			$User->role 	= $request->role;
			$User->password  =  Hash::make($request->password);
			$User->save();
		}
		else if($request->action == "update")
		{
			
			\DB::table('users')
			  ->where('id', $request->id)
              ->update([
					'name' => $request->name,
					'email' => $request->email,
					'role' => $request->role,
					'password' => Hash::make($request->password),
			  ]);
		}
		
		
		echo "save";
		

	}
	
	public function get_user(Request $request)
	{
			$getDetails = User::where('id',$request->id)->first();
			return response()->json(array('success' => true,'data'=>$getDetails));
	}
	
	
	
	
	public function delete_user(Request $request)
	{
		User::where('id',$request->id)->delete();
		echo "delete";
		
	}
	
	
	
	
}
