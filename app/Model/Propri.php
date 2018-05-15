<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Propri extends Model
{
    protected $table		= 'BUDGETING.DAT_PROPRI';
    protected $primaryKey 	= 'PROPRI_ID';
    public $timestamps 		= false;
    public $incrementing 	= false;

    public function skpd(){
        return $this->belongsTo('App\Model\SKPD', 'SKPD_ID');
    }

    public function program(){
        return $this->belongsTo('App\Model\Program', 'PROGRAM_ID');
    }
}
