<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kegunit extends Model
{
	protected $table		= 'BUDGETING.DAT_KEGUNIT';
    protected $primaryKey 	= 'KEGUNIT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function skpd(){
    	return $this->belongsTo('App\Model\SKPD','SKPD_ID');
    }
    public function kegiatan(){
    	return $this->belongsTo('App\Model\Kegiatan','KEGIATAN_ID');
    }
}
