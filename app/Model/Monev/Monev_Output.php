<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Output extends Model
{
	protected $table		= 'MONEV.DAT_OUTPUT';
    protected $primaryKey 	= 'OUTPUT_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
