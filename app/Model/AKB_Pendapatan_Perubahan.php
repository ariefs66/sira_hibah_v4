<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AKB_Pendapatan_Perubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_AKB_PENDAPATAN_PERUBAHAN';
    protected $primaryKey 	= 'AKB_PENDAPATAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function pendapatan(){
        return $this->belongsTo('App\Model\Pendapatan', 'PENDAPATAN_ID');
    }

    public function rekening(){
        return $this->hasMany('App\Model\Rekening', 'REKENING_ID');
    }
}
