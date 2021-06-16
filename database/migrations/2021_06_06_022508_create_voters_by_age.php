<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotersByAge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
        CREATE VIEW view_voters_by_age
            AS
			SELECT  
				COUNT(CASE WHEN  year(curdate())- year(t1.dob) >= 18 AND year(curdate())- year(t1.dob) <= 30  THEN 'YOUNG' END) as Young,
				COUNT(CASE WHEN  year(curdate())- year(t1.dob) >= 31 AND year(curdate())- year(t1.dob) <= 45  THEN 'Middle' END) as Middle,
				COUNT(CASE WHEN  year(curdate())- year(t1.dob) >= 46 AND year(curdate())- year(t1.dob) <= 59  THEN 'OLD' END) as OLD,
				COUNT(CASE WHEN  year(curdate())- year(t1.dob) >= 60 AND t1.dob != '0000-00-00'  THEN 'SENIOR' END) as SeniorCitizens
			 FROM votersinfomations as t1

            

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_voters_by_age');
    }
}
