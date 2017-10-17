<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BTLCek extends Model
{
    protected $table		= 'BUDGETING.DAT_BTL_CEK';
    protected $primaryKey 	= 'BTL_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subunit(){
    	return $this->belongsTo('App\Model\Subunit','SUB_ID');
    }
    public function urusan(){
    	return $this->belongsTo('App\Model\Urusan','BTL_URUSAN_ID');
    }
    public function program(){
    	return $this->belongsTo('App\Model\Program','BTL_PROGRAM_ID');
    }
    public function koor(){
    	return $this->belongsTo('App\Model\SKPD','BTL_SKPD_ID');
    }
    public function rekening(){
    	return $this->belongsTo('App\Model\Rekening','REKENING_ID');
    }
}
