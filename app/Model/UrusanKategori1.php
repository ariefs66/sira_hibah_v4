<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UrusanKategori1 extends Model
{
    protected $table		= 'REFERENSI.REF_URUSAN_KATEGORI1';
    protected $primaryKey 	= 'URUSAN_KAT1_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function urusan(){
    	return $this->hasMany('App\Model\Urusan','URUSAN_KAT1_ID');
    }
}
