<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OutputMaster extends Model
{
    protected $table		= 'REFERENSI.REF_OUTPUT';
    protected $primaryKey 	= 'OUTPUT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function kegiatan(){
    	return $this->belongsTo('App\Model\kegiatan','KEGIATAN_ID');
    }
    public function satuan(){
    	return $this->belongsTo('App\Model\Satuan','SATUAN_ID');
    }    
}
