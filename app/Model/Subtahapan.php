<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subtahapan extends Model
{
    protected $table		= 'BUDGETING.DAT_SUBTAHAPAN';
    protected $primaryKey 	= 'SUBTAHAPAN_ID'; 
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function tahapan(){
    	return $this->subtahapan('App\Model\Tahapan','TAHAPAN_ID');
    }
}
