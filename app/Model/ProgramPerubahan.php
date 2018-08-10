<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProgramPerubahan extends Model
{
	protected $table		= 'REFERENSI.REF_PROGRAM';
    protected $primaryKey 	= 'PROGRAM_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function progunit(){
    	return $this->belongsTo('App\Model\Progunit','PROGRAM_ID');
    }
    public function urusan(){
    	return $this->belongsTo('App\Model\Urusan','URUSAN_ID');
    }
    public function kegiatan(){
        return $this->hasMany('App\Model\Kegiatan','PROGRAM_ID');
    }
    public function outcome(){
        return $this->hasMany('App\Model\OutcomePerubahan','PROGRAM_ID');
    }
    public function impact(){
        return $this->hasMany('App\Model\ImpactPerubahan','PROGRAM_ID');
    }
}
