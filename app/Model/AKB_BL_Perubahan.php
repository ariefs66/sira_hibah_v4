<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AKB_BL_Perubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_AKB_BL_PERUBAHAN';
    protected $primaryKey 	= 'AKB_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BL', 'BL_ID');
    }

    public function rekening(){
        return $this->hasMany('App\Model\Rekening', 'REKENING_ID');
    }
}
