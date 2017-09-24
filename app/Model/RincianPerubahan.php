<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RincianPerubahan extends Model
{
	protected $table		= 'BUDGETING.DAT_RINCIAN_PERUBAHAN';
    protected $primaryKey 	= 'RINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

	public function subrincian(){
        return $this->belongsTo('App\Model\SubrincianPerubahan', 'SUBRINCIAN_ID');
    }
    public function bl(){
        return $this->belongsTo('App\Model\BLPerubahan', 'BL_ID');
    }
    public function rincianmurni(){
        return $this->belongsTo('App\Model\Rincian', 'RINCIAN_ID');
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
