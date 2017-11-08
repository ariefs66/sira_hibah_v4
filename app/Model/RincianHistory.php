<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RincianHistory extends Model
{

    protected $table		= 'BUDGETING.DAT_RINCIAN_HISTORY';
    protected $primaryKey 	= 'RINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function subrincian(){
        return $this->belongsTo('App\Model\Subrincian', 'SUBRINCIAN_ID');
    }
	public function subrincianperubahan(){
        return $this->belongsTo('App\Model\Subrincian', 'SUBRINCIAN_ID');
    }
    public function bl(){
        return $this->belongsTo('App\Model\BL', 'BL_ID');
    }
    public function blperubahan(){
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
}
