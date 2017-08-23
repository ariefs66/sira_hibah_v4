<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
	protected $table 		= 'REFERENSI.REF_TUJUAN';
	protected $primaryKey	= 'TUJUAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function usulan(){
        return $this->hasMany('App\Model\Usulan','TUJUAN_ID');
    }
}
