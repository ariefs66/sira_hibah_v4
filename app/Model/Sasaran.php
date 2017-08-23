<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sasaran extends Model
{
	protected $table		= 'REFERENSI.REF_SASARAN';
    protected $primaryKey 	= 'SASARAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->hasMany('App\Model\BL');
    }
}
