<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RkpPerubahan extends Model
{
    protected $table		= 'BUDGETING.RKP_PERUBAHAN';
    protected $primaryKey 	= 'RKP_PENDAPATAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subunit(){
    	return $this->belongsTo('App\Model\Subunit','SUB_ID');
    }
    public function rekening(){
    	return $this->belongsTo('App\Model\Rekening','REKENING_ID');
    }
     public function pendapatan(){
    	return $this->belongsTo('App\Model\Pendapatan','PENDAPATAN_ID');
    }
}
