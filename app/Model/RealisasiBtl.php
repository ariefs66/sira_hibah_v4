<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RealisasiBtl extends Model
{
    protected $table        = 'BUDGETING.DAT_BTL_REALISASI';
    protected $primaryKey   = 'BTL_ID';
    public $timestamps      = false;
    public $incrementing    = false;
}
