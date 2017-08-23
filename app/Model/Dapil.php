<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dapil extends Model
{
    protected $table	= 'RESES.DAT_DAPIL';
    protected $primaryKey = 'DAPIL_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function dewan(){
        return $this->hasMany('App\Model\Dewan','DAPIL_ID');
    }

    public function dapkec(){
        return $this->hasMany('App\Model\Dapkec','DAPIL_ID');
    }
}
