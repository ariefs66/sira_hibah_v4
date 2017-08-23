<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBudget extends Model
{
	protected $table 		= 'USER_BUDGET';
   	protected $primaryKey 	= 'id';
   	public $timestamps 		= false;
    public $incrementing 	= false;

    public function user(){
    	return $this->belongsTo('App\Model\User','USER_ID');
    }

    public function skpd(){
    	return $this->belongsTo('App\Model\SKPD','SKPD_ID');
    }

    public function btl(){
      
    }
}
