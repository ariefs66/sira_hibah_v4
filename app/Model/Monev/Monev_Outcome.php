<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Outcome extends Model
{
	protected $table		= 'MONEV.DAT_OUTCOME';
    protected $primaryKey 	= 'OUTCOME_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
