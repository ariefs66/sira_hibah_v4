<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsulanKomponenMember extends Model
{
    protected $table		= 'EHARGA.DAT_USULAN_MEMBER';
    protected $primaryKey 	= 'MEMBER_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function usulan(){
    	return $this->belongsTo('App\Model\UsulanKomponen','USULAN_ID');
    }
    public function komponen(){
    	return $this->belongsTo('App\Model\Komponen','KOMPONEN_ID');
    }
}
