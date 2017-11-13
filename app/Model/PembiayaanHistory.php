<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PembiayaanHistory extends Model
{
    protected $table		= 'BUDGETING.DAT_PEMBIAYAAN_HISTORY';
    protected $primaryKey 	= 'PEMBIAYAAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subunit(){
    	return $this->belongsTo('App\Model\Subunit','SUB_ID');
    }
    public function rekening(){
    	return $this->belongsTo('App\Model\Rekening','REKENING_ID');
    }   
}
