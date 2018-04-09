<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Program extends Model
{
	protected $table		= 'MONEV.DAT_PROGRAM';
    protected $primaryKey 	= 'PROGRAM_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
