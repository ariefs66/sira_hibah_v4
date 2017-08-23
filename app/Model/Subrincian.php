<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subrincian extends Model
{
    protected $table		= 'BUDGETING.DAT_SUBRINCIAN';
    protected $primaryKey 	= 'SUBRINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

	public function bl(){
        return $this->belongsTo('App\Model\BL', 'BL_ID');
    }
	public function rincian(){
        return $this->hasMany('App\Model\Rincian', 'SUBRINCIAN_ID');
    }
}
