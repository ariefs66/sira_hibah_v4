<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OutcomePerubahan extends Model
{
    protected $table		= 'REFERENSI.REF_OUTCOME_PERUBAHAN';
    protected $primaryKey 	= 'OUTCOME_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function program(){
    	return $this->belongsTo('App\Model\ProgramPerubahan','PROGRAM_ID');
    }
    public function satuan(){
    	return $this->belongsTo('App\Model\Satuan','SATUAN_ID');
    }    
}
