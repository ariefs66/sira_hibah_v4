<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
	protected $table		= 'REFERENSI.REF_SATUAN';
    protected $primaryKey 	= 'SATUAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
    
    public function indikator(){
        return $this->hasMany('App\Model\Indikator');
    }
    public function outcome(){
        return $this->hasMany('App\Model\Outcome');
    }
    public function output(){
        return $this->hasMany('App\Model\Output');
    }
    public function impact(){
        return $this->hasMany('App\Model\Impact');
    }
    public function outputMaster(){
        return $this->hasMany('App\Model\OutputMaster');
    }
}
