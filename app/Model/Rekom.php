<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rekom extends Model
{
	protected $table		= 'EHARGA.DAT_KOMPONEN_REKENING';
    protected $primaryKey 	= 'REKOM_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

	public function rekening(){
        return $this->belongsTo('App\Model\Rekening', 'REKENING_ID');
    }
	public function komponen(){
        return $this->belongsTo('App\Model\Komponen', 'KOMPONEN_ID');
    }
}
