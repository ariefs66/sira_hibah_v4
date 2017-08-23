<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TahunAnggaran extends Model
{
    protected $table	= 'DATA.TAHUN_ANGGARAN';
    protected $primaryKey = 'ID'; 
    public $timestamps = false;
    public $incrementing = false;

}
