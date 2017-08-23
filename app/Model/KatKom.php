<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KatKom extends Model
{
    protected $table		= 'EHARGA.DAT_KOMPONEN_KATEGORI';
    protected $primaryKey 	= 'KATEGORI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function usulan(){
    	return $this->hasMany('App\Model\UsulanKomponen','KATEGORI_ID');
    }
}
