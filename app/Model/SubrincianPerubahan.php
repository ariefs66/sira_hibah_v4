<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubrincianPerubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_SUBRINCIAN_PERUBAHAN';
    protected $primaryKey 	= 'SUBRINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

	public function bl(){
        return $this->belongsTo('App\Model\BLPerubahan', 'BL_ID');
    }
	public function rincian(){
        return $this->hasMany('App\Model\RincianPerubahan', 'SUBRINCIAN_ID');
    }
}
