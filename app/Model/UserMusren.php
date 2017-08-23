<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserMusren extends Model
{
    protected $table 	    = 'USER_MUSREN';
   	protected $primaryKey 	= 'id';
   	public $timestamps      = false;
    public $incrementing    = false;

    public function user(){
    	return $this->belongsTo('App\Model\User','USER_ID');
    }

    public function rw(){
    	return $this->belongsTo('App\Model\RW','RW_ID');
    }
}
