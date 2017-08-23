<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table	= 'REFERENSI.REF_KECAMATAN';
    protected $primaryKey = 'KEC_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kelurahan(){
        return $this->hasMany('App\Model\Kelurahan', 'KEC_ID');
    }
    public function userreses(){
        return $this->hasMany('App\Model\UserReses', 'KEC_ID');
    }
    public function usulanreses(){
        return $this->hasMany('App\Model\UsulanReses', 'KEC_ID');
    }    
}
