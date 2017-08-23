<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
	protected $table		= 'REFERENSI.REF_REKENING';
    protected $primaryKey 	= 'REKENING_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function rincian(){
        return $this->hasMany('App\Model\Rincian','REKENING_ID');
    }    
    public function rekom(){
        return $this->hasMany('App\Model\Rekom','REKENING_ID');
    }
    public function btl(){
        return $this->hasMany('App\Model\BTL','REKENING_ID');
    }    
    public function pendapatan(){
        return $this->hasMany('App\Model\Pendapatan','REKENING_ID');
    }    
    public function pembiayaan(){
        return $this->hasMany('App\Model\Pembiayaan','REKENING_ID');
    }
    public function usulanKomponen(){
        return $this->hasMany('App\Model\usulanKomponen','REKENING_ID');
    }    
}
