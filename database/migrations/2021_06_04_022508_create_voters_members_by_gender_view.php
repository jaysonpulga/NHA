<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotersMembersByGenderView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
		
        CREATE VIEW voter_member_gender
            AS
			 SELECT gender, COUNT(*) AS total_number
			 FROM votersinfomations
			 WHERE id IN (SELECT user_id FROM campaign_group_members)
			 GROUP BY gender
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voters_members_by_gender_view');
    }
}
