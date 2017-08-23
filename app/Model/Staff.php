<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
	protected $table		= 'BUDGETING.DAT_STAFF';
    protected $primaryKey 	= 'STAFF_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BL','BL_ID');
    }

    public function user(){
        return $this->belongsTo('App\Model\User','USER_ID');
    }
}
