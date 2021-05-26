<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		
		//re-able ONLY_FULL_GROUP_BY
       \DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
	   
	   //Total Voters and Projected Voters
	   $TotalVoters  = \DB::table('votersinfomations')->count();
	   $TotalprojectedVoters  = \DB::table('campaign_group_members')->count();
	   
	   
	   $getMothchart  = \DB::table('campaign_group_members')
								->selectRaw('year(created_at) year, monthname(created_at) month, count(*) data')
								->where(\DB::raw('YEAR(created_at)'), '=', '2021' )
								 ->groupBy('year', 'month')
								 ->orderBy('year', 'desc')
								 ->get();
	   
	   $months = array(
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
		);
		
		
		
	 
	   $getMonth = array();
	   foreach( $months as $data)
	   {	
			$ss = (object) array(
					 'year' => "", 
					 'month' => "",
					 'data' => 0
					
			);
			foreach($getMothchart as $dd){
				
				if($data  == $dd->month)
				{
					$ss = $dd;
				}
				
			}

			$getMonth[$data] = $ss;
		   
	   }
	   
	   
	   $coordinators = "(SELECT COUNT(coordinator_id) from coordinators  where barangay = t1.id) as coordinators";
	   $leaders      = "(SELECT COUNT(leader_id) from leaders  where barangay = t1.id) as leaders";
	   $members      = "(SELECT COUNT(b.id) from campaign_groups as a LEFT JOIN campaign_group_members as b ON a.group_id = b.group_id  where a.barangay = t1.id ) as members";
	   $totalvoters  = "(SELECT COUNT(vin_number) from votersinfomations  where barangay = t1.id) as total_voters";
	
	   //member by barangay
		$select2 ="t1.id,t1.name , {$coordinators},{$leaders},{$members},{$totalvoters} ";
		$barangays = \DB::table('barangays as t1')
		->select(\DB::raw($select2))
		->get();
	   
	   	
		$databarangay = array();
		foreach($barangays as  $barangay)
		{
			
			$databarangay[] = array(
			
								'id' => $barangay->id,
								'name'=> $barangay->name .":".$barangay->total_voters,
								'coordinators' => $barangay->coordinators,
								'leaders' => $barangay->leaders,
								'members' => $barangay->members,
								'total_voters' => $barangay->total_voters,
								
						);
		}
		
		/*
		echo "<pre>";
		print_r($databarangay);
		echo "</pre>";
		exit;
		*/
		
	   
	   
	   
	   //Religions
		$select2 ="t1.name,t2.name as religion";
		$getallReligions = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select2))
		->leftJoin('religions AS t2','t2.id','=','t1.religion')
		->get();	
		
		$religions = array();
		foreach($getallReligions  as $data)
		{	
			if($data->religion == "")
			{
				
				$religions['Undetermined'][] = $data;
			}
			else
			{
				$religions[$data->religion][] = $data;
			}
			
		}
		
		$background_colors = array('#4698d4', '#81ae0a', '#e82b96', '#01a096', '#FF3838');
		$i = 0;
		$dataReligion = array();
		foreach($religions as $key => $religion){
			
			$count = 1;
			foreach($religion as $datax)
			{
				$count++;
			}

			$arr = array(
						'count' => $count,
						'backgroundColor' => $background_colors[$i],
						'percentage' => number_format (($count * 100) / $TotalVoters ,2),
			);
			
			$dataReligion[$key] = $arr;
			
			$i++;
		}
		
	
	   
	   // GET FEMALE AND MALE
	    $voterMale  = \DB::table('votersinfomations')->where('gender', ['M'])->count();	
	    $voterFemale  = \DB::table('votersinfomations')->where('gender', ['F'])->count();	
		
		
		
		  //Voters Gender
		$select2 ="t1.*";
		$getGenderVoters = \DB::table('votersinfomations as t1')
		->select(\DB::raw($select2))
		->Join('campaign_group_members AS t2','t2.vin_number','=','t1.vin_number')
		->get();
	
		$genderVoters = array();
		foreach($getGenderVoters  as $data)
		{	
			$genderVoters[$data->gender][] = $data;
			
		}
		
		$voterMemberMale  = count($genderVoters['M']);
		$voterMemberFemale  = count($genderVoters['F']);
		
		$gender = array('Male' => $voterMale , 'Female' => $voterFemale, 'voterMemberMale' => $voterMemberMale, 'voterMemberFemale' => $voterMemberFemale );
		
		/*
		echo "<pre>";
		print_r(count($genderVoters['F']));
		print_r(count($genderVoters['M']));
		echo "</pre>";
		exit;
		*/
		
		
		
		
		
		
		
		
		
		
		$age  = \DB::table('votersinfomations')->select('*')->get();		

		if(!empty($age)){
		
			$age18to30  = 0;
			$age31to45  = 0;
			$age46to59  = 0;
			$age60plus  = 0;


		
		
			foreach($age  as $value)
			{
				
					$years = \Carbon\Carbon::parse($value->dob)->diff(\Carbon\Carbon::now())->format('%y');
				
					if($years >= 18 && $years <= 30)
					{
						$age18to30 = $age18to30 + 1;
					}
					else if($years >= 31 && $years <= 45)
					{
						$age31to45 = $age31to45 + 1;
					}
					else if($years >= 46 && $years <= 59)
					{
						$age46to59 = $age46to59 + 1;
					}
					else if($years >= 60)
					{
						$age60plus = $age60plus + 1;
					}
			}
			
			
					
			
		}
		
		$arraymenu = array('parent_menu'=>'dashboard');	
		
		$ageData = array( 
						'age18to30'  => $age18to30, 
						'age31to45'  => $age31to45,
						'age46to59'  => $age46to59,						
						'age60plus'  => $age60plus, 
					);

		
        return view( 'dashboard', array( 
					'ChartSex'=> (object)$gender , 
					'ChartAge' => (object)$ageData ,
					'TotalVoters' => $TotalVoters, 
					'TotalprojectedVoters' => $TotalprojectedVoters, 
					'religions' => $religions, 
					'dataReligion' => ($dataReligion), 
					'MonthChart' => $getMonth,
					'barangays' => $databarangay,
					
					),
					$arraymenu
				);
    }
	
	
}
