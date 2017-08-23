<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RW extends Model
{
    protected $table	= 'REFERENSI.REF_RW';
    protected $primaryKey = 'RW_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kelurahan()
    {
        return $this->belongsTo('App\Model\Kelurahan', 'KEL_ID');
    }

    public function usulan()
    {
        return $this->hasMany('App\Model\Usulan', 'RW_ID');
    }

    public function usermusren(){
        return $this->hasMany('App\Model\UserMusren','RW_ID');
    }
}