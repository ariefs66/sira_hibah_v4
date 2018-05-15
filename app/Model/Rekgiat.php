<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rekgiat extends Model
{
    protected $table		= 'REFERENSI.REF_REKGIAT';
    protected $primaryKey 	= 'REKGIAT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function rekening(){
        return $this->belongsTo('App\Model\Rekening', 'REKENING_ID');
    }

    public function kegiatan(){
        return $this->belongsTo('App\Model\Kegiatan', 'KEGIATAN_ID');
    }
}
