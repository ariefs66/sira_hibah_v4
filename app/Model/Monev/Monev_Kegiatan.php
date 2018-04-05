<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Kegiatan extends Model
{
	protected $table		= 'MONEV.DAT_KEGIATAN';
    protected $primaryKey 	= 'KEGIATAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
