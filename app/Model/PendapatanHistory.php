<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PendapatanHistory extends Model
{
    protected $table		= 'BUDGETING.DAT_PENDAPATAN_HISTORY';
    protected $primaryKey 	= 'PENDAPATAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subunit(){
    	return $this->belongsTo('App\Model\Subunit','SUB_ID');
    }
    public function rekening(){
    	return $this->belongsTo('App\Model\Rekening','REKENING_ID');
    }
}
