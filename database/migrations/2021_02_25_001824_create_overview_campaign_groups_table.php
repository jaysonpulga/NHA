<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverviewCampaignGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overview_campaign_groups', function (Blueprint $table) {
            $table->id();
			$table->string('group_id');
			$table->string('city_municipality');
			$table->string('coordinator');
			$table->string('barangay');
			$table->string('subcoordinator');
			$table->string('purokleader');			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overview_campaign_groups');
    }
}
