<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AKB_BTL_Perubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_AKB_BTL_PERUBAHAN';
    protected $primaryKey 	= 'AKB_BTL_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BTL', 'BTL_ID');
    }

    public function rekening(){
        return $this->hasMany('App\Model\Rekening', 'REKENING_ID');
    }
}
