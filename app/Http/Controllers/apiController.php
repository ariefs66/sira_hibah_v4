<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Carbon;
use Response;
use Auth;
use App\Model\Usulan;
use App\Model\UsulanReses;
use App\Model\Kamus;
use App\Model\UserBudget;
use App\Model\Komponen;
use App\Model\UserReses;
use App\Model\BL;
use App\Model\BLPerubahan;
use App\Model\Rincian;
use App\Model\RincianPerubahan;
use App\Model\Rekening;
use App\Model\Log;
use App\Model\Kegiatan;
use App\Model\Program;
use App\Model\Pekerjaan;
use App\Model\Subunit;
use App\Model\Skpd;
use Illuminate\Support\Facades\Input;

class apiController extends Controller
{
    public function api($tahun,$status){
        $data   = Usulan::where('USULAN_STATUS',1)->select('USULAN_ID')->get();
        return Response::JSON($data);
    }


    public function apiSirupKegiatan($tahun,$status){
      if($status == 'murni'){
    	$data 	= Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
    				->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
    				->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
    				->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
    				->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
    				->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
    				->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
    				->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
    				->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
    				->WHERE('DAT_BL.BL_TAHUN',$tahun)
    				->WHERE('DAT_BL.BL_DELETED',0)
    				->WHERE('DAT_BL.BL_VALIDASI',1)
    				->take('1000')
    				->orderBy('REF_SKPD.SKPD_ID','asc')
    				->get();		
    	}else{
    		$data 	= RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
    				->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
    				->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
    				->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
    				->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
    				->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
    				->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
    				->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
    				->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
    				->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
    				->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
    				->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
    				->take('1000')
    				->orderBy('REF_SKPD.SKPD_ID','asc')
    				->get();
    	}			

    	$view 			= array();
    	foreach ($data as $data) {
            if($data->BL_PAGU == 0){
                $deleted = 'false';
            }else $deleted = 'true';

    		array_push($view, array( 
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'KEGIATAN_ID'          =>$data->KEGIATAN_ID,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'KEGIATAN_NAMA'        =>$data->KEGIATAN_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'BL_DELETED'           =>$deleted,
    		));
    	}
		$out = array("aaData"=>$view);    	
    	return Response::JSON($out);
    }

    public function apiSirupProgram($tahun,$status){
      if($status == 'murni'){
        $data   = Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();        
        }else{
            $data   = RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();
        }           

        $view           = array();
        foreach ($data as $data) {
            if($data->BL_PAGU == 0){
                $deleted = 'false';
            }else $deleted = 'true';
            array_push($view, array( 
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'PROGRAM_NAMA'         =>$data->PROGRAM_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'BL_DELETED'           =>$deleted,
            ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function apiSirupPenyedia($tahun,$status){
      if($status == 'murni'){
        $data   = Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL.SUMBER_ID')
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();        
        }else{
            $data   = RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL_PERUBAHAN.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL_PERUBAHAN.SUMBER_ID')
                    ->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();
        }           

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'RUP_ID'               =>0,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'KEGIATAN_ID'          =>$data->KEGIATAN_ID,
                                     'SUBRINCIAN_NAMA'      =>$data->SUBRINCIAN_NAMA,
                                     'LOKASI_NAMA'          =>$data->LOKASI_NAMA,
                                     'DETAIL_LOKASI_NAMA'   =>"-",
                                     'RINCIAN_VOLUME'       =>$data->RINCIAN_VOLUME,
                                     'PEKERJAAN_NAMA'       =>$data->PEKERJAAN_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'DANA_ID'              =>$data->DANA_ID,
                                     'DANA_NAMA'            =>$data->DANA_NAMA,
                                     'DANA_SKPD'            =>'-',
                                     'MAK'                  =>$data->SKPD_KODE.'.'.$data->PROGRAM_KODE.'.'.$data->KEGIATAN_KODE.'.'.$data->REKENING_KODE,
                                     'RINCIAN_TOTAL'        =>$data->RINCIAN_TOTAL,
                                     'BL_AWAL'              =>$data->BL_AWAL,
                                     'BL_AKHIR'             =>$data->BL_AKHIR,
                                     'TIME_CREATED'         =>$data->TIME_CREATED,
                                     'TIME_UPDATED'         =>$data->TIME_UPDATED,
                                     'AKTIF'                =>'TRUE',
                                     
            ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }

    public function apiSirupSwakelola($tahun,$status){
      if($status == 'murni'){
        $data   = Rincian::JOIN('BUDGETING.DAT_SUBRINCIAN','DAT_RINCIAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL','DAT_RINCIAN.BL_ID','=','DAT_BL.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL.SUMBER_ID')
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();        
        }else{
            $data   = RincianPerubahan::JOIN('BUDGETING.DAT_SUBRINCIAN_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.SUBRINCIAN_ID','=','DAT_SUBRINCIAN_PERUBAHAN.SUBRINCIAN_ID')
                    ->JOIN('REFERENSI.REF_REKENING','DAT_RINCIAN_PERUBAHAN.REKENING_ID','=','REF_REKENING.REKENING_ID')
                    ->JOIN('EHARGA.DAT_KOMPONEN','DAT_RINCIAN_PERUBAHAN.KOMPONEN_ID','=','DAT_KOMPONEN.KOMPONEN_ID')
                    ->JOIN('REFERENSI.REF_PEKERJAAN','DAT_RINCIAN_PERUBAHAN.PEKERJAAN_ID','=','REF_PEKERJAAN.PEKERJAAN_ID')
                    ->JOIN('BUDGETING.DAT_BL_PERUBAHAN','DAT_RINCIAN_PERUBAHAN.BL_ID','=','DAT_BL_PERUBAHAN.BL_ID')
                    ->JOIN('REFERENSI.REF_KEGIATAN','DAT_BL_PERUBAHAN.KEGIATAN_ID','=','REF_KEGIATAN.KEGIATAN_ID')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL_PERUBAHAN.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->JOIN('REFERENSI.REF_LOKASI','REF_LOKASI.LOKASI_ID','=','DAT_BL_PERUBAHAN.LOKASI_ID')
                    ->JOIN('REFERENSI.REF_SUMBER_DANA','REF_SUMBER_DANA.DANA_ID','=','DAT_BL_PERUBAHAN.SUMBER_ID')
                    ->WHERE('DAT_BL_PERUBAHAN.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_DELETED',0)
                    ->WHERE('DAT_BL_PERUBAHAN.BL_VALIDASI',1)
                    ->take('1000')
                    ->orderBy('REF_SKPD.SKPD_ID','asc')
                    ->get();
        }           

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'RUP_ID'               =>0,
                                     'SKPD_ID'              =>$data->SKPD_ID,
                                     'PROGRAM_ID'           =>$data->PROGRAM_ID,
                                     'KEGIATAN_ID'          =>$data->KEGIATAN_ID,
                                     'SUBRINCIAN_NAMA'      =>$data->SUBRINCIAN_NAMA,
                                     'LOKASI_NAMA'          =>$data->LOKASI_NAMA,
                                     'DETAIL_LOKASI_NAMA'   =>"-",
                                     'RINCIAN_VOLUME'       =>$data->RINCIAN_VOLUME,
                                     'PEKERJAAN_NAMA'       =>$data->PEKERJAAN_NAMA,
                                     'BL_PAGU'              =>$data->BL_PAGU,
                                     'DANA_ID'              =>$data->DANA_ID,
                                     'DANA_NAMA'            =>$data->DANA_NAMA,
                                     'DANA_SKPD'            =>'-',
                                     'MAK'                  =>$data->SKPD_KODE.'.'.$data->PROGRAM_KODE.'.'.$data->KEGIATAN_KODE.'.'.$data->REKENING_KODE,
                                     'RINCIAN_TOTAL'        =>$data->RINCIAN_TOTAL,
                                     'BL_AWAL'              =>$data->BL_AWAL,
                                     'BL_AKHIR'             =>$data->BL_AKHIR,
                                     'TIME_CREATED'         =>$data->TIME_CREATED,
                                     'TIME_UPDATED'         =>$data->TIME_UPDATED,
                                     'AKTIF'                =>'TRUE',
                                     
            ));
        }
        $out = array("aaData"=>$view);      
        return Response::JSON($out);
    }


    public function apiMonevProgram($tahun,$kode){
      //if($status == 'murni'){
        $data   = BL::JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                    ->JOIN('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID','LEFT')
                    ->JOIN('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID','LEFT')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->where('REF_SKPD.SKPD_KODE',$kode)
                    ->where('REF_SKPD.SKPD_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->get();        
       // }         

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'PROGRAM_KODE'           =>$data->PROGRAM_KODE,
                                     'PROGRAM_NAMA'           =>$data->PROGRAM_NAMA,
                                     
            ));
        }
        //$view = array("aaData"=>$view);      
        return Response::JSON($view);
    }


    public function apiMonevKegiatan($tahun,$kode, $kode_p){
      //if($status == 'murni'){
        $data   = BL::JOIN('REFERENSI.REF_KEGIATAN','REF_KEGIATAN.KEGIATAN_ID','=','DAT_BL.KEGIATAN_ID')
                    ->JOIN('BUDGETING.DAT_OUTPUT','DAT_OUTPUT.BL_ID','=','DAT_BL.BL_ID','LEFT')
                    ->JOIN('REFERENSI.REF_SATUAN','REF_SATUAN.SATUAN_ID','=','DAT_OUTPUT.SATUAN_ID','LEFT')
                    ->JOIN('REFERENSI.REF_PROGRAM','REF_PROGRAM.PROGRAM_ID','=','REF_KEGIATAN.PROGRAM_ID')
                    ->JOIN('REFERENSI.REF_SUB_UNIT','REF_SUB_UNIT.SUB_ID','=','DAT_BL.SUB_ID')
                    ->JOIN('REFERENSI.REF_SKPD','REF_SKPD.SKPD_ID','=','REF_SUB_UNIT.SKPD_ID')
                    ->where('REF_SKPD.SKPD_KODE',$kode)
                    ->where('REF_PROGRAM.PROGRAM_KODE',$kode_p)
                    ->where('REF_SKPD.SKPD_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_TAHUN',$tahun)
                    ->WHERE('DAT_BL.BL_DELETED',0)
                    ->WHERE('DAT_BL.BL_VALIDASI',1)
                    ->get();        
       // }         

        $view           = array();
        foreach ($data as $data) {
            array_push($view, array( 
                                     'PROGRAM_KODE'           =>$data->PROGRAM_KODE,
                                     'PROGRAM_NAMA'           =>$data->PROGRAM_NAMA,
                                     'KEGIATAN_KODE'          =>$data->KEGIATAN_KODE,
                                     'KEGIATAN_NAMA'          =>$data->KEGIATAN_NAMA,
                                     'OUTPUT_TOLAK_UKUR'      =>$data->OUTPUT_TOLAK_UKUR,
                                     'OUTPUT_TARGET'      =>$data->OUTPUT_TARGET,
                                     'SATUAN_NAMA'      =>$data->SATUAN_NAMA,
                                     
            ));
        }
       // $view = array("aaData"=>$view);      
        return Response::JSON($view);
    }



}
