<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table	= 'REFERENSI.REF_KELURAHAN';
    protected $primaryKey = 'KEL_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kecamatan(){
        return $this->belongsTo('App\Model\Kecamatan', 'KEC_ID');
    }

    public function subunit(){
        return $this->belongsTo('App\Model\Subunit', 'SUB_ID');
    }

    public function rw(){
        return $this->hasMany('App\Model\RW', 'KEL_ID');
    }
}
