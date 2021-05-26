<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\cities;
use App\Coordinator;
use App\electionturnouts;
use App\precincts;

use Auth;
use Hash;
use PDF;
use App\Exports\ExportTurnout;
use Maatwebsite\Excel\Facades\Excel;

class ElectionTurnOutController extends Controller
{

	public function turnout(Request $request)
	{
			\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
			
			/*
			$select ="t1.id";
			$getCountPrecint = \DB::table('precincts as t1')
			->select(\DB::raw($select))
			->leftJoin('barangays as t2','t2.id','=','t1.barangay_id')
			->leftJoin('cities as t3','t3.id','=','t2.city_id')
			->where('t3.province_id', '=',1)
			->count();
			*/
			
			$select ="t1.*";
			$getCountCluster = \DB::table('precincts as t1')
			->select(\DB::raw($select))
			->leftJoin('barangays as t2','t2.id','=','t1.barangay_id')
			->leftJoin('cities as t3','t3.id','=','t2.city_id')
			->where('t3.province_id', '=',1)
			->groupBy('t1.barangay_id','t1.cluster')
			->get();
			

			

			$select ="t1.id";
			$getCountPrecintwithTally = \DB::table('electionturnouts as t1')
			->select(\DB::raw($select))
			->where('t1.province', '=',1)
			->count();
		
			$cities = cities::where('province_id','1')->get();
			$arraymenu = array('parent_menu'=>'turnout');
			
			return view('election_turnout.turnout',array('cities'=>$cities,'getCountCluster'=>count($getCountCluster),'getCountPrecintwithTally'=>$getCountPrecintwithTally),$arraymenu);
			
	}
	
	
	public function LoadTurnout(Request $request)
	{
		
		
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.id,t1.voters_count,t1.turnout,province.name as province, city.name as city,  barangay.name as barangay, t1.cluster ";
		$getallData = \DB::table('electionturnouts as t1')
		->select(\DB::raw($select))
		->leftJoin('provinces as province','province.id','=','t1.province')
		->leftJoin('cities as city','city.id','=','t1.city')
		->leftJoin('barangays as barangay','barangay.id','=','t1.barangay')

		->get();
		
		
		$data = array();
		
		if(!empty($getallData))
		{
		
			foreach ($getallData as $dd) 
			{
				
				
				$row = array();
				$row['id'] = $dd->id;
				$row['province'] = $dd->province;
				$row['city'] = $dd->city;
				$row['barangay'] = $dd->barangay;
				$row['cluster'] = $dd->cluster;
				$row['voters_count'] = $dd->voters_count;
				$row['turnout'] = $dd->turnout;
				$row['variance'] = $dd->voters_count - $dd->turnout; 
				
				$row['action'] = "<a href='javascript:void(0)' class='btn btn-raised btn-primary btnEdit'   data-id=".$dd->id."> EDIT </a>&nbsp;";
				$row['action'] .= "<a href='javascript:void(0)' class='btn btn-raised btn-danger btnDelete' data-id=".$dd->id."> Delete </a>";
				
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
	
	
	
	public function getAvailableBarangayforTurnOut(Request $request)
	{
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.id,t1.name";
		$getbarangay = \DB::table('barangays as t1')
		->select(\DB::raw($select))
		->where('t1.city_id', '=',$request->city)
		->groupBy('t1.id')
		->orderby('t1.name','asc')
		->get();
		
		$data = array();
		if(!empty($getbarangay))
		{
			
			
			foreach ($getbarangay as $dd) 
			{
				
				$select ="t1.id";
				$getTotalPrecint = \DB::table('precincts as t1')
				->select(\DB::raw($select))
				->where('t1.barangay_id', '=',$dd->id)
				->count();
				
				$select2 ="t1.id";
				$getCountTurnOut = \DB::table('electionturnouts as t1')
				->select(\DB::raw($select2))
				->where('t1.barangay', '=',$dd->id)
				->count();
				
				
									     	
				if($getTotalPrecint > $getCountTurnOut )
				{
					
					$row = array();
					$row['id'] = $dd->id;
					$row['name'] = $dd->name;	
					$row['getTotalPrecint'] = $getTotalPrecint;		
					$row['getCountTurnOut'] = $getCountTurnOut;						
					$data[] = $row;
				}
				
			}
			
			
		}
		else
		{
			
			$data = [];
		}
	
		
		
		
	
		$data = array("data" => $data);
		return response()->json($data);
		
		
		
	}
	
	
	public function getClusterbelongtoBarangay(Request $request)
	{
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		$select ="t1.id,t1.cluster";
		$getbarangay = \DB::table('precincts as t1')
		->select(\DB::raw($select))
		->leftJoin('electionturnouts AS t2','t2.cluster','=','t1.cluster')
		->where('t1.barangay_id', '=',$request->barangay)
		->whereNull('t2.cluster')
		->groupBy('t1.cluster')
		->get();
	
		$data = array("data" => $getbarangay);
		return response()->json($data);
		
		
		
	}
	
	
	
	public function getClusterTotalVoters(Request $request)
	{
		 //re-able ONLY_FULL_GROUP_BY
		\DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		
		
		$select ="t1.id";
		$getPrecintID = \DB::table('precincts as t1')
		->select(\DB::raw($select))
		->where('t1.cluster', '=',$request->cluster)
		->get();
		
		$precint = array();
		
		foreach($getPrecintID  as $data)
		{
			$precint[] = $data->id;
		}
		
		
		$select ="t1.*";
		$getTotalVotersPrecint = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select))
		->where('t1.barangay', '=',$request->barangay)
		->where('t1.city_municipality', '=',$request->city)
		->whereIn('t1.precint_number', $precint)
		->get();
		
		$data = array("data" => $getTotalVotersPrecint,'total_voters'=>count($getTotalVotersPrecint),'precint' => $precint);
		return response()->json($data);
		
	}
	
	public function save_turnout(Request $request)
	{
			
			
		$electionturnouts = new electionturnouts();
		$electionturnouts->province   	  =  $request->province;
		$electionturnouts->city    	 	  =  $request->city;
		$electionturnouts->barangay  	  =  $request->barangay;
		$electionturnouts->cluster   	  =  $request->cluster;
		$electionturnouts->voters_count   =  $request->voters_count;
		$electionturnouts->turnout   	  =  $request->turnout;
		$electionturnouts->save();
		
		
		echo "save";
	}
	public function update_turnout(Request $request)
	{
		
		
			\DB::table('electionturnouts')
			  ->where('id', $request->id_val)
              ->update([
					'turnout' => $request->turnout_val,
			  ]);
		
		
		echo "save";
	}
	
	public function delete_turnout(Request $request)
	{
		electionturnouts::where('id',$request->id)->delete();
		echo "deleted";
	}
	
	
	
	public function editTurnout(Request $request)
	{
			$select ="t1.id, t1.voters_count, t1.turnout, city.name as city,  barangay.name as barangay, t1.cluster  ";
			$getData = \DB::table('electionturnouts as t1')
			->select(\DB::raw($select))
			->leftJoin('cities as city','city.id','=','t1.city')
			->leftJoin('barangays as barangay','barangay.id','=','t1.barangay')
			->where('t1.id', '=',$request->id)
			->first();
			
			
			$data = array("details" => $getData);
			return response()->json($data);
			
	}
	
	public function print_turnout(Request $requests)
    {
        $array = $requests->data_form;
        $body_data = json_decode($array);
		
		$header_data = array();
						
       $header = (object) $header_data;
	   	
       // This  $data array will be passed to our PDF blade
       $datax = [
			  'user_id' 		  =>  Auth::user()->name,
			  'date_genarated'    =>  Carbon::now(),
			  'title' 			  => 'Election Turnout Report',
			  'header_data'       => $header,
			  'table_data'        => $body_data,
          ];
     		
		
	    $pdf = PDF::loadView('pdf_reports.print_turnout', $datax);  
        $filename = trans('Election Turnout Report');
		return $pdf->download($filename.'.pdf');
		
    }
	
	public function excel_turnout(Request $requests)
    {
        $array = $requests->data_form;
        $body_data = json_decode($array);
		$excel_data = array();
		
		foreach($body_data as $datas)
		{
			
			 $excel_data[] = array(
					   'Province' => $datas->province,
					   'Cities/Municipalities' => $datas->city ,
					   'Barangay' => $datas->barangay,
					   'Precinct' => $datas->precinct,
					   'Voters_count' => $datas->voters_count,
					   'turnout' => $datas->turnout,
					   'variance' => $datas->variance,
					  
				);
		}
		
		
				
		
		
		$filename = trans('Election Turnout Report');
		return Excel::download(new ExportTurnout($excel_data), $filename.'.xlsx');
		
    }
	

}