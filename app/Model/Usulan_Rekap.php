<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Usulan_Rekap extends Model
{
    protected $table	= 'MUSRENBANG.RKP_USULAN';
    protected $primaryKey = 'REKAP_ID'; 
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

    public function rt()
    {
        return $this->belongsTo('App\Model\RT', 'RT_ID');
    }

}
