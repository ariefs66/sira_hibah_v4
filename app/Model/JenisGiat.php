<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JenisGiat extends Model
{
    protected $table		= 'REFERENSI.REF_JENIS_KEGIATAN';
    protected $primaryKey 	= 'JENIS_KEGIATAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->hasMany('App\Model\BL');
    }
}
