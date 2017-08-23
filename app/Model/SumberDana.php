<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
	protected $table		= 'REFERENSI.REF_SUMBER_DANA';
    protected $primaryKey 	= 'DANA_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
    public function bl(){
        return $this->hasMany('App\Model\BL');
    }
}
