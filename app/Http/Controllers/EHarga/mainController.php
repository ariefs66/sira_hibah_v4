<?php

namespace App\Http\Controllers\EHarga;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class mainController extends Controller
{
    public function index($tahun){
    	return View('eharga.index',['tahun'=>2019]);
    }
}
