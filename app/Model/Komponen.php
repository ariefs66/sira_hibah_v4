<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
	protected $table		= 'EHARGA.DAT_KOMPONEN';
    protected $primaryKey 	= 'KOMPONEN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
    
    public function rincian(){
        return $this->hasMany('App\Model\Rincian','KOMPONEN_ID');
    }
    public function komponenmember(){
        return $this->hasMany('App\Model\KomponenMember','KOMPONEN_ID');
    }
    public function rekom(){
        return $this->hasMany('App\Model\Rekom','KOMPONEN_ID');
    }    
    public function kamus(){
        return $this->hasMany('App\Model\Kamus','KOMPONEN_ID');
    }
    public function usulanmember(){
        return $this->hasMany('App\Model\UsulanKomponenMember','KOMPONEN_ID');
    }
}
