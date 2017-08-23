<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table		= 'BUDGETING.DAT_LOG';
    protected $primaryKey 	= 'LOG_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function user(){
    	return $this->belongsTo('App\Model\User','USER_ID');
    }
}
