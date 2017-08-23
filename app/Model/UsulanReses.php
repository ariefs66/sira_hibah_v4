<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsulanReses extends Model
{
	protected $table	= 'RESES.DAT_USULAN';
    protected $primaryKey = 'USULAN_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kamus(){
        return $this->belongsTo('App\Model\Kamus', 'KAMUS_ID');
    }

    public function dewan(){
        return $this->belongsTo('App\Model\Dewan','DEWAN_ID');
    }

    public function kecamatan(){
        return $this->belongsTo('App\Model\Kecamatan','KEC_ID');
    }

}
