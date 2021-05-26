<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;



class PreElectionReportsController extends Controller
{

	public function reports(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'election_reports');
			return view('PreElection_reports.report',$arraymenu);
	}

}