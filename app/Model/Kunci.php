<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kunci extends Model
{
    protected $table		= 'BUDGETING.DAT_KUNCI';
    protected $primaryKey 	= 'KUNCI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BL', 'BL_ID');
    }
}
