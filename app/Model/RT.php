<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RT extends Model
{
    protected $table	= 'REFERENSI.REF_RT';
    protected $primaryKey = 'RT_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function rw()
    {
        return $this->belongsTo('App\Model\RW', 'RW_ID');
    }

    public function usulan()
    {
        return $this->hasMany('App\Model\Usulan', 'RT_ID');
    }
}
