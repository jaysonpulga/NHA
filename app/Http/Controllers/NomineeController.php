<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class NomineeController extends Controller
{

	public function nominee(Request $request)
	{		
			$arraymenu = array('parent_menu'=>'nominee');
			return view('admin.nominee',$arraymenu);
	}


}
