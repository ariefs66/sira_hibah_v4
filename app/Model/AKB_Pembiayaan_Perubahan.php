<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AKB_Pembiayaan_Perubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_AKB_PEMBIAYAAN_PERUBAHAN';
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
