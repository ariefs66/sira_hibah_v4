<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsulanKomponen extends Model
{
    protected $table		= 'EHARGA.DAT_USULAN';
    protected $primaryKey 	= 'USULAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function katkom(){
    	return $this->belongsTo('App\Model\KatKom','KATEGORI_ID');
    }
    public function rekening(){
    	return $this->belongsTo('App\Model\Rekening','REKENING_ID');
    }
    public function user(){
        return $this->belongsTo('App\Model\User','USER_CREATED');
    }
    public function datadukung(){
        return $this->belongsTo('App\Model\DataDukung','DD_ID');
    }
    public function surat(){
        return $this->belongsTo('App\Model\UsulanSurat','SURAT_ID');
    }
}
