<?php

namespace App\Http\Controllers\Asosiasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class asosiasiController extends Controller
{
   public function index($tahun){
	  return View('asosiasi.index',['tahun'=>$tahun]);

   }
   public function visiMisi($tahun){
	  return View('asosiasi.visimisi',['tahun'=>$tahun]);

   }
   public function tujuan($tahun){
	  return View('asosiasi.tujuan',['tahun'=>$tahun]);

   }
   public function strategi($tahun){
	  return View('asosiasi.strategi',['tahun'=>$tahun]);

   }
   public function arahKebijakan($tahun){
	  return View('asosiasi.arahKebijakan',['tahun'=>$tahun]);

   }
   public function program($tahun){
	  return View('asosiasi.program',['tahun'=>$tahun]);

   }
   public function kegiatan($tahun){
	  return View('asosiasi.kegiatan',['tahun'=>$tahun]);

   }
}
