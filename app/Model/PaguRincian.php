<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaguRincian extends Model
{
    protected $table		= 'BUDGETING.DAT_PAGU_RINCIAN';
    protected $primaryKey 	= 'PAGRIN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function skpd(){
    	return $this->belongsTo('App\Model\SKPD','SKPD_ID');
    }

    public function bl(){
    	return $this->belongsTo('App\Model\BL','BL_ID');
    }
}
