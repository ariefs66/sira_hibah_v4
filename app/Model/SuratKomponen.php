<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SuratKomponen extends Model
{
    protected $table		= 'EHARGA.DAT_SURAT';
    protected $primaryKey 	= 'SURAT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function skpd(){
        return $this->belongsTo('App\Model\SKPD','SKPD_ID');
    }        
}
