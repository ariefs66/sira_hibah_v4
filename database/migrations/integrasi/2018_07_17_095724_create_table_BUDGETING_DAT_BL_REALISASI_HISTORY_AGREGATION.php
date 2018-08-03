<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBUDGETINGDATBLREALISASIHISTORYAGREGATION extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BUDGETING.DAT_BL_REALISASI_HISTORY_AGREGATION', function (Blueprint $table) {
            $table->increments('REALISASI_HISTORY_AGREGATION_ID');
            $table->integer('TAHUN');
            $table->timestamp('TANGGAL_GET_REALISASI');
            $table->integer('SKPD_ID')->unsigned();
            $table->integer('COUNT_UPDATE')->unsigned();
            $table->integer('COUNT_INSERT')->unsigned();
            $table->integer('COUNT_TOTAL')->unsigned();
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
        Schema::dropIfExists('BUDGETING.DAT_BL_REALISASI_HISTORY_AGREGATION');
    }
}
