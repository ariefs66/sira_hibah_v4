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
      $data = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)->orderBy('REKENING_KODE')->get();        

      $p1 = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.1%')
              ->sum('PEMBIAYAAN_TOTAL'); 

      $p2 = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')
              ->where('PEMBIAYAAN_TAHUN',$tahun)
              ->where('REKENING_KODE','LIKE', '6.2%')
              ->sum('PEMBIAYAAN_TOTAL'); 


    	$view 			= array();
    	foreach ($data as $data) {
          if($data->REKENING_KODE == '6' || $data->REKENING_KODE == '6.1' || $data->REKENING_KODE == '6.2'){
              $opsi = '';
              $id       = '<b>'.$data->PEMBIAYAAN_ID.'<b>';
              $rek_kode = '<b>'.$data->REKENING_KODE.'<b>';
              $rek_nama = '<b>'.$data->REKENING_NAMA.'<b>';
              if($data->REKENING_KODE == '6.1'){
                $total    = '<b>'.number_format($p1,0,'.',',').'<b>';
              }elseif($data->REKENING_KODE == '6.2'){
                $total    = '<b>'.number_format($p2,0,'.',',').'<b>';
              }else{
               $total    = '<b>0<b>'; 
              }
              

          }else{
              $opsi = '<a onclick="return ubah(\''.$data->PEMBIAYAAN_ID.'\')"><i class="fa fa-pencil-square"></i>Ubah</a> <a onclick="return hapus(\''.$data->PEMBIAYAAN_ID.'\')"><i class="fa fa-close"></i>Hapus</a>';
              $id       = $data->PEMBIAYAAN_ID;
              $rek_kode = $data->REKENING_KODE;
              $rek_nama = $data->REKENING_NAMA;
              $total    = number_format($data->PEMBIAYAAN_TOTAL,0,'.',',');
          }
          
          
    		array_push($view, array( 
                                 'ID'			               =>$id,
                								 'REKENING_KODE'	       =>$rek_kode,
                                 'REKENING_NAMA'         =>$rek_nama,
                                 'PEMBIAYAAN_DASHUK'     =>$data->PEMBIAYAAN_DASHUK,
                								 'PEMBIAYAAN_TOTAL'      =>$total,
                                 'PEMBIAYAAN_KETERANGAN' =>$data->PEMBIAYAAN_KETERANGAN,
                                 'OPSI'                  =>$opsi,
                                 
                               ));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
   	}

    public function edit($tahun,$status,$id){
        $data   = Pembiayaan::JOIN('REFERENSI.REF_REKENING','REF_REKENING.REKENING_ID','=','DAT_PEMBIAYAAN.REKENING_ID')->where('PEMBIAYAAN_TAHUN',$tahun)->where('PEMBIAYAAN_ID',$id)->first();

        $data->REKENING_KODE   = $data->REKENING_KODE;
        $data->REKENING_NAMA   = $data->REKENING_NAMA;
        $data->PEMBIAYAAN_KETERANGAN        = $data->PEMBIAYAAN_KETERANGAN;
        $data->PEMBIAYAAN_DASHUK        = $data->PEMBIAYAAN_DASHUK;
        $data->PEMBIAYAAN_TOTAL        = $data->PEMBIAYAAN_TOTAL;
      
      return Response::JSON($data);
    }


    public function update($tahun,$status){ 

        Pembiayaan::where('PEMBIAYAAN_ID',Input::get('PEMBIAYAAN_ID'))
                        ->update([
                            'PEMBIAYAAN_DASHUK'         => Input::get('PEMBIAYAAN_DASHUK'),
                            'PEMBIAYAAN_KETERANGAN'     => Input::get('PEMBIAYAAN_KETERANGAN'),
                            'PEMBIAYAAN_TOTAL'          => Input::get('PEMBIAYAAN_TOTAL')
                            /*'USER_UPDATED'              => Auth::user()->id,
                            'TIME_UPDATED'              => Carbon\Carbon::now()*/
                            ]); 

        return ("sukses mengubah pembiayaan");                              

    }

    public function hapus(){
        pembiayaan::where('PEMBIAYAAN_ID',Input::get('PEMBIAYAAN_ID'))->update(['PEMBIAYAAN_TOTAL'=>0]);
        return "Hapus Berhasil!";
    }

   	
}
