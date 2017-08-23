<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
	protected $table		= 'REFERENSI.REF_LOKASI';
    protected $primaryKey 	= 'LOKASI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->hasMany('App\Model\BL');
    }
}
