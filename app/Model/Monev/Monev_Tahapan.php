<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Tahapan extends Model
{
	protected $table		= 'MONEV.DAT_TAHAPAN';
    protected $primaryKey 	= 'TAHAPAN_TAHUN';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
