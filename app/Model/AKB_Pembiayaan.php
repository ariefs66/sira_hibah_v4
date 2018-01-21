<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AKB_Pembiayaan extends Model
{
    protected $table		= 'BUDGETING.DAT_AKB_PEMBIAYAAN';
    protected $primaryKey 	= 'AKB_PEMBIAYAAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function pembiayaan(){
        return $this->belongsTo('App\Model\Pembiayaan', 'PEMBIAYAAN_ID');
    }

    public function rekening(){
        return $this->hasMany('App\Model\Rekening', 'REKENING_ID');
    }
}
