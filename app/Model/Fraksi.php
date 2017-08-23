<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fraksi extends Model
{
    protected $table	= 'RESES.DAT_FRAKSI';
    protected $primaryKey = 'FRAKSI_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function dewan(){
        return $this->hasMany('App\Model\Dewan','FRAKSI_ID');
    }
}
