<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserReses extends Model
{
    protected $table 	    = 'USER_RESES';
   	protected $primaryKey 	= 'id';
   	public $timestamps      = false;
    public $incrementing    = false;

    public function user(){
    	return $this->belongsTo('App\Model\User','USER_ID');
    }

    public function kecamatan(){
    	return $this->belongsTo('App\Model\Kecamatan','KEC_ID');
    }
}
