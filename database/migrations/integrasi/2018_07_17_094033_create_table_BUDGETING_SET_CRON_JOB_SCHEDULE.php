<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBUDGETINGSETCRONJOBSCHEDULE extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BUDGETING.SET_CRON_JOB_SCHEDULE', function (Blueprint $table) {
            $table->increments('CRON_JOB_ID_SCHEDULE');
            $table->string('TYPE')->unique();
            $table->date('START_DATE');
            $table->date('END_DATE');
            $table->string('LOOP_TYPE');
            $table->string('CRONTAB_PARAM');
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
        Schema::dropIfExists('BUDGETING.SET_CRON_JOB_SCHEDULE');
    }
}
