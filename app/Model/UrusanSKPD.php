<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UrusanSKPD extends Model
{
    protected $table		= 'REFERENSI.REF_URUSAN_SKPD';
    protected $primaryKey 	= 'URUSAN_SKPD_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function urusan(){
    	return $this->belongsTo('App\Model\Urusan','URUSAN_ID');
    }

    public function skpd(){
    	return $this->belongsTo('App\Model\SKPD','SKPD_ID');
    }

}
