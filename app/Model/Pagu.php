<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pagu extends Model
{
	protected $table		= 'REFERENSI.REF_KATEGORI_PAGU';
    protected $primaryKey 	= 'PAGU_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->hasMany('App\Model\BL');
    }
}
