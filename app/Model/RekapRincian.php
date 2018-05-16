<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RekapRincian extends Model
{
	protected $table		= 'BUDGETING.RKP_RINCIAN';
    protected $primaryKey 	= 'REKAP_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

	public function bl(){
        return $this->belongsTo('App\Model\BL', 'BL_ID');
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
	public function tahapan(){
        return $this->belongsTo('App\Model\Tahapan', 'TAHAPAN_ID');
    }
}
