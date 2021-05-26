<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Carbon\Carbon;

use App\votersinfomations;
class ImportController extends Controller
{
  
	
	public function importdata(){
		
		 return view('importdata');
	}
	
	
	public function importExcel(Request $request)
	{
		
			
		
			$arr_file = explode('.', $_FILES['file']['name']);
			
			$extension = end($arr_file);
	
			if('csv' == $extension){
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
	
			$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			
			$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
			$highestColumn = $spreadsheet->getActiveSheet()->getHighestColumn();
			
			$i = 1;
			
			foreach($sheetData as $data)
			{
					if($data[0] == "VIN NUMBER")
					{
						
						
					}
					else
					{
						
						
							$vin_number = $data[0];
							$name = $data[1];
							$address = $data[2];
							$barangay = $data[3];
							$city = $data[4];
							$gender = $data[5];
							$dob = Carbon::parse($data[6])->format('Y-m-d');;
							$age = str_replace(' ', '',$data[7]);
							$IS_SC_TAG = $data[8];
							$IS_PWD_TAG = $data[9];
							$IS_IL_TAG = $data[10];
							$mobile_number = $data[11];
							$precint_number = $data[12];
							$cluster = $data[13];
							
							
							$votersinfomations = new votersinfomations(); 
							$votersinfomations->vin_number       =  $vin_number;
							$votersinfomations->name       =  $name;
							$votersinfomations->address       =  $address;
							$votersinfomations->barangay       =  $barangay;
							$votersinfomations->city_municipality       =  $city;
							$votersinfomations->gender       =  $gender;
							$votersinfomations->dob       =  Carbon::parse(@$dob)->format('Y-m-d');
							$votersinfomations->age       =  $age;
							$votersinfomations->IS_SC_TAG       =  $IS_SC_TAG;
							$votersinfomations->IS_PWD_TAG       =  $IS_PWD_TAG;
							$votersinfomations->IS_IL_TAG       =  $IS_IL_TAG;
							$votersinfomations->mobile_number       =  $mobile_number;
							$votersinfomations->precint_number       =  $precint_number;
							$votersinfomations->cluster       =  $cluster;
							$votersinfomations->save();
							
							
						
					
						$i++;
					}
					
				
			}
			
			
			echo  "save recrod = " .$i;
			
			
			
			exit;
			
		
			for($row=2; $row<=$highestRow; $row++){
				
				
				$vin_number = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
				$name = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
				$address = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
				$barangay = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
				$city = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
				echo $gender = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
				echo "<br>";
				echo $dob = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
				echo "<br>";
				echo $age = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue();
				echo "<br>";
				$IS_SC_TAG = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue();
				$IS_PWD_TAG = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(10, $row)->getValue();
				$IS_IL_TAG = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(11, $row)->getValue();
				$mobile_number = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(12, $row)->getValue();
				$precint_number = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(13, $row)->getValue();
				$cluster = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(14, $row)->getValue();
				
			exit;
				
				/*
				$votersinfomations = new votersinfomations(); 
				$votersinfomations->vin_number       =  $vin_number;
				$votersinfomations->name       =  $name;
				$votersinfomations->address       =  $address;
				$votersinfomations->barangay       =  $barangay;
				$votersinfomations->city_municipality       =  $city;
				$votersinfomations->gender       =  $gender;
				$votersinfomations->dob       =  Carbon::parse(@$dob)->format('Y-m-d');
				$votersinfomations->age       =  $age;
				$votersinfomations->IS_SC_TAG       =  $IS_SC_TAG;
				$votersinfomations->IS_PWD_TAG       =  $IS_PWD_TAG;
				$votersinfomations->IS_IL_TAG       =  $IS_IL_TAG;
				$votersinfomations->mobile_number       =  $mobile_number;
				$votersinfomations->precint_number       =  $precint_number;
				$votersinfomations->cluster       =  $cluster;
				$votersinfomations->save();
				*/
				
				
				$i++;
				
			}
				 
		
			echo  "save recrod = " .$i;
		 
		
		
	}
	
	
}
