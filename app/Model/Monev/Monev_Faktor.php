<?php

namespace App\Model\Monev;

use Illuminate\Database\Eloquent\Model;

class Monev_Faktor extends Model
{
	protected $table		= 'MONEV.DAT_FAKTOR';
    protected $primaryKey 	= 'FAKTOR_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
