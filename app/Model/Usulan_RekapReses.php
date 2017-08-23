<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Usulan_RekapReses extends Model
{
    protected $table	= 'RESES.RKP_USULAN';
    protected $primaryKey = 'REKAP_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kamus()
    {
        return $this->belongsTo('App\Model\Kamus', 'KAMUS_ID');
    }

    public function usulan_rekapreses()
    {
        return $this->hasMany('App\Model\Usulan_RekapReses', 'USULAN_ID');
    }

    public function kecamatan()
    {
        return $this->belongsTo('App\Model\Kecamatan', 'KEC_ID');
    }
}
