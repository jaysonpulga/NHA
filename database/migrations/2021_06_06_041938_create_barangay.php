<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
        CREATE VIEW view_barangay
            AS
           
			SELECT t1.id, t1.name AS barangay, 
			(SELECT COUNT(coordinator_id) from coordinators  where barangay = t1.id) as coordinators,
			(SELECT COUNT(leader_id) from leaders  where barangay = t1.id) as leaders,
			(SELECT COUNT(b.id) from campaign_groups as a LEFT JOIN campaign_group_members as b ON a.group_id = b.group_id  where a.barangay = t1.id ) as members,
			(SELECT COUNT(id) from votersinfomations  where barangay = t1.id) as total_voters
			 FROM barangays as t1
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
        Schema::dropIfExists('view_barangay');
    }
}
