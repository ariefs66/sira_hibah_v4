<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kunciperubahan extends Model
{
    protected $table		= 'BUDGETING.DAT_KUNCI_PERUBAHAN';
    protected $primaryKey 	= 'KUNCI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function bl(){
        return $this->belongsTo('App\Model\BLPerubahan', 'BL_ID');
    }
}
