<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Impact extends Model
{
    protected $table		= 'REFERENSI.REF_IMPACT';
    protected $primaryKey 	= 'IMPACT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function program(){
    	return $this->belongsTo('App\Model\Program','PROGRAM_ID');
    }
    public function satuan(){
    	return $this->belongsTo('App\Model\Satuan','SATUAN_ID');
    }
}
