<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;
use Response;
use DB;
use Auth;
use App\Model\Program;
use App\Model\Kegiatan;
use App\Model\JenisGiat;
use App\Model\SumberDana;
use App\Model\Pagu;
use App\Model\Sasaran;
use App\Model\Tag;
use App\Model\Lokasi;
use App\Model\Satuan;
use App\Model\BTL;
use App\Model\Indikator;
use App\Model\Kunci;
use App\Model\Pekerjaan;
use App\Model\Rekening;
use App\Model\Komponen;
use App\Model\Rekom;
use App\Model\Rincian;
use App\Model\SKPD;
use App\Model\Pembiayaan;
class pembiayaanController extends Controller
{
public function index($tahun,$status){
		$skpd 		= SKPD::all();
    	$rekening 	= Rekening::where('REKENING_KODE','like','6%')->whereRaw('length("REKENING_KODE") = 11')->get();
        return View('budgeting.pembiayaan.index',['tahun'=>$tahun,'status'=>$status,'skpd'=>$skpd,'rekening'=>$rekening]);
    }

    public function submitAdd($tahun,$status){
    	$pembiayaan 	= new Pembiayaan;
    	$pembiayaan->PEMBIAYAAN_TAHUN		= $tahun;
    	$pembiayaan->SKPD_ID				= Input::get('SKPD_ID');
    	$pembiayaan->REKENING_ID			= Input::get('REKENING_ID');
    	$pembiayaan->PEMBIAYAAN_NAMA		= Input::get('PEMBIAYAAN_NAMA');
    	$pembiayaan->PEMBIAYAAN_KETERANGAN	= Input::get('PEMBIAYAAN_NAMA');
    	$pembiayaan->PEMBIAYAAN_TOTAL		= Input::get('PEMBIAYAAN_TOTAL');
    	$pembiayaan->save();
    	return "Input Berhasil!";
    }

    public function submitEdit(){

    }

    public function delete(){

    }

    //API
    public function getPembiayaan($tahun,$status){
   		$data       = DB::table('BUDGETING.DAT_PEMBIAYAAN')
   						->leftJoin('REFERENSI.REF_REKENING','BUDGETING.DAT_PEMBIAYAAN.REKENING_ID','=','REFERENSI.REF_REKENING.REKENING_ID')
   						->leftJoin('REFERENSI.REF_SKPD','BUDGETING.DAT_PEMBIAYAAN.SKPD_ID','=','REFERENSI.REF_SKPD.SKPD_ID')
   						->groupBy('DAT_PEMBIAYAAN.SKPD_ID','SKPD_KODE','SKPD_NAMA')
   						->select('DAT_PEMBIAYAAN.SKPD_ID','SKPD_KODE','SKPD_NAMA',DB::raw('SUM("PEMBIAYAAN_TOTAL") AS TOTAL'))
   						->get();
    	$view 			= array();
    	foreach ($data as $data) {
    		array_push($view, array( 'ID'			=>$data->SKPD_ID,
    								 'KODE'			=>$data->SKPD_KODE,
    								 'NAMA'			=>$data->SKPD_NAMA,
                                     'TOTAL'		=>number_format($data->total,0,'.',',')));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

   	public function getDetail($tahun,$status,$skpd){
   		$data 	= Pembiayaan::where('SKPD_ID',$skpd)->where('PEMBIAYAAN_TAHUN',$tahun)->get();
    	$view 			= array();
    	$no 			= 1;
    	$opsi 			= '';
    	foreach ($data as $data) {
            if(Auth::user()->level == 7){
    		  $opsi = '<div class="action visible pull-right"><a onclick="return ubah(\''.$data->PEMBIAYAAN_ID.'\')" class="action-edit"><i class="mi-edit"></i></a><a onclick="return hapus(\''.$data->PEMBIAYAAN_ID.'\')" class="action-delete"><i class="mi-trash"></i></a></div>';  
            }else{
              $opsi   = '-';
            }
    		array_push($view, array( 'NO'  			=> $no++,
    								 'AKSI'			=> $opsi,
    								 'REKENING'		=> $data->rekening->REKENING_KODE,
    								 'RINCIAN'		=> $data->PEMBIAYAAN_NAMA,
                                     'TOTAL'		=> number_format($data->PEMBIAYAAN_TOTAL,0,'.',',')));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}
}
