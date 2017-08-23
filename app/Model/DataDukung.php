<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataDukung extends Model
{
    protected $table		= 'EHARGA.DAT_DATA_DUKUNG';
    protected $primaryKey 	= 'DD_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function usulan(){
    	return $this->hasMany('App\Model\UsulanKomponen','DD_ID');
    }
}
