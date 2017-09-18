<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KomponenMember extends Model
{
    protected $table		= 'EHARGA.DAT_KOMPONEN_MEMBER';
    protected $primaryKey 	= 'MEMBER_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function komponen(){
    	return $this->belongsTo('App\Model\Komponen','KOMPONEN_ID');
    }
}
