<?php

namespace App\Http\Controllers\Asosiasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class asosiasiController extends Controller
{
   public function index($tahun){
	  return View('asosiasi.index',['tahun'=>$tahun]);

   }
}
