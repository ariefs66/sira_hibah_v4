<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RincianLog extends Model
{
    protected $table		= 'BUDGETING.DAT_RINCIAN_LOG';
    protected $primaryKey 	= 'RINCIAN_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function user()
    {
        return $this->hasMany('App\Model\User', 'USER_ID');
    }

}
