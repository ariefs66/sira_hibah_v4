<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    protected $table		= 'BUDGETING.DAT_OUTPUT';
    protected $primaryKey 	= 'OUTPUT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
    	return $this->belongsTo('App\Model\BL','BL_ID');
    }
    public function satuan(){
    	return $this->belongsTo('App\Model\Satuan','SATUAN_ID');
    }    
}
