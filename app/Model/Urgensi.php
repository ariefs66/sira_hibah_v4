<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Urgensi extends Model
{
    protected $table		= 'BUDGETING.DAT_URGENSI';
    protected $primaryKey 	= 'URGENSI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BL', 'BL_ID');
    }    
}
