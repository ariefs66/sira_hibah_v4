<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dewan extends Model
{
    protected $table	= 'RESES.DAT_DEWAN';
    protected $primaryKey = 'DEWAN_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function usulanreses(){
        return $this->hasMany('App\Model\UsulanReses', 'DEWAN_ID');
    }

    public function fraksi(){
        return $this->belongsTo('App\Model\Fraksi','FRAKSI_ID');
    }

    public function dapil(){
        return $this->belongsTo('App\Model\Dapil','DAPIL_ID');
    }
}
