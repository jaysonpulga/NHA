<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionturnoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electionturnouts', function (Blueprint $table) {
            $table->id();
			
			$table->bigInteger('province')->unsigned()->index()->nullable();
			$table->foreign('province')->references('id')->on('provinces')->onDelete('cascade');
			
			$table->bigInteger('city')->unsigned()->index()->nullable();
			$table->foreign('city')->references('id')->on('cities')->onDelete('cascade');
			
			$table->bigInteger('barangay')->unsigned()->index()->nullable();
			$table->foreign('barangay')->references('id')->on('barangays')->onDelete('cascade');
			
		
			$table->string('cluster', 5);
			
			$table->integer('voters_count');
			$table->integer('turnout');
			
			
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
        Schema::dropIfExists('electionturnouts');
    }
}
