<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotersByReligion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	 

	 
    public function up()
    {
		
		
		
        \DB::statement("
        CREATE VIEW view_voters_by_religion
            AS

			 SELECT t1.id, t1.name AS religion_name, 
			(SELECT count(id) FROM votersinfomations where religion = t1.id) as total_number
			 FROM religions as t1
			 GROUP BY t1.id,t1.name
			
			
			
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voters_by_religion');
    }
}
