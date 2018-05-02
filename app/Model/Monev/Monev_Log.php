<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Log extends Model
{
	protected $table		= 'MONEV.DAT_LOG';
    protected $primaryKey 	= 'LOG_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
