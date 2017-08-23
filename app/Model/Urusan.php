<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Urusan extends Model
{
	protected $table		= 'REFERENSI.REF_URUSAN';
    protected $primaryKey 	= 'URUSAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function program(){
    	return $this->hasMany('App\Model\Program','URUSAN_ID');
    }
}
