<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kamus extends Model
{
    protected $table	= 'REFERENSI.REF_KAMUS';
    protected $primaryKey = 'KAMUS_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function isu()
    {
        return $this->belongsTo('App\Model\Isu', 'ISU_ID');
    }

    public function usulan()
    {
        return $this->hasMany('App\Model\Usulan', 'KAMUS_ID');
    }

    public function kegiatan(){
        return $this->belongsTo('App\Model\Kegiatan','KEGIATAN_ID');
    }

    public function komponen(){
        return $this->belongsTo('App\Model\Komponen','KOMPONEN_ID');
    }
}
