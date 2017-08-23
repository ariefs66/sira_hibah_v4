<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table	= 'REFERENSI.REF_TAG';
    protected $primaryKey = 'TAG_ID'; 
    public $timestamps = false;
    public $incrementing = false;
    public function bl(){
        return $this->hasMany('App\Model\BL');
    }
}
