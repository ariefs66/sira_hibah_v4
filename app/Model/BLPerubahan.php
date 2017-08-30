<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BLPerubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_BL_PERUBAHAN';
    protected $primaryKey 	= 'BL_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subunit(){
        return $this->belongsTo('App\Model\Subunit', 'SUB_ID');
    }
    public function kegiatan(){
        return $this->belongsTo('App\Model\Kegiatan', 'KEGIATAN_ID');
    }
    public function jenis(){
        return $this->belongsTo('App\Model\JenisGiat', 'JENIS_ID');
    }
    public function sumber(){
        return $this->belongsTo('App\Model\SumberDana', 'SUMBER_ID');
    }
    public function pagu(){
        return $this->belongsTo('App\Model\Pagu', 'PAGU_ID');
    }
    public function sasaran(){
        return $this->belongsTo('App\Model\Sasaran', 'SASARAN_ID');
    }
    public function lokasi(){
        return $this->belongsTo('App\Model\Lokasi', 'LOKASI_ID');
    }
    public function tag1(){
        return $this->belongsTo('App\Model\Tag', 'BL_TAG_1');
    }
    public function tag2(){
        return $this->belongsTo('App\Model\Tag', 'BL_TAG_2');
    }
    public function tag3(){
        return $this->belongsTo('App\Model\Tag', 'BL_TAG_3');
    }
    public function indikator(){
        return $this->hasMany('App\Model\Indikator', 'BL_ID');
    }
    public function kunci(){
        return $this->hasOne('App\Model\Kunciperubahan', 'BL_ID');
    }
    public function subrincian(){
        return $this->hasMany('App\Model\SubrincianPerubahan', 'BL_ID');
    }
    public function rincian(){
        return $this->hasMany('App\Model\RincianPerubahan', 'BL_ID');
    }
    public function staff(){
        return $this->hasMany('App\Model\Staff','BL_ID');
    }
    public function output(){
        return $this->hasMany('App\Model\Output','BL_ID');
    }
    public function pagurincian(){
        return $this->hasMany('App\Model\PaguRincian','BL_ID');
    }
    public function urgensi(){
        return $this->hasMany('App\Model\Urgensi','BL_ID');
    }
}
