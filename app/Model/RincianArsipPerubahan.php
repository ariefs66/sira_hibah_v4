<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RincianArsipPerubahan extends Model
{
    protected $table		= 'BUDGETING.ARC_RINCIAN_PERUBAHAN';
    protected $primaryKey 	= 'RINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subrincianperubahan(){
        return $this->belongsTo('App\Model\SubrincianPerubahan', 'SUBRINCIAN_ID');
    }
    public function blperubahan(){
        return $this->belongsTo('App\Model\BLPerubahan', 'BL_ID');
    }
	public function rekening(){
        return $this->belongsTo('App\Model\Rekening', 'REKENING_ID');
    }
	public function komponen(){
        return $this->belongsTo('App\Model\Komponen', 'KOMPONEN_ID');
    }
	public function pekerjaan(){
        return $this->belongsTo('App\Model\Pekerjaan', 'PEKERJAAN_ID');
    }

}
