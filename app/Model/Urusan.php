<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Urusan extends Model
{
	protected $table		= 'REFERENSI.REF_URUSAN';
    protected $primaryKey 	= 'URUSAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function urusanKategori1(){
    	return $this->belongsTo('App\Model\UrusanKategori1','URUSAN_KAT1_ID');
    }

    public function program(){
    	return $this->hasMany('App\Model\Program','URUSAN_ID');
    }
}
