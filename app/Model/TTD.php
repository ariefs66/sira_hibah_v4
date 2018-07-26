<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TTD extends Model
{
    protected $table	= 'DATA.DAT_TTD';
    protected $primaryKey = 'TTD_ID'; 
    public $timestamps = false;
    public $incrementing = false;

}
