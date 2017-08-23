<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
	protected $table		= 'REFERENSI.REF_PEKERJAAN';
    protected $primaryKey 	= 'PEKERJAAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function rincian(){
        return $this->hasMany('App\Model\rincian');
    }    
}
