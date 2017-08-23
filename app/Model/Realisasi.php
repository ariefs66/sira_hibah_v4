<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    protected $table		= 'BUDGETING.DAT_BL_REALISASI';
    protected $primaryKey 	= 'BTL_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;
}
