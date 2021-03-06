<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KegiatanPerubahan extends Model
{
	protected $table		= 'REFERENSI.REF_KEGIATAN';
    protected $primaryKey 	= 'KEGIATAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function program(){
    	return $this->belongsTo('App\Model\ProgramPerubahan','PROGRAM_ID');
    }
    public function bl(){
        return $this->hasMany('App\Model\BLPerubahan','KEGIATAN_ID');
    }
    public function kegunit(){
        return $this->hasMany('App\Model\Kegunit','KEGIATAN_ID');
    }
    public function kamus(){
        return $this->hasMany('App\Model\Kamus','KEGIATAN_ID');
    }
}
