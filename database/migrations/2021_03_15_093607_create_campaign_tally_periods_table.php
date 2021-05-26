<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTallyPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_tally_periods', function (Blueprint $table) {
            $table->id();
			$table->integer('campaign_period_id');
			$table->string('group_id');
			$table->integer('total_yes');
			$table->integer('total_no');
			$table->integer('total_undecided');
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
        Schema::dropIfExists('campaign_tally_periods');
    }
}
