<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BTLPagu extends Model
{
    protected $table		= 'BUDGETING.DAT_BTL_PAGU';
    protected $primaryKey 	= 'PAGU_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function skpd(){
        return $this->belongsTo('App\Model\SKPD', 'SKPD_ID');
    }
}
