<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\barangays;

class PreElectionReportsController extends Controller
{

	public function reports(Request $request)
	{		
			$barangays = barangays::all();
			$arraymenu = array('parent_menu'=>'election_reports','barangays'=>$barangays);
			return view('PreElection_reports.report',$arraymenu);
	}

}