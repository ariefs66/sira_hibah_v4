<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Realisasi extends Model
{
	protected $table		= 'MONEV.DAT_REALISASI';
    protected $primaryKey 	= 'REALISASI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
