<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotersinfomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votersinfomations', function (Blueprint $table) {
             $table->id();
			 $table->string('vin_number')->unique();
			 $table->string('name');
			 $table->string('address');
			 $table->string('barangay');
			 $table->string('city/municipality');
			 $table->string('gender');
			 $table->date('dob');
			 $table->integer('age');
			 $table->string('IS_SC_TAG',10)->nullable();
			 $table->string('IS_PWD_TAG',10)->nullable();
			 $table->string('IS_IL_TAG',10)->nullable();
			 $table->integer('mobile_number')->nullable();
			 $table->string('precint_number');
			 $table->string('cluster');
			 $table->string('IS_COORD_TAG',10)->nullable();
			 $table->string('IS_SUB_COORD_TAG',10)->nullable();
			 $table->string('IS_PL_TAG','10')->nullable();
			
			 
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
        Schema::dropIfExists('votersinfomations');
    }
}
