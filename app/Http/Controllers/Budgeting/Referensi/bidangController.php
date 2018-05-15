<?php

namespace App\Http\Controllers\Budgeting\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Urusan;
use View;
use Carbon;
use Response;
use Illuminate\Support\Facades\Input;
use Auth;
class bidangController extends Controller
{
    public function index($tahun,$status){
    	return View('budgeting.referensi.bidang',['tahun'=>$tahun,'status'=>$status]);
    }

    public function getData($tahun){
    	$data 			= Urusan::where('URUSAN_TAHUN',$tahun)->orderBy('URUSAN_KODE')->get();
    	$no 			= 1;
    	$aksi 			= '';
    	$view 			= array();
    	foreach ($data as $data) {
            if(Auth::user()->active==15){
    		$aksi 		= '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->URUSAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->URUSAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';
            }else{
                $aksi="";
            }
    		array_push($view, array( 'no'			=>$no,
                                     'URUSAN_TAHUN'  =>$data->URUSAN_TAHUN,
                                     'URUSAN_KODE'  =>$data->URUSAN_KODE,
                                     'URUSAN_NAMA'	=>$data->URUSAN_NAMA,
                                     'aksi'			=>$aksi));
    		$no++;
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function getDetail($tahun,$status,$id){
    	$data 			= Urusan::where('URUSAN_ID',$id)->get();
    	return $data;
    }

    public function submitAdd(){
    	$urusan = new Urusan;
    	$cekKode 	= Urusan::where('URUSAN_KODE',Input::get('kode_urusan'))
    						->where('URUSAN_TAHUN',Input::get('tahun'))
    						->value('URUSAN_NAMA');
    	if(empty($cekKode)){
	    	$urusan->URUSAN_KODE		= Input::get('kode_urusan');
	    	$urusan->URUSAN_TAHUN		= Input::get('tahun');
	    	$urusan->URUSAN_NAMA		= Input::get('nama_urusan');
	    	$urusan->save();
	    	return '1';
    	}else{
	    	return '0';
    	}
    }

    public function submitEdit(){
    	$cekKode 	= Urusan::where('URUSAN_KODE',Input::get('kode_urusan'))
    						->where('URUSAN_TAHUN',Input::get('tahun'))
    						->value('URUSAN_KODE');
    	if(empty($cekKode) || $cekKode == Input::get('kode_urusan')){    						
    	Urusan::where('URUSAN_ID',Input::get('id_urusan'))
    			->update(['URUSAN_KODE'=>Input::get('kode_urusan'),
    					  'URUSAN_TAHUN'=>Input::get('tahun'),
    					  'URUSAN_NAMA'=>Input::get('nama_urusan')]);
    		return '1';
    	}else{
	    	return '0';
    	}
    }

    public function delete(){
    	Urusan::where('URUSAN_ID',Input::get('id_urusan'))->delete();
    	return 'Berhasil dihapus!';
    }
}
