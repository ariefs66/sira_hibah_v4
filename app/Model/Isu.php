<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Isu extends Model
{
    protected $table	= 'REFERENSI.REF_ISU';
    protected $primaryKey = 'ISU_ID'; 
    public $timestamps = false;
    public $incrementing = false;

    public function kamus()
    {
        return $this->hasMany('App\Model\Kamus', 'ISU_ID');
    }

}
