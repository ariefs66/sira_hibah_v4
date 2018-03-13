<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndikatorPerubahan extends Model
{
	protected $table		= 'BUDGETING.DAT_INDIKATOR';
    protected $primaryKey 	= 'INDIKATOR_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BL_PERUBAHAN', 'BL_ID');
    }
    public function satuan(){
        return $this->belongsTo('App\Model\Satuan','SATUAN_ID');
    }
}
