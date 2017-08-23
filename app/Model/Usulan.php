<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    protected $table	= 'MUSRENBANG.DAT_USULAN';
    protected $primaryKey = 'USULAN_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kamus()
    {
        return $this->belongsTo('App\Model\Kamus', 'KAMUS_ID');
    }

    public function usulan_rekap()
    {
        return $this->hasMany('App\Model\Usulan_Rekap', 'USULAN_ID');
    }

    public function rw()
    {
        return $this->belongsTo('App\Model\RW', 'RW_ID');
    }

    public function tujuan(){
        return $this->belongsTo('App\Model\Tujuan','USULAN_TUJUAN');
    }
}
