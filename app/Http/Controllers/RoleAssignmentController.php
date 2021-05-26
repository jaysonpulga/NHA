<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;


class RoleAssignmentController extends Controller
{

	public function Overview(Request $request){
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

		$select ="*";
		$city_coordinators = \DB::table('coordinators')
		->select(\DB::raw($select))
		->groupBy('city_municipality')
		->orderby('city_municipality','asc')
		->get();
		
		
		  return view('role_assignment.overview',array('menu'=>'Overview','city_coordinators' => $city_coordinators));	
		
	}
	
	
	public function Coordinator(Request $request){
		
		$select ="t1.city_municipality";
		$city_municipality = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->leftJoin('coordinators AS t2','t2.city_municipality','=','t1.city_municipality')
		->whereNull('t2.city_municipality')
		->groupBy('t1.city_municipality')
		->orderby('t1.city_municipality','asc')
		->get();
		
		  return view('role_assignment.coordinator',array('menu'=>'Coordinator','city_municipality' => $city_municipality));	
		
	}
	

	
	public function Subcoordinator(Request $request){
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

		$select ="*";
		$city_coordinators = \DB::table('coordinators')
		->select(\DB::raw($select))
		->groupBy('city_municipality')
		->orderby('city_municipality','asc')
		->get();
	
		return view('role_assignment.sub-coordinator',array('menu'=>'Sub-coordinator','city_coordinators' => $city_coordinators));	
		
	}
	
	public function PurokLeader(Request $request){
		
		//re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

		$select ="*";
		$city_coordinators = \DB::table('coordinators')
		->select(\DB::raw($select))
		->groupBy('city_municipality')
		->orderby('city_municipality','asc')
		->get();
		
		  return view('role_assignment.purokleader',array('menu'=>'PurokLeader','city_coordinators' => $city_coordinators));	
		
	}

}