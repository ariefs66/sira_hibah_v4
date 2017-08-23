<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subunit extends Model
{
	protected $table		= 'REFERENSI.REF_SUB_UNIT';
    protected $primaryKey 	= 'SUB_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function skpd(){
    	return $this->belongsTo('App\Model\SKPD','SKPD_ID');
    }    
    public function bl(){
        return $this->hasMany('App\Model\BL','SUB_ID');
    }  
    public function blperubahan(){
        return $this->hasMany('App\Model\BLPerubahan','SUB_ID');
    }  
    public function btl(){
        return $this->hasMany('App\Model\BTL','SUB_ID');
    }  
    public function pendapatan(){
        return $this->hasMany('App\Model\Pendapatan','SUB_ID');
    }  
    public function pembiayaan(){
        return $this->hasMany('App\Model\Pembiayaan','SUB_ID');
    }  
    public function kelurahan(){
        return $this->hasMany('App\Model\Kelurahan','SUB_ID');
    }  
}
