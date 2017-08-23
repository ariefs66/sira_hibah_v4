<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TahapanMusren extends Model
{
	protected $table		= 'MUSRENBANG.DAT_TAHAPAN';
    protected $primaryKey 	= 'TAHAPAN_ID'; 
    public $timestamps 		= false;
    public $incrementing 	= false;
}
