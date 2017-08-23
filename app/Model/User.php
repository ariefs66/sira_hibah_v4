<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
   	protected $table 			  = 'users';
   	protected $primaryKey 	= 'id';
   	public $timestamps 			= false;
    public $incrementing 		= false;

    public function usermusren(){
    	return $this->hasOne('App\Model\UserMusren','USER_ID');
    }
    public function staff(){
    	return $this->hasMany('App\Model\Staff','USER_ID');
    }
    public function userbudget(){
      return $this->hasMany('App\Model\UserBudget','USER_ID');
    }
    public function userreses(){
      return $this->hasMany('App\Model\UserReses','USER_ID');
    }
    public function log(){
      return $this->hasMany('App\Model\Log','USER_ID');
    }
    public function UsulanKomponen(){
      return $this->hasMany('App\Model\UsulanKomponen','USER_CREATED');
    }
}
