<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SKPD extends Model
{
	protected $table		= 'REFERENSI.REF_SKPD';
    protected $primaryKey 	= 'SKPD_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function progunit(){
        return $this->hasMany('App\Model\Progunit','SKPD_ID');
    }    
    public function kegunit(){
    	return $this->hasMany('App\Model\Kegunit','SKPD_ID');
    }    
    public function btl(){
        return $this->hasMany('App\Model\BTL','SKPD_ID');
    }
    public function pendapatan(){
        return $this->hasMany('App\Model\Pendapatan','SKPD_ID');
    }        
    public function pembiayaan(){
        return $this->hasMany('App\Model\Pembiayaan','SKPD_ID');
    }        
    public function koor(){
        return $this->hasMany('App\Model\BTL','BTL_SKPD_ID');
    }        
    public function userbudget(){
        return $this->hasMany('App\Model\UserBudget','SKPD_ID');
    }        
    public function usulansurat(){
        return $this->hasMany('App\Model\UsulanSurat','SKPD_ID');
    }
    public function pagurincian(){
        return $this->hasMany('App\Model\PaguRincian','SKPD_ID');
    }        
}
