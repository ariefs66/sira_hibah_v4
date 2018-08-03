<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBUDGETINGDATBLREALISASIHISTORY extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BUDGETING.DAT_BL_REALISASI_HISTORY', function (Blueprint $table) {
            $table->increments('REALISASI_HISTORY_ID');
            $table->integer('REALISASI_ID')->unsigned();
            $table->integer('BL_ID')->unsigned()->nullable();
            $table->integer('REKENING_ID')->unsigned()->nullable();
            $table->double('REALISASI_TOTAL')->nullable();
            $table->timestamp('TANGGAL_GET_REALISASI')->nullable();
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
        Schema::dropIfExists('BUDGETING.DAT_BL_REALISASI_HISTORY');
    }
}
