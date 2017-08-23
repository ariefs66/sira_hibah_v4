<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dapkec extends Model
{
    protected $table	= 'RESES.DAT_DAPIL_KECAMATAN';
    protected $primaryKey = 'DAPKEC_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function dapil(){
        return $this->belongsTo('App\Model\Dapil', 'DAPIL_ID');
    }

    public function kecamatan(){
        return $this->belongsTo('App\Model\Kecamatan','KEC_ID');
    }
}
