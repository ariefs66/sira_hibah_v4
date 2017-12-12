<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UrusanKategori2 extends Model
{
    protected $table		= 'REFERENSI.REF_URUSAN_KATEGORI2';
    protected $primaryKey 	= 'URUSAN_KAT2_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function urusan(){
    	return $this->hasMany('App\Model\Urusan','URUSAN_KAT2_ID');
    }
}
