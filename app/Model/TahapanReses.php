<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TahapanReses extends Model
{
	protected $table		= 'RESES.DAT_TAHAPAN';
    protected $primaryKey 	= 'TAHAPAN_ID'; 
    public $timestamps 		= false;
    public $incrementing 	= false;
}
