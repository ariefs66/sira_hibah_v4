<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RincianHistory extends Model
{
    protected $table		= 'BUDGETING.DAT_RINCIAN_HISTORY';
    protected $primaryKey 	= 'RINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function user()
    {
        return $this->hasMany('App\Model\User', 'USER_ID');
    }
}
