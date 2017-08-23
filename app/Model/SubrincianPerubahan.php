<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubrincianPerubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_SUBRINCIAN_PERUBAHAN';
    protected $primaryKey 	= 'SUBRINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

	public function blperubahan(){
        return $this->belongsTo('App\Model\BLPerubahan', 'BL_ID');
    }
	public function rincianperubahan(){
        return $this->hasMany('App\Model\RincianPerubahan', 'SUBRINCIAN_ID');
    }
}
