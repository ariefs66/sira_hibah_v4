<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tahapan extends Model
{
	protected $table		= 'BUDGETING.DAT_TAHAPAN';
    protected $primaryKey 	= 'TAHAPAN_ID'; 
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function rekaprincian(){
    	return $this->hasMany('App\Model\RekapRincian','TAHAPAN_ID');
    }
    public function rekapBL(){
        return $this->hasMany('App\Model\RekapBL','TAHAPAN_ID');
    }
    public function subtahapan(){
        return $this->hasMany('App\Model\Subtahapan','TAHAPAN_ID');
    }
}
